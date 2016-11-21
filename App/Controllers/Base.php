<?php

namespace App\Controllers;

/**
*   Контроллер сайта
*
*/
abstract class Base extends Controller
{
    protected $view;                // экземпляр класса для отображения
    

    /**
    *   Конструктор
    *
    *   @param $title string    Заголовок страницы
    */
    public function __construct()
    {        
        $this->view = new \App\View\View;
    }
    
    /**
    *   Авторизация пользователя
    *
    *   @param string $_POST['login']
    *   @param string $_POST['password']
    *   @param bool $_POST['remember']
    *   @return bool - флаг состояния авторизации
    */
    protected function login()
    {
        try {
            
            $user = \App\Models\User::getUser($_POST['login'], $_POST['password']);
            
            \App\Etc\Auth::openSession($user, $_POST['remember']);
        
            $this->redirect('/edit/index');
            
        } catch(\Exception $e) {
            
            $this->view->login = $e;
            
            $this->actionIndex();
        }
        
    }
    
    /**
    *   выход пользователя    
    */
    protected function logout()
    {
        \App\Etc\Auth::closeSession();
        
        $this->redirect('/'); 
    }
    
    /**
    *   обработка формы отзыва
    */
    protected function feedForm()
    {
        // TODO
    }
    
    
    /**
    *   Действия до вызова основного метода
    *   проверка авторизации
    */
    protected function before() {
       
       $this->view->title = static::TITLE;
       $this->view->url = static::URL;       
    }
    
}

?>