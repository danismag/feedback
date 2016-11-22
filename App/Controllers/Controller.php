<?php

namespace App\Controllers;

/**
*   Базовый класс контроллера
*
*/
abstract class Controller
{

    // Функция, вызываемая до основного метода
    abstract protected function before();

    /**
    *   Прокси-метод для вызова остальных методов
    *   
    *   @param $action string вторая часть имени вызываемого метода
    */
    public function action($action, $id = '')        
    {
        $MethodName = 'action' . $action;
        
        $this->before();
        
        if ('' != $id) {
            
            return $this->$MethodName($id);
        }
        return $this->$MethodName();      
    }
    
    /**
    *   Обработка запроса к несуществующему методу
    *
    */
    public function __call($name, $params)
    {
        $this->view->page404();
        
    }
    
    /**
    *   Перенаправляет на указанную страницу
    *
    *   @param $url string Адрес страницы
    */
    protected function redirect($url)
    {
        if ($url[0] == '/') {
            $url = BASE_URL . substr($url, 1);
        }

        header("Location: $url");
        exit();
    }
}

?>