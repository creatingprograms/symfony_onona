<?php if(sizeof($shops)) :?>
  <ul<?=$ul_class ? ' class="'.$ul_class.'"' : ''?>>
    <?php foreach ($shops as $shop) : ?>
      <?php
        $metro = $shop->getMetro();
        if(mb_strlen($metro) > 40)
          $metro = str_replace('Пункт выдачи интернет заказов ', '', $metro);
      ?>
      <li<?=$li_class ? ' class="'.$li_class.'"' : ''?>>
        <?php if(isset($is_list) && $is_list) :?>
          <div class="name-holder">
  					<a href="<?= url_for('shops/'.$shop->getSlug()) ?>">
              <span class="img-holder">
                <img alt="image description" src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/newdis/images/metro00.png'?>">
              </span>
              <span class="text"><?= $metro ?></span>
            </a>
            <?php if($shop->getIsNew()) : ?>
              <span class="text"></span><span style="font-size:16px;">&nbsp;&nbsp;<span style="color: rgb(255, 0, 0);"><strong>NEW</strong></span></span>
            <?php endif ?>
          </div>
          <div class="address">
            <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
            <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
          </div>
        <?php else :?>
          <div class="img-holder">
            <div class="metro">
              <span class="ico-holder">
                <img alt="image description" src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/newdis/images/metro00.png'?>" />
              </span>
              <span class="text"><?=$metro ? $metro : ''?></span>
            </div>
            <img
              alt="Магазин для взрослых Он и Она,<?=$shop->getAddress()?>"
              src="/uploads/assets/images/<?=$shop->getPreviewImage() ? $shop->getPreviewImage() : '001.jpg'?>" />
            <a class="more-btn" href="<?= url_for('shops/'.$shop->getSlug()) ?>"><span>Подробнее</span></a>
          </div>
          <div class="title">Адрес:</div>
          <div class="address">
            <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
            <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
          </div>
          <div class="work-time"><?=$shop->getWorktime()?></div>
        <?php endif ?>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>
<?php if(sizeof($shopsVib)) :?>
  <h3>Сеть магазинов Взрослые Подарки Vibrosklad</h3>
  <ul<?=$ul_class ? ' class="'.$ul_class.'"' : ''?>>
    <?php foreach ($shopsVib as $shop) : ?>
      <?php
        $metro = $shop->getMetro();
        if(mb_strlen($metro) > 40)
          $metro = str_replace('Пункт выдачи интернет заказов ', '', $metro);
      ?>
      <li<?=$li_class ? ' class="'.$li_class.'"' : ''?>>
        <?php if(isset($is_list) && $is_list) :?>
          <div class="name-holder">
  					<a href="<?= url_for('shops/'.$shop->getSlug()) ?>">
              <span class="img-holder">
                <img alt="image description" src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/newdis/images/metro00.png'?>">
              </span>
              <span class="text"><?= $metro ?></span>
            </a>
            <?php if($shop->getIsNew()) : ?>
              <span class="text"></span><span style="font-size:16px;">&nbsp;&nbsp;<span style="color: rgb(255, 0, 0);"><strong>NEW</strong></span></span>
            <?php endif ?>
          </div>
          <div class="address">
            <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
            <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
          </div>
        <?php else :?>
          <div class="img-holder">
            <div class="metro">
              <span class="ico-holder">
                <img alt="image description" src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/newdis/images/metro00.png'?>" />
              </span>
              <span class="text"><?=$metro ? $metro : ''?></span>
            </div>
            <img
              alt="Магазин для взрослых Он и Она,<?=$shop->getAddress()?>"
              src="/uploads/assets/images/<?=$shop->getPreviewImage() ? $shop->getPreviewImage() : '001.jpg'?>" />
            <a class="more-btn" href="<?= url_for('shops/'.$shop->getSlug()) ?>"><span>Подробнее</span></a>
          </div>
          <div class="title">Адрес:</div>
          <div class="address">
            <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
            <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
          </div>
          <div class="work-time"><?=$shop->getWorktime()?></div>
        <?php endif ?>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>
<?php if(sizeof($shopsAdam)) :?>
  <h3>Сеть магазинов Адам и Ева</h3>
  <ul<?=$ul_class ? ' class="'.$ul_class.'"' : ''?>>
    <?php foreach ($shopsAdam as $shop) : ?>
      <?php
        $metro = $shop->getMetro();
        if(mb_strlen($metro) > 40)
          $metro = str_replace('Пункт выдачи интернет заказов ', '', $metro);
      ?>
      <li<?=$li_class ? ' class="'.$li_class.'"' : ''?>>
        <?php if(isset($is_list) && $is_list) :?>
          <div class="name-holder">
  					<a href="<?= url_for('shops/'.$shop->getSlug()) ?>">
              <span class="img-holder">
                <img alt="image description" src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/newdis/images/metro00.png'?>">
              </span>
              <span class="text"><?= $metro ?></span>
            </a>
            <?php if($shop->getIsNew()) : ?>
              <span class="text"></span><span style="font-size:16px;">&nbsp;&nbsp;<span style="color: rgb(255, 0, 0);"><strong>NEW</strong></span></span>
            <?php endif ?>
          </div>
          <div class="address">
            <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
            <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
          </div>
        <?php else :?>
          <div class="img-holder">
            <div class="metro">
              <span class="ico-holder">
                <img alt="image description" src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/newdis/images/metro00.png'?>" />
              </span>
              <span class="text"><?=$metro ? $metro : ''?></span>
            </div>
            <img
              alt="Магазин для взрослых Он и Она,<?=$shop->getAddress()?>"
              src="/uploads/assets/images/<?=$shop->getPreviewImage() ? $shop->getPreviewImage() : '001.jpg'?>" />
            <a class="more-btn" href="<?= url_for('shops/'.$shop->getSlug()) ?>"><span>Подробнее</span></a>
          </div>
          <div class="title">Адрес:</div>
          <div class="address">
            <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
            <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
          </div>
          <div class="work-time"><?=$shop->getWorktime()?></div>
        <?php endif ?>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>
