<?php if(!$username): ?>
<!-- Форма обратной связи

    Принимает следующие данные:
    
    $username   - введенное имя пользователя
    $email      - введенный email
    $text       - текст отзыва
 -->
<?php endif; ?>
 
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

<form class="well" id="feedback" method="post" enctype="multipart/form-data" 
    action="index.php">
    <fieldset>
    <div class="form-group col-md-4">
        <label class="sr-only" for="name">Имя</label>
        <input type="text" class="form-control inline" id="name"
            name="name" placeholder="Имя" value="<?= $username; ?>">
    </div>
    <div class="form-group col-md-4">
        <label class="sr-only" for="email">Email address</label>
        <input type="email" class="form-control inline" id="email" 
            name="email" placeholder="Email" value="<?= $email; ?>">
    </div>
    <div class="form-group col-md-4">
        <label for="file">Загрузить картинку</label>
        <input type="file" id="file" name="image">
        <p class="help-block">Здесь Вы можете прикрепить к отзыву картинку</p>
    </div>
    <div class="form-group">
        <textarea class="form-control" rows="3" name="text"
            placeholder="Напишите свой отзыв здесь">
        <?= $text; ?>
        </textarea>
    </div>

    <div class="form-group col-md-6 col-md-offset-6">
        <button type="button" class="btn btn-info" 
            onclick="ajaxFormRequest('div#preview', 'form#feedback', '/preview.php');
            return false">Предварительный просмотр</button>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </div>
    </fieldset>
</form>
    
        </div>
    </div>
</div>