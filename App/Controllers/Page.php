<?php

namespace App\Controllers;

/**
*   Контроллер страниц просмотра
*
*/
class Page extends Base
{
    const TITLE = 'Просмотр оставленных отзывов';
    const URL = '/page/index';
    
    /**
    *   вывод главной страницы
    *   сортировка отзывов    
    */
    protected function actionIndex($sort = 'sortbydate')
    {
        // очистка от временных файлов изображений
        \App\Models\Comment::clearImages();
        
        // передача данных и отрисовка главной страницы
        $this->view->sortby = $sort;        
        $this->view->feed = \App\Models\Comment::getComments($sort);
        
        $this->view->mainPage();
    }
    
    /**
    *   Предпросмотр отзывов
    */
    protected function actionPreview()
    {
        try {
            $comment = \App\Models\Comment::validate(
                $_POST['username'], $_POST['email'], $_POST['text']);
                
            @$image = \App\Models\Image::create($_FILES[0]);
            @$comment->image = $image->imagepath;
            
            $this->view->comment = $comment;
            
            $this->view->preview();
            
        } catch(\App\Exceptions\Multiexception $e) {
            
            $this->view->warning = $e;
        }
       
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
              
            @$comment->image = \App\Models\Image::create($_FILES['image'])->imagepath;
           
           
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