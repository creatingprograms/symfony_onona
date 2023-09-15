<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', $product['title'] == '' ? str_replace(array("{name}", "{articul}"), array(str_replace(array("'", '"'), "", $product['name']), $product['code']), csSettings::get('titleProduct')) : $product['title'] );
slot('metaKeywords', $product['keywords'] == '' ? str_replace(array("{name}", "{articul}"), array(str_replace(array("'", '"'), "", $product['name']), $product['code']), csSettings::get('titleProduct')) : $product['keywords']);
slot('metaDescription', $product['description'] == '' ? str_replace(array("{name}", "{articul}"), array(str_replace(array("'", '"'), "", $product['name']), $product['code']), csSettings::get('titleProduct')) : $product['description']);
?>
<div id="productCart">
    <div class="info-box">
        <h1 class="title">
            <?php echo $product['name'] ?>
        </h1>
    </div>
    <div class="numberProduct" onclick="$(this).html('<?= $product['code'] ?>');">
        №: <?= str_pad($product['id'], 5, "0", STR_PAD_LEFT); /* substr($product['id1c'], -5, 5) != "" ? substr($product['id1c'], -5, 5) : $product['code']; */ ?>
    </div>

    <div class="photoGallery">
        <?
        if ($product['bonuspay'] > 0) {
            ?>
            <div style="position: absolute; left: 10px; top: 0px;z-index: 10; cursor:pointer;">

                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 156 156" enable-background="new 0 0 156 156" xml:space="preserve" width="46px" height="46px">
                    <g>

                        <g>
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#C41718" d="M139.5,84.9l10.3-5.9L139.5,73l8.9-7.8l-11.3-3.8l7.2-9.4
                                  l-11.8-1.6l5.3-10.7L126,40.5l3.1-11.5l-11.5,3.1l0.8-11.9l-10.7,5.3l-1.6-11.8l-9.4,7.2L92.9,9.6l-7.8,8.9L79.1,8.3l-5.9,10.3
                                  l-7.8-8.9l-3.8,11.3l-9.4-7.2l-1.6,11.8l-10.7-5.3l0.8,11.9L29.1,29l3.1,11.5l-11.9-0.8l5.3,10.7l-11.8,1.6l7.2,9.4L9.8,65.2
                                  l8.9,7.8L8.4,79l10.3,5.9l-8.9,7.8l11.3,3.8l-7.2,9.4l11.8,1.6l-5.3,10.7l11.9-0.8L29.1,129l11.5-3.1l-0.8,11.9l10.7-5.3l1.6,11.8
                                  l9.4-7.2l3.8,11.3l7.8-8.9l5.9,10.3l6-10.3l7.8,8.9l3.8-11.3l9.4,7.2l1.6-11.8l10.7,5.3l-0.8-11.9l11.5,3.1l-3.1-11.5l11.9,0.8
                                  l-5.3-10.7l11.8-1.6l-7.2-9.4l11.3-3.8L139.5,84.9z"/>
                        </g>
                    </g>
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M42,59.7h77.2v20.5H42V59.7z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M70.3,56.6h22.1v3.2H70.3V56.6z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M48.3,84.9h64.6v29.9H48.3V84.9z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M78.2,53.4c-0.4,6.1-3.4,6.3-9.5,6.3s-13.8-4.9-14.2-11
                          c-0.4-6.6,5.8-12.9,12.6-12.6C73.3,36.4,78.7,45.6,78.2,53.4z M67.2,40.8c-4,0-7.9,3.9-7.9,7.9s5.5,5.9,9.5,6.3
                          c3.3,0.3,4.9-0.3,4.7-3.2C73.2,47.9,71.2,40.8,67.2,40.8z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M82.9,53.4c0.4,6.1,3.4,6.3,9.5,6.3c6.1,0,13.8-4.9,14.2-11
                          c0.4-6.6-5.8-12.9-12.6-12.6C87.9,36.4,82.5,45.6,82.9,53.4z M94,40.8c4,0,7.9,3.9,7.9,7.9s-5.5,5.9-9.5,6.3
                          c-3.3,0.3-4.9-0.3-4.7-3.2C87.9,47.9,90,40.8,94,40.8z"/>
                    <rect x="78.3" y="59.7" fill-rule="evenodd" clip-rule="evenodd" fill="#C41718" width="4.7" height="55.1"/>
                </svg>
            </div><?php
            echo '<span class="sale" style="background:url(/newdis/images/sale' . $product['bonuspay'] . '.png) no-repeat;top: -5px; right: 5px;"></span>';
        } elseif ($product['discount'] > 0) {
            ?>

            <div style="position: absolute; left: 10px; top: 0px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!">
                <a href="<?php echo url_for('@category_bestprice') ?>">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 156 156" enable-background="new 0 0 156 156" xml:space="preserve" width="46px" height="46px">
                        <g>
                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#FAB72F" d="M78.4,7.3l24.3,44l49.3,9.5l-34.4,36.7l6.2,49.9L78.4,126
                                      l-45.5,21.3l6.2-49.9L4.8,60.8l49.3-9.5L78.4,7.3"/>
                            </g>
                        </g>
                        <path display="none" opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" fill="#EF7B17" d="M77.9,14.8l21.8,39.6l44.4,8.5
                              l-30.9,33l5.6,44.8l-40.9-19.2L37,140.7l5.6-44.8l-30.9-33l44.4-8.5L77.9,14.8"/>
                        <g>
                            <defs>
                                <path id="SVGID_1_" d="M70.2,102.1V95h7.3c3.3,0,6.4-0.4,9.4-1.3c3-0.9,5.6-2.2,7.9-4c2.3-1.8,4.1-4,5.4-6.7c1.3-2.7,2-5.8,2-9.5
                                      c0-3.5-0.6-6.5-1.7-8.9c-1.2-2.4-2.8-4.3-4.8-5.7c-2.1-1.4-4.5-2.5-7.4-3.1c-2.9-0.6-6-0.9-9.4-0.9H61.5v32.7H57V95h4.5v7.1H57
                                      v6.2h4.5v13.2h8.7v-13.2h23.4v-6.2H70.2L70.2,102.1z M92.1,80.7c-0.8,1.7-1.9,3.1-3.3,4.1c-1.4,1-3.1,1.8-5.2,2.2
                                      c-2,0.4-4.3,0.7-6.9,0.7h-6.6V62.6h7.2c2.3,0,4.5,0.1,6.4,0.4c1.9,0.3,3.6,0.8,5,1.7c1.4,0.8,2.5,2,3.3,3.6
                                      c0.8,1.5,1.2,3.6,1.2,6.1C93.2,76.9,92.8,79,92.1,80.7L92.1,80.7z"/>
                            </defs>
                            <use xlink:href="#SVGID_1_"  overflow="visible" fill="none" stroke="#FFFFFF" style="fill:#ffffff;fill-opacity:1" stroke-miterlimit="10"/>
                        </g>
                    </svg>
                </a>
            </div><?php
            echo '<span class="sale" style="background:url(/newdis/images/sale' . $product['discount'] . '.png) no-repeat;top: -5px; right: 5px;"></span>';
        } elseif (strtotime($product['created_at']) > time() - (csSettings::get('logo_new') * 24 * 60 * 60)) {

            echo '<span class="newProduct" style=" left: 10px; top: 0px;"></span>';
        }
        ?>
        <?php
        JSInPages("function changePhoto(button,addr){
                    $('.photoGallery .photo img').attr('src',addr);
                    $('.photoGallery .control ul .active').removeClass('active');
                    $(button).addClass('active');
                
                    $('.photoGallery .photo img').attr('data-photoNum',$(button).attr('data-photoNum'));
                }
                function touche_start(event) {
                    // При начальном нажатии получить координаты
                    touch_position = event.touches[0].pageX;
                }
                function touche_move(event, tag){
                    // При движении нажатия отслеживать направление движения
                    var tmp_move = touch_position-event.touches[0].pageX;
                    
                    if (tmp_move<0) {
                        $(tag).children('img').css('margin-right',tmp_move);
                    }
                    else {
                        $(tag).children('img').css('margin-left',0-tmp_move);
                    }
                }
                
                function touche_stop(event,tag){
                    var tmp_move = touch_position-event.changedTouches[0].pageX;
                    if (Math.abs(tmp_move)<100) {
                        $(tag).children('img').css('margin-right',0);
                        $(tag).children('img').css('margin-left',0);
                        return false; 
                    }else{
                        if (tmp_move<0) {
                        
                            if($(tag).children('img').attr('data-photoNum')!=0){
                                $(tag).children('img').animate({
                                    marginRight: 0-($(tag).children('img').width()+$(window).width())
                                }, {'duration':100, 'complete':function(){
                                    $(tag).children('img').css('margin-right', 0);
                                    $(tag).children('img').css('margin-left',0-($(tag).children('img').width()+$(window).width()));
                                    $(tag).children('img').attr('src',$('#photoNum-'+(eval($(tag).children('img').attr('data-photoNum'))-1)).attr('data-photoSrc'));
                                    $(tag).children('img').attr('data-photoNum',eval($(tag).children('img').attr('data-photoNum'))-1);
                                    $(tag).children('img').animate({
                                        marginLeft: 0
                                        }, {'duration':100});
                                }} );
                                $('.slidesjs-pagination-item a').each(function(index){
                                    $(this).removeClass('active');
                                });
                                $('#photoNum-'+(eval($(tag).children('img').attr('data-photoNum'))-1)).addClass('active');
                            }else{
                                $(tag).children('img').animate({
                                        marginRight: 0
                                        }, {'duration':100});
                            }
                            // Листаем вправо
                        }
                        else {
                            if($(tag).children('img').attr('data-photoNum')<$(tag).children('img').attr('data-photoCount')){
                                $(tag).children('img').animate({
                                    marginLeft: 0-($(tag).children('img').width()+$(window).width())
                                }, {'duration':100, 'complete':function(){
                                    $(tag).children('img').css('margin-left',($(tag).children('img').width()+$(window).width()));
                                    $(tag).children('img').attr('src',$('#photoNum-'+(eval($(tag).children('img').attr('data-photoNum'))+1)).attr('data-photoSrc'));
                                    $(tag).children('img').attr('data-photoNum',(eval($(tag).children('img').attr('data-photoNum'))+1));
                                    $(tag).children('img').animate({
                                        marginLeft: 0
                                        }, {'duration':100});
                                }} );
                                $('.slidesjs-pagination-item a').each(function(index){
                                    $(this).removeClass('active');
                                });
                                $('#photoNum-'+(eval($(tag).children('img').attr('data-photoNum'))+1)).addClass('active');
                                // Листаем влево
                            }else{
                                $(tag).children('img').animate({
                                        marginLeft: 0
                                        }, {'duration':100});
                            }
                        }
                    }
                }
        ");
        ?>
        <div class="photo" ontouchstart="touche_start(event);" ontouchmove="touche_move(event, this);" ontouchend="touche_stop(event, this);"><img src="/uploads/photo/thumbnails_250x250/<?= $photosProduct[0]['filename'] ?>" data-photoNum="0" data-photoCount="<?= (count($photosProduct) - 1) ?>" style="max-width: 100%; height: 250px;" alt="<?= str_replace(array("'", '"'), "", $product['name']) ?>"></div>
        <div class="control">
            <ul class="slidesjs-pagination">
                <?php foreach ($photosProduct as $num => $photo) { ?>
                    <li class="slidesjs-pagination-item"><a href="#"<?= $num == '0' ? ' class="active"' : '' ?> onclick="changePhoto(this, '/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>')" data-photoNum="<?= $num ?>" data-photoSrc="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>" id="photoNum-<?= $num ?>">•</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="rating">
        <div class="text">
            Рейтинг
        </div>
        <div class="stars">
            <span style="width:<?= $product['rating'] > 0 ? (@round($product['rating'] / $product['votes_count'])) * 10 : 0 ?>%;"></span>
        </div>
        <div class="text">
            (<?= $product['votes_count'] ?>)
        </div>
    </div>
    <div class="silverBlock">
        <div class="priceBlock">
            <? If ($product['bonuspay'] > 0) { ?>
                <div>
                    <div class="text">Стоимость с учетом бонусов:</div>
                    <span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price><?= number_format($product['price'] - $product['price'] * $product['bonuspay'] / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                </div>
                <div>
                    <div class="text">Полная стоимость:</div>
                    <span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= number_format($product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>

                <div>
                    <div class="text">Оплата бонусами:</div>
                    <?= number_format($product['price'] * $product['bonuspay'] / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></div>
            </div><div class="actionBlockInPriceBlock">
                <div class="Icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                       viewBox="0 0 156 156" enable-background="new 0 0 156 156" xml:space="preserve" width="46px" height="46px">
                        <g>

                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#C41718" d="M139.5,84.9l10.3-5.9L139.5,73l8.9-7.8l-11.3-3.8l7.2-9.4
                                      l-11.8-1.6l5.3-10.7L126,40.5l3.1-11.5l-11.5,3.1l0.8-11.9l-10.7,5.3l-1.6-11.8l-9.4,7.2L92.9,9.6l-7.8,8.9L79.1,8.3l-5.9,10.3
                                      l-7.8-8.9l-3.8,11.3l-9.4-7.2l-1.6,11.8l-10.7-5.3l0.8,11.9L29.1,29l3.1,11.5l-11.9-0.8l5.3,10.7l-11.8,1.6l7.2,9.4L9.8,65.2
                                      l8.9,7.8L8.4,79l10.3,5.9l-8.9,7.8l11.3,3.8l-7.2,9.4l11.8,1.6l-5.3,10.7l11.9-0.8L29.1,129l11.5-3.1l-0.8,11.9l10.7-5.3l1.6,11.8
                                      l9.4-7.2l3.8,11.3l7.8-8.9l5.9,10.3l6-10.3l7.8,8.9l3.8-11.3l9.4,7.2l1.6-11.8l10.7,5.3l-0.8-11.9l11.5,3.1l-3.1-11.5l11.9,0.8
                                      l-5.3-10.7l11.8-1.6l-7.2-9.4l11.3-3.8L139.5,84.9z"/>
                            </g>
                        </g>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M42,59.7h77.2v20.5H42V59.7z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M70.3,56.6h22.1v3.2H70.3V56.6z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M48.3,84.9h64.6v29.9H48.3V84.9z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M78.2,53.4c-0.4,6.1-3.4,6.3-9.5,6.3s-13.8-4.9-14.2-11
                              c-0.4-6.6,5.8-12.9,12.6-12.6C73.3,36.4,78.7,45.6,78.2,53.4z M67.2,40.8c-4,0-7.9,3.9-7.9,7.9s5.5,5.9,9.5,6.3
                              c3.3,0.3,4.9-0.3,4.7-3.2C73.2,47.9,71.2,40.8,67.2,40.8z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M82.9,53.4c0.4,6.1,3.4,6.3,9.5,6.3c6.1,0,13.8-4.9,14.2-11
                              c0.4-6.6-5.8-12.9-12.6-12.6C87.9,36.4,82.5,45.6,82.9,53.4z M94,40.8c4,0,7.9,3.9,7.9,7.9s-5.5,5.9-9.5,6.3
                              c-3.3,0.3-4.9-0.3-4.7-3.2C87.9,47.9,90,40.8,94,40.8z"/>
                        <rect x="78.3" y="59.7" fill-rule="evenodd" clip-rule="evenodd" fill="#C41718" width="4.7" height="55.1"/>
                    </svg></div>
                <div class="description">Оплачивайте товары своими Бонусами.<br />
                    <a href="<?php echo url_for('@page?slug=programma-on-i-ona-bonus') ?>">Условия акции УПРАВЛЯЙ ЦЕНОЙ! ></a>
                </div>
            <? } elseif ($product['discount'] > 0) { ?>
                <div>
                    <div class="text">Стоимость сегодня:</div>
                    <span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>
                        <?= $product['price'] ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span>
                    </span>
                </div>
                <div>
                    <div class="text">Стоимость без скидки:</div>
                    <span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">
                        <?= $product['old_price'] ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span>
                </div>
                <div>
                    <div class="text">Экономия:</div>
                    <?= number_format($product['old_price'] - $product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span>
                </div>
            </div><div class="actionBlockInPriceBlock">
                <div class="Icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                       viewBox="0 0 156 156" enable-background="new 0 0 156 156" xml:space="preserve" width="46px" height="46px">
                        <g>
                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#FAB72F" d="M78.4,7.3l24.3,44l49.3,9.5l-34.4,36.7l6.2,49.9L78.4,126
                                      l-45.5,21.3l6.2-49.9L4.8,60.8l49.3-9.5L78.4,7.3"/>
                            </g>
                        </g>
                        <path display="none" opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" fill="#EF7B17" d="M77.9,14.8l21.8,39.6l44.4,8.5
                              l-30.9,33l5.6,44.8l-40.9-19.2L37,140.7l5.6-44.8l-30.9-33l44.4-8.5L77.9,14.8"/>
                        <g>
                            <defs>
                                <path id="SVGID_1_" d="M70.2,102.1V95h7.3c3.3,0,6.4-0.4,9.4-1.3c3-0.9,5.6-2.2,7.9-4c2.3-1.8,4.1-4,5.4-6.7c1.3-2.7,2-5.8,2-9.5
                                      c0-3.5-0.6-6.5-1.7-8.9c-1.2-2.4-2.8-4.3-4.8-5.7c-2.1-1.4-4.5-2.5-7.4-3.1c-2.9-0.6-6-0.9-9.4-0.9H61.5v32.7H57V95h4.5v7.1H57
                                      v6.2h4.5v13.2h8.7v-13.2h23.4v-6.2H70.2L70.2,102.1z M92.1,80.7c-0.8,1.7-1.9,3.1-3.3,4.1c-1.4,1-3.1,1.8-5.2,2.2
                                      c-2,0.4-4.3,0.7-6.9,0.7h-6.6V62.6h7.2c2.3,0,4.5,0.1,6.4,0.4c1.9,0.3,3.6,0.8,5,1.7c1.4,0.8,2.5,2,3.3,3.6
                                      c0.8,1.5,1.2,3.6,1.2,6.1C93.2,76.9,92.8,79,92.1,80.7L92.1,80.7z"/>
                            </defs>
                            <use xlink:href="#SVGID_1_"  overflow="visible" fill="none" stroke="#FFFFFF" style="fill:#ffffff;fill-opacity:1" stroke-miterlimit="10"/>
                        </g>
                    </svg></div>
                <div class="description">Самые низкие цены в Рунете.<br />
                    Не упустите выгодную покупку!
                </div>
            <? } else { ?>
                <div>
                    <div class="text">Стоимость сегодня:</div>
                    <span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price><?= number_format($product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>

                <? If ($product['bonuspay'] != '0') { ?>
                    <div>
                        <div class="text">Стоимость при оплате бонусами:</div><span style="font-size: 16px; margin-right: 3px;text-decoration: underline;"><?= number_format($product['price'] - $product['price'] * ($product['bonuspay'] > 0 ? $product['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY')) / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>

                <? } ?>
                <div>
                    <div class="text">Бонусы за покупку:</div>
                    <span style="text-decoration: underline;"><?= round(($product['price'] - $product['price'] * ($product['bonuspay'] > 0 ? $product['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100)) ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span>
                    </span></div>

            <? } ?>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="buttonsBlock">
        <?
        $arrayProdCart = array();
        $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
        if (is_array($products_old)) {
            foreach ($products_old as $key => $productCart) {
                $arrayProdCart[] = $productCart['productId'];
            }
        }
        if ($product['count'] == 0) {
            ?>
            <div style="color: #c3060e;">Товара нет в наличии</div>
            <a href="<?php echo url_for('@product_senduser?slug=' . $product['slug']) ?>" class="redButton" style="margin-top:0;">Сообщить о поступлении</a>
            <?php
        } elseif ((in_array($product['id'], $arrayProdCart) === true)) {
            ?>
            <div style="color: #42b039;">Товар добавлен в корзину</div>
            <a href="<?= url_for('@cart_index') ?>" class="greenButton" style="margin-top:0;">Перейти в корзину</a>
            <?php
        } else {
            JSInPages("function addToCart(tag){
                $.post('" . url_for('@product_addtocart?slug=' . $product['slug']) . "', {id:" . $product['id'] . ", quantity: 1}, function (data) {
                            $('#svgCartButtonCount').text(data);
                        $.post('" . url_for('@product_addtocart?slug=' . $product['slug']) . "', {id:" . $product['id'] . ", quantity: 1}, function (data) {
                            $('#svgCartButtonCount').text(data);
                        });
                    });
                    $('#svgCartButton').css('display','block');
                    $(tag).after($('<a class=\'greenButton\' id=\'greenButtonPreShowAddedToCart\' style=\'margin-top:0;\'>').attr('href', '" . url_for('@cart_index') . "').text('Перейти в корзину')).remove();
                    $('#greenButtonPreShowAddedToCart').before($('<div style=\'color: #42b039;\'>').text('Товар добавлен в корзину'))
                    $('.freeDelivery').remove();
                    
                    return false;
                }");
            if ($product['price'] >= csSettings::get('free_deliver'))
                echo '<div class="freeDelivery"></div>';
            ?>
            <div class="redButton" onClick="addToCart(this)" style="margin-top:0;">Добавить в корзину</div>
        <? } ?>
    </div>

    <?php
    JSInPages("function togglePoint(tag){
                $(tag).parent().children('div.content').toggle();
                if($(tag).children('div.togglePoint').hasClass('minus')){
                    $(tag).children('div.togglePoint').removeClass('minus').addClass('plus');
                }else{
                    $(tag).children('div.togglePoint').removeClass('plus').addClass('minus');
                }
                }");
    ?>

    <div class="point paramsPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint minus">
            </div>
            <div class="namePointRed">
                Общие характеристики
            </div>
        </div>
        <div class="content" style="padding: 20px;"><?php
            include_partial("product/params", array("product" => $product, "params" => $params, "countProductsParams" => $countProductsParams));
            ?>
        </div>
    </div>

    <div class="point infoPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint minus">
            </div>
            <div class="namePointRed">
                Подробная информация о товаре
            </div>
        </div>
        <div class="content" id="contentProductDescription" style="padding: 20px;">
            <?
            $dom = new DOMDocument;
            //$dom->loadHTML($product['content']);
            $dom->loadHTML(mb_convert_encoding($product['content'], 'HTML-ENTITIES', 'UTF-8'));
            foreach ($dom->getElementsByTagName('iframe') as $node) {
                if ($node->hasAttribute('src')) {
                    echo '<div style="display: block;position: relative;padding-top: 56.2857%;">
<iframe allowfullscreen="" frameborder="0" height="425" src="' . $node->getAttribute('src') . '" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;"></iframe></div>';

                    $node->parentNode->removeChild($node);
                }
            }
            echo mb_convert_encoding($dom->saveHTML(), 'UTF-8', 'HTML-ENTITIES');
            
    JSInPages('function disableSelection(target) {
                            if (typeof target.onselectstart != "undefined")
                                target.onselectstart = function () {
                                    return false
                                }
                            else if (typeof target.style.MozUserSelect != "undefined")
                                target.style.MozUserSelect = "none"
                            else
                                target.onmousedown = function () {
                                    return false
                                }
                            target.style.cursor = "default"
                        }

                        disableSelection(document.getElementById("contentProductDescription"));');
            ?>
            
        </div>
    </div>

    <div class="point commentPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="namePoint">
                Отзывы покупателей <?= count($commentsProduct) ?>
            </div>
        </div>
        <div class="content" style="display: none;">
            <div class="addCommentBlock">
                Ваше мнение важно для нас.
                <a href="<?php echo url_for('@product_addComment?slug=' . $product['slug']) ?>"><div class="pinkButton">Оставить свой отзыв о товаре</div></a>
            </div>
            <div class="commentsBlock">
                <?php foreach ($commentsProduct as $numComment => $comment): ?>
                    <div style="comment">
                        <div class="top silverBlock">
                            <div class="rating"><div class="stars" style="margin: 3px 0 0 5px;">
                                    <span style="width:<?= $comment['rate_set'] > 0 ? $comment['rate_set'] * 10 : ($product['rating'] > 0 ? @round($product['rating'] / $product['votes_count']) * 10 : 0) ?>%;"></span>
                                </div></div>
                            <div class="name"><?= $comment['username'] != "" ? $comment['username'] : $comment['first_name'] ?></div>
                        </div>
                        <div class="content"><?= $comment['text'] ?></div>
                        <div class="footer">
                            <div class="date"><?= date("d.m.Y H:i", strtotime($comment['created_at'])) ?></div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="point stockPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="namePoint">
                Наличие в магазинах Москвы
            </div>
        </div>
        <div class="content" style="display: none;">
            <div class="stockBlock">
                <div class="topStockBlock">
                    Магазины, в которых этот товар есть в наличии:
                </div>
                <?php
                $stock = unserialize($product['stock']);
                if (( count($stock['storages']['storage']) > 0)) :
                    ?>
                    <ul class="stockList">
                        <?php
                        foreach ($stock['storages']['storage'] as $storage):
                            if (@$storage['@attributes']['code1c'] or $storage['code1c']):

                                @$shopsId = is_array($storage['@attributes']) ? $storage['@attributes']['code1c'] : $storage['code1c'];

                                $codeShop1cIsStock[] = is_array($storage['@attributes']) ? "'".$storage['@attributes']['code1c'] ."'": "'".$storage['code1c']."'";
                                if (@is_array($shops[$shopsId])):
                                    ?><li>
                                        <a href="<?php echo url_for('@page?slug=' . $shops[$shopsId]['slug']) ?>">
                                            <div class="arrowRight">
                                            </div>
                                            <div class="shop">
                                                <div style="float: left;margin-right: 5px;"><img src="/uploads/metro/<?= $shops[$shopsId]['iconmetro'] ?>" alt="metro"></div>

                                                <div class="address-holder">
                                                    <div class="name-holder">
                                                        <span class="text"><?= $shops[$shopsId]['metro'] ?></span>
                                                    </div>
                                                    <div class="address"><?= $shops[$shopsId]['address'] ?></div>
                                                </div>
                                                <div class="stockInfo">
                                                    <div style="color: #42b039;">В наличии</div>
                                                </div>
                                                <div class="work-time"><?
                                                    $workTime = explode(",", $shops[$shopsId]['worktime']);
                                                    if ($workTime[0] == $workTime[6]) {
                                                        $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
                                                    } elseif ($workTime[0] == $workTime[4]) {
                                                        $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                                                        if ($workTime[5] == $workTime[6]) {
                                                            $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                        } else {
                                                            $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                            $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                                                        }
                                                    } else {
                                                        $strWorktime = "Пн " . $workTime[0] . "<br />";
                                                        $strWorktime.="Вт " . $workTime[1] . "<br />";
                                                        $strWorktime.="Ср " . $workTime[2] . "<br />";
                                                        $strWorktime.="Чт " . $workTime[3] . "<br />";
                                                        $strWorktime.="Пн " . $workTime[4] . "<br />";
                                                        if ($workTime[5] == $workTime[6]) {
                                                            $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                        } else {
                                                            $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                            $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                                                        }
                                                    }
                                                    echo $strWorktime;
                                                    ?></div>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                endif;
                            endif;
                        endforeach;
                        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

                        $shopsNotCount = $q->execute("SELECT * "
                                        . "FROM shops "
                                        . "WHERE id1c not in (" . implode(",", $codeShop1cIsStock) . ") ")->fetchAll(Doctrine_Core::FETCH_UNIQUE);

                        foreach ($shopsNotCount as $shop):
                            ?><li>
                                <a href="<?php echo url_for('@page?slug=' . $shop['slug']) ?>">
                                    <div class="arrowRight">
                                    </div>
                                    <div class="shop">
                                        <div style="float: left;margin-right: 5px;"><img src="/uploads/metro/<?= $shop['iconmetro'] ?>" alt="metro"></div>

                                        <div class="address-holder">
                                            <div class="name-holder">
                                                <span class="text"><?= $shop['metro'] ?></span>
                                            </div>
                                            <div class="address"><?= $shop['address'] ?></div>
                                        </div>
                                        <div class="stockInfo">
                                            <div>Под заказ </div>
                                        </div>
                                        <div class="work-time"><?
                                            $workTime = explode(",", $shop['worktime']);
                                            if ($workTime[0] == $workTime[6]) {
                                                $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
                                            } elseif ($workTime[0] == $workTime[4]) {
                                                $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                                                if ($workTime[5] == $workTime[6]) {
                                                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                } else {
                                                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                                                }
                                            } else {
                                                $strWorktime = "Пн " . $workTime[0] . "<br />";
                                                $strWorktime.="Вт " . $workTime[1] . "<br />";
                                                $strWorktime.="Ср " . $workTime[2] . "<br />";
                                                $strWorktime.="Чт " . $workTime[3] . "<br />";
                                                $strWorktime.="Пн " . $workTime[4] . "<br />";
                                                if ($workTime[5] == $workTime[6]) {
                                                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                } else {
                                                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                                                }
                                            }
                                            echo $strWorktime;
                                            ?></div>
                                    </div>
                                </a>
                            </li>
                            <?php
                        endforeach;
                        ?>
                    </ul>
                    <?php
                else:
                endif;
                ?>
            </div>
        </div>
    </div>

    <div class="point deliveryAndPaymentPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="namePoint">
                Доставка и оплата
            </div>
        </div>
        <div class="content" style="display: none;padding: 20px;">
            <?= $deliveryAndPayment['content_mobile'] != "" ? $deliveryAndPayment['content_mobile'] : $deliveryAndPayment['content']; ?>
        </div>
    </div>

    <div class="point similarProductPoint">
        <div class="topPoint">
            <div class="togglePoint minus" onclick="togglePoint(this);">
            </div>
            <div class="namePoint">
                Похожие товары
            </div>
        </div>
        <ul class="product">
            <?php
            foreach ($productsSimilar as $product['id'] => $product) {
                echo "<li>";


                include_partial("product/productBooklet", array(
                    'sf_cache_key' => $product['id'],
                    'product' => $product,
                    'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                    'photo' => $photosAll[$product['id']]
                        )
                );
                echo "</li>";
            }
            ?>
        </ul>
        <a href="<?php echo url_for('@category?slug=' . $category['slug']) ?>">
            <div class="bottomPoint">

                <div class="arrowRight">
                </div>
                <div class="textPoint">
                    Все товары категории
                </div>
            </div>
        </a>
    </div>
</div>