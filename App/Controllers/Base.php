<?php

namespace App\Controllers;

/**
*   Контроллер сайта
*
*/
abstract class Base extends Controller
{
    protected $title;               // заголовок страницы
    protected $view;                // экземпляр класса для отображения
    protected $need_login;          // требуется ли авторизация для этой страницы
    protected $user;                // авторизованный пользователь или null
    

    /**
    *   Конструктор
    *
    *   @param $title string    Заголовок страницы
    *   @param $need_login = false bool     Требуется ли авторизация для просмотра
    */
    public function __construct($title, $need_login = false)
    {
        $this->title = $title;
        $this->view = new App\View\View; 
        $this->need_login = $need_login;
        $this->user = App\Model\User::instance()->get();       
    }
    
    /**
    *   Авторизация пользователя
    *
    *   @param string $_POST['login']
    *   @param string $_POST['password']
    *   @param bool $_POST['remember']
    */
    public function login()
    {
        $user = \App\Model\User::getUser($_POST['login'], $_POST['password']);
        $session = \App\Etc\Auth::openSession($user, $_POST['remember']);
        
        // TODO
            
        
    }
    
    /**
    *   выход пользователя
    *
    *
    */
    public function logout()
    {
        // TODO
        
        
    }
    
    
    /**
    *   Действия до вызова основного метода
    *   проверка авторизации
    */
    protected function before() {
        if ($this->need_login && $this->user === null) {
            $this->redirect('/index.php');
        }
    }
    
}

?>