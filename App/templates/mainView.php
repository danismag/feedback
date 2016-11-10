<?php if(false): ?>
<!--
    Шаблон главной страницы

    могут быть переданы следующие данные:

    $title -        заголовок страницы
    $loginForm -    html-код формы входа или текст приветствия админа
    $feedForm -     html-код с формой отправки отзыва
    $content -     html-код с отзывами или формой редактирования отзыва
    $message -      html-код для сообщений о статусе действий
    $errorLogin     html-код сообщения о неудаче авторизации
    $sortby -       строка с указанием типа сортировки отзывов
    ('date', 'name', 'email' или 'no' - не отображать сортировку)

-->
<?php endif; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= ($title ?? ''); ?></title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
     <link href="/App/css/feedmain.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Главная</a>
                <a class="navbar-brand" href=#feedbackform>К форме обратной связи</a>
            </div>

           <!--  Вкладки для сортировки -->
<?php
$sortbydate = '';
$sortbyemail = '';
$sortbyname = '';
$display = true;
switch(($sortby) ?? '') {
    case 'date':
        $sortbydate = 'active';
        break;
    case 'email':
        $sortbyemail = 'active';
        break;
    case 'name':
        $sortbyname = 'active';
        break;
    default:
        $display = false;
        break;
}
?>
        <div class="col-md-4 col-md-offset-1">
<?php if ($display): ?>
        <ul class="nav nav-pills nav-justified">
            <li role="presentation" class="<?= $sortbydate; ?>"><a href="/index.php">по дате</a></li>
            <li role="presentation" class="<?= $sortbyname; ?>"><a href="/index.php?sort=sortbyname">по имени</a></li>
            <li role="presentation" class="<?= $sortbyemail; ?>"><a href="/index.php?sort=sortbyemail">по e-mail</a></li>
        </ul>
<?php endif; ?>
        </div>

        <!-- Форма Входа -->
<?= ($loginForm ?? '<p class="navbar-text">Добрый день, Админ!</p>'); ?>
 
        </div>
    </nav>
    
    <!-- Контейнер для сообщений о неудачной аутентификации -->
    <div class="container">        

        <?= ($errorLogin ?? ''); ?>

    </div>
    
    <!-- контейнер для отзывов -->
    <div class="container" id="main">

<?= ($content ?? ''); ?>

    </div>

    <!-- Контейнер для предпросмотра отзыва (ajax-запрос) -->
    <div class="container" id="outpreview">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" id="preview"></div>
        </div>
    </div>

    <!-- Контейнер для сообщения об отправке формы -->
    <div class="container">        

        <?= ($message ?? ''); ?>

    </div>
    
    <!-- Якорь перехода к форме -->
    <a name="feedbackform"></a>
    
    <!-- Форма обратной связи -->
<?= ($feedForm ?? ''); ?>    

    <script src="/js/jquery-3.1.0.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/App/js/feedjs.js"></script>
  </body>
</html>