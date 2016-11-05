<?php

namespace App\View;

/**
*   Класс для отображения страниц
*   
*   @property array $data - массив для хранения данных
*/
class View
{   
    // поле для хранения переданных данных
    protected $data = [];
    
    // Обработка присваивания значения несуществующему свойству
    public function __set ($k, $object)
    {
        foreach($object as $key => $value) {
            
            $this->data[$key] = $value;
        }
                
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
    *   Внесение данных в шаблон
    *   @return string 
    *   @param $template string Путь к файлу-шаблону
    */
    public function render($template)
    {
        ob_start();
        
        // Установка переменных для шаблона
        foreach ($this->data as $key => $value) {
            
            $$key = $value;
        }
        
        include $template;
        
        return ob_get_clean();      
        
    }
}

?>