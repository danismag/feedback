<?php

namespace App\Controllers;

/**
*   Контроллер сайта
*
*/
abstract class Base extends Controller
{
    protected $view;                // экземпляр класса для отображения
    protected $need_login;          // требуется ли авторизация для этой страницы
    

    /**
    *   Конструктор
    *
    *   @param $title string    Заголовок страницы
    *   @param $need_login = false bool     Требуется ли авторизация для просмотра
    */
    public function __construct($need_login = false)
    {        
        $this->view = new \App\View\View; 
        $this->need_login = $need_login;   
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
        $user = \App\Model\User::getUser($_POST['login'], $_POST['password']);
        return \App\Etc\Auth::openSession($user, $_POST['remember']);
        $this->redirect('/edit/index/');
    }
    
    /**
    *   выход пользователя    
    */
    protected function logout()
    {
        \App\Etc\Auth::closeSession();   
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