<?php if (false): ?>
<!-- шаблон сообщения о статусе действия
    Принимает следующие данные:

    $message    - текст сообщения
    $status      - степень важности (строка)
 -->
 <?php endif; ?>
 
 <?php
 
     switch (($status) ?? '') {
     
         case 'success':
             $alert = 'alert-success';
             break;
         case 'info':
             $alert = 'alert-info';
             break;
         case 'warning':
             $alert = 'alert-warning';
             break;
         case 'danger':
             $alert = 'alert-danger';
             break;
         default:
             $alert = 'alert-info';
             break;
     }
  
 ?>
 
 
 <div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="alert <?= $alert; ?>">
            <button class="close" data-dismiss="alert">×</button>
        
            <?= ($message ?? ''); ?>
            
        </div>
    </div>
</div>