<?php

namespace App\Log;

class ComposerLog
{
    private static $translations = [
        'en' => [
            'title' => 'Title',
            'message' => 'Message'
        ],
        'th' => [
            'title' => 'หัวข้อ',
            'message' => 'ข้อความ'
        ],
        'ch' => [
            'title' => '标题',
            'message' => '信息'
        ],
    ];

    /**
     * Format and return the log title.
     *
     * @param string $title
     * @param string $lang
     * @return string
     */
    public static function logTitle($title, $lang = 'en'): string
    {
        self::validateLanguage($lang);
        return self::$translations[$lang]['title'] . ": " . strtoupper($title);
    }

    /**
     * Format and return the log message.
     *
     * @param string $message
     * @param string $lang
     * @return string
     */
    public static function logMessage($message, $lang = 'en'): string
    {
        self::validateLanguage($lang);
        return self::$translations[$lang]['message'] . ": " . $message;
    }

    /**
     * Write a log entry to the specified log file.
     *
     * @param string $title
     * @param string $message
     * @param string $lang
     * @throws \Exception
     */
    public static function writeLog($title, $message, $lang = 'en'): void
    {
        $logEntry = self::logTitle($title, $lang) . " - " . self::logMessage($message, $lang) . "\n";
        $logFilePath = 'C:\Users\ervin\composermakerprojet\var\log\logfile.log';

        // Use file_put_contents with FILE_APPEND flag to append to the log file
        if (file_put_contents($logFilePath, $logEntry, FILE_APPEND) === false) {
            throw new \Exception("Error writing to the log file.");
        }
    }

    /**
     * Validate the language code.
     *
     * @param string $lang
     * @throws \Exception
     */
    private static function validateLanguage($lang): void
    {
        if (!isset(self::$translations[$lang])) {
            throw new \Exception("Error: Invalid language code. Possible values are [th, en, ch].");
        }
    }
}
