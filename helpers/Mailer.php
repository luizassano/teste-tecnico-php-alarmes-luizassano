<?php

class Mailer
{
    public static function send(string $to, string $subject, string $message, string $from = "no-reply@system.com"): bool
    {
        $headers = "From: $from\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8";

        return mail($to, $subject, $message, $headers);
    }
}
