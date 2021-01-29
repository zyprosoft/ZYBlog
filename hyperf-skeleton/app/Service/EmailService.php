<?php


namespace App\Service;


use App\Model\EmailAddressEntry;
use App\Model\EmailAttachmentEntry;
use App\Model\EmailEntry;
use ZYProSoft\Log\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

class EmailService
{
    protected function mailer()
    {
        $mail = new PHPMailer(true);
        $config = config('hyperf-common.mail.smtp');
        $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $config['host'];                    // Set the SMTP server to send through
        $mail->SMTPAuth   = $config['auth'];                                   // Enable SMTP authentication
        $mail->Username   = $config['username'];                     // SMTP username
        $mail->Password   = $config['password'];                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = $config['port'];
        return $mail;
    }

    public function sendEmail(EmailEntry $emailEntry, bool $isInTask = true)
    {
        if (empty($emailEntry)) {
            return;
        }

        $logger = Log::logger('task');
        if (!$isInTask) {
            $logger = Log::logger('default');
        }

        $mail = $this->mailer();
        try{
            $mail->setFrom($emailEntry->from->address, $emailEntry->from->name);
            if (!empty($emailEntry->receivers)) {
                array_map(function (EmailAddressEntry $address) use ($mail) {
                    $mail->addAddress($address->address,$address->name);
                }, $emailEntry->receivers);
            }
            if (isset($emailEntry->replyTo)) {
                $mail->addReplyTo($emailEntry->replyTo->address,$emailEntry->replyTo->name);
            }
            if (isset($emailEntry->ccReceivers)) {
                array_map(function (EmailAddressEntry $address) use ($mail) {
                    $mail->addCC($address->address,$address->name);
                }, $emailEntry->ccReceivers);
            }
            if (isset($emailEntry->bccReceivers)) {
                array_map(function (EmailAddressEntry $address) use ($mail) {
                    $mail->addBCC($address->address,$address->name);
                }, $emailEntry->bccReceivers);
            }
            if (isset($emailEntry->attachments)) {
                array_map(function (EmailAttachmentEntry $attachment) use ($mail) {
                    $mail->addAttachment($attachment->path, $attachment->name);
                }, $emailEntry->attachments);
            }
            $mail->isHTML($emailEntry->isHtml);
            $mail->Subject = $emailEntry->subject;
            $mail->Body = $emailEntry->body;
            if (isset($emailEntry->altBody)) {
                $mail->AltBody = $emailEntry->altBody;
            }
            $mail->send();
            $logger->info("did send email with info:".json_encode($emailEntry));
        }catch (Exception $exception) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $trace = $exception->getTraceAsString();
            $logger->error("send email error code:$code message:$message");
            $logger->error($trace);
        }
    }
}