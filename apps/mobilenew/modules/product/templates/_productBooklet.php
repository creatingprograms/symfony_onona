<div class="productBooklet<?php if ($product['count'] == 0): ?> notCount<?php endif; ?>">
    <?php if ($product['bonuspay'] > 0) { ?>
        <div style="position: absolute; left: 0px; top: 60px;z-index: 10; cursor:pointer;">

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
        </div>
        <?
        echo '<span class="sale" style="background:url(/newdis/images/sale' . $product['bonuspay'] . '.png) no-repeat;">-' . $product['bonuspay'] . '%</span>';
    } elseif ($product['discount'] > 0) {
        ?>
        <div style="position: absolute; left: 0px; top: 60px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!">
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
        </div>
        <?
        echo '<span class="sale" style="background:url(/newdis/images/sale' . $product['discount'] . '.png) no-repeat;">-' . $product['discount'] . '%</span>';
    } else {
        echo strtotime($product['created_at']) > time() - (csSettings::get('logo_new') * 24 * 60 * 60) ? '<span class="newProduct"></span>' : '';
    }

    echo (($product['video'] != '' or $product['videoenabled'] ) and false) ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : '';
    ?>
    <div class="title">
        <a href="<?php echo url_for('@product_show?slug=' . $product['slug']) ?>"><?= mb_substr($product['name'], 0, 55) ?></a>
    </div>
    <div class="img-holder">
        <a href="<?php echo url_for('@product_show?slug=' . $product['slug']) ?>">
            <img src="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>" alt="<?= $product['name'] ?>" title="<?= $product['name'] ?>" class="thumbnails">
        </a>
        <?
        if ($product['endaction'] != ""):

            $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
            if ($product['step'] != "") {
                //echo $product['endaction'] - time() + 24 * 60 * 60;
                if ((strtotime($product['endaction']) - time() + 24 * 60 * 60) > $step[$product['step']] * 24 * 60 * 60) {
                    $dateEnd = date("Y, m-1, d", (strtotime($product['endaction']) - floor((strtotime($product['endaction']) - time() + 24 * 60 * 60) / ($step[$product['step']] * 24 * 60 * 60)) * $step[$product['step']] * 24 * 60 * 60));
                } else {
                    $dateEnd = date("Y, m-1, d", strtotime($product['endaction']));
                }
            } else {
                $dateEnd = date("Y, m-1, d", strtotime($product['endaction']));
            }
            JSInPages("$('.countdown .actionProduct_" . $product['id'] . "').countdown({
                            //format: 'd hh:mm:ss',
                            format: 'hh:mm:ss',
                            compact: true,
                            description: '',
                            until: new Date(" . $dateEnd . ", 23, 59, 59)
                                    //timezone: +6
                        });");
            ?>
            <div class="countdown">

                <a class="buy">Успейте купить!</a>
                <span class="time actionProduct_<?= $product['id'] ?>"></span>

            </div> 
            <? Endif; ?>
    </div>
    <div class="bottom-box">
        <div class="row">
            <?php if ($comment['countcomm'] > 0) : ?>
                <a href="<?php echo url_for('@product_show?slug=' . $product['slug']) ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comment['countcomm'] ?></a>  
            <?php endif; ?>
            <div class="stars">
                <span style="width:<?= $product['rating'] > 0 ? (@round($product['rating'] / $product['votes_count'])) * 10 : 0 ?>%;"></span>
            </div>
        </div>
        <div class="price-box">
            <?php if ($product['old_price'] > 0) { ?>
                <span class="old-price"><?= number_format($product['old_price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                <span class="new-price"><?= number_format($product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
            <? } elseif ($product['bonuspay'] > 0) { ?>
                <span class="old-price"><?= number_format($product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                <span class="new-price"><?= number_format($product['price'] - $product['price'] * $product['bonuspay'] / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
            <? } else { ?>
                <span class="price"><?= number_format($product['price'], 0, '', ' ') ?>  <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
            <? } ?>
        </div>

        <div class="bonus-box-price"><?php
            if ($product['bonuspay'] > 0) {
                $bonusAddUser = round(($product['price'] - $product['price'] * ($product['bonuspay'] / 100)) * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                if ($bonusAddUser > 0) {
                    ?>+<?= $bonusAddUser ?> бонусов<?
                }
            } else {
                ?>+<?= round($product['price'] * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100)) ?> бонусов<? } ?>                   
        </div>  
        <div style="clear: both"></div>
    </div>
</div>