<?php

namespace App\Controllers;

use \App\Models\Comment;
use \App\Etc\Auth;
use \App\Exceptions\Multiexception;

/**
*   Контроллер страниц редактирования (админ)
*
*/
class Edit extends Base
{
    const TITLE = 'Редактирование отзывов';
    const URL = '/edit/index';
    
    
    /**
    *   Действия перед вызовом основного метода
    */
    protected function before()
    {
        // Предотвращение неавторизованного доступа
        if (!Auth::isLogin()) {
            
            $this->redirect('/');
        }
        
        parent::before();
    }
    
    /**    
    *   отображает страницу просмотра для админа
    */
    protected function actionIndex($sort = 'sortbydate')
    {
        $this->view->sortby = $sort;        
        $this->view->feed = Comment::getComments($sort, false);
        
        $this->view->adminPage();        
    }
    
    /**
    *   одобрение отзыва
    */
    protected function actionApprove($id)
    {
        try {
            
            Comment::getCommentById($id)->approve();
            
        } catch(\Exception $e) {
                
            $this->view->success = $e;
            $this->actionIndex();
        }
    }
    
    /*
    *   Отображение отзыва для редактирования
    */
    protected function actionComment($id)
    {
        $this->view->comment = Comment::getCommentById($id);
        $this->view->sortby = 'no';
        
        $this->view->commentPage();
    }
    
    /*
    *   Сохранение отзыва после редактирования
    */
    protected function actionSave($id)
    {
        try {
           
           $newcom = Comment::validate($_POST['username'], 
               $_POST['email'], $_POST['text']);
               
           $oldcom = Comment::getCommentById($id);
           
           $oldcom->edit($newcom);
           
       } catch(Multiexception $e) {
           
            $this->view->warning = $e;
            $this->actionComment($id);
           
       } catch(\Exception $e) {
           
           $this->view->success = $e;
           $this->actionIndex();
       }
    }
    
    /*
    *   Удаление отзыва
    */
    protected function actionDelete($id)
    {
        try {
            
            Comment::getCommentById($id)->delete();
            
        } catch(\Exception $e) {
            
            $this->view->success = $e;
            
            $this->actionIndex();
        }
    }
    
    /*
    *    выход админа
    */
    protected function actionlogout()
    {
        $this->logout();
    }
}


?>