<?php

include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/autoload.php');

session_start();

    // Обработка GET-запроса и вызов соответствующего контроллера
    $info = explode('/', $_SERVER['REQUEST_URI']);
    $params = [];

    foreach ($info as $v) {
        
        if ($v != '') {
            
            $params[] = $v;
        }
    }
        
    switch ($params[0] ?? 'page') {
        
        case 'page':
            $controller = new App\Controllers\Page;
            break;
        case 'edit':
            $controller = new App\Controllers\Edit;
            break;
        default:
            $controller = new App\Controllers\Page;
            break;        
    }
    
    // Название с большой буквы
    $action = ucfirst($params[1] ?? 'index');
    
    $id = ($params[2] ?? null);
    
    if (null != $id) {
        
        $controller->action($action, $id);
    }
    
    $controller->action($action);
       
?>