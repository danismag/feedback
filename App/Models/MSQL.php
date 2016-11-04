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
         
         $q->setFetchMode(\PDO::FETCH_CLASS, $class);
         
         $q->execute();

         return $q->fetch();
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

        foreach ($object as $key => $value) {

            if (substr_count($value, 'id', 0, 2)) {
                continue;
            }
            $colomns[] = $key;
            $masks[] = ":$key";
            $values[] = $value;

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
    *   @param string $where - условие (подстрока запроса)
    *   @return dec - число измененных строк
    */
    public function update($table, $object, $where)
    {

        $sets = [];
        $values = [];

        foreach ($object as $key => $value) {

            $sets[] = "$key = :$key";
            $values[] = $value;

            if ($value === null) {
                $values[$key] = 'NULL';
            }
        }

        $sets_s = implode(', ', $sets);
        $query = "UPDATE $table SET $sets_s WHERE $where";

        $q = $this->dbh->prepare($query);
        $q->execute($values);

        return $q->rowCount();
    }

    /**
    *   Удаление строк
    *
    *   @param string $table - имя таблицы
    *   @param string $where - условие (подстрока запроса)
    *   @return numeric - число удаленных строк
    */
    public function delete($table, $where)
    {

        $query = "DELETE FROM $table WHERE $where";

        $q = $this->dbh->query($query);

        return $q->rowCount();
    }

}

?>