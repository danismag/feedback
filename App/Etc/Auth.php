<?php

namespace App\Etc;

/**
*   Класс для работы с сессиями
*
*   @property object $user - ссылка на пользователя
*   @property string $sid - идентификатор текущей сессии
*/
class Auth 
{
    public static $user;
    public static $sid;
    
    /**
    *   Открытие сессии
    *
    *   @param object $user - объект пользователя или null
    *   @param bool $remember - нужно ли запоминать пользователя
    *   @return bool - открыта ли сессия
    */
    public static function openSession($user, $remember = false)
    {        
        if ($user) {
            // Регистрируем сессию
            $_SESSION['sid'] = self::$sid = $user->sid;
            self::$user = $user;
            
            // запоминаем имя и пароль
            if ($remember) {
                $expire = time() + 3600*24*30;
                setcookie('login', $user->login, $expire);
                setcookie('password', $user->password, $expire);
            }
            
            return true;            
        }

        return false;
    }
    
    /**
    *   Проверка авторизации пользователя
    *
    *   @return bool является ли пользователь авторизованным
    */
     public static function isLogin()
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
    *   Закрытие сессии
    */
    public static function closeSession()
    {
        setcookie('login', '', time() - 1);
        setcookie('password', '', time() - 1);
        unset($_COOKIE['login']);
        unset($_COOKIE['password']);
        unset($_SESSION['sid']);
        self::$sid = null;
        self::$user = null;
    }
}


?>