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
    $url            текущий адрес страницы
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
                <a class="navbar-brand" href=#feedbackform>К форме</a>
            </div>

           <!--  Вкладки для сортировки -->
<?php
$sortbydate = '';
$sortbyemail = '';
$sortbyname = '';
$display = true;
switch($sortby) {
    case 'sortbydate':
        $sortbydate = 'active';
        break;
    case 'sortbyemail':
        $sortbyemail = 'active';
        break;
    case 'sortbyname':
        $sortbyname = 'active';
        break;
    default:
        $sortbydate = 'active';
        break;
}
?>
        <div class="col-md-4">
<?php if ($display): ?>
        <ul class="nav nav-pills nav-justified">
            <li role="presentation" class="<?= $sortbydate; ?>"><a href="<?= $url;?>/">по дате</a></li>
            <li role="presentation" class="<?= $sortbyname; ?>"><a href="<?= $url;?>/sortbyname">по имени</a></li>
            <li role="presentation" class="<?= $sortbyemail; ?>"><a href="<?= $url;?>/sortbyemail">по e-mail</a></li>
        </ul>
<?php endif; ?>
        </div>

        <!-- Форма Входа -->
<?= ($loginForm ?? ''); ?>
 
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
    <div class="container" id="preview"></div>

    <!-- Контейнер для сообщения об отправке формы -->
    <div class="container">        

        <?= ($message ?? ''); ?>

    </div>
    
    <!-- Якорь перехода к форме -->
    <a name="feedbackform"></a>
    
    <!-- Форма обратной связи -->
<?= ($feedForm ?? ''); ?>    

    <script src="/js/jquery-3.1.0.js"></script>
    <script src="/js/jquery.form.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/App/js/feedjs.js"></script>
  </body>
</html>