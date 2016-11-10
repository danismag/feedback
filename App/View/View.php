<?php

namespace App\View;

/**
*   Класс для отображения страниц
*   
*   'form' = - объект отзыва для отображения в форме
*   'feed' = [] - массив объектов-отзывов
*   'comment' - объект отзыва для просмотра
*   @property array $data - массив для хранения данных
*/
class View
{   
    // Пути к шаблонам
    
    const TMAIN         = __DIR__ . '/App/templates/mainView.php';
    const TLOGINFORM    = __DIR__ . '/App/templates/loginFormView.php';
    const TFEEDFORM     = __DIR__ . '/App/templates/feedFormView.php';
    const TCOMMENT      = __DIR__ . '/App/templates/commentView.php';
    const TPREVIEW      = __DIR__ . '/App/templates/commentPreView.php';
    const TALERT        = __DIR__ . '/App/templates/alertView.php';
    const TCOMADMIN     = __DIR__ . '/App/templates/commentAdminView.php';
    const TCOMEDIT      = __DIR__ . '/App/templates/commentEditView.php';
    
    
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
    *   Отображение главной страницы
    *
    *   @param array 'comments' - массив отзывов
    *   @param \App\Model\User 'user' - объект пользователя
    */
    public function mainPage()
    {
        $this->data['loginForm'] = $this->emptyRender(self::TLOGINFORM);
        $this->data['feedForm'] = $this->commentRender('form', self::TFEEDFORM);
        $this->data['content'] = $this->comsRender('feed' ,self::TCOMMENT);
        
        
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
        
        if (isset($this->data[$com])) {
            
            foreach($this->data[$com] as $key => $val) {
                
                $$key = $val;
            }
            
        }
        
        include $template;
        
        return ob_get_clean();        
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
    *   пустой шаблон
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