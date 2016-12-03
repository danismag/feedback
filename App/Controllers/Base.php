<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Etc\Auth;

/**
*   Контроллер сайта
*
*/
abstract class Base extends Controller
{
    protected $view;                // экземпляр класса для отображения страниц
    

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
            
            $user = User::getUser($_POST['login'], $_POST['password']);
            
            Auth::openSession($user, $_POST['remember']);
        
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
        Auth::closeSession();
        
        $this->redirect('/'); 
    }
    
    /**
    *   Действия до вызова основного метода
    */
    protected function before() {
       
       $this->view->title = static::TITLE;
       $this->view->url = static::URL;       
    }
    
}

?>