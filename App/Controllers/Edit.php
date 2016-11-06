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
    
    // отображает страницу для админа
    protected function actionIndex()
    {
        //TODO
        
    }
    
    // Проводит одобрение отзыва
    protected function actionApprove()
    {
        // TODO
    }
    
    // Редактирование отзыва
    protected function actionComment($id)
    {
        //TODO
    }
    
    
}


?>