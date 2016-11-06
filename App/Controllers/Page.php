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
        $errors = [];       // массив сообщений об ошибках
        
        // если были отправлены данные
        if ($this->isPost()) {
            
            try {
                
                $this->login();
                
            } catch(\Exception $e) {
                
                $errors[] = $e->getMessage();
            }
            
        } 
        
        $comments = '';      // html-код отзывов
        
        // подготовка каждого отзыва к отображению
        while ($comment = \App\Models\Comment::getComment($sort)) {
            
            $view = new \App\View\View;
            $view->comment = $comment;
            $comments .= $view->render(__DIR__ . '/App/templates/commentView.php');
        }         
        
        // отрисовка главной страницы
        $this->view->page = [
            'title' => 'Просмотр оставленных отзывов',
            'is_admin' => '0',
            'form_send' => '0',
            'comments' => $comments,
            'sortby' => $sort];
        echo $this->view->render(__DIR__ . '/App/templates/mainView.php');
        echo 'Контроллер страницы Просмотра!<br>';
    }

    protected function actionPreview()
    {
        if (!M_Users::Instance()->Can()) {
            $this->redirect('/index');
        }

        $this -> title .= '::Редактирование';
        $id = isset($this->params[2]) ? (int)$this->params[2] : 1;
        $mPages = M_Pages::Instance();

            if ($this -> isPost()) {
                $mPages->text_set($_POST['text'], $id);
                $this->redirect("/feedback/index/$id");
            }   

            $text= $mPages->text_get($id);
            $this->content = $this->Template('view/v_edit.php', array('text' => $text));

    }
    
    protected function actionForm()
    {
            //TODO
    }

}


?>