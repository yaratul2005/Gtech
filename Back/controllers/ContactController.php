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
        $to = getenv('SMTP_FROM') ?: 'contact@greatentech.com';
        $subject = "New Agency Lead: {$name} - {$service}";
        
        $body = "You have received a new inquiry from your website contact form:\n\n";
        $body .= "Name: {$name}\n";
        $body .= "Email: {$email}\n";
        $body .= "Service: {$service}\n\n";
        $body .= "Message:\n{$message}\n";

        $headers = "From: {$to}\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Check if SMTP details are defined in env to send via external mailer (Resource.md Section 3)
        // For shared hosting, standard mail() with proper headers is the most robust default
        try {
            return mail($to, $subject, $body, $headers);
        } catch (\Exception $e) {
            error_log("Failed to send mail: " . $e->getMessage());
            return false;
        }
    }
}
