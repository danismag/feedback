<?php

include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/autoload.php');

//$user = new App\Models\User;

$view = new App\View\View;
var_dump($view);
echo '<br>';
$user = App\Models\User::find();
var_dump($user);
echo '<br>';
/*
echo '<br>';
$view->user = $user;
var_dump($view);
echo '<br>';*/

$a = [1, 2, 3, 4];
$b = implode(' AND ', $a);
echo $b;


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