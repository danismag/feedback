<?php if (false): ?>
<!--
    Форма для редактирования отзыва

    Должны быть переданы следующий данные:

    $id_comment -   id отзыва из БД
    $username -     имя пользователя, оставившего отзыв
    $email -        email пользователя
    $text -         текст отзыва
    $image -        путь к изображению (если есть)

-->
<?php endif; ?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">

    <form class="well" method="post" action="/edit/save/<?= $id_comment;?>"><fieldset>
        <div class="form-group col-md-4">
    <label class="sr-only" for="name">Имя</label>
    <input type="text" class="form-control inline" id="name" name="username" placeholder="Имя" value="<?= $username; ?>">
        </div>
        <div class="form-group col-md-4">
    <label class="sr-only" for="email">Email address</label>
    <input type="email" class="form-control inline" id="email" name="email" placeholder="Email" value="<?= $email; ?>">
        </div>
        <div class="form-group col-md-4">
    <img class="img-rounded img-responsive center-block" src="<?= ($image ?? ''); ?>">
        </div>
        <div class="form-group">
    <textarea class="form-control" rows="6" name="text"
        placeholder="Напишите свой отзыв здесь">
        <?= $text; ?>
    </textarea>
        </div>
        <div class="form-group col-md-4 col-md-offset-8">
    <button type="submit" class="btn" formaction="/edit/index">Отмена</button>
    <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </fieldset></form>
    </div>
</div>