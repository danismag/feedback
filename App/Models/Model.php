<?php

namespace App\Models;

/**
*   Базовый класс моделей
*
*   @method array findAll() выборка всех объектов класса из БД
*   @method object get() выборка объекта из БД (генератор)
*/
abstract class Model
{    
    const TABLE = '';
    
    /**
    *   Выборка всех объектов из БД
    *
    *   @return array - массив объектов класса
    */
    public static function findAll()
    {
        $db = MSQL::instance();

        return $db->select(
            'SELECT * FROM '. static::TABLE,
            static::class);
    }

    /**
    *   Выборка по объектов из БД по очереди
    *
    *   @return object - объект класса
    */
    public static function find()
    {
        $db = MSQL::instance();

        return ($db->selectOne(
            'SELECT * FROM '. static::TABLE,
            static::class));
    }
}

?>