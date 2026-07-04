<?php
/**
 * Great Endured Technology — Email Service Engine
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

namespace Back\Services;

class EmailService
{
    /**
     * Send an email with a branded HTML template.
     */
    public static function send(string $to, string $subject, string $bodyContent, string $titleHeader = "Notification Log"): bool
    {
        $smtpHost = $_ENV['SMTP_HOST'] ?? '';
        $smtpPort = intval($_ENV['SMTP_PORT'] ?? 587);
        $smtpUser = $_ENV['SMTP_USER'] ?? '';
        $smtpPass = $_ENV['SMTP_PASS'] ?? '';
        $fromEmail = $_ENV['SMTP_FROM'] ?? 'contact@greatentech.com';
        $fromName = $_ENV['SMTP_FROM_NAME'] ?? 'Great Endured Technology';

        $htmlBody = self::getHtmlTemplate($titleHeader, $bodyContent);

        // Standard headers
        $boundary = uniqid('np', true);
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        // Multi-part content (Text + HTML)
        $textVersion = strip_tags(str_replace(['<br>', '<p>', '</p>'], ["\n", "\n\n", ""], $bodyContent));
        
        $message = "This is a multi-part message in MIME format.\r\n\r\n";
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= "{$textVersion}\r\n\r\n";
        
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= chunk_split(base64_encode($htmlBody)) . "\r\n";
        $message .= "--{$boundary}--";

        // If SMTP credentials are provided, use socket-based SMTP delivery
        if (!empty($smtpHost)) {
            return self::sendSmtp($smtpHost, $smtpPort, $smtpUser, $smtpPass, $fromEmail, $to, $subject, $message, $headers);
        }

        // Fallback to PHP native mail()
        try {
            $formattedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
            return mail($to, $formattedSubject, $message, $headers);
        } catch (\Exception $e) {
            error_log("EmailService: native mail failed - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email via raw SMTP socket connection (zero-dependency PHP implementation)
     */
    private static function sendSmtp(
        string $host,
        int $port,
        string $user,
        string $pass,
        string $from,
        string $to,
        string $subject,
        string $message,
        string $headers
    ): bool {
        $timeout = 15;
        $encryption = '';

        if ($port === 465) {
            $host = 'ssl://' . $host;
        }

        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$socket) {
            error_log("EmailService: SMTP connection failed to {$host}:{$port} ({$errno} - {$errstr})");
            return false;
        }

        $readResponse = function($socket) {
            $response = '';
            while ($line = fgets($socket, 515)) {
                $response .= $line;
                if (substr($line, 3, 1) === ' ') {
                    break;
                }
            }
            return $response;
        };

        $writeCommand = function($socket, $cmd) use ($readResponse) {
            fputs($socket, $cmd . "\r\n");
            return $readResponse($socket);
        };

        // Welcome banner
        $readResponse($socket);

        // EHLO
        $writeCommand($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));

        // STARTTLS if port is 587
        if ($port === 587) {
            $response = $writeCommand($socket, "STARTTLS");
            if (strpos($response, '220') === 0) {
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_ANY_CLIENT)) {
                    error_log("EmailService: SMTP TLS negotiation failed.");
                    fclose($socket);
                    return false;
                }
                // Resend EHLO after secure negotiation
                $writeCommand($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
            }
        }

        // AUTH LOGIN
        if (!empty($user) && !empty($pass)) {
            $response = $writeCommand($socket, "AUTH LOGIN");
            if (strpos($response, '334') === 0) {
                $writeCommand($socket, base64_encode($user));
                $response = $writeCommand($socket, base64_encode($pass));
                if (strpos($response, '235') !== 0) {
                    error_log("EmailService: SMTP authentication failed.");
                    fclose($socket);
                    return false;
                }
            }
        }

        // MAIL FROM
        $writeCommand($socket, "MAIL FROM:<{$from}>");
        // RCPT TO
        $writeCommand($socket, "RCPT TO:<{$to}>");
        
        // DATA
        $response = $writeCommand($socket, "DATA");
        if (strpos($response, '354') === 0) {
            // Headers + Body payload
            $dataPayload = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $dataPayload .= "To: <{$to}>\r\n";
            $dataPayload .= $headers . "\r\n\r\n";
            $dataPayload .= $message . "\r\n.\r\n";
            
            fputs($socket, $dataPayload);
            $response = $readResponse($socket);
            if (strpos($response, '250') !== 0) {
                error_log("EmailService: SMTP DATA send failed.");
                fclose($socket);
                return false;
            }
        }

        // QUIT
        $writeCommand($socket, "QUIT");
        fclose($socket);
        return true;
    }

    /**
     * Generate responsive HTML email layout aligned with Great Endured Technology brand specifications
     */
    public static function getHtmlTemplate(string $titleHeader, string $bodyContent): string
    {
        $appUrl = $_ENV['APP_URL'] ?? 'https://greatentech.com';
        $appName = $_ENV['APP_NAME'] ?? 'Great Endured Technology';
        
        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($titleHeader) . '</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #030508;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #c9d1d9;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #030508;
            padding-bottom: 40px;
            padding-top: 40px;
        }
        .main-table {
            background-color: #0d1117;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
        .header {
            background-color: #090d16;
            padding: 30px 40px;
            border-bottom: 2px solid #00d4ff;
            text-align: center;
        }
        .logo-text {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            text-decoration: none;
        }
        .logo-dot {
            color: #00d4ff;
        }
        .content {
            padding: 40px;
            line-height: 1.6;
            font-size: 15px;
            color: #c9d1d9;
        }
        .content h2 {
            color: #ffffff;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .content p {
            margin-top: 0;
            margin-bottom: 20px;
        }
        .btn-container {
            margin: 30px 0;
            text-align: center;
        }
        .btn {
            background-color: #ffffff;
            color: #090d16 !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
        }
        .footer {
            background-color: #05070a;
            padding: 25px 40px;
            text-align: center;
            font-size: 12px;
            color: #8b949e;
            border-top: 1px solid rgba(255,255,255,0.03);
        }
        .footer a {
            color: #00d4ff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main-table">
            <!-- Header -->
            <tr>
                <td class="header">
                    <a href="' . $appUrl . '" class="logo-text">GET<span class="logo-dot">.</span></a>
                </td>
            </tr>
            <!-- Content -->
            <tr>
                <td class="content">
                    <h2>' . htmlspecialchars($titleHeader) . '</h2>
                    \' . $bodyContent . \'
                </td>
            </tr>
            <!-- Footer -->
            <tr>
                <td class="footer">
                    &copy; \' . date(\'Y\') . \' <a href="\' . $appUrl . \'">\' . htmlspecialchars($appName) . \'</a>. All rights reserved.<br>
                    This is an automated notification. Please do not reply directly to this address.
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
';
    }
}
