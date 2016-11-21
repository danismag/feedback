<?php if (false): ?>
<!-- Шаблон для отзыва - админ

должны быть переданы следующие данные:

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
   <div class="panel panel-<?php if ($approved):?>success<?php else:?>danger<?php endif;?>">
        <div class="panel-heading">
        <p>
            <span class="col-md-3"><?= $username; ?></span>
            <span class="col-md-3 text-success"><?= $email; ?></span>
            <span class="col-md-3 col-md-offset-3 text-muted"><?= $create_time; ?></span>
        </p>
        </div>

        <div class="panel-body">
<?php if('NULL' != $image): ?>
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

    <span class="col-md-3 text-danger">Изменен администратором</span>
    <span class="col-md-3 text-muted"><?= $edit_time;?></span>
<?php else:?>
    <span class="col-md-3 text-danger">&nbsp;</span>
    <span class="col-md-3 text-muted">&nbsp;</span>
    
<?php endif;?>

<span class="col-md-6">
<form id="edit<?= $id_comment; ?>" class="hidden" method="post" action="/edit/comment/<?= $id_comment; ?>"></form>
<button type="submit" class="btn btn-info" form="edit<?= $id_comment; ?>">Редактировать</button>
    
<?php if (!$approved): ?>

<form id="approve<?= $id_comment; ?>" class="hidden" method="post" action="/edit/approve/<?= $id_comment; ?>">
</form>
<button type="submit" class="btn btn-primary" form="approve<?= $id_comment; ?>" 
    name="approve" value="<?= $id_comment; ?>">Одобрить</button>
    
<?php endif; ?>

<form id="del<?= $id_comment; ?>" class="hidden" method="post" action="/edit/delete/<?= $id_comment; ?>"></form>
<button type="submit" class="btn btn-danger" form="del<?= $id_comment; ?>">Удалить</button>

</span>
</p>
</div>

    </div>
  </div>
</div>