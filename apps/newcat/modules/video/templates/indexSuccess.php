<?php
 slot('metaTitle', "«Он и Она» | TUBE/Видео");
 slot('metaDescription', "TUBE/Видео. Круглосуточный интим-магазин, широкий ассортимент секс-товаров по доступным ценам. В нашем каталоге вся представленная продукция имеет подлинные сертификаты качества.");

?><script>
    function VideoPlay($id, $videoKeys) {
        $('<div/>').css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockVideoPlay").appendTo('body');
        $.post("/video/preshow/" + $id, {videoKeys: $videoKeys, catSlug:'/video'},
                            function (data) {
                            $('.blockVideoPlay').html(data);
                            });
        //$('.blockVideoPlay').html($(".VideoPlayBlock" + $id).html());
        //$('body').css('overflow', 'hidden');
    }

</script>
<?
if (sfContext::getInstance()->getRequest()->getParameter('videoslug') != "") {
    $video = VideoTable::getInstance()->findOneBySlug(sfContext::getInstance()->getRequest()->getParameter('videoslug'));
    ?>
    <script type='text/javascript'>
        $(document).ready(function () {
            window.setTimeout("VideoPlay(<?= $video->getId() ?>, '<?= $video->getId() ?>');", 1000);
        });
    </script>
    <?/*<div class="VideoPlayBlock<?= $video->getId() ?>" style="display: none;">
        <div style="
             margin: 0 auto;
             position: relative;
             width: 1000px;
             text-align: center;
             ">
            <div style="min-width: 540px; display: inline-block;
                 background-color: rgba(255, 255, 255, 1);
                 border:1px solid #c3060e; padding: 10px; position: relative; ">
                <div onClick="$('.blockVideoPlay').remove();
                                            history.pushState('', '', '/video');" class='close' style="right: 5px;"></div>

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
    </div>*/?>
<? } ?>
     <div class="video-container">
        <ul class="breadcrumbs">
            <li>
                <a href="/">Главная</a>
            </li>
            <li>Он и Она | TUBE</li>
        </ul>

        <div>
            <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
            <div class="yashare-wrapper" style="background: none; height: 22px; margin-left:-6px;margin-top:0;float:right;">
                <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                     data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>
               ></div>
            </div>
        </div>
        <h1 class="title">Он и Она | TUBE</h1>

        <div class="old-browsers first"></div>
        <div class="article-arrow">
          <a href="/video/Рекомендованное" style="color: #C3060E">Рекомендуемое видео <img src="/newdis/images/strelka.png" alt="strelka"></a>
        </div>
        <ul class="video-block">
            <?
            $videos = VideoTable::getInstance()->createQuery()->where("is_public='1'")->addWhere("is_related='1'")->orderBy("rand()")->limit(4)->execute();
            foreach ($videos as $video) {
                ?>
                <li class="video-frame">
                    <img src="/uploads/photovideo/<?= $video->getPhoto() ?>" class="video-cover" onclick="
                                            history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/video?videoslug=<?= $video->getSlug() ?>');
                                            VideoPlay(<?= $video->getId() ?>,'<?= implode(",", $videos->getPrimaryKeys()) ?>')">
                    <br />
                            <a href="/video?videoslug=<?= $video->getSlug() ?>" style="border: 0px; display: inline" onclick="
                                    history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/video?videoslug=<?= $video->getSlug() ?>');
                                    VideoPlay(<?= $video->getId() ?>,'<?= implode(",", $videos->getPrimaryKeys()) ?>'); return false;"><?= $video->getName() ?></a>
                    <?/*<div class="VideoPlayBlock<?= $video->getId() ?>" style="display: none;">

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
                                            history.pushState('', '', '/video');" class='close' style="right: 5px;"></div>

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
                                    echo $video->getVideoyoutube();
                                }
                                ?>
                                <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
                      <div class="yashare-wrapper" style="background: none; height: 22px; margin: 20px auto;">
                      <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                           data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"

                           ></div>
                  </div>
                                      </div>
                                  </div>
                              </div>*/?>
                </li>
<? } ?>
        </ul>

        <div class="old-browsers first"></div>
        <div class="article-arrow">
          <a href="/video/Новое" style="color: #C3060E">Новое видео <img src="/newdis/images/strelka.png" alt="strelka"></a>
        </div>

        <ul class="video-block">
            <?
            $videos = VideoTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("created_at desc")->limit(4)->execute();
            foreach ($videos as $video) {
                ?>
                <li class="video-frame">

                    <img src="/uploads/photovideo/<?= $video->getPhoto() ?>" class="video-cover" onclick="
                                history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/video?videoslug=<?= $video->getSlug() ?>');
                                VideoPlay(<?= $video->getId() ?>,'<?= implode(",", $videos->getPrimaryKeys()) ?>')">
                    <br />
                            <a href="/video?videoslug=<?= $video->getSlug() ?>" style="border: 0px; display: inline" onclick="
                                    history.pushState({videoslug: '<?= $video->getSlug() ?>'}, '<?= $video->getName() ?>', '/video?videoslug=<?= $video->getSlug() ?>');
                                    VideoPlay(<?= $video->getId() ?>,'<?= implode(",", $videos->getPrimaryKeys()) ?>'); return false;"><?= $video->getName() ?></a>
                    <?/*<div class="VideoPlayBlock<?= $video->getId() ?>" style="display: none;">
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
                                            history.pushState('', '', '/video');" class='close' style="right: 5px;"></div>

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
                                    echo $video->getVideoyoutube();
                                }
                                ?>
                      <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
                                  <div class="yashare-wrapper" style="background: none; height: 22px; margin: 20px auto;">
                                  <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                                       data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"

                                       ></div>
                              </div>
                                                  </div>
                                              </div>
                                          </div>*/?>
                </li>
<? } ?>
        </ul>
    </div>

    <div class="video-sidebar">
        <div class="benefits-box box">
            <div class="title-holder-box">
                <div class="title-holder"><ul class="cat-menu-article">
                        <li>
                            <ul style="display: block;" class="drop">
                                <?php
                                //$categoryArt= ArticlecategoryTable::getInstance()->createQuery()->
                                $tags = VideoTable::getInstance()->createQuery()->where("is_public='1'")/*->groupBy("tag")*/->fetchArray();
                                $arrayTags['Новое'] = 0;
                                $arrayTags['Рекомендованное'] = 0;
                                foreach ($tags as $tag) {
                                    if ($tag['is_related']) {
                                        $arrayTags['Рекомендованное'] = $arrayTags['Рекомендованное'] + 1;
                                    }
                                    if (strtotime($tag['created_at']) > (time() - 30 * 24 * 60 * 60)) {
                                        $arrayTags['Новое'] = $arrayTags['Новое'] + 1;
                                    }
                                    $thisTags = explode(";", $tag['tag']);
                                    foreach ($thisTags as $thisTag) {
                                        @$arrayTags[trim($thisTag)] = $arrayTags[trim($thisTag)] + 1;
                                    }
                                }

                                foreach ($arrayTags as $nameTag => $countVideo):
                                    ?>
                                    <li<?php if (/* $sf_request->getParameter('slug') == $ArticleCattegory->getSlug() or get_slot('articcategorySlug') == $ArticleCattegory->getSlug() */0) { ?> class="active"<?php } ?> style="margin-left: 10px;">
                                        <a href="/video/<?=$nameTag?>"><?= ($nameTag == "Новое" or $nameTag == "Рекомендованное") ? '<b>' : '' ?><?= $nameTag ?> (<?= $countVideo ?>)<?= ($nameTag == "Новое" or $nameTag == "Рекомендованное") ? '</b>' : '' ?></a>
                                    </li>
                                <?php endforeach;
                                ?>
                            </ul>
                        </li>
                    </ul></div>
            </div>

        </div>
    </div>
