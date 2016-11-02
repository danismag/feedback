<?php

include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/autoload.php');

$ex = new App\Model\Image($_FILES['image']);
var_dump($ex);


/*$formSend = false;
$post = null;

if (isset($_POST)) {
    $formSend = true;
    $post = $_POST;
    $image = $_FILES;
}

include_once(__DIR__ . '/App/templates/mainView.php')

//$page = new App\Model\Comment;
//var_dump($page);

/*session_start();

// Обработка GET-запроса и вызов соответствующего контроллера
$info = explode('/', $_SERVER['REQUEST_URI']);
$params = [];

foreach ($info as $v)
{
    if ($v != '')
        $params[] = $v;
}

    $action = 'action_';
    $action .= (isset($params[0])) ? $params[0] : 'index';


    $controller = new C_Page();
    $controller -> Request($action, $params);
    */

?>