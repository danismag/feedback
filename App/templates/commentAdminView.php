<?php if (!$id_comment): ?>
<!-- Шаблон для отзыва - админ

Должны быть переданы следующие данные:

$id_comment -   id отзыва из БД
$approved -     одобрен ли отзыв админом
$username -     имя пользователя, оставившего отзыв
$email -        email пользователя
$create_time -  дата и время создания отзыва
$text -         текст отзыва
$image -        путь к изображению (если есть)
$edited -       был ли отзыв отредактирован
$edit_time -    время редактирования (если установлен предыдущий параметр)
-->
<?php endif; ?>

<div class="row" id="<?= $id_comment; ?>">
    <div class="col-md-8 col-md-offset-2">

<!-- Принят или отклонен отзыв: показано цветом заголовка -->
   <div class="panel panel-
<?php if ($approved):?>success<?php else:?>danger<?php endif;?>">
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

    <!-- Отображение миниатюры картинки, прикрепленной к отзыву -->
    <span><img class="img-rounded img-responsive center-block" src="<?= $image; ?>">
    &nbsp;</span>
            </div>
<?php endif; ?>


    <!-- Текст комментария -->
       <div><span class="text-justify"><?= $text; ?></span></div>

        </div>

<!-- Возможность одобрения и редактирования -->

<div class="panel-footer">
<p>

<?php if ($edited) :?>

    <span class="col-md-4 text-danger">Изменен администратором</span>
    <span class="col-md-3 text-muted"><?= $edit_time;?></span>
    
<?php endif;?>

<span class="col-md-5">
<form id="edit<?= $id_comment; ?>" class="hidden" method="post" action="/index.php"></form>
<button type="submit" class="btn btn-info" form="edit<?= $id_comment; ?>" name="edit" 
    value="<?= $id_comment; ?>">Редактировать</button>
    
<?php if (!$approved): ?>

<form id="approve<?= $id_comment; ?>" class="hidden" method="post" action="/index.php">
</form>
<button type="submit" class="btn btn-primary" form="approve<?= $id_comment; ?>" 
    name="approve" value="<?= $id_comment; ?>" 
    onclick="ajaxFormRequest('div#<?= $id_comment; ?>', 'form#approve<?= $id_comment; ?>', '/index.php');
    return false">Одобрить</button>
    
<?php endif; ?>

</span>
</p>
</div>

    </div>
  </div>
</div>