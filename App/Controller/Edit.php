<?php

/**
*   Контроллер страниц редактирования (админ)
*
*/

namespace App\Controller;

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