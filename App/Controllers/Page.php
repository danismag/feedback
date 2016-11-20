<?php

namespace App\Controllers;

/**
*   Контроллер страниц просмотра
*
*/
class Page extends Base
{
    const TITLE = 'Просмотр оставленных отзывов';
    
    /**
    *   вывод главной страницы
    *   сортировка отзывов    
    */
    protected function actionIndex($sort = 'sortbydate')
    {
        
        // передача данных и отрисовка главной страницы
        $this->view->url = '/page/index';
        $this->view->sortby = $sort;        
        $this->view->feed = \App\Models\Comment::getComments($sort);
        
        $this->view->mainPage();
    }
    
    /**
    *   Предпросмотр отзывов
    */
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
    
    /**
    *   Обработка данных формы отзыва
    */
    protected function actionForm()
    {
        // создание отзыва
        try {
           
           $comment = \App\Models\Comment::validate($_POST['username'], 
               $_POST['email'], $_POST['text']);
               
           $comment->image = \App\Models\Image::create($_FILES['image'])->imagepath;
           
           $comment->create();
           
       } catch(\App\Exceptions\Multiexception $e) {
           
            $this->view->warning = $e;
            $this->view->form = $_POST;
           
       } catch(\Exception $e) {
           
           $this->view->success = $e;
       }
       
       $this->actionIndex();  
    }
    
    /**
    *   Авторизация пользователя
    */
    protected function actionLogin()
    {
        $this->login();
    }

}


?>