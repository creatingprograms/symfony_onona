<?php
//slot('articleleftBlock', true);

slot('horoscopeleftBlock', true);
slot('metaTitle', "Любовный гороскоп для знака зодиака " . stripslashes($horoscope->getName()) . " * Любовный гороскоп * Главная");
slot('metaKeywords', "Любовный гороскоп для знака зодиака " . stripslashes($horoscope->getName()) . " * Любовный гороскоп * Главная");
slot('metaDescription', "Любовный гороскоп для знака зодиака " . stripslashes($horoscope->getName()) . " * Любовный гороскоп * Главная");
slot('articlerightpad', true);
?>

<div class="horoscope-wrapper">
<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>
        <a href="/horoscope">Любовный гороскоп</a>
    </li>
    <li>Любовный гороскоп для знака зодиака <?php echo $horoscope->getName() ?></li>
</ul>
<h1 class="title">Любовный гороскоп для знака зодиака <?php echo $horoscope->getName() ?></h1>
<div id="horoscopeInfo"><div class="logoInfo" style="background: url('/images/horoscope/icon/<?php echo $horoscope->getId() ?>.png'); "></div>
    <div class="infoBlock"><span class="name"><?php echo $horoscope->getName() ?></span> (<?php echo $horoscope->getDate() ?>)<br/>
        <?php echo $horoscope->getInfo() ?>
    </div>
</div>

<div style="clear: both" class="hide-tablet"></div>


<div class="tabset horoscopeTabset">
    <ul class="tab-control">
        <li class="active" style="margin-left: 10px;"><a href="#"><span>Любовный гороскоп на месяц</span></a></li>
        <li><a href="#"><span>Гороскоп на год</span></a></li>
        <li><a href="#"><span>Общая характеристика</span></a></li>
        <li><a href="#"><span>Совместимость знаков</span></a></li>
    </ul>
    <div class="tab" style="display:block;"><?php echo $horoscope->getMonth() ?>
    </div>
    <div class="tab"><?php echo $horoscope->getYear() ?>
    </div>
    <div class="tab"><?php echo $horoscope->getCharacteristic() ?>
    </div>
    <div class="tab">
        <div style="color: #C3060E;
             font: 18px/21px Tahoma,Geneva,sans-serif;
             margin: 0 0 14px; text-align: center;">Вы <?php echo $horoscope->getName() ?>, выберите знак зодиака своего партнёра</div>
        <script>
            // wait for the DOM to be loaded
            $(document).ready(function() {



                // bind 'myForm' and provide a simple callback function
                $('#horoscopeSovmForm').ajaxForm(function(result) {
                    $("#horoscopeSovm").html(result);

                });
            });
        </script>
        <form action="/horoscopesovm" id="horoscopeSovmForm" method="POST">
          <div class="form-wrapper">
                <div class="select-wrapper">Знак женщины: <br />
                    <select name="horoscope_g_id" style="width: 180px;" onChange="jQuery('#horoscopeSovmForm').submit();" id="HoroscopeSovnG">
                        <option value="1"<?= $horoscope->getId() == 1 ? ' selected="selected"' : '' ?>>Овен</option>
                        <option value="2"<?= $horoscope->getId() == 2 ? ' selected="selected"' : '' ?>>Телец</option>
                        <option value="3"<?= $horoscope->getId() == 3 ? ' selected="selected"' : '' ?>>Близнецы</option>
                        <option value="4"<?= $horoscope->getId() == 4 ? ' selected="selected"' : '' ?>>Рак</option>
                        <option value="5"<?= $horoscope->getId() == 5 ? ' selected="selected"' : '' ?>>Лев</option>
                        <option value="6"<?= $horoscope->getId() == 6 ? ' selected="selected"' : '' ?>>Дева</option>
                        <option value="7"<?= $horoscope->getId() == 7 ? ' selected="selected"' : '' ?>>Весы</option>
                        <option value="8"<?= $horoscope->getId() == 8 ? ' selected="selected"' : '' ?>>Скорпион</option>
                        <option value="9"<?= $horoscope->getId() == 9 ? ' selected="selected"' : '' ?>>Стрелец</option>
                        <option value="10"<?= $horoscope->getId() == 10 ? ' selected="selected"' : '' ?>>Козерог</option>
                        <option value="11"<?= $horoscope->getId() == 11 ? ' selected="selected"' : '' ?>>Водолей</option>
                        <option value="12"<?= $horoscope->getId() == 12 ? ' selected="selected"' : '' ?>>Рыбы</option>
                    </select>
                </div>
                <div class="horoscope-image"><img src="/images/horoscope/m-g.png"></div>
                <div class="select-wrapper">Знак мужчины: <br />
                    <select name="horoscope_m_id" style="width: 180px;" onChange="jQuery('#horoscopeSovmForm').submit();" id="HoroscopeSovnM">
                        <option value="1"<?= $horoscope->getId() == 1 ? ' selected="selected"' : '' ?>>Овен</option>
                        <option value="2"<?= $horoscope->getId() == 2 ? ' selected="selected"' : '' ?>>Телец</option>
                        <option value="3"<?= $horoscope->getId() == 3 ? ' selected="selected"' : '' ?>>Близнецы</option>
                        <option value="4"<?= $horoscope->getId() == 4 ? ' selected="selected"' : '' ?>>Рак</option>
                        <option value="5"<?= $horoscope->getId() == 5 ? ' selected="selected"' : '' ?>>Лев</option>
                        <option value="6"<?= $horoscope->getId() == 6 ? ' selected="selected"' : '' ?>>Дева</option>
                        <option value="7"<?= $horoscope->getId() == 7 ? ' selected="selected"' : '' ?>>Весы</option>
                        <option value="8"<?= $horoscope->getId() == 8 ? ' selected="selected"' : '' ?>>Скорпион</option>
                        <option value="9"<?= $horoscope->getId() == 9 ? ' selected="selected"' : '' ?>>Стрелец</option>
                        <option value="10"<?= $horoscope->getId() == 10 ? ' selected="selected"' : '' ?>>Козерог</option>
                        <option value="11"<?= $horoscope->getId() == 11 ? ' selected="selected"' : '' ?>>Водолей</option>
                        <option value="12"<?= $horoscope->getId() == 12 ? ' selected="selected"' : '' ?>>Рыбы</option>
                    </select>
                </div></div></form>
        <br />

        <div id="horoscopeSovm">
          <?php $horoscopesovm = HoroscopesovmTable::getInstance()
            ->createQuery()
            ->where("horoscope_m_id=?", $horoscope->getId())
            ->addWhere("horoscope_g_id=?", $horoscope->getId())
            ->fetchOne();
          ?>
            <div style="
                 font: 18px/21px Tahoma,Geneva,sans-serif;
                 margin: 0 0 14px;">Совместимость <?= $horoscopesovm->getHoroscopeg()->getName() ?>-женщина + <?= $horoscopesovm->getHoroscopem()->getName() ?>-мужчина</div>
                 <?
                 echo $horoscopesovm->getContent()
                 ?>
        </div>
    </div>
</div>
<div style="clear: both"></div>
<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
<!--            <a href="http://club.onona.ru/index.php/topic/121-akcija-laikni-tovar-i-poluchi-ego-v-podarok/
" target="_blank"><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus" style="background: url('/images/SocSeti2.jpg') no-repeat scroll 0pt 0pt transparent; text-align: center; width: 294px; padding-top: 24px; height: 32px;"></div></a> -->
            <div class="yashare-wrapper" style="background: none; height: 22px; margin-left:-6px;margin-top:-6px;">
            <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                 data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

                 ></div>
        </div>

        <div class="old-browsers"></div>
        <div class="article-arrow"><span style="color: #C3060E; font-size: 17px;">ДРУГИЕ ЗНАКИ</span></div>

<div id="horoscopeList"><ul>
        <?php foreach ($horoscopes as $horoscope): ?>
            <li style="background: url('/images/horoscope/icon/<?php echo $horoscope->getId() ?>.png'); " <? if ($horoscope->getSlug() == $sf_request->getParameter('slug')) echo " class=\"selectHoroscope\""; ?>>
                <a href="/horoscope/<?php echo $horoscope->getSlug() ?>"><div><span class="name"><?php echo $horoscope->getName() ?></span><br/>
                        <?php echo $horoscope->getDate() ?></div></a>
            </li>

        <?php endforeach; ?>
    </ul>
</div>
<div style="clear: both"></div>

<?
/* <table>
  <tbody>
  <tr>
  <th>Id:</th>
  <td><?php echo $horoscope->getId() ?></td>
  </tr>
  <tr>
  <th>Name:</th>
  <td><?php echo $horoscope->getName() ?></td>
  </tr>
  <tr>
  <th>Date:</th>
  <td><?php echo $horoscope->getDate() ?></td>
  </tr>
  <tr>
  <th>Image:</th>
  <td><?php echo $horoscope->getImage() ?></td>
  </tr>
  <tr>
  <th>Info:</th>
  <td><?php echo $horoscope->getInfo() ?></td>
  </tr>
  <tr>
  <th>Month:</th>
  <td><?php echo $horoscope->getMonth() ?></td>
  </tr>
  <tr>
  <th>Year:</th>
  <td><?php echo $horoscope->getYear() ?></td>
  </tr>
  <tr>
  <th>Characteristic:</th>
  <td><?php echo $horoscope->getCharacteristic() ?></td>
  </tr>
  <tr>
  <th>Compatibility:</th>
  <td><?php echo $horoscope->getCompatibility() ?></td>
  </tr>
  <tr>
  <th>Created at:</th>
  <td><?php echo $horoscope->getCreatedAt() ?></td>
  </tr>
  <tr>
  <th>Updated at:</th>
  <td><?php echo $horoscope->getUpdatedAt() ?></td>
  </tr>
  <tr>
  <th>Slug:</th>
  <td><?php echo $horoscope->getSlug() ?></td>
  </tr>
  </tbody>
  </table>

  <hr />

  <a href="<?php echo url_for('horoscope/edit?id='.$horoscope->getId()) ?>">Edit</a>
  &nbsp;
  <a href="<?php echo url_for('horoscope/index') ?>">List</a>
 */?></div>
