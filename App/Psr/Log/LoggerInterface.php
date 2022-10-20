<?php

namespace Psr\Log;

interface LoggerInterface
{
    /**
    * Система не может быть использована
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function emergency($message, array $context = array());
    /**
    * Требуется немедленная реакция
    *
    * Например, весь веб-сайт не открывается, БД недоступна и т. д.
    * В такой ситуации можно оправлять СМС-уведомление среди ночи
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function alert($message, array $context = array());
    /**
    * Критическое состояние.
    *
    * Например, компонент системы недоступен
    * или выброшено неожиданное исключение
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function critical($message, array $context = array());
    /**
    * Ошибка во время исполнения программы, не требующая
    * немедленной реакции; обычно должна логироваться и наблюдаться
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function error($message, array $context = array());
    /**
    * Исключительное событие, не являющееся ошибкой
    *
    * Например, использование устаревшего контракта,
    * нежелательные действия, которые не обязательно неправильны
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function warning($message, array $context = array());
    /**
    * Нормальное, однако значительное событие
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function notice($message, array $context = array());
    /**
    * События, представляющие некоторый интерес
    *
    * Например, пользователь авторизовался в системе; SQL-запрос *
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function info($message, array $context = array());
    /**
    * Детальная отладочная информация
    *
    * @param string $message
    * @param array $context
    * @return void
    */
    public function debug($message, array $context = array());
    /**
    * Логирование с произвольным уровнем
    *
    * @param mixed $level
    * @param string $message
    * @param array $context
    * @return void
    */
    public function log($level, $message, array $context = array());
}
