<?php

namespace App\Models;

use \App\Exceptions\Multiexception;

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
        $this->edited = $this->edited ?? 0;
        $this->approved = $this->approved ?? 0;
        
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
        $err = new Multiexception;

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

        $comment = new self;
        $comment->username = $name;
        $comment->email = $email;
        $comment->text = $text;
        
        return $comment;        
    }
    
    /**
    *   Выборка отзывов, отсортированных по: дате (по умолч.), имени, email
    *   
    *   @param string $sort - параметр сортировки 'sortbydate', 'sortbyname' , 'sortbyemail'
    *   @param bool $approved - вывод только одобренных отзывов
    *   @return Comment - массив объектов отзывов
    */
    public static function getComments($sort, $approved = true)
    {

        // Выводить ли неодобренные отзывы
        $where = ($approved ? 'WHERE approved = 1' : '');
        
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
    public static function getCommentById($id)
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
                
            throw new \Exception('Отзыв успешно создан');
                
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
        $result = $this->db->update(
            self::TABLE, 
            $this, 
            ['id_comment' => $this->id_comment]);
        
        throw new \Exception('Отзыв успешно сохранен');
        
        return $result;
        
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
        if ('NULL' != $this->image) {
            
            unlink(BASE_PATH . $this->image);
        }
        
        
        $result = $this->db->delete(
            self::TABLE,
            ['id_comment' => $this->id_comment]);
        
        throw new \Exception('Отзыв удален');
        
        return $result;
    }
    
    /**
    *   Редактирование комментария
    *   
    *   @param Comment $newcom - объект комментария с изменениями
    */
    public function edit($newcom)
    {
        // Передача отредактированных данных
        $this->username = $newcom->username;
        $this->email = $newcom->email;
        $this->text = $newcom->text;
        
        // Внесение сопутсвующих изменений
        $this->edited = 1;
        $this->approved = 1;
        $this->edit_time = date('Y-m-d H:i:s', time());
        
        $this->save();
    }
    
    /**
    *   очистка папки images от временных файлов-изображений,
    *   образовавшихся при предпрсмотре
    */
    public static function clearImages()
    {
        // Получаем список файлов в папке images
        $images = scandir(BASE_PATH . '/images');
        
        // Убираем из списка элементы '.' и '..'
        unset($images[0], $images[1]);
        
        // добавляем относительный путь от корня сайта
        foreach($images as &$img) {
            
            $img = '/images/' . $img;
        }
        
        // получаем список всех отзывов
        $comments = self::findAll();
        
        foreach($comments as $com) {
            
            if ('NULL' != $com->image) {
                
                // получаем ключ
                $key = array_search($com->image, $images);
                
                // удаляем из списка
                unset($images[$key]);
            }
        }
        
        // Удаляем несвязанные с записями в базе файлы изображений
        
        foreach($images as $img) {
            
            if (file_exists(BASE_PATH . $img)) {
                
                unlink(BASE_PATH . $img);
            }
        }
    }
}

?>