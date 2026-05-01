<?php
namespace App\Config;

class Mail
{
    public static function getConfig()
    {
        // Load from environment variables or config file
        return [
            'host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
            'port' => getenv('SMTP_PORT') ?: 587,
            'username' => getenv('SMTP_USERNAME') ?: '',
            'password' => getenv('SMTP_PASSWORD') ?: '',
            'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
            'from_email' => getenv('MAIL_FROM_EMAIL') ?: 'noreply@' . $_SERVER['HTTP_HOST'],
            'from_name' => getenv('MAIL_FROM_NAME') ?: 'Blueprint App'
        ];
    }
}