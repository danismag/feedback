<?php

namespace App\Controllers;

/**
*   Контроллер страниц просмотра
*
*/
class Page extends Base
{
    /**
    *   Конструктор
    */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
    *   Действия перед вызовом основного метода
    */
    public function before()
    {
        parent::before();
    }

    //
    // вывод главной страницы просмотра
    // - сортировка отзывов
    // - ловля исключений
    // - вывод сообщений о состоянии
    protected function actionIndex($sort = 'sortbydate')
    {
        $errors = [];       // массив сообщений об ошибках формы отзыва
        $log = [];          // сообщения об ошибке входа
        
        // если были отправлены данные
        if ($this->isPost()) {
            
            if (isset($_POST['login'])) {
                try {
                    
                    $this->login();
                    
                } catch(\Exception $e) {
                    
                    $log[] = ['message' => $e->getMessage()];
                }
            }
            
        } 
        
        // передача данных и отрисовка главной страницы
        $this->view->title = 'Просмотр оставленных отзывов';
        $this->view->sortby = $sort;
        $this->view->feed = \App\Models\Comment::getComments($sort);
        $this->view->login = $log;
        
        $this->view->mainPage();
    }

    protected function actionPreview()
    {
        /*try {
            $this->view->comment = \App\Models\Comment::validate(
                $_POST['username'], $_POST['email'], $_POST['text']);
            
            $this->view->image = \App\Models\Image::create($_FILES['image']);
            
        } catch(\App\Exceptions\Multiexception $e) {
            
        }*/
       
       var_dump($_FILES);
        //$this->view->preview();
    }
    
    protected function actionForm()
    {
        // создание отзыва
        try {
           
           $comment = \App\Models\Comment::validate($_POST['username'], 
               $_POST['email'], $_POST['text']);
               
           $comment->image = \App\Models\Image::create($_FILES['image']);
           
           $comment->create();
           
       } catch(\App\Exceptions\Multiexception $e) {
           
            $this->view->warning = $e;
           
       }
       
       $this->view->form = $_POST;
                
       $this->actionIndex();
    }

}


?>