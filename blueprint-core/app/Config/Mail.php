<?php
namespace App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    public static function getConfig()
    {
        return [
            'host'       => $_ENV['SMTP_HOST']       ?? 'smtp.hostinger.com',
            'port'       => $_ENV['SMTP_PORT']       ?? 587,
            'username'   => $_ENV['SMTP_USERNAME']   ?? '',
            'password'   => $_ENV['SMTP_PASSWORD']   ?? '',
            'encryption' => $_ENV['SMTP_ENCRYPTION'] ?? 'tls',
            'from_email' => $_ENV['MAIL_FROM_EMAIL'] ?? 'noreply@blueprintcaretech.com',
            'from_name'  => $_ENV['MAIL_FROM_NAME']  ?? 'Blueprint Clinical System',
        ];
    }

   public static function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        $config = self::getConfig();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['username'];
            $mail->Password   = $config['password'];
            $mail->SMTPSecure = $config['encryption'] === 'ssl'
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int)$config['port'];

            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($toEmail, $toName);

            // Embed Blueprint logo as CID attachment
            // Works in Outlook, Gmail, Apple Mail and mobile clients
            $logoPath = self::getLogoPath();
            if ($logoPath && file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'blueprint-logo', 'blueprint-logo.png', 'base64', 'image/png');
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('Blueprint Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    private static function getLogoPath(): ?string
    {
        // Try multiple paths to support both local and live environments
        $possiblePaths = [
            // Live — Hostinger
            '/home/u469750643/domains/blueprintcaretech.com/public_html/assets/images/favicon.png',
            // Local — XAMPP
            'C:/xampp_new/htdocs/Blueprint/public/assets/images/favicon.png',
            // Relative from Mail.php (App/Config/) — go up to project root
            dirname(__DIR__, 2) . '/public/assets/images/favicon.png',
            dirname(__DIR__, 3) . '/public_html/assets/images/favicon.png',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}