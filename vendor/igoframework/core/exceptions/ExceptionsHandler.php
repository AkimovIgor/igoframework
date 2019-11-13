<?php

namespace Igoframework\Core\Exceptions;

class ExceptionsHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }

        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    public function errorHandler($errCode, $errMessage, $errFile, $errLine)
    {
        // $error = error_get_last();
        // dd($error);
        // if (!error_reporting()) {
        //     return false;
        // }
        $this->errorLog($errMessage, $errFile, $errLine);
        $this->displayException($this->errorCode($errCode), $errMessage, $errFile, $errLine);
        return true;
        // var_dump($errno, $errstr, $errfile, $errline);

        // throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    public function getTrace($e)
    {
        $trace = explode("\n", $e->getTraceAsString());
        // $trace = array_reverse($trace);
        array_shift($trace); 
        array_pop($trace); 
        $length = count($trace);
        $result = [];
        
        for ($i = 0; $i < $length; $i++)
        {
            $result[] = substr($trace[$i], strpos($trace[$i], ' '));
        }
        return $result;
    }

    public function exceptionHandler($e)
    {
        $this->errorLog($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayException(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $this->getTrace($e), $e->getCode());
    }

    public function displayException($errno, $errstr, $errfile, $errline, $trace = [], $response = 503)
    {
        http_response_code($response);

        if ($response === 404 && !DEBUG) {
            require_once WWW . '/errors/404.html';
            die;
        }

        if (DEBUG) {
            require_once WWW . '/errors/dev_error.php';
        } else {
            require_once WWW . '/errors/prod_error.php';
        }
        die;
    }

    protected function errorLog($errMessage, $errFile, $errLine, $logFile = ROOT . '/tmp/log/errors.log', $delimiter = '->')
    {
        $str = "[ " . date('Y-m-d H:m:i') . " ] | Сообщение: {$errMessage} | Файл: {$errFile} | Строка: {$errLine}";
        // $delCount = mb_strlen($str);
        // $delLine = "\n";
        // for ($i = 0; $i < $delCount; $i++) {
        //     $delLine .= $delimiter;
        // }
        $delLine = "\n\n$delimiter\n\n";
        error_log($str . $delLine, 3, $logFile);
    }

    private function errorCode($errorCode){
        $errors = array(
            E_ERROR    => 'Ошибка',
            E_WARNING    => 'Предупреждение',
            E_PARSE    => 'Парсинг',
            E_NOTICE    => 'Уведомление',
            E_CORE_ERROR   => 'Основная ошибка',
            E_CORE_WARNING   => 'Основное предупреждение',
            E_COMPILE_ERROR  => 'Ошибка компиляции',
            E_COMPILE_WARNING  => 'Предупреждение компиляции',
            E_USER_ERROR   => 'Пользовательская ошибка',
            E_USER_WARNING   => 'Пользовательское предупреждение',
            E_USER_NOTICE   => 'Пользовательское уведомление',
            E_STRICT    => 'Строгое требование',
            E_RECOVERABLE_ERROR => 'Устранимая ошибка',
            E_DEPRECATED  => 'Возражение',
            E_USER_DEPRECATED  => 'Пользовательское возражение',
        );
        return $errors[$errorCode];
    }

}