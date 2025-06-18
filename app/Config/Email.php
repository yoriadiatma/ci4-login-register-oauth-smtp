<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail;
    public string $fromName;
    public string $recipients = '';

    public string $protocol = 'smtp';

    // SMTP Settings
    public string $SMTPHost;
    public string $SMTPUser;
    public string $SMTPPass; // BUKAN password utama akun Google
    public int $SMTPPort;
    public int $SMTPTimeout;
    public bool $SMTPKeepAlive = true;
    public string $SMTPCrypto;

    // Pengaturan tambahan opsional
    public string $mailType = 'html';
    public string $charset = 'UTF-8';
    public bool $validate = true;

    public function __construct()
    {
        parent::__construct();

        $this->fromEmail     = env('EMAIL_FROM_EMAIL', 'noreply@example.com');
        $this->fromName      = env('EMAIL_FROM_NAME', 'My App');
        $this->SMTPHost      = env('SMTP_HOST', 'smtp.gmail.com');
        $this->SMTPUser      = env('SMTP_USER', 'example@gmail.com');
        $this->SMTPPass      = env('SMTP_PASS', 'your-app-password');
        $this->SMTPPort      = (int) env('SMTP_PORT', 587);
        $this->SMTPTimeout   = (int) env('SMTP_TIMEOUT', 5);
        $this->SMTPCrypto    = env('SMTP_CRYPTO', 'tls');
    }
}
