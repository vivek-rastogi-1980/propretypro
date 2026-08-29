<?php
namespace App\Helpers;

class MailHelper {
    /**
     * Send an email with dynamic configuration, falling back to local simulation logging
     */
    public static function send(string $to, string $subject, string $message, array $settings): bool {
        $fromEmail = !empty($settings['smtp_from_email']) ? $settings['smtp_from_email'] : ($settings['company_email'] ?? 'no-reply@vigtezreality.com');
        $fromName = !empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : ($settings['company_name'] ?? 'Vigtez Reality');

        // Prepare simulated log entry
        $timestamp = date('Y-m-d H:i:s');
        $logDir = __DIR__ . '/../uploads';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/sent_replies.log';
        $logEntry = "====================================================================\n";
        $logEntry .= "TIMESTAMP: {$timestamp}\n";
        $logEntry .= "FROM: \"{$fromName}\" <{$fromEmail}>\n";
        $logEntry .= "TO: {$to}\n";
        $logEntry .= "SUBJECT: {$subject}\n";
        $logEntry .= "MESSAGE:\n";
        $logEntry .= "--------------------------------------------------------------------\n";
        $logEntry .= $message . "\n";
        $logEntry .= "====================================================================\n\n";

        // Append to local log file
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        // Attempt actual PHP mail sending
        $headers = [
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=utf-8',
            'From' => "\"{$fromName}\" <{$fromEmail}>",
            'Reply-To' => $fromEmail,
            'X-Mailer' => 'PHP/' . phpversion()
        ];

        $headersStr = '';
        foreach ($headers as $k => $v) {
            $headersStr .= "{$k}: {$v}\r\n";
        }

        // Convert newlines in message to HTML breaks for standard mail
        $htmlMessage = nl2br(htmlspecialchars($message));
        
        // Suppress warning from mail() in case sendmail is not configured on local XAMPP
        @mail($to, $subject, $htmlMessage, $headersStr);

        return true;
    }
}
