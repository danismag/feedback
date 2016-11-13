<?php if (false): ?>
<!-- Шаблон для предпросмотра отзыва

могут быть переданы следующие данные:

$username -     имя пользователя, оставившего отзыв
$email -        email пользователя
$text -         текст отзыва
$imagepath -        путь к изображению (если есть)

-->
<?php endif; ?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
        <p>
    <span class="col-md-3"><?= ($username ?? ''); ?></span>
    <span class="col-md-3 text-success"><?= ($email ?? ''); ?></span>
    <span class="col-md-3 col-md-offset-3 text-muted"><?= date('H:i:s d-m-Y'); ?></span>
        </p>
            </div>
            <div class="panel-body">
<?php if(($imagepath ?? false)): ?>
            <div class="col-md-6">

    <!-- Отображение картинки, прикрепленной к отзыву -->
    <span><img class="img-rounded img-responsive center-block" 
        src="<?= ($imagepath ?? ''); ?>">&nbsp;</span>
            </div>
<?php endif; ?>


    <!-- Текст комментария -->
            <div>
        <span class="text-justify"><?= ($text ?? ''); ?></span>
            </div>
        </div>
        
        <div class="panel-footer"></div>
        
    </div>
  </div>
</div>