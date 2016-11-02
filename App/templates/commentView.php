<?php if (!$comment_id): ?>
<!-- Шаблон для отзыва с учетом верстки главной страницы

Должны быть переданы следующие данные:

$comment_id -   id отзыва из БД
$approved -     одобрен ли отзыв админом
$username -     имя пользователя, оставившего отзыв
$email -        email пользователя
$create_time -  дата и время создания отзыва
$text -         текст отзыва
$image -        путь к изображению (если есть)
$edited -       был ли отзыв отредактирован
$edit_time -    время редактирования (если установлен предыдущий параметр)
$is_admin -      является ли текущий пользователь админом

-->
<?php endif; ?>

<div class="row" id="comment
    <?= $comment_id ?>">
    <div class="col-md-8 col-md-offset-2">

<!-- Принят или отклонен отзыв: показано цветом заголовка -->
   <div class="panel panel-
<?php if ($approved):?>success<?php else:?>danger<?php endif;?>">
        <div class="panel-heading">
        <p>
            <span class="col-md-3"><?= $username ?></span>
            <span class="col-md-3 text-success"><?= $email ?></span>
            <span class="col-md-3 col-md-offset-3 text-muted"><?= $create_time ?></span>
        </p>
        </div>

        <div class="panel-body">
<?php if($image): ?>
            <div class="col-md-6">

    <!-- Отображение картинки, прикрепленной к отзыву -->
    <span><img class="img-rounded img-responsive center-block" src="
        <?= $image ?>
    ">&nbsp;</span>
            </div>
<?php endif; ?>


    <!-- Текст комментария -->
       <div><span class="text-justify">

          <?= $text ?></span></div>

        </div>

<!-- Возможность одобрения и редактирования -->
<?php if ($is_admin || $edited) :?>
                    <div class="panel-footer">
                <p>
<?php if ($edited): ?>
                    <span class="col-md-4 text-danger">Изменен администратором</span>
                    <span class="col-md-3 text-muted"><?= $edit_time?></span>
<?php endif; ?>

<?php if ($is_admin):?>

           <span class="col-md-5">
            <form id="edit<?= $comment_id; ?>" class="hidden" method="post" action="/index.php"></form>
            <button type="submit" class="btn btn-info" form="edit<?= $comment_id; ?>" name="edit" value="1">Редактировать</button>

            <form id="approve<?= $comment_id; ?>" class="hidden" method="post" action="/index.php">
            </form>
            <button type="submit" class="btn btn-primary" form="approve<?= $comment_id; ?>" name="approve" value="1" onclick="ajaxFormRequest('div#preview', 'form#approve', '/preview.php'); return false">Одобрить</button>

            </span>

<?php endif; ?>
                </p>
                    </div>
<?php endif;?>
    </div>
  </div>
</div>