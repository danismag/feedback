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
            
            if (isset($_POST['feedform'])) {
                
                try {
                    
                    $this->feedForm();
                    
                } catch(\App\Exceptions\Multiexception $e) {
                    
                    $errors[] = ['status' => 'warning',
                        'message' => $e->getMessage()];
                }
            }
            
            if (isset($_POST['preview'])) {
                
                $this->preview();
                die;
            }
        } 
        
        $comments = [];         // массив отзывов
        
        // Формирование массива отзывов
        while (\App\Models\Comment::getComment($sort)) {
            
            $comments[] = \App\Models\Comment::getComment($sort); 
        }
        
        
        // передача данных и отрисовка главной страницы
        $this->view->title = 'Просмотр оставленных отзывов';
        $this->view->sortby = $sort;
        $this->view->feed = $comments;
        $this->view->errors = $errors;
        $this->view->login = $log;
        
        $this->view->mainPage();
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