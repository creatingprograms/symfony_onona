<?php
//slot('articleleftBlock', true);
slot('horoscopeleftBlock', true);
slot('metaTitle', "Любовный гороскоп * Главная");
slot('metaKeywords', "Любовный гороскоп * Главная");
slot('metaDescription', "Любовный гороскоп * Главная");
slot('articlerightpad', true);
?>

<div class="horoscope-wrapper">
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>Любовный гороскоп</li>
    </ul>
    <div>
        <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
        <div class="yashare-wrapper" style="background: none; height: 22px; margin-left:-6px;margin-top:-6px;float:right;">
            <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
                 data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

                 ></div>
        </div>
    </div>
    <?php
    $page = PageTable::getInstance()->findOneById(325);
    echo $page->getContent();
    ?>
    <div id="horoscopeList"><ul>
            <?php foreach ($horoscopes as $horoscope): ?>
                <li style="background: url('/images/horoscope/icon/<?php echo $horoscope->getId() ?>.png'); ">
                    <a href="/horoscope/<?php echo $horoscope->getSlug() ?>"><div><span class="name"><?php echo $horoscope->getName() ?></span><br/>
                            <?php echo $horoscope->getDate() ?></div></a>
                </li>

            <?php endforeach; ?>
        </ul>
    </div>
    <div style="clear: both"></div>
    <? /* <tr>
      <td><a href="<?php echo url_for('horoscope/show?id='.$horoscope->getId()) ?>"><?php echo $horoscope->getId() ?></a></td>
      <td><?php echo $horoscope->getName() ?></td>
      <td><?php echo $horoscope->getDate() ?></td>
      <td><?php echo $horoscope->getImage() ?></td>
      <td><?php echo $horoscope->getInfo() ?></td>
      <td><?php echo $horoscope->getMonth() ?></td>
      <td><?php echo $horoscope->getYear() ?></td>
      <td><?php echo $horoscope->getCharacteristic() ?></td>
      <td><?php echo $horoscope->getCompatibility() ?></td>
      <td><?php echo $horoscope->getCreatedAt() ?></td>
      <td><?php echo $horoscope->getUpdatedAt() ?></td>
      <td><?php echo $horoscope->getSlug() ?></td>
      </tr> */ ?></div>
