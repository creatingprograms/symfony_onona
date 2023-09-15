<?/* if (sfContext::getInstance()->getRequest()->getParameter('videoslug') != $video->getSlug()) { ?>
    <script>
        $(document).ready(function () {
            history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '<?= sfContext::getInstance()->getRequest()->getParameter('catSlug') ?>?videoslug=<?= $video->getSlug() ?>');
                    //window.setTimeout(" history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/video?videoslug=<?= $video->getSlug() ?>');", 1000);
                });
    </script>
<? } */?>
<div class="video-popup-container">
    <div style="float:left; width: 85px;    min-height: 1px;"><? if ($preId != "") { ?><script>
            function loadPreShowLeft($id, $videoKeys) {
                $.post("/video/preshow/" + $id, {videoKeys: $videoKeys},
                function (data) {
                    $('.blockVideoPlay').html(data);
                });
            }
            </script><div class="leftProductPreShow" onclick="loadPreShowLeft(<?= $preId ?>, '<?= implode(",", $videoKeys) ?>', 0)"></div><? } ?></div>





    <div class="video-frame">
        <div onClick="$('.blockVideoPlay').remove();
                history.pushState('', '', '<?= sfContext::getInstance()->getRequest()->getParameter('catSlug') ?>');" class='close' style="right: 5px;"></div>

        <div class="header"><?= $video->getName() ?></div>
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
            echo '<div class="video">'.$video->getVideoyoutube().'</div>';
        }
        ?>
        <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
        <div class="yashare-wrapper" style="background: none; height: 22px; margin: 10px auto 5px;">
            <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                 data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

                 ></div>
        </div>
    </div>
    <div style="float:left; width: 85px;    min-height: 1px;"><? if ($postId != "") { ?><script>
                function loadPreShowRight($id, $videoKeys) {
                    $.post("/video/preshow/" + $id, {videoKeys: $videoKeys},
                    function (data) {
                        $('.blockVideoPlay').html(data);
                    });
                }
            </script><div class="rightProductPreShow"onclick="loadPreShowRight(<?= $postId ?>, '<?= implode(",", $videoKeys) ?>', 0)"></div><? } ?></div>

</div>
