<?php
/**
*   Контроллер страниц просмотра
*
*/

namespace App\Controller;

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

      
    // вывод главной страницы просмотра
    protected function actionIndex()
    {

        // сообщения об ошибках отправки данных
        $err_login = '';
        $err_name = '';
        $err_email = '';
        $err_text = '';

        // если были отправлены данные
        if ($this->isPost()) {
            // была ли попытка входа админа
            if (isset($_POST['login']) && isset($_POST['password']) 
                && isset($_POST['remember'])) {
                // проводим попытку входа
                if (App\Model\User::instance()->login($_POST['login'], $_POST['password'],
                    $_POST['remember'])) {

                    $this->redirect('/edit');
                }

                //сообщение об ошибке входа
                $err_login = 'Неверная пара логин-пароль';
            }

            // произошла ли отправка формы обратной связи
            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['text'])) {

            }

        }   

        $this -> title .= 'Просмотр оставленных отзывов';

        // Если сортировка не задана, берем по дате
        $sortby = isset($this->params[1]) ? $this->params[1] : 'sortbydate';

        // получаем комментарии из базы
        $mComments = M_Comments::Instance();
        $commentsArr = $mComments->Get($sortby);

        // подготовка каждого отзыва к отображению
        $commentsView;          // html-код комментариев
        foreach ($commentsArr as $com) {
            $commentsView .= $this-> Template('view/v_comment.php', $com);
            $commentsView .= '<br>';
        }

        // отрисовка шаблона
        $this -> content = $this -> Template('view/v_index.php', array('comments' =>
            $commentsView, 'sortby' => $sortby, 'can_edit' => M_Users::Instance()->Can()));

    }

    protected function actionEdit()
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
            $this->content = $this->Template('view/v_edit.php', array('text' => $text))

    }

}


?>