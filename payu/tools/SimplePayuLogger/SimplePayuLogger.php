<?php

define('LOG_DIR', _PS_MODULE_DIR_ . 'payu/log/');
define('LOG_LEVEL', 0);

class SimplePayuLogger
{
    const CUSTOM_DATE_FORMAT = 'Y-m-d G:i:s.u';
    public static $logFile = 'payu.log';

    public static function addLog($type, $function, $message, $order_id = '')
    {
        if (LOG_LEVEL == 1) {
            $file = self::$logFile;
            if (is_array($type)) {
                foreach ($type as $t) {
                    $file = LOG_DIR . $type . '.log';
                    self::writeToLog($function, $message, $order_id, $file);
                }
            } else {
                $file = LOG_DIR . $type . '.log';
                self::writeToLog($function, $message, $order_id, $file);
            }
        }
    }

    public static function formatMessage($message)
    {
        return "[" . self::getTimestamp() . "] {$message}" . PHP_EOL;
    }

    public static function getTimestamp()
    {
        $originalTime = microtime(true);
        $micro = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);
        $date = new DateTime(date('Y-m-d H:i:s.' . $micro, $originalTime));
        return $date->format(self::CUSTOM_DATE_FORMAT);
    }

    /**
     * @param $function
     * @param $message
     * @param $order_id
     */
    private static function writeToLog($function, $message, $order_id, $logFile)
    {
        if (!file_exists($logFile)) {
            fopen($logFile, 'a');
        };

        if (!self::$logFile) {
            throw new RuntimeException('Cannot open' . $logFile . ' file!');
        }
        $msg = ' <' . $order_id . '> ' . ' {' . $function . '} ' . $message;
        file_put_contents($logFile, self::formatMessage($msg), FILE_APPEND);
    }


} 
