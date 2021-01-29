<?php


namespace App\Model;

class EmailEntry
{
    public EmailAddressEntry $from;

    public array $receivers;//内容是EmailAddressEntry

    public EmailAddressEntry $replyTo;//EmailAddressEntry

    public array $ccReceivers;//抄送列表,内容是EmailAddressEntry

    public array $bccReceivers;//密送列表,内容是EmailAddressEntry

    public array $attachments;//附件信息,内容是EmailAttachmentEntry

    public bool $isHtml = true;//是不是html内容

    public string $subject;//邮件主题

    public string $body;//邮件内容

    public string $altBody;//当邮件客户端不支持Html时候的备用显示内容
}