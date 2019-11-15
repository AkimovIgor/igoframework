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

    /**
     * Обработка ошибок
     *
     * @param  string $errCode Сообщение об ошибке
     * @param  string $errMessage Файл, в котором произошла ошибка
     * @param  int $errFile Строка на которой произошла ошибка
     * @param  string $errLine Строка на которой произошла ошибка
     *
     * @return void
     */
    public function errorHandler($errCode, $errMessage, $errFile, $errLine)
    {
        $this->errorLog($errMessage, $errFile, $errLine);
        $this->displayException($this->errorCode($errCode), $errMessage, $errFile, $errLine);
        return true;
        // throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    /**
     * Получить трассировку стека в виде массива
     *
     * @param  object $e Объект исключения
     *
     * @return array
     */
    public function getTrace($e)
    {
        $trace = explode("\n", $e->getTraceAsString());
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

    /**
     * Обработка исключений
     *
     * @param  object $e Объект исключения
     *
     * @return void
     */
    public function exceptionHandler($e)
    {
        $this->errorLog($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayException(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $this->getTrace($e), $e->getCode());
    }

    /**
     * Отображение исключений и ошибок
     *
     * @param  int $errno Номер/тип ошибки/исключения
     * @param  string $errstr Сообщение об ошибке
     * @param  string $errfile Файл, в котором произошла ошибка
     * @param  string $errline Строка на которой произошла ошибка
     * @param  string $trace Данные стека
     * @param  string $response Код ответа сервера
     *
     * @return void
     */
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

    /**
     * Записать информацию об ошибке или исключении в файл лога
     *
     * @param  string $errMessage Сообщение об ошибке
     * @param  string $errFile Файл, в котором произошла ошибка
     * @param  int $errLine Строка на которой произошла ошибка
     * @param  string $logFile Путь и имя файла лога в который будут записываться ошибки
     * @param  string $delimiter Разделитель между сообщениями в файле лога
     *
     * @return void
     */
    protected function errorLog($errMessage, $errFile, $errLine, $logFile = ROOT . '/tmp/log/errors.log', $delimiter = '->')
    {
        $str = "[ " . date('Y-m-d H:m:i') . " ] | Сообщение: {$errMessage} | Файл: {$errFile} | Строка: {$errLine}";
        $delLine = "\n\n$delimiter\n\n";
        error_log($str . $delLine, 3, $logFile);
    }

    /**
     * Вернуть тип ошибки по её коду
     *
     * @param  int $code Код ошибки
     *
     * @return string
     */
    private function errorCode($code){
        $errors = [
            E_ERROR             => 'Ошибка',
            E_WARNING           => 'Предупреждение',
            E_PARSE             => 'Парсинг',
            E_NOTICE            => 'Уведомление',
            E_CORE_ERROR        => 'Основная ошибка',
            E_CORE_WARNING      => 'Основное предупреждение',
            E_COMPILE_ERROR     => 'Ошибка компиляции',
            E_COMPILE_WARNING   => 'Предупреждение компиляции',
            E_USER_ERROR        => 'Пользовательская ошибка',
            E_USER_WARNING      => 'Пользовательское предупреждение',
            E_USER_NOTICE       => 'Пользовательское уведомление',
            E_STRICT            => 'Строгое требование',
            E_RECOVERABLE_ERROR => 'Устранимая ошибка',
            E_DEPRECATED        => 'Возражение',
            E_USER_DEPRECATED   => 'Пользовательское возражение',
        ];
        return $errors[$code];
    }

}