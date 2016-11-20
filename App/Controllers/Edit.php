<?php

namespace App\Controllers;

/**
*   Контроллер страниц редактирования (админ)
*
*/
class Edit extends Base
{
    const TITLE = 'Редактирование отзывов';
    
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
    protected function before()
    {
        if (!\App\Etc\Auth::isLogin()) {
            
            $this->redirect('/');
        }
        
        parent::before();
    }
    
    /**    
    *   отображает страницу просмотра для админа
    */
    protected function actionIndex($sort = 'sortbydate')
    {
        $this->view->url = '/edit/index';
        $this->view->sortby = $sort;        
        $this->view->feed = \App\Models\Comment::getComments($sort, false);
        
        $this->view->adminPage();        
    }
    
    /**
    *   одобрение отзыва
    */
    protected function actionApprove($id)
    {
        \App\Models\Comment::getCommentById($id)->approve();
        
        $this->redirect('/edit/index');
    }
    
    /*
    *   Отображение отзыва для редактирования
    */
    protected function actionComment($id)
    {
        $this->view->comment = \App\Models\Comment::getCommentById($id);
        $this->view->sortby = 'no';
        
        $this->view->commentPage();
    }
    
    /*
    *   Удаление отзыва
    */
    protected function actionDelete($id)
    {
        \App\Models\Comment::getCommentById($id)->delete();
        
        $this->redirect('/edit/index');
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