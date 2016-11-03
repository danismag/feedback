<?php

/**
*   Класс для работы с отзывами
*/

namespace App\Model;

class Comment extends Model
{
    const TABLE = 'comments';

    public $id_comment;                 // id отзыва
    public $text;                       // содержание отзыва
    public $username;                   // имя пользователя
    public $email;                      // e-mail пользователя
    public $image;                      // путь к файлу с изображением
    public $create_time;                // дата/время создания отзыва
    public $edit_time;                  // дата/время редактирования отзыва
    public $edited;                     // был ли отзыв отредактирован
    public $approved;                   // был ли отзыв одобрен


    /**
    *   Конструктор
    */
    public function __construct()
    {

        $this->edit_time = null;
        $this->com_text = null;
        $this->email = null;
        self::db = MSQL::instance();
    }

    /**
    *   Валидация комментария
    *   Результат - массив наличия ошибок $error
    *   $error['exist'] - true (1) / false (0) - есть ли вообще ошибки
    *   $error['name', 'email', 'text'] - есть ли ошибки в имени, email, тексте
    */
    public function validat($name, $email, $text)
    {

        // массив для записи ошибок
        $error = array();

        // Обрезка и фильтрация текста
        $this->username = filter_var(trim($name), FILTER_SANITIZE_STRING);
        $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $this->com_text = filter_var(trim($text), FILTER_SANITIZE_STRING);

        // проверка на заполнение
        if ($this->username == '') $error['name'] = 1;
        if ($this->email == '') $error['email'] = 1;
        if ($this->com_text == '') $error['text'] = 1;

        // Проверка e-mail
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) $error['email'] = 1;

        // подсчет ошибок
        if (count($error) == 0) {
        $error['exist'] = 0;
        }   else {
                $error['exist'] = 1;
            }

        return $error;
    }

    /**
    *   Предпросмотр комментария
    *   Результат - массив "поле - значение" либо null
    */
    public function preview($name, $email, $text)
    {

        // Проверка валидности отзыва
        $error = $this->validat($name, $email, $text);

        // Если ошибок нет
        if ($error['exist'] == 0) {
            // массив отзыва для предпросмотра
            $comment['name'] = $this->username;
            $comment['email'] = $this->email;
            $comment['text'] = $this->com_text;

            return $comment;
        }

        return null;
    }

    /**
    *   Внесение комментария в базу данных
    *   результат - true / false
    */
    public function setCom($name, $email, $text, $image = null)
    {

        // Проверка валидности отзыва
        $error = $this->validat($name, $email, $text);

        // если ошибок нет
        if ($error['exist'] == 0) {
            // Формирование массива для вставки
            $object['username'] = $this->username;
            $object['email'] = $this->email;
            $object['text'] = $this->com_text;

            if ($image != null) {
                $object['image'] = $image;
            }

            $this->db->Insert('comments', $object);

            return true;
        }

        // Валидация не пройдена, запись не произошла
        return false;
    }

    /**
    *   Отображение отзыва по id
    *   Результат -  ассоциативный массив "поле - значение"
    */
    public function getById($id_com)
    {

        // Формирование запроса к БД
        $query = 'SELECT * FROM '. self::TABLE. ' WHERE id_comment = '. $id_com;

        return $this->db->Select($query);
    }

    /**
    *   Сохранение отредактированного комментария
    *   Результат - число записанных строк (1) либо null
    */
    public function save($name, $email, $text, $id_com)
    {

        // Проверка валидности
        $error = $this->validat($name, $email, $text);

        // если ошибок нет
        if ($error['exist'] == 0) {
            // формирование массива для БД
            $object['username'] = $name;
            $object['email'] = $email;
            $object['text'] = $text;

            $where = "id_comment = $id_com";

            // запись в БД
            return $this->db->Update('comments', $object, $where);
        }

        return null;
    }

    /**
    *   Изменение статуса комментария администратором
    *   Результат - число строк (1) либо null
    */
    public function approve($id_com)
    {

        // формирование запроса к БД
        $object['approved'] = 1;
        $where = "id_comment = $id_com";

        return $this->db->Update('comments', $object, $where);
    }

    /**
    *   Удаление комментария из базы данных
    *   Результат - 1 либо null
    */
    public function delete($id_com)
    {

        $where = "id_comment = $id_com";

        return $this->db->Delete('comments', $where);
    }

    /*
    *   Вывести комментарии по: дате (по умолч.), имени, email
    *   $sort - строковый параметр сортировки 'date', 'name' , 'email'
    *   $notapproved - 1 или 0 - выводить ли неодобренные отзывы
    *   Результат - двумерный ассоциативный массив с полями отзывов
    */
    public function get($sort, $notApproved = 0)
    {

            // Выводить ли неодобренные отзывы
            $notApproved == 0 ? $where = 'WHERE approved = 1' : $where = '';

            // анализ параметра сортировки
            switch ($sort) {

                case 'sortbydate': 	$order = 'create_time DESC';
                    break;
                case 'sortbyname':	$order = 'username';
                    break;
                case 'sortbyemail':	$order = 'email';
                    break;
                default: 	$order = 'create_time DESC';
            }
            // формирование запроса к БД
            $query = "SELECT * FROM comments $where ORDER BY $order";

            return $this->db->Select($query);
    }

}

?>