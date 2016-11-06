<!--
    Шаблон главной страницы

    Должны быть переданы следующие данные:

    $title -        заголовок страницы
    $is_admin -     является ли текущий пользователь админом (true/false)
    $form_send -    отправлена ли форма с отзывом (true/false)
    $comments -     html-код с отзывами или формой редактирования отзыва
    $sortby -       строка с указанием типа сортировки отзывов
    ('date', 'name', 'email' или 'no' - не отображать сортировку)

-->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
     <link href="/App/css/feedmain.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Главная</a>
            </div>

           <!--  Вкладки для сортировки -->
<?php
$sortbydate = '';
$sortbyemail = '';
$sortbyname = '';
$display = true;
switch($sortby) {
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
<?php if (!$is_admin): ?>

            <form class="navbar-form navbar-right">
                <div class="form-group">
            <label class="sr-only" for="loginfield">Логин</label>
            <input type="text" class="form-control" id="loginfield" placeholder="Логин" name="login">
                </div>
                <div class="form-group">
            <label class="sr-only" for="passwordfield">Пароль</label>
            <input type="password" class="form-control" id="passwordfield" placeholder="Пароль" name="password">
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Запомнить меня
                    </label>
                </div>
                <button type="submit" class="btn btn-default">Войти</button>
            </form>

<?php else : ?>

<p class="navbar-text">Добро пожаловать, Админ!</p>

<?php endif; ?>

        </div>
    </nav>

<!-- Контейнер для сообщения об отправке формы -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" id="MessageFormSend">

<?php if ($form_send): ?>

                <div class="alert alert-success">
                    <button class="close" data-dismiss="alert">×</button>
                    <strong>Поздравляем!</strong>
                    Ваш отзыв успешно отправлен.
                </div>

<?php endif; ?>

            </div>
        </div>
    </div>

    <!-- контейнер для отзывов -->
    <div class="container" id="main">

<?= $comments; ?>

    </div>

    <!-- Контейнер для предпросмотра отзыва (ajax-запрос) -->
    <div class="container" id="outpreview">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" id="preview"></div>
        </div>
    </div>

    <!-- Форма обратной связи -->
<?php if (!$is_admin):?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

    <form class="well" id="feedback" method="post" enctype="multipart/form-data" action="index.php"><fieldset>
        <div class="form-group col-md-4">
    <label class="sr-only" for="name">Имя</label>
    <input type="text" class="form-control inline" id="name" name="name" placeholder="Имя">
        </div>
        <div class="form-group col-md-4">
    <label class="sr-only" for="email">Email address</label>
    <input type="email" class="form-control inline" id="email" name="email" placeholder="Email">
        </div>
        <div class="form-group col-md-4">
     <label for="file">Загрузить картинку</label>
     <input type="file" id="file" name="image">
        <p class="help-block">Здесь Вы можете прикрепить к отзыву картинку</p>
        </div>
        <div class="form-group">
    <textarea class="form-control" rows="3" name="text"
        placeholder="Напишите свой отзыв здесь"></textarea>
        </div>

        <div class="form-group col-md-6 col-md-offset-6">
    <button type="button" class="btn btn-info" onclick="ajaxFormRequest('div#preview', 'form#feedback', '/preview.php'); return false">Предварительный просмотр</button>
    <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </fieldset></form>
            </div>
        </div>
    </div>
<?php endif; ?>

    <script src="/js/jquery-3.1.0.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/App/js/feedjs.js"></script>
  </body>
</html>