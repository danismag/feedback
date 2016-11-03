<?php

namespace App\Model;

/**
*   Менеджер пользователей
*
*/
class User extends Model
{
    const TABLE = 'users';
    
    public $login;
    public $sid;                           // идентификатор текущей сессии
    public $is_admin;
    private $uid;                           // идентификатор текущего пользователя
    private $db;                            // подключенная БД

    /**
    *   Конструктор
    */
    /*private function __construct()
    {

        $this->sid = null;
        $this->uid = null;
        $this->db = MSQL::instance();
    }*/

    /**
    *   Авторизация
    *   $login - логин
    *   $password - пароль
    *   $remember - нужно ли запоминать в куках
    *   Результат - true / false
    */
    public function login($login, $password, $remember = true)
    {

        // вытаскиваем пользователя из БД
        $user = $this->getByLogin($login);

        if ($user == null) return false;

        $id_user = $user['id_user'];

        // проверяем пароль
        if ($user['password'] != hash('ripemd128', 'qm&h*'.$pass.'pg!@')) {
                return false;
        }

        // запоминаем имя и пароль
        if ($remember) {
            $expire = time() + 3600*24*30;
            setcookie('login', $login, $expire);
            setcookie('password', hash('ripemd128', 'qm&h*'.$pass.'pg!@'), $expire);
        }

        // открываем сессию и запоминаем SID
        $this->sid = $this->OpenSession($id_user);
        $this->uid = $id_user;

        return true;
    }

    /**
    *   Выход
    */
    public function logout()
    {

        setcookie('login', '', time() - 1);
        setcookie('password', '', time() - 1);
        unset($_COOKIE['login']);
        unset($_COOKIE['password']);
        unset($_SESSION['sid']);
        $this->sid = null;
        $this->uid = null;
    }

    /**
    *   Получение пользователя
    *   $id_user - если не указан, берем текущего
    *   Результат - объект пользователя
    */
    public function get($id_user = null)
    {

        // Если id_user не указан, берем его по текущей сессии
        if ($id_user == null) {
            $id_user = $this->GetUid();
        }

        if ($id_user == null) {
            return null;
        }

        // А теперь просто возвращаем пользователя по id_user
        $query = "SELECT * FROM users WHERE id_user = $id_user";

        return $this->db->Select($query)[0];
    }

    /**
    *   Получаем пользователя по логину
    */
    public function getBylogin($login)
    {

        $query = 'SELECT * FROM users WHERE login = ' . htmlentities($login);

        return $this->db->Select($query)[0];
    }

    /**
    *   Проверка наличия привилегий
    *   $id-user - если не указан, значит, для текущего
    *   Результат - true / false
    */
    public function can($id_user = null)
    {

        if ($id_user == null) {
            $id_user = $this->GetUid();
        }

        if ($id_user == null) {
            return false;
        }

        $query = "SELECT is_admin FROM users WHERE id_user = $id_user";

        return $this->db->Select($query)['is_admin'];
    }

    /**
    *   Получение id текущего пользователя
    *   Результат - UID
    */
    public function getUid()
    {

        // Проверка кеша
        if ($this->uid != null) {
            return $this->uid;
        }

        // Берем по текущей сессии
        $sid = $this->GetSid();
        if ($sid == null) {
            return null;
        }

        $query = "SELECT id_user FROM users WHERE sid = $sid";
        $result = $this->db->Select($query);

        // Если сессию не нашли, значит пользователь не авторизован
        if (count($result) == 0) { return null; }

        // Если нашли - запоминаем ее
        $this->uid = $result[0]['id_user'];
        return $this->uid;
    }

    /**
    *   Функция возвращает идентификатор текущей сессии
    *   Результат - SID
    */
    private function getSid()
    {

        // Проверка кеша
        if ($this->sid != null) { return $this->sid;    }

        // Ищем SID в сессии
        $sid = $_SESSION['sid'];

        // Если нет сессии, ищем логин и пароль в куках
        // и пробуем переподключиться
        if ($sid == null && isset($_COOKIE['login'])) {
            $user = $this->GetBylogin($_COOKIE['login']);
        }

        if ($user != null && $user['password'] == $_COOKIE['password']) {
            $sid = $this->OpenSession($user['id_user']);
        }

        // запоминаем в кэш
        if ($sid != null) $this->sid = $sid;

        return $sid;
    }
    
    /**
    *   Открытие новой сессии
    *   Результат - SID
    */
    private function openSession($id_user)
    {

        // Выбираем SID из БД и/или генерируем SID
        $query = "SELECT sid FROM users WHERE id_user = $id_user";
        $result = $this->db->Select($query);

        // Берем SID из таблицы, даже null
        $sid = $result[0]['sid'];

        // проверка на null и создание sid
        if ($sid == null) {
            $sid = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].time());

            // внесение sid в базу
            $object = array('sid' => $sid);
            $where = "id_user = $id_user";
            $this->db->Update('users', $object, $where);
        }

        // Регистрируем сессию
        $_SESSION['sid'] = $sid;

        return $sid;
    }

}

?>