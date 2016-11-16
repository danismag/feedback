<?php

namespace App\Models;

/**
*   Класс для работы с отзывами
*/
class Comment extends Model
{
    const TABLE = 'comments';

    public $id_comment;                 // id отзыва
    public $text;                       // содержание отзыва
    public $username;                   // имя пользователя
    public $email;                      // e-mail пользователя
    public $image;                      // путь к файлу с изображением
    //public $create_time;                // дата/время создания отзыва
    //public $edit_time;                  // дата/время редактирования отзыва
    public $edited;                     // был ли отзыв отредактирован
    public $approved;                   // был ли отзыв одобрен
    protected $db;                      // подключение к БД

    /**
    *   Конструктор
    */
    public function __construct()
    {
        $this->edited = 0;
        $this->approved = 0;
        
        $this->db = MSQL::instance();
    }
    
    /**
    *   Валидация и создание объекта отзыва
    *
    *   @param string $name - имя пользователя
    *   @param string $email - email пользователя
    *   @param string $text - текст комментария
    *   @return Comment - объект отзыва или null
    */
    public static function validate($name, $email, $text)
    {
        // мультиисключение для сообщений об ошибках
        $err = new \App\Exceptions\Multiexception;

        // Обрезка и фильтрация текста
        $name = filter_var(trim($name), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $text = filter_var(trim($text), FILTER_SANITIZE_STRING);

        // проверка на заполнение
        if ($name == '') {
            
            $err[] = new \Exception('Введите имя пользователя');
        }
        
        if ($text == '') {
            
            $err[] = new \Exception('Введите текст отзыва');
        }

        // Валидация e-mail
        if ($email == '') {
            
            $err[] = new \Exception('Введите e-mail');
            
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            $err[] = new \Exception('Введенный e-mail не соответствует стандартам');
        }

        // подсчет исключений
        if (count($err) > 0) {
            
            throw $err;
            return null;            
        }

        $comment = new Comment;
        $comment->username = $name;
        $comment->email = $email;
        $comment->text = $text;
        
        return $comment;        
    }
    
    /**
    *   Выборка отзывов, отсортированных по: дате (по умолч.), имени, email
    *   
    *   @param string $sort - параметр сортировки 'sortbydate', 'sortbyname' , 'sortbyemail'
    *   @param bool $not_approved - выводить ли неодобренные отзывы
    *   @return Comment - массив объектов отзывов
    */
    public static function getComments($sort, $not_approved = false)
    {

        // Выводить ли неодобренные отзывы
        $where = ($not_approved ? 'WHERE approved = 1' : '');

        // анализ параметра сортировки
        switch ($sort) {

            case 'sortbydate':
                $order = 'create_time DESC';
                break;
            case 'sortbyname': 
                $order = 'username';
                break;
            case 'sortbyemail': 
                $order = 'email';
                break;
            default:
                $order = 'create_time DESC';
                break;                    
        }
            
        // формирование запроса к БД
        $sql = "SELECT * FROM comments $where ORDER BY $order";
            
        $db = MSQL::instance();
            
        return $db->select($sql, self::class);
    }
    
    /**
    *   Выборка отзыва из БД по id
    *   
    *   @param int $id - id отзыва в БД
    *   @return Comment - объект отзыва
    */
    public function getCommentById($id)
    {

        return self::findWhere(['id_comment' => $id]);
    }
    
    /**
    *   Сохранение отзыва в БД
    *
    *   @return bool - сохранен ли отзыв в БД
    */
    public function create()
    {

        if ($this->db->insert(self::TABLE, $this)) {
                
            throw new \Exception('Отзыв успешно сохранен');
                
            return true;
        }
               
        // запись не произошла        
        return false;
    }

    /**
    *   Сохранение отредактированного комментария
    *   
    *   @param int - число записанных строк (1) либо null
    */
    public function save()
    {

        return $this->db->update(
            self::TABLE, 
            self, 
            ['id_comment' => $this->id_comment]);        

    }

    /**
    *   Одобрение комментария
    *   
    *   @return int - число строк (1)
    */
    public function approve()
    {
        $this->approved = 1;

        return $this->save();
    }

    /**
    *   Удаление комментария из базы данных
    *   
    *   @return int - число удаленных строк (1)
    */
    public function delete()
    {
        return $this->db->delete(
            self::TABLE,
            ['id_comment' => $this->id_comment]);
    }
     
}

?>