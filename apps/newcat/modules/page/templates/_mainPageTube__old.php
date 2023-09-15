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
        </div>