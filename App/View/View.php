<?php

namespace App\View;

/**
*   Класс для отображения страниц -
*   реализует "магические" способы доступа к данным на лету
*   
*   'form' = - объект отзыва для отображения в форме
*   'feed' = [] - массив объектов-отзывов
*   'comment' - объект отзыва для просмотра
*   'image' - объект изображения
*   'warning' = [] - массив мультиисключений об ошибках
*   'info' = [] - массив информационных сообщений
*   'success' = [] - массив сообщений об успешных действиях
*   @property array $data - массив для хранения данных
*/
class View
{   
    // Пути к шаблонам
    
    const TMAIN         = BASE_PATH . '/App/templates/mainView.php';
    const TADMIN        = BASE_PATH . '/App/templates/adminView.php';
    const TLOGINFORM    = BASE_PATH . '/App/templates/loginFormView.php';
    const TLOGOUTFORM   = BASE_PATH . '/App/templates/logoutFormView.php';
    const TFEEDFORM     = BASE_PATH . '/App/templates/feedFormView.php';
    const TCOMMENT      = BASE_PATH . '/App/templates/commentView.php';
    const TPREVIEW      = BASE_PATH . '/App/templates/commentPreView.php';
    const TALERT        = BASE_PATH . '/App/templates/alertView.php';
    const TCOMADMIN     = BASE_PATH . '/App/templates/commentAdminView.php';
    const TCOMEDIT      = BASE_PATH . '/App/templates/commentEditView.php';
    
    
    // поле для хранения переданных данных
    protected $data = [];
    
    // Обработка присваивания значения несуществующему свойству
    public function __set ($key, $object)
    {
        $this->data[$key] = $object;
    }
    
    // Обработка запроса на чтение недоступного свойства
    public function __get ($key)
    {
        return $this->data[$key];
    }
    
    // Обработка проверки существования недоступного свойства
    public function __isset ($key)
    {
        return array_key_exists($key, $this->data);      
    }
    
    /**
    *   Отображение главной страницы просмотра
    *
    *   @param object 'form' - объект отзыва для отображения в форме
    *   @param array 'feed' - массив отзывов
    *   @param array 'alerts' - массив сообщений об исключениях
    *   @param array 'login' - сообщение о входе
    */
    public function mainPage()
    {
        // Подготовка данных
        $this->data['loginForm'] = $this->emptyRender(self::TLOGINFORM);
        $this->data['feedForm'] = $this->defRender('form', self::TFEEDFORM);
        $this->data['content'] = $this->comsRender('feed', self::TCOMMENT);
        $this->data['message'] = $this->alertsRender('warning', self::TALERT);
        @$this->data['message'] .= $this->exceptionRender($this->data['success'], 
            'success', self::TALERT);
        @$this->data['errorLogin'] = $this->exceptionRender($this->data['login'], 
            'danger', self::TALERT);
            
        // Отображение главного шаблона
        echo $this->render(self::TMAIN);
    }
    
    /**
    *   Отображение главной страницы админа
    * 
    *   @param array 'feed' - массив отзывов
    *   @param array 'success', 'warning' -исключения
    */
    public function adminPage()
    {
        // Подготовка данных
        $this->data['logoutForm'] = $this->emptyRender(self::TLOGOUTFORM);
        $this->data['content'] = $this->comsRender('feed', self::TCOMADMIN);
        @$this->data['message'] = $this->exceptionRender($this->data['success'], 
            'success', self::TALERT);
        @$this->data['message'] .= $this->exceptionRender($this->data['warning'], 
            'warning', self::TALERT);
            
        // Отображение главного шаблона
        echo $this->render(self::TADMIN);
    }
    
    /**
    *   Отображение отзыва для редактирования
    * 
    *   @param array 'comment' - объект отзыва
    *   @param array 'success', 'warning' -исключения
    */
    public function commentPage()
    {
        // Подготовка данных
        $this->data['logoutForm'] = $this->emptyRender(self::TLOGOUTFORM);
        $this->data['content'] = $this->commentRender('comment', self::TCOMEDIT);
        @$this->data['message'] = $this->exceptionRender($this->data['success'], 
            'success', self::TALERT);
        @$this->data['message'] .= $this->exceptionRender($this->data['warning'], 
            'warning', self::TALERT);
            
        // Отображение главного шаблона
        echo $this->render(self::TADMIN);
    }
    
    /**
    *   Предпросмотр отзыва
    *
    *   @param object 'comment' - объект отзыва
    */
    public function preview()
    {
        if (isset($this->data['comment'])) {
            
            if (isset($this->data['image'])) {
                
                $this->data['comment'][] = $this->data['image'];
            }
            
            echo $this->commentRender('comment', self::TPREVIEW);
        }
    }
    
    /**
    *   Получение html-кода массива отзывов
    *
    *   @param array $comments - массив отзывов
    *   @param string $template - путь к шаблону для отзыва
    *   @return string 
    */
    private function comsRender($coms, $template)
    {
        $result = '';
        
        if (isset($this->data[$coms])) {
            
            foreach($this->data[$coms] as $comment) {
            
                $result .= $this->commentRender($comment, $template);                
            }
        }
        
        return $result;        
    }
    
    /**
    *   Внесение данных отзыва в шаблон
    *
    *   @param object $comment - объект отзыва
    *   @param string $template - путь к шаблону
    *   @return string - html-код отзыва
    */
    private function commentRender($com, $template)
    {
        ob_start();
        
        if (isset($com)) {
            
            foreach($com as $key => $val) {
                
                $$key = $val;
            }
            
        }
        
        include $template;
        
        return ob_get_clean();        
    }
    
    /**
    *   Внесение данных в шаблон
    *
    *   @param object $com - объект с данными
    *   @param string $template - путь к шаблону
    *   @return string - html-код данных
    */
    private function defRender($com, $template)
    {
        ob_start();
        
        if (isset($this->data[$com])) {
            
            foreach($this->data[$com] as $key => $val) {
                
                $$key = $val;
            }
            
        }
        
        include $template;
        
        return ob_get_clean();        
    }
    
    /**
    *   Получение html-кода сообщений об ошибках
    *
    *   @param object $alert - объект мультисключения
    *   @param string $template - путь к шаблону
    *   @return string - html-код сообщений
    */
    private function alertsRender($alert, $template)
    {
        $result = '';
                
        if (isset($this->data[$alert])) {
            
            foreach($this->data[$alert] as $e) {
                
                $result .= $this->exceptionRender($e, $alert, $template);
            }
            
            return $result;
        }
        
    }
    
    /**
    *   Внесение данных сообщения в шаблон
    *
    *   @param Exception $e - объект исключения
    *   @param string $type - тип сообщения (по шаблону)
    *   @param string $template - путь к шаблону
    *   @return string - html-код сообщения
    */
    private function exceptionRender($e, $type, $template)
    {
        
        if (isset($e)) {
            
            ob_start();
            
            $message = $e->getMessage();
            $status = $type;
            
            include $template;
            
            return ob_get_clean();
        }
        
        return ;
    }
    
    /**
    *   "простое" внесение данных в шаблон
    *
    *   @return string 
    *   @param $template string Путь к файлу-шаблону
    */
    private function render($template)
    {
        ob_start();
        
        // Установка переменных для шаблона
        
        foreach ($this->data as $key => $value) {
            
            $$key = $value;
        }
        
        include $template;
        
        return ob_get_clean();      
        
    }   
    
    /**
    *   только пустой шаблон
    *
    *   @return string 
    *   @param $template string - путь к шаблону
    */
    private function emptyRender($template)
    {
        ob_start();
        
        include $template;
        
        return ob_get_clean();   
    }
}

?>