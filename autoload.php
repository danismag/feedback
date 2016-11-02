<?php
//
// Автозагрузка классов
//

spl_autoload_register();

function loadclass ($classname)
{

    $class = str_replace('\\', '/', $classname);
    include_once(__DIR__ .'/'.$class.'.php');

}

spl_autoload_register('loadclass');

?>