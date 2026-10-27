<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

class MailService
{
    private static function loadEnv(): array
    {
        $envPath = __DIR__ . '/../../.env';
        if (!file_exists($envPath)) {
            return [];
        }
        $env = [];
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                $env[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
            }
        }
        return $env;
    }

    private static function getMailer(): PHPMailer
    {
        $env = self::loadEnv();

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $env['MAIL_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $env['MAIL_USERNAME'] ?? '';
        $mail->Password   = $env['MAIL_PASSWORD'] ?? '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int) ($env['MAIL_PORT'] ?? 587);
        $mail->CharSet    = 'UTF-8';

        $fromAddress = $env['MAIL_FROM_ADDRESS'] ?? $mail->Username;
        $fromName    = $env['MAIL_FROM_NAME'] ?? 'Rentifia';
        $mail->setFrom($fromAddress, $fromName);

        return $mail;
    }

    /**
     * Gửi mã OTP xác thực email khi đăng ký tài khoản
     */
    public static function sendOtp(string $recipientEmail, string $otpCode): bool
    {
        try {
            $mail = self::getMailer();
            $mail->addAddress($recipientEmail);
            $mail->isHTML(true);
            $mail->Subject = "[$otpCode] Mã xác thực đăng ký tài khoản - Rentifia";

            $mail->Body = '
            <!DOCTYPE html>
            <html lang="vi">
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 24px; color: #1e293b; }
                    .container { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; }
                    .header { background: linear-gradient(135deg, #09154a 0%, #0f172a 100%); padding: 32px 24px; text-align: center; }
                    .logo { font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; }
                    .logo span { color: #10b981; }
                    .content { padding: 32px 28px; }
                    .greeting { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
                    .desc { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 24px; }
                    .otp-box { background: #f8fafc; border: 2px dashed #10b981; border-radius: 16px; padding: 20px; text-align: center; margin-bottom: 24px; }
                    .otp-label { font-size: 12px; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 1px; margin-bottom: 8px; }
                    .otp-code { font-family: "Courier New", Courier, monospace; font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #09154a; margin: 0; }
                    .warning { font-size: 13px; color: #94a3b8; line-height: 1.5; border-top: 1px solid #f1f5f9; padding-top: 20px; margin-top: 20px; }
                    .footer { background: #f8fafc; padding: 18px 24px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <div class="logo">Rent<span>ifia</span></div>
                    </div>
                    <div class="content">
                        <div class="greeting">Xác thực địa chỉ email của bạn 👋</div>
                        <div class="desc">
                            Cảm ơn bạn đã đăng ký tài khoản tại <strong>Rentifia</strong>. Vui lòng sử dụng mã xác thực (OTP) dưới đây để hoàn tất việc đăng ký:
                        </div>
                        <div class="otp-box">
                            <div class="otp-label">Mã xác thực OTP của bạn</div>
                            <div class="otp-code">' . htmlspecialchars($otpCode) . '</div>
                        </div>
                        <div class="warning">
                            ⏱️ Mã này có hiệu lực trong vòng <strong>5 phút</strong>.<br>
                            🔒 Tuyệt đối không chia sẻ mã này với bất kỳ ai để đảm bảo an toàn cho tài khoản.
                        </div>
                    </div>
                    <div class="footer">
                        © ' . date('Y') . ' Rentifia - Hệ Thống Quản Lý Căn Hộ & Phòng Trọ Thông Minh.
                    </div>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = "Mã xác thực đăng ký Rentifia của bạn là: {$otpCode}. Mã có hiệu lực trong vòng 5 phút. Vui lòng không chia sẻ mã này với bất kỳ ai.";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Rentifia Mail Error: " . $e->getMessage());
            return false;
        }
    }
}
