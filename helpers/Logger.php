<?php

class Logger
{
    private static string $logFile = __DIR__ . '/../logs/actions.log';

    public static function log(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[$timestamp] $message" . PHP_EOL;

        file_put_contents(self::$logFile, $entry, FILE_APPEND);
    }
}
