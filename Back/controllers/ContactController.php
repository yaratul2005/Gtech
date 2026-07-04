<?php
/**
 * Great Endured Technology — Contact Controller
 * Governed by RBP (AGENTS.md) & Stack Decisions (Resource.md)
 */

declare(strict_types=1);

namespace Back\Controllers;

use Back\Middleware\CSRF;
use Back\Middleware\RateLimit;
use Back\Models\Lead;

class ContactController
{
    public function submit(): void
    {
        header('Content-Type: application/json');

        // 1. Verify CSRF
        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed. Request aborted.']);
            return;
        }

        // 2. Verify Rate Limiting (Resource.md 3 & 4)
        if (!RateLimit::check('contact')) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'Too many submissions. Please try again in an hour.']);
            return;
        }

        // 3. Retrieve and sanitize inputs
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $service = trim($_POST['service'] ?? 'General Inquiry');
        $message = trim($_POST['message'] ?? '');

        // 4. Basic Validation
        if (empty($name) || empty($email) || empty($message)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All fields (Name, Email, Message) are required.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
            return;
        }

        // 5. Save lead in Database (Resilient fallback if DB not configured)
        $dbSaved = Lead::create([
            'name' => $name,
            'email' => $email,
            'service' => $service,
            'message' => $message
        ]);

        // 6. Send notification email
        $emailSent = $this->sendNotificationEmail($name, $email, $service, $message);

        // We count as successful if either the database saved it or the email went out
        if ($dbSaved || $emailSent) {
            echo json_encode([
                'success' => true,
                'message' => 'Thank you for contacting Great Endured Technology! We will get back to you shortly.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Unable to send message due to a server error. Please try again later.'
            ]);
        }
    }

    private function sendNotificationEmail(string $name, string $email, string $service, string $message): bool
    {
        $subject = "New Agency Lead: {$name} - {$service}";
        $to = $_ENV['SMTP_FROM'] ?? 'contact@greatentech.com';

        $body = "<p>You have received a new lead inquiry from the website contact form:</p>";
        $body .= "<table style='width: 100%; border: 1px solid rgba(255,255,255,0.05); border-collapse: collapse; margin-top: 15px;'>";
        $body .= "<tr><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05); font-weight: bold; width: 120px;'>Name:</td><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05);'>" . htmlspecialchars($name) . "</td></tr>";
        $body .= "<tr><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05); font-weight: bold;'>Email:</td><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05);'><a href='mailto:" . htmlspecialchars($email) . "' style='color: #00d4ff;'>" . htmlspecialchars($email) . "</a></td></tr>";
        $body .= "<tr><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05); font-weight: bold;'>Service:</td><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05);'>" . htmlspecialchars($service) . "</td></tr>";
        $body .= "<tr><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05); font-weight: bold; vertical-align: top;'>Message:</td><td style='padding: 10px; border: 1px solid rgba(255,255,255,0.05);'>" . nl2br(htmlspecialchars($message)) . "</td></tr>";
        $body .= "</table>";

        return \Back\Services\EmailService::send($to, $subject, $body, "New Contact Lead");
    }
}
