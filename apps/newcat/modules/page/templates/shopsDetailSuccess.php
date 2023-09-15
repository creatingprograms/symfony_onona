<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', $shop->getName());
slot('metaKeywords', $shop->getName());
slot('metaDescription', $shop->getName());
slot('rightBlock', true);
?>

<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a></li>
    <li>
        <?= $shop->getName() ?></li>
</ul>
<h1 class="title"><?= $shop->getName() ?></h1>
<div class="shop-detail">
  <?php if($shop->getAddress()): ?>
    <div>
      <span class="header">Адрес:</span>
      <?= $shop->getAddress() ?>
    </div>
  <?php endif ?>

  <?php if($shop->getWorktime()): ?>
    <br>
    <div>
      <span class="header">Режим работы:</span>
      <?=$shop->getWorktime()?>
    </div>
  <?php endif ?>

  <?php if($shop->getOutdescription()): ?>
    <br>
    <div>
      <span class="header">Схема проезда:</span>
      <strong><?=$shop->getMetro()?></strong><?=$shop->getOutdescription()?>
    </div>
  <?php endif ?>

  <?php if( $shop->getLatitude() && $shop->getLongitude() ): ?>
    <br>
    <div id="YMapsID">
      <script src="//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full" type="text/javascript"></script>
     </div>
     <?php
      $worktime=$shop->getWorktime();
      $paytype='';
      if($shop->getCash()) $paytype.="Наличными";
      if($shop->getCard())
        if($paytype=='') $paytype.="Банковской картой";
        else $paytype.=", Банковской картой";
     ?>
     <div id="points-info" style="display: none;">
       <div
        class="city-<?=$shop->getCityId()?>"
        data-pay="<?=$paytype?>"
        data-worktime="<?=$worktime?><br />"
        data-metro="<?=$shop->getMetro()?>"
        data-address="<?=$shop->getAddress()?>"
        data-imagesize="[28, 28]"
        data-image="https://onona.ru/favicon.ico"
        data-lat="<?=$shop->getLatitude()?>"
        data-lon="<?=$shop->getLongitude()?>"
        data-name="<?=$shop->getName()?>"
      ></div>
     </div>
     <select id="cities-list" style="display:none;">
       <option value=<?=$shop->getCityId()?> selected=true>Текущий город</option>
     </select>
  <?php endif ?>

  <?= $shop->getDescription(); ?>
  <div class="shops-comments-block">
    <?php if(isset($comments) && $comments->count()) :?>
      <div align="center" style="padding:10px;">
        Отзывов: <?=$comments->count()?>
        <a href="#" onClick="$('#comments').toggle('slow'); return false;">(показать)</a>
      </div>
      <div id="comments" style="display:none">
        <table border="0" id="commentsTable" class="noBorder">
          <a name="отзывы"></a>
          <?php foreach ($comments as $comment2) :?>
            <tr>
              <td>
                <a name="comm<?=$comment2->getId()?>"></a>
                <div style="border:1px solid #CCC;">
                  <div style="padding:5px;">
                    <?=($comment2->getMail() != "") ? '<u>' : ''?><?=$comment2->getSfGuardUser()->getName() == "" ? $comment2->getUsername() : $comment2->getSfGuardUser()->getName()?><?=($comment2->getMail() != "") ? '</u>' : ''?>, <?=$comment2->getCreatedAt()?>
                  </div>
                  <div style="padding:5px;"><?=nl2br($comment2->getText())?>
                    <?php if ($comment2->getAnswer() != ""):?>
                      <div style="margin-left: 15px; margin-top: 15px;"><b>Ответ:</b><p style="margin: 0px;"><?= $comment2->getAnswer() ?></p></div>
                    <?php endif;?>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach ?>
        </table>
      </div>
    <?php else: ?>
      <div align="center">Вы можете стать первым, кто оставит отзыв к этому магазину.</div>
    <?php endif ?>
  </div>
  <div align="center" style="padding:10px;">
    <a href="#" onClick="$('#comments-form').toggle('slow'); return false;">Написать отзыв</a>
  </div>
  <div id="comments-form" class="add-coment" style="display: none;">
    <form class="js-submit-form" method="post" action="/send-shop-comment" id="commentForm">
      <fieldset>
        <input type="hidden"  class="shop-comment-id" value="<?=$shop->getId()?>">
        <div class="descr">Внимание! Публикация отзывов производится после предварительной модерации.</div>
        <div class="row">
          <div class="label-holder">
              <label>Ваше имя:*</label>
          </div>
          <div class="input-holder">
              <input type="text" class="shop-comment-name"/>
          </div>
        </div>
        <div class="row">
            <div class="label-holder">
                <label>Ваш e-mail:</label>
            </div>
            <div class="input-holder">
                <input type="text"  class="shop-comment-email" />
            </div>
        </div>
        <div class="row">
            <div class="label-holder">
                <label>Сообщение:*</label>
            </div>
            <div class="textarea-holder">
                <textarea cols="30" rows="5"  class="shop-comment-comment"></textarea>
            </div>
        </div>
        <div class="required">* - поля, отмеченные * обязательны для заполнения.</div>
        <div class="btn-holder centr">
          <div class="red-btn  colorWhite">
              <span>Отправить</span>
              <input type="button" value="Отправить" class="red-btn js-send-shop-comment"/>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
<script>
  $(document).on('ready', function(){
    $(document).on('click', '.js-send-shop-comment', function(){
      var $form=$(this).closest('form');
      var name=$form.find('.shop-comment-name').val();
      if(name==''){
        $form.find('.shop-comment-name').focus();
        return;
      }
      var comment=$form.find('.shop-comment-comment').val();
      if(comment==''){
        $form.find('.shop-comment-comment').focus();
        return;
      }
      var email=$form.find('.shop-comment-email').val();
      var id=$form.find('.shop-comment-id').val();
      $.ajax({
        url: $form.attr('action'),
        data:{
          email: email,
          id: id,
          name: name,
          comment: comment,
          passkey: 'Robots will not pass'
        },
        type: 'post',
        dataType: 'json',
        success: function (response){
          console.log(response);
          if(typeof response.error !== 'undefined'){
            alert('error '+response.error);
            return;
          }
          if(typeof response.success!=='undefined'){
            $form.html('<p>'+response.success+'</p>');
          }
        }
      });
    });
  });
</script>
