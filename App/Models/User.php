<?php

namespace App\Models;

/**
*   Менеджер пользователей
*
*   @property string $login - логин пользователя
*   @property string $password - пароль пользователя
*   @property string $sid - идентификатор сессии
*   @property bool $is_admin - наличие административных прав
*   @property int $id_user - идентификатор пользователя
*/
class User extends Model
{
    const TABLE = 'users';
    
    public $login;
    public $password;
    public $sid;            
    public $is_admin;       
    public $id_user;
    
    private static $instance;          // ссылка на текущего пользователя
    
    /**
    *
    */
    public function __construct()
    {
        self::$instance = $this;
    }
    
    /**
    *   Возвращает теущего пользователя
    *
    *   @return User объект текущего пользователя
    */
    public static function currentUser()
    {
        return self::$instance;
        
    }
    
    /**
    *   Поиск пользователя по логину и паролю в БД
    *
    *   @param string $login - логин
    *   @param string $password - пароль
    *   @return User - объект пользователя или null
    */
    public static function getUser($login, $password)
    {
        // санитация данных
        $login = filter_var(trim($login), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($password), FILTER_SANITIZE_STRING);
        
        if ($login == '' || $password == '') {
            
            throw new \Exception('Неверная пара логин-пароль');            
            return null;            
        }
        
        // ищем пользователя в БД
        $password = hash('ripemd128', 'qm&h*' . $password . 'pg!@');
        $user = User::findWhere(['login' => $login, 'password' => $password]);

        if ($user == null) {
            
            throw new \Exception('Неверная пара логин-пароль');            
            return null;
        }
                
        return $user;               
    }
    
    /**
    *   Поиск пользователя по логину в БД
    *
    *   @param string $login - логин
    *   @return User - объект пользователя или null
    */
    public static function getUserByLogin($login)
    {
        // санитация данных
        $login = filter_var(trim($login), FILTER_SANITIZE_STRING);
        
        if ($login == '') {
            
            return null;            
        }
        
        // ищем пользователя в БД
        $user = User::findWhere(['login' => $login]);

        if ($user == null) {
            
            return null;
        }
                
        return $user;               
    }
    
}

?>