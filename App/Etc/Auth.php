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
            $_SESSION['sid'] = $user->sid;
            
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
        // Ищем SID в сессии
        if (isset($_SESSION['sid'])) { 
            
            return true;
        }
        
        // Если нет сессии, ищем логин и пароль в куках
        // и пробуем переподключиться
        if (isset($_COOKIE['password']) && isset($_COOKIE['login'])) {
            
            $user = \App\Model\User::getUserByLogin($_COOKIE['login']);
            
            if ($user) {
                
                if ($_COOKIE['password'] == $user->password) {
                    
                    return self::openSession($user, true);                    
                }
            }
        }

        return false;
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
    }
}


?>