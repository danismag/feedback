<?php

namespace App\Controllers;

/**
*   Контроллер страниц редактирования (админ)
*
*/
class Edit extends Base
{
       
    /**
    *   Конструктор
    */
    
    public function __construct($title, $need_login = false)
    {
        parent::__construct($title);
        $this->need_login = $need_login;
        $this->user = App\Model\User::instance()->get();
    }
    
}


?>