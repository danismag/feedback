<?php if(!$username) ?>
<!-- Шаблон для отзыва - пользователь

Должны быть переданы следующие данные:

$username -     имя пользователя, оставившего отзыв
$email -        email пользователя
$create_time -  дата и время создания отзыва
$text -         текст отзыва
$image -        путь к изображению (если есть)
$edited -       был ли отзыв отредактирован
$edit_time -    время редактирования (если установлен предыдущий параметр)
-->
<?php endif; ?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">

    <div class="panel panel-info">
        <div class="panel-heading">
        <p>
            <span class="col-md-3"><?= $username; ?></span>
            <span class="col-md-3 text-success"><?= $email; ?></span>
            <span class="col-md-3 col-md-offset-3 text-muted"><?= $create_time; ?></span>
        </p>
        </div>

        <div class="panel-body">
<?php if($image): ?>
            <div class="col-md-6">

    <!-- Отображение картинки, прикрепленной к отзыву -->
    <span><img class="img-rounded img-responsive center-block" src="<?= $image; ?>">
    &nbsp;</span>
            </div>
<?php endif; ?>


    <!-- Текст комментария -->
       <div><span class="text-justify"><?= $text; ?></span></div>

        </div>

<!-- был ли отзыв отредактирован -->
        <div class="panel-footer">
            <p>
<?php if ($edited): ?>

        <span class="col-md-4 text-danger">Изменен администратором</span>
        <span class="col-md-3 text-muted"><?= $edit_time;?></span>
        
<?php endif; ?>

            </p>
        </div>
    </div>
  </div>
</div>