<div class="mob-nav">
  <div class="mob-nav-wrap">
    <div class="mob-nav-plate">
      <div class="mob-nav-plate-col -but">
        <div class="header-nav-but">&nbsp;</div>
      </div>
      <? if($sf_user->isAuthenticated()) :?>
      <div class="mob-nav-plate-col -red">
        <a href="/lk">&nbsp;Личный кабинет</a>
      </div>
      <? else : ?>
        <div class="mob-nav-plate-col -enter -red">
          <a href="/guard/login">Вход</a>
        </div>
        <div class="mob-nav-plate-col -reg">
          <a href="/guard/login">Регистрация</a>
        </div>
      <? endif ?>

    </div>
    <nav>
      <? if(sizeof($menuNav)): //Верхняя, белая часть. Управляется из меню типа main_frontend ?>
        <ul class="mob-nav-top">
          <? foreach ($menuNav as $key=>$link) :?>

            <li class="<?=$link['link']=='/catalog' ? '-red ':''?><?=$link['link']=='/akcii-i-bonusy' ? '-action ':''?>">
              <?
                $hasSub=false;
                if($link['link']=='/catalog') {
                  $link['name']='Каталог продукции';
                  $link['link']='#';
                }
                if($link['link']=='/akcii-i-bonusy') $link['name']='АКЦИИ';
                /* Ниже Будет выпадашка по ID-ку */
                if(sizeof($link['submenu']) && is_array($link['submenu'])) {
                  // $link['link']='#';
                  $subs['sub-'.$key]=[
                    'name' => $link['name'],
                    'elements' => $link['submenu']
                  ];
                  $hasSub=true;
                }
              ?>
              <?
                $isRealLink=true;
                if(!$link['link'] || $link['link']=='#') $isRealLink=false;
              ?>
              <a href="<?=$link['link']?>"<?=(!$isRealLink) ? ' data-href="#sub-'.$key.'"':''?><?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?= $link['name'] ?></a>
                <? if(sizeof($link['submenu']) && is_array($link['submenu'])) :?>
                  <div class="mob-nav-arrow" data-href="#sub-<?= $key ?>">
                    <svg>
                      <use xlink:href="#backArrowIcon" />
                    </svg>
                  </div>
                <? endif ?>
            </li>
          <? endforeach?>
        </ul>
      <? endif ?>
      <? if(sizeof($menuBot)) : /*Нижняя, серая часть меню, управляемая из типа меню mobile_frontend*/?>
        <ul class="mob-nav-bot">
          <? foreach ($menuBot as $link) :?>
            <li>
              <a href="<?= $link['link'] ?>"<?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?= $link['name'] ?></a>
            </li>
          <? endforeach ?>
        </ul>
      <? endif?>

      <? if (sizeof($subs)) foreach ($subs as $key => $lineBlock) ://Выпадашки для пунктов из первой итерации ?>
        <div class="mob-nav-pop" id="<?= $key ?>">
          <div class="mob-nav-plate">
            <div class="mob-nav-plate-col -back">
              <svg>
                <use xlink:href="#backArrowIcon" />
              </svg>
              Назад
            </div>
          </div>
          <div class="mob-nav-head">
            <?= $lineBlock['name'] ?>
          </div>
          <ul class="mob-nav-top">
            <? foreach($lineBlock['elements'] as $key => $link) :?>
              <li>
                <? if(sizeof($link['submenu']) && is_array($link['submenu'])) {
                    $subsSubs['subs-subs-'.$key]=[
                      'name' => isset($link['name_part']) ? $link['name_part'].' '.$link['name_part_2'] : $link['name'],
                      'elements' => $link['submenu'],
                    ];
                  ?>
                  <?/*<div class="mob-nav-link" data-href="#<?='subs-subs-'.$key?>">*/?>
                  <a class="mob-nav-link" href="<?=$link['link']?>">
                    <? if (isset($link['name_part'])) :?>
                      <span><?= $link['name_part'] ?></span> <?= $link['name_part_2'] ?>
                    <? else : ?>
                      <?= $link['name'] ?>
                    <? endif ?>
                    <div class="mob-nav-arrow" data-href="#<?='subs-subs-'.$key?>">
                      <svg>
                        <use xlink:href="#backArrowIcon" />
                      </svg>
                    </div>
                  </a>
                <? }
                else { ?>
                  <a href="<?=$link['link']?>"<?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?= $link['name'] ?></a>
                <?  }  ?>
            </li>
          <? endforeach ?>
          </ul>
        </div>
      <? endforeach ?>

      <? if (sizeof($subsSubs)) foreach ($subsSubs as $key => $lineBlock) ://Выпадашки для пунктов из второй итерации ?>
        <div class="mob-nav-pop" id="<?= $key ?>">
          <div class="mob-nav-plate">
            <div class="mob-nav-plate-col -back">
              <svg>
                <use xlink:href="#backArrowIcon" />
              </svg>
              Назад
            </div>
          </div>
          <div class="mob-nav-head">
            <?= $lineBlock['name'] ?>
          </div>
          <ul class="mob-nav-top">
            <? foreach($lineBlock['elements'] as $key2 => $link) :?>
              <li>
                <? if(sizeof($link['submenu']) && is_array($link['submenu'])) {
                    $subsSubsSubs['subs-subs-'.$key . $key2]=[
                      'name' => $link['name'],
                      'elements' => $link['submenu'],
                    ];
                  ?>

                  <?/*<div class="mob-nav-link" data-href="#<?='subs-subs-'.$key?>">*/?>
                  <a class="mob-nav-link" href="<?=$link['link']?>">
                    <? if (isset($link['name_part'])) :?>
                      <span><?= $link['name_part'] ?></span> <?= $link['name_part_2'] ?>
                    <? else : ?>
                      <?= $link['name'] ?>
                    <? endif ?>
                    <div class="mob-nav-arrow" data-href="#<?='subs-subs-'.$key . $key2?>">
                      <svg>
                        <use xlink:href="#backArrowIcon" />
                      </svg>
                    </div>
                  </a>

                <? }
                else { ?>
                  <a href="<?=$link['link']?>"<?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?= $link['name'] ?></a>
                <?  }  ?>
            </li>
          <? endforeach ?>
          </ul>
        </div>
      <? endforeach ?>


        <? if (sizeof($subsSubsSubs)) foreach ($subsSubsSubs as $key => $lineBlock) ://Выпадашки для пунктов из второй итерации ?>
<!--            --><?php //die(var_dump($lineBlock)) ?>
            <div class="mob-nav-pop" id="<?= $key ?>">
                <div class="mob-nav-plate">
                    <div class="mob-nav-plate-col -back">
                        <svg>
                            <use xlink:href="#backArrowIcon" />
                        </svg>
                        Назад
                    </div>
                </div>
                <div class="mob-nav-head">
                    <?= $lineBlock['name'] ?>
                </div>
                <ul class="mob-nav-top">
                    <? foreach($lineBlock['elements'] as $key2 => $link) :?>
                        <li>
                            <a href="<?=$link['link']?>"<?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?= $link['name'] ?></a>
                        </li>
                    <? endforeach ?>
                </ul>
            </div>
        <? endforeach ?>


    </nav>
    <ul class="mob-nav-tel">
      <li>
        <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone1')) ?>">  <?=csSettings::get('phone1')?> <b>Россия</b> </a>
      </li>
      <li>
        <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"> <?=csSettings::get('phone2')?> <b>Москва</b> </a>
      </li>
    </ul>
  </div>
</div>
