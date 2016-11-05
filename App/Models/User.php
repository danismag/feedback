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
    
    /**
    *   Поиск пользователя в БД
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
            
            return null;            
        }
        
        // ищем пользователя в БД
        $password = hash('ripemd128', 'qm&h*' . $password . 'pg!@');
        $user = User::findWhere(['login' => $login, 'password' => $password]);

        if ($user == null) {
            
            return null;
        }
                
        return $user;               
    }
    
}

?>