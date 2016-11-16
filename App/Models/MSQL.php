<?php
namespace App\Models;

/**
*   Класс для работы с БД
*/
class MSQL
{
    use \App\Traits\Singleton;           // паттерн "Singleton"

    private $dbh;                        // подключение к БД

    private function __construct()
    {
        // Языковая настойка
        setLocale(LC_ALL, 'ru_RU.UTF8');

        // Создание подключения к БД и настройка режима выборки и ошибок
        $this->dbh = new \PDO ('mysql:host=' . DB_SERVER . '; dbname=' . DB_BASE,
            DB_USER, DB_PASSWORD);
        $this->dbh->exec('SET NAMES UTF-8');
        $this->dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
    *   Выборка всех объектов по запросу
    *
    *   @param string $sql - полный текст SQL-запроса
    *   @param string $class - имя класса
    *   @return object - массив объектов указанного класса
    */
    public function select($sql, $class)
    {

        $q = $this->dbh->prepare($sql);
        
        $q->setFetchMode(\PDO::FETCH_CLASS, $class);
        
        $q->execute();

        return $q->fetchAll();
    }

    /**
    *   Выборка объектов из БД по очереди (генератор)
    *
    *   @param string $sql - полный текст SQL-запроса
    *   @param string $class - имя класса
    *   @return object - объект указанного класса
    */
    public function selectOne($sql, $class)
    {
         $q = $this->dbh->prepare($sql);
         
         $q->execute();

         return $q->fetchObject($class);
    }
    
    /**
    *   Выборка объекта по запросу
    *
    *   @param string $table - имя таблицы
    *   @param object $object - переданный запрос в виде объекта/массива
    *   @param string $class - имя класса
    *   @return object - объект указанного класса
    */
    public function selectWhere($table, $object, $class)
    {
        $wheres = [];
        $values = [];

        foreach ($object as $key => $value) {
            
            $wheres[] = "$key = :$key";
            $values[$key] = $value;

            if ($value === null) {
                $values[$key] = 'NULL';
            }
        }

        $wheres_s = implode(' AND ', $wheres);
        $sql = "SELECT * FROM $table WHERE $wheres_s ";
        
        $q = $this->dbh->prepare($sql);
         
        $q->execute($values);
        
        return $q->fetchObject($class);        
    }

    /**
    *   Вставка строки
    *
    *   @param string $table - имя таблицы
    *   @param object $object - переданный объект
    *   @return string - идентификатор последней вставленной строки
    */
    public function insert($table, $object)
    {

        $colomns = [];
        $values = [];
        $masks = [];

        foreach ($object as $key => $value) {
            // предотвращение вставки поля id
            if (substr_count($key, 'id', 0, 2)) {
                
                continue;
            }
            $colomns[] = $key;
            $masks[] = ":$key";
            $values[$key] = $value;

            if ($value === null) {
                $values[$key] = 'NULL';
            }
        }

        $colomns_s = implode(', ', $colomns);
        $masks_s = implode(', ', $masks);
        $sql = "INSERT INTO $table ( $colomns_s ) VALUES ( $masks_s )";

        $q = $this->dbh->prepare($sql);
        $q->execute($values);

        return $this->dbh->lastInsertId();
    }

    /**
    *   Изменение строк
    *
    *   @param string $table - имя таблицы
    *   @param object $object - переданный объект
    *   @param object $where - условие в виде объекта/массива 'поле' - 'значение'
    *   @return int - число измененных строк
    */
    public function update($table, $object, $where)
    {

        $sets = [];
        $wheres = [];
        $values = [];

        foreach ($object as $key => $value) {

            $sets[] = "$key = :$key";
            $values[$key] = $value;

            if ($value === null) {
                $values[$key] = 'NULL';
            }
        }
        
        foreach ($where as $wkey => $wvalue) {
            
            $wheres[] = "$wkey = :$wkey";
            $values[$wkey] = $wvalue;

            if ($wvalue === null) {
                $values[$wkey] = 'NULL';
            }
        }
        
        $sets_s = implode(', ', $sets);
        $wheres_s = implode(' AND ', $wheres);       
        
        $sql = "UPDATE $table SET $sets_s WHERE $wheres_s";

        $q = $this->dbh->prepare($sql);
        $q->execute($values);

        return $q->rowCount();
    }

    /**
    *   Удаление строк
    *
    *   @param string $table - имя таблицы
    *   @param object $where - условие в виде массива/объекта
    *   @return int - число удаленных строк
    */
    public function delete($table, $where)
    {
        $wheres = [];
        $values = [];

        foreach ($where as $key => $value) {
            
            $wheres[] = "$key = :$key";
            $values[$key] = $value;

            if ($value === null) {
                $values[$key] = 'NULL';
            }
        }

        $wheres_s = implode(' AND ', $wheres);
        
        $sql = "DELETE FROM $table WHERE $wheres_s";

        $q = $this->dbh->prepare($sql);
        $q->execute($values);

        return $q->rowCount();
    }

}

?>