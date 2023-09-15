
<div class="form-popup-wrapper">
  <div class="form-popup-content form-popup-content--user-photos">
      <div onClick="$('.blockShowUserPhoto').remove();" class='close'></div>

      <div style="width:100%; text-align: center; border-bottom: 1px solid #e0e0e0; position: relative;">
        <table>
          <tr>
            <td>
              <img
                src="/uploads/photouser/<?= $photo->getFilename() ?>" <?= !$sf_user->isAuthenticated() ? ' style="opacity: 0.1;"' : '' ?>
                <?= $postId != "" ? ' onclick="loadShowUserPhotos('.$postId.', \''.implode(",", $photosKeys).'\')"' : ''?>
                  >
                  <?/*
                  onclick="loadShowUserPhotos(<?= $postId ?>, '<?= implode(",", $photosKeys) ?>')"<? } ?>
                  */?>
            </td>
          </tr>
        </table>
        <? if (!$sf_user->isAuthenticated()) { ?>
          <div class="autorize-alert">
            <div onClick="$('.blockShowUserPhoto').remove();" class='close' style="right: 10px;"></div>

            <div style="color:#c3060e; width: 100%; font-size: 15px;">Внимание!</div>
            <br/>Большие фото могут просматривать только авторизированные пользователи.<br/><br/><br/>
            <div class="notification-buttons">
              <div>
                <a class="AuthPhotoShowButton" href="/guard/login"></a>
                Для постоянных клиентов
              </div>
              <div>
                <a class="RegPhotoShowButton" href="/register"></a>
                Для новых клиентов
              </div>
            </div>

          </div>
        <? } ?>
      </div>
      <div style="padding: 10px;">
        <div style="position: relative;">
          <div style="position: absolute;left: 0;bottom: -20px;">Фото загружено: <?= $photo->getUserId()!=""?$photo->getSfGuardUser()->getFirstName():$photo->getUsername() ?></div>
        </div>
        <div class="controls">
          <script>
            function loadShowUserPhotos($id, $photosKeys) {
                $.post("/product/showuserphotos/" + $id, {photosKeys: $photosKeys},
                function (data) {
                    $('.blockShowUserPhoto').html(data);
                });
            }
          </script>
          <? if ($preId != "") { ?><div class="leftPhotoShowButton" onclick="loadShowUserPhotos(<?= $preId ?>, '<?= implode(",", $photosKeys) ?>')"></div><? } ?>
          <? if ($postId != "") { ?><div class="rightPhotoShowButton" onclick="loadShowUserPhotos(<?= $postId ?>, '<?= implode(",", $photosKeys) ?>')"></div><? } ?>
          <div class="closePhotoShowButton" onclick="$('.blockShowUserPhoto').remove();"></div>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>
</div>
</div>
