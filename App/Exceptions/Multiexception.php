<?php

/**
*   Мультиисключение для валидации формы
*
*/

namespace App\Exceptions;

class Multiexception extends \Exception
    implements \ArrayAccess, \Iterator
{
    use \App\Traits\TCollection;
    
}


?>