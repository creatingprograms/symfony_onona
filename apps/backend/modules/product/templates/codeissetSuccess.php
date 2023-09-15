<?php use_helper('I18N', 'Date') ?>
<?php include_partial('product/assets') ?>
<?
      if(is_array($file)){
          ?><b>Нету артикулов:</b><br/><?
          foreach($artNot as $art){
              echo $art."<br/>";
          }
      }else{
     ?><form enctype="multipart/form-data" action="" method="post">
Файл с артикулами: <input name="file" type="file">
<input type="submit" value="Отправить">
</form><?
      }