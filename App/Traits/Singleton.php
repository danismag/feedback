<?php

namespace App\Traits;

/**
*   Трейт Singleton
*
*/
trait Singleton
{
    private static $instance;
   
    /**
    *   Возвращает экземпляр класса
    *
    *   @return object - экземпляр указанного класса
    */
    public static function instance()
    {
        if (null === static::$instance){
            static::$instance = new static;
        }

        return static::$instance;
    }
}

?>