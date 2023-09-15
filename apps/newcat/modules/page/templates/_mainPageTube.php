
<!-- БЛОК С БРЕНДАМИ ГАГА -->
<div class="brandspro">
  <div class="title-holder"><a href="/manufacturers#our_brend" style="color: #C3060E" id="brend">Наши бренды</a></div>
    
    <div style="margin:15px 0;"><?
     $footer = PageTable::getInstance()->findOneBySlug("blok-brendov");
    echo $footer->getContent();
    ?>
    </div>
</div>


<div style="margin-bottom: 30px;">
    <a href="/video" style="text-decoration: none;"> <strong><span style="color:#c3060e; font-size: 24px;">Он и Она | TUBE</span></strong> - видео-презентации секс игрушек, видео-обзоры статей, видео блог о сексе</div>
<script>
 /* function VideoPlay($id) {
  $('<div/>').css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockVideoPlay").appendTo('body');
  $('.blockVideoPlay').html($(".VideoPlayBlock" + $id).html());
  $('body').css('overflow', 'hidden');
  } */
    function VideoPlay($id, $videoKeys) {
      // alert('VideoPlay'); return;
        $('<div/>').css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockVideoPlay").appendTo('body');
        $.post("/video/preshow/" + $id, {videoKeys: $videoKeys, catSlug: '/'},
                function (data) {
                  // alert(data);
                    $('.blockVideoPlay').html(data);
                });
        //$('.blockVideoPlay').html($(".VideoPlayBlock" + $id).html());
        //$('body').css('overflow', 'hidden');
    }

</script>
<div class="promo-gallery-holder video-slider main-slider-tubes swiper-container">
    <a href="#" class="prev"></a>
    <a href="#" class="next"></a>
    <?php/*<div class="promo-gallery video swiper-wrapper">*/?>
    <div class="swiper-wrapper">
        <?php /*<ul class="shop-list" style="min-height:215px;">*/?>
            <?
            $videos = VideoTable::getInstance()->createQuery()->where("is_public='1'")->addWhere("is_publicmainpage='1'")->addWhere("manager_id is null")->orderBy("rand()")->limit(5)->execute();
            foreach ($videos as $video) {
                ?>
                <div class="swiper-slide"><?php /* li */?>
                    <? if (sfContext::getInstance()->getRequest()->getParameter('videoslug') == $video->getSlug()) { ?>
                        <script type='text/javascript'>
                            $(document).ready(function () {
                                window.setTimeout("VideoPlay(<?= $video->getId() ?>, '<?= $video->getId() ?>');", 1000);
                            });
                        </script>
                    <? } ?>
                    <img src="/uploads/photovideo/<?= $video->getPhoto() ?>" alt='<?= str_replace(array("'", '"'), "", $video->getName()) ?>' onclick="
                                history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/?videoslug=<?= $video->getSlug() ?>');
                                VideoPlay(<?= $video->getId() ?>, '<?= implode(",", $videos->getPrimaryKeys()) ?>')">
                    <br />
                    <a href="/?videoslug=<?= $video->getSlug() ?>" style="border: 0px; display: inline; font-size: 13px;" onclick="
                                history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/?videoslug=<?= $video->getSlug() ?>');
                                VideoPlay(<?= $video->getId() ?>, '<?= implode(",", $videos->getPrimaryKeys()) ?>');
                                return false;"><?= $video->getName() ?></a>
                    <div class="VideoPlayBlock<?= $video->getId() ?>" style="display: none;">
                        <div style="
                             margin: 0 auto;
                             position: relative;
                             width: 1000px;
                             text-align: center;
                             ">
                            <div style="min-width: 540px; display: inline-block;
                                 background-color: rgba(255, 255, 255, 1);
                                 border:0px solid #c3060e; padding: 10px 0; position: relative; ">
                                <div onClick="$('.blockVideoPlay').remove();
                                            history.pushState('', '', '/');" class='close' style="right: 5px;"></div>

                                <div style="color:#414141; text-align: left; width: 100%; font-size: 13px; padding-left: 15px; margin-bottom: 15px;"><?= $video->getName() ?></div>
                                <?
                                if ($video->getVideoserver()) {
                                    ?>
                                    <script type="text/javascript" src="/player/js/hdwebplayer.js"></script>
                                    <script type='text/javascript'>
                                    $('.blockVideoPlay').find(".playerVideoDivMainPage").attr('id', 'playerVideoDivMainPage');
                                    player = $(".blockVideoPlay #playerVideoDivMainPage");
                                    hdwebplayer({
                                        id: 'playerVideoDivMainPage',
                                        swf: '/player/player.swf?api=true',
                                        width: '540',
                                        height: '305',
                                        margin: '15',
                                        video: '<?= "/uploads/video/" . $video->getVideoserver() ?>',
                                        autoStart: 'true',
                                        shareDock: 'false'
                                    });
                                    </script>

                                    <div class="playerdiv" class="simple_overlay">
                                        <div class="playerVideoDivMainPage"></div>

                                    </div>
                                    <?
                                } else {
                                    echo str_replace(' src=', ' data-src=', $video->getVideoyoutube());
                                }
                                ?>

                                <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
                                <div class="yashare-wrapper" style="background: none; height: 22px; margin: 20px auto;">
                                    <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                                         data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

                                         ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php /*</li>*/?>
              </div>
            <? } ?>
        <?php /*</ul>*/?>
    </div>
</div><? /*
              <div style="">
              <strong><span style="color:#c3060e; font-size: 24px;">Он и Она | TUBE</span></strong> - видео-презентации секс игрушек, видео-обзоры статей, видео блог о сексе</div>
              <script>
              function VideoPlay($id) {
              $('<div/>').css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockVideoPlay").appendTo('body');
              $('.blockVideoPlay').html($(".VideoPlayBlock" + $id).html());
              }
              </script>
              <div class="promo-gallery-holder">
              <a href="#" class="prev"></a>
              <a href="#" class="next"></a>
              <div class="promo-gallery" style="width: 800px; overflow: hidden; margin: auto;">
              <ul class="shop-list" style="min-height:215px;">
              <?
              $videos = VideoTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->limit(10)->execute();
              foreach ($videos as $video) {
              ?>
              <li style="border: 0px; width: 380px; margin: 0 10px;">
              <img src="/uploads/photovideo/<?= $video->getPhoto() ?>" style="width: 380px;" onclick="VideoPlay(<?= $video->getId() ?>)">
              <br />
              <a href="<?= $video->getLink() ?>" style="border: 0px; display: inline"><?= $video->getName() ?></a>
              <div class="VideoPlayBlock<?= $video->getId() ?>" style="display: none;">
              <div style="
              margin: 0 auto;
              position: relative;
              width: 1000px;
              ">
              <div style="width:540px; margin-left: 210px;
              background-color: rgba(255, 255, 255, 1);
              border:1px solid #c3060e; padding: 10px; ">
              <div onClick="$('.blockVideoPlay').remove();" class='close'></div>

              <div style="color:#c3060e; text-align: center; width: 100%; font-size: 15px;"><?= $video->getName() ?></div>
              <?
              if ($video->getVideoserver()) {
              ?>
              <script type="text/javascript" src="/player/js/hdwebplayer.js"></script>
              <script type='text/javascript'>
              $('.blockVideoPlay').find(".playerVideoDivMainPage").attr('id', 'playerVideoDivMainPage');
              player = $(".blockVideoPlay #playerVideoDivMainPage");

              hdwebplayer({
              id: 'playerVideoDivMainPage',
              swf: '/player/player.swf?api=true',
              width: '540',
              height: '305',
              margin: '15',
              video: '<?= "/uploads/video/" . $video->getVideoserver() ?>',
              autoStart: 'true',
              shareDock: 'false'
              });
              </script>

              <div class="playerdiv" class="simple_overlay">
              <div class="playerVideoDivMainPage"></div>

              </div>
              <?
              } else {
              echo $video->getVideoyoutube();
              }
              ?>
              </div>
              </div>
              </div>
              </li>
              <? } ?>
              </ul>
              </div>
              </div> */ ?>
