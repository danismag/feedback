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
    
    /*
    $action = 'action' . ucfirst($params[1] ?? 'index');
    
    $id = ($params[2] ?? null);
    
    $controller->action($action, $id);*/
    
    $view = new \App\View\View;
    $view->page = [
            'title' => 'Просмотр оставленных отзывов',
            'is_admin' => '0',
            'form_send' => '0',
            'comments' => '',
            'sortby' => ''];
        echo $view->render(__DIR__ . '/App/templates/mainView.php');
     

?>