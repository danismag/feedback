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
    public function action($action)        
    {
        $MethodName = 'action' . $action;
        
        $this->before();
        
        return $this->$MethodName();        
    }
    
    /**
    *   Обработка запроса к несуществующему методу
    *
    */
    public function __call($name, $params)
    {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    
    /**
    *   Определяет, произведен ли запрос методом GET
    *
    *   @return $request boolean 
    */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
    *   Определяет, произведен ли запрос методом POST
    *
    *   @return $request boolean 
    */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
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