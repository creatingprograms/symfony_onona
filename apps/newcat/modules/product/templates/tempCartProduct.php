<li style="margin: 0 17px 32px 0;">
                    <!--
            Сообщить о поступлении
                    -->
                    <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
                        <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
                        <img src="/images/topToSend.png">

                        <?php if (!$errorCapSu and sfContext::getInstance()->getRequest()->getParameter('senduser')): ?>
                            <center>Спасибо за запрос. Вам будет сообщено о поступление товара.</center>
                        <?php else: ?>
                            <form id="senduser" action="/product/<?= $product->getSlug() ?>/senduser" method="post">
                                <div style="clear: both; color:#4e4e4e; text-align: left;">    
                                    <table width="100%" class="noBorder">
                                        <tbody><tr>
                                                <td width="120" style="color:#073f72;padding: 5px 0;text-align: left;">
                                                    Представьтесь<span style="color: #ff94bc;">*</span>
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" name="name" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="color:#073f72;padding: 5px 0;text-align: left;">
                                                    Ваш e-mail<span style="color: #ff94bc;">*</span>
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" name="mail" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>">
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    <span style="font-size: 10px;">Поля отмеченные * обязательны для заполнения.</span>
                                    <center>
                                        <br />
                                        <?php /* if (sfConfig::get('app_recaptcha_active', false)): ?>
                                          <?php echo recaptcha_get_html(sfConfig::get('app_recaptcha_publickey'), $reCaptcha['response']->getError()) ?>
                                          <?php endif ?>
                                          <?php if ($reCaptcha['response']->getError() != "") { ?>
                                          <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                          <br />
                                          <?php } */ ?>
                                        <img border="0" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>">
                                        <br /><br />
                                        <input type="text" name="sucaptcha" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;">
                                        <br />
                                        <?php if ($errorCapSu) { ?>
                                            <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                            <br />
                                        <?php } ?>
                                        <span style="font-size: 10px;">Введите текст с картинки.</span>
                                        <br />
                                        <br />
                                        <input type="submit" style="width: 198px; position: relative; left: 0px; top: 0px; margin: 5px 0px 7px 0px;" value="Отправить запрос" id="addToBasket">
                                    </center>

                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                    <!--
                Сообщить о поступлении
                    -->
                    

                    <div class="t"></div>
                    <div class="c">
                        <div class="content">
                            <?= $product->getDiscount() > 0 ? '<span class="sale' . $product->getDiscount() . '">-' . $product->getDiscount() . '%</span>' : ''; ?>
                            <div class="title"><a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a></div>
                            <div class="img-holder">
                                <a href="/product/<?= $product->getSlug() ?>"><img id="photoimg_<?= $product->getId() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt="<?= $product->getName() ?>" /></a>
                                <? /* <div class="countdown">
                                  <a href="#" class="buy">Успейте купить!</a>
                                  <span class="time">72:26:32</span>
                                  </div> */ ?>
                            </div>
                            <div class="bottom-box">
                                <div class="row">
                                    <a href="#" class="rewiev">Отзывы: <?= $comments->count() ?></a>
                                    <div class="stars">
                                        <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
                                    </div>
                                </div>
                                <div class="price-box">
                                    <?php if ($product->getOldPrice() > 0) { ?>
                                        <span class="old-price"><?= number_format( $product->getOldPrice(), 0, '', ' ' ) ?> р.</span>
                                        <span class="new-price"><?= number_format( $product->getPrice(), 0, '', ' ' ) ?> р.</span>
                                    <? } else { ?>
                                        <span class="price"><?= number_format( $product->getPrice(), 0, '', ' ' ) ?>    р.</span>
                                    <? } ?>
                                </div>
                                <div class="tools">
                                    <a href="#" class="att"></a><?php if ($product->getCount() > 0): ?>
                                        <a href="#" class="red-btn small to-card" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: 1 },  function(data) {
                                        $.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: 1 },  function(data) {});
                                    });
                                            <?/*addToCartAnim('Cart', '#photoimg_<?= $product->getId() ?>');location.reload();*/?>addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'",'"','\\'),"",$product->getName()) ?>', '<?=$product->getPrice() ?>', '<?=round(($product->getPrice()  * csSettings::get("persent_bonus_add")) / 100) ?>');
                                   return false;
                                           " title="Купить"> 

                                            <span>В корзину</span>
                                        </a>
                                        <a href="#" class="to-desire" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></a>

                                    <?php else: ?>
                                        <a id="addToBasket" class="red-btn to-card small" onclick="return hs.htmlExpand(this, { contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
                                            headingText: '', width: 553, height: 470, slideshowGroup: 'groupToSend', left: -9 } )"
                                           class="highslide" style=""/><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="popup-content" style="display:none">
                                <h1 class="title centr"><?= $product->getName() ?></h1>
                                <div class="item-box">
                                    <div class="item-media">
                                        <div class="img-holder">
                                            <a href="#"><img id="photoimg_pr_<?= $product->getId() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt="image description" /></a>
                                        </div>
                                        <? /* <div class="more-photo">
                                          <a href="#">Фотогалерея <?= $photos->count() ?> фото</a>
                                          <?php
                                          foreach ($photos as $key => $photo):
                                          if ($key != 0){
                                          echo '<a href = "/uploads/photo/' . $photo->getFilename() . '" title = " " style = "display:none;" class = "highslide" onclick = "return hs.expand(this)"></a>';

                                          }else{
                                          echo '<a href = "/uploads/photo/' . $photo->getFilename() . '" title = " " style = "" class = "highslide" onclick = "return hs.expand(this)">55</a>';


                                          }
                                          endforeach;
                                          ?>
                                          </div> */ ?>
                                    </div>
                                    <div class="item-char">
                                        <form action="/cart/addtocart/<?= $product->getId() ?>" class="search">
                                            <fieldset>
                                                <?php
                                                $arrayDopInfo = array();
                                                $dopInfos = $product->getDopInfoProducts();
                                                /* foreach ($dopInfos as $info):
                                                  $dopParam = $info->getDopInfoProduct()->getData();
                                                  $arraySubInfo['value'] = $info->getValue();
                                                  $arraySubInfo['code'] = $dopParam[0]->getCode();
                                                  $arraySubInfo['dopInfoID'] = $info->getId();
                                                  $arrayInfo[$info->getName()][] = $arraySubInfo;
                                                  endforeach; */
                                                if ($product->getParent() != "")
                                                    $productProp = $product->getParent();
                                                else
                                                    $productProp = $product;
                                                $i = 0;
                                                $childrens = $productProp->getChildren();
                                                $childrens[] = $productProp;
                                                foreach ($dopInfos as $key => $property):
                                                    $doparray['value'] = $property['value'];
                                                    $doparray['product_id'] = $product->getSlug() != "" ? $product->getSlug() : $product->getId();

                                                    $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
                                                    $doparray['sort'] = $dopInfoCategory->getPosition();


                                                    $doparray['dopInfoID'] = $property->getId();
                                                    $arrayDopInfo[$property['dicategory_id']][] = $doparray;
                                                    foreach ($childrens as $children) {
                                                        foreach ($children->getDopInfoProducts() as $dopInfoChildren) {
                                                            //echo $dopInfoChildren['value'];
                                                            //echo $dopInfoChildren['dicategory_id'].' '.$property['dicategory_id']." упымап ";
                                                            if ($dopInfoChildren['dicategory_id'] == $property['dicategory_id'] and $children->getId() != $product->getId()) {
                                                                /* if (/$dopInfoChildren['dicategory_id'] == 62) {
                                                                  //echo $dopInfoChildren['value'];
                                                                  } */
                                                                $in_array_di = false;

                                                                foreach ($arrayDopInfo[$property['dicategory_id']] as $value) {
                                                                    //print_r($value);
                                                                    if (in_array($dopInfoChildren['value'], $value)) {
                                                                        //print_r($dopInfoChildren['value']);

                                                                        $in_array_di = true;
                                                                    }
                                                                }

                                                                if ($in_array_di === false) {
                                                                    //echo "<pre>".$dopInfoChildren['value'];
                                                                    $doparray['value'] = $dopInfoChildren['value'];
                                                                    $doparray['product_id'] = $children->getSlug() != "" ? $children->getSlug() : $children->getId();
                                                                    $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
                                                                    $doparray['sort'] = $dopInfoCategory->getPosition();
                                                                    $arrayDopInfo[$property['dicategory_id']][] = $doparray;
                                                                }
                                                                //print_r($arrayDopInfo);
                                                            }
                                                        }
                                                    }
                                                    $i++;

                                                endforeach; //print_r($arrayDopInfo);
                                                //print_r($arrayDopInfo);
                                                unset($sort_numcie, $arrayDopInfoKeys);
                                                foreach ($arrayDopInfo as $c => $key) {
                                                    $sort_numcie[$c] = $key['0']['sort'];
                                                }
                                                $sort_numcie2 = $sort_numcie;
                                                array_multisort($sort_numcie, SORT_ASC, $arrayDopInfo);
                                                asort($sort_numcie2);

                                                foreach ($sort_numcie2 as $c => $null) {
                                                    $arrayDopInfoKeys[] = $c;
                                                }
                                                //print_r($arrayDopInfo);
                                                $arrayDopInfo = array_combine($arrayDopInfoKeys, $arrayDopInfo);
                                                if ($arrayDopInfo):
                                                    foreach ($arrayDopInfo as $key => $property):
                                                        $key_old = $key;
                                                        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($key);
                                                        $key = $dopInfoCategory->getName();
                                                        ?>

                                                        <?php
                                                        if (count($property) == 1):

                                                            if ($key == "Таблица размеров" and $property[0]['value'] == 1):
                                                                $tableSize = true;
                                                            else:
                                                                ?>
                                                                <dl>
                                                                    <dt>
                                                                    <input type="hidden" name="productOptions[]" value="<?= $property[0]['dopInfoID'] ?>" />
                                                                    <?= $key ?>:</dt>
                                                                    <dd><?php
                                        if ($key == "Производитель") {
                                            echo "<a href=\"/manufacturer/" . $property[0]['dopInfoID'] . "\">" . $property[0]['value'] . "</a>";
                                        } else {
                                            echo $property[0]['value'];
                                        }
                                                                    ?></dd></dl>
                                                            <?php
                                                            endif;
                                                        else:
                                                            ?><dl>
                                                                <dt><?= $key ?>:</dt>
                                                                <dd>
                                                                    <script>
                                                                    function changeArtCode(select){
                                                                        document.location.href = "/product/"+select.value;
                                                                    }
                                                                    </script>
                                                                    <select name="productOptions[]" onchange="changeArtCode(this)" style="width: 82px;" id="select_<?= $i ?>">
                                                                        <?php foreach ($property as $keySub => $sub): ?>
                                                                            <option value="<?= $sub['product_id'] ?>"><?= $sub['value'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </dd></dl>
                                                        <?php endif; ?>

                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>

                                                <dl>
                                                    <dt><label for="count">Количество:</label></dt>
                                                    <dd><?php if ($product->getCount() > 0): ?><input type="number" required="required" id="count_<?= $product->getId() ?>" value="1" min="1" max="100" name="quantity" style="width: 30px;" /><?php else: ?>
                                                            Нет в наличии

                                                        <?php endif; ?></dd>
                                                </dl>
                                                <div class="more-expand-holder">
                                                    <dl style="display: none;" class="productCode">
                                                        <dt>Артикул:</dt>
                                                        <dd class="artCode"><?= $product->getCode() ?></dd>
                                                    </dl>
                                                    <a href="#" class="more-expand" onclick=" $(this).prevAll('.productCode').toggle();return false"></a>
                                                </div>
                                                <div class="price-box">
                                                    <div class="row">
                                                        <div class="btn-holder">
                                                            <?php if ($product->getCount() > 0): ?>
                                                                <a href="#" class="red-btn to-card small" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: $('.popup-holder #count_<?= $product->getId() ?>').val() },  function(data) {});
                                                                <?/*addToCartAnim('Cart', '#photoimg_pr_<?= $product->getId() ?>');location.reload();*/?>
                                                                    addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'",'"','\\'),"",$product->getName()) ?>', '<?=$product->getPrice() ?>', '<?=round(($product->getPrice()  * csSettings::get("persent_bonus_add")) / 100) ?>');
                                   return false;
                                                                   ">
                                                                    <span>В корзину</span>
                                                                </a>
                                                                <a href="#" class="to-desire" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></a>
                                                            <?php else: ?>
                                                                <a id="addToBasket" class="red-btn to-card small" onclick="return hs.htmlExpand(this, { contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
                                                                headingText: '', width: 553, height: 470, slideshowGroup: 'groupToSend', left: -9 } )"
                                                                   class="highslide" style=""/><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="price-col">
                                                            <div class="title">&nbsp;</div>
                                                            <span class="new-price"><?= number_format( $product->getPrice(), 0, '', ' ' ) ?> р.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                                 <?php if ($product->getoldPrice() == "" or $product->getoldPrice() == 0): ?>
                                <a href="/programma-on-i-ona-bonus" target="_blank"><div class="bonus-box"><div class="plashbon"><?= $product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add') ?>%
                        <span class="plashbontxt">= <?=round($product->getPrice()*(($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add'))/100))?> бонусов возвращаем на ваш личный счет / 1 бонус = 1 руб.</span></div>
                </div></a>
                    <? Endif; ?>
                                <h2 class="title">Описание товара</h2>
                                <div class="info-box"><?php if ($product->getVideo() != ''): ?>
                                        <div class="video-holder">
                                            <a href="#" class="player" onClick="
                                    hdwebplayer({ 
                                        id       : 'playerVideoDiv',
                                        swf      : '/player/player.swf?api=true',
                                        width    : '620',
                                        height   : '366',
                                        margin   : '15',
                                        video    : '<?= str_replace(array("http://www.onona.ru/video/", "http://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo()) ?>',
                                        autoStart: 'true',
                                        shareDock: 'false'
                                    });
                                    $('.close').click(function(){
                                        $('#playerBG').remove();
                                        $('#playerdiv').css('display','none');
                                        player = document.getElementById('playerVideoDiv');
                                        player.stopVideo();
                                    });

                                    $('#playerdiv').css({'display':'block',
                                        'position':'fixed',
                                        'top':(($(window).height() - $('#playerdiv').height())/2),
                                        'left':(($(window).width() - $('#playerdiv').width())/2)});
                                                                                                                                         
                                                                                                                                        
                                    return false;
                                               ">
                                                <img src="/newdis/images/video.png" width="142" height="90" alt="image description" />
                                                <span class="name">Видео-презентация</span>
                                                <span class="play"></span>
                                            </a>
                                        </div><?php endif; ?>
                                    <p><?= $product->getContent() ?></p>
                                </div>
                                <div class="social-box">
                                    <div class="row">
                                        <a href="javascript:CallPrint('.quick-view');" class="print"><img src="/newdis/images/ico02.png" width="16" height="16" alt="image description" /></a>
                                        <? /* <ul class="social">
                                          <li><a href="#"><img src="images/ico03.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico04.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico05.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico06.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico07.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico08.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico09.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico10.png" width="16" height="16" alt="image description" /></a></li>
                                          <li><a href="#"><img src="images/ico11.png" width="16" height="16" alt="image description" /></a></li>
                                          </ul>
                                          <div class="yashare-wrapper" style="background: none; text-align: center; width: 294px; padding-top: 24px; height: 32px; ">
                                          <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus" style="width: 196px;float: left; padding-left: 11px;"></div>
                                          </div> */ ?>
                                    </div>
                                    <div class="row">
                                        <? /* <a href="#" class="more-expand"></a>
                                          <ul class="share">
                                          <li><a href="#"><img src="/newdis/images/vk.png" width="102" height="20" alt="image description" /></a></li>
                                          <li><a href="#"><img src="/newdis/images/fb.png" width="123" height="20" alt="image description" /></a></li>
                                          </ul> */ ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="b"></div>
                </li>