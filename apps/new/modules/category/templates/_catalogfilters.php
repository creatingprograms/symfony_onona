<aside class="sidebar-filter">
  <form action=""  method="POST" class="form form-catalog js-filters-form" id="js-filters-form">
    <input type="hidden" name="page" value="<?= $page ? $page : 1 ?>" id="form-filter-page">
    <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="form-filter-sort">
    <input type="hidden" name="direction" value="<?= $direction ?>" id="form-filter-direction">
    <?/*<input type="hidden" name="isStock" value="<?= $isStock ?>" id="isStockFilter">*/?>
    <input type="hidden" name="novice" value="<?= isset($isNovice) ? $isNovice : '' ?>" id="form-filter-is-novice">
    <div class="wrap-form">
      <div class="wrap-form-catalog__mob">
        <div class="form-catalog__title-mob"><noindex>Фильтр</noindex></div>
        <div class="btn-close">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.29301 16.2929C-0.0975142 16.6834 -0.0975142 17.3166 0.29301 17.7071C0.683534 18.0976 1.3167 18.0976 1.70722 17.7071L0.29301 16.2929ZM17.7072 1.70711C18.0977 1.31658 18.0977 0.683418 17.7072 0.292894C17.3167 -0.0976307 16.6835 -0.0976307 16.293 0.292894L17.7072 1.70711ZM1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L1.70711 0.292893ZM16.2929 17.7071C16.6834 18.0976 17.3166 18.0976 17.7071 17.7071C18.0976 17.3166 18.0976 16.6834 17.7071 16.2929L16.2929 17.7071ZM1.70722 17.7071L17.7072 1.70711L16.293 0.292894L0.29301 16.2929L1.70722 17.7071ZM0.292893 1.70711L16.2929 17.7071L17.7071 16.2929L1.70711 0.292893L0.292893 1.70711Z" fill="#1A1A1A"></path>
          </svg>
        </div>
      </div>
      <div class="aside_changer">
        <div>
          <?
            include_component(
              'page',
              'catalogSidebar',
              array(
                'isCat' => $isCat,
                'isShowFull' => $isSale,
                'filter_service_category' => $filter_service_category,
                'sf_cache_key' => "catalogSidebar".$isCat.'|'.$isSale.'|'.$filter_service_category,
              )
            );

          ?>
        </div>
        <div <?=get_slot('wrap_aside') ? 'class="first"' : ''?>>
          <? //if(!get_slot('wrap_aside')) echo $catalogSidebar; ?>
          <div class="form-group-block form-group-block_range form-group-block-price"><?/* Цена */?>
          
            <? if(!empty($price) || !empty($filters)) :?>
              <noindex><a href="" class="btn-full btn-full_rad">Сбросить</a><br></noindex>
            <? endif ?>
            <div class="form-group__title">
              <span>Цена, ₽</span>
            </div>
            <div class="form-group__content -range">
              <div class="sidebar-f-accord-cont custom-range">
                <div
                  class="range sidebar-f-range"
                  data-min="<?= $filtersCategory['minPrice'] ?>"
                  data-initminval="<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>"
                  data-initmaxval="<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>"
                  data-max="<?= $filtersCategory['maxPrice'] ?>"
                  data-step="10"
                  data-dimension="true"
                >
                  <div class="sidebar-f-range-services custom-range__number">
                    <div class="sidebar-f-range-services-from">
                      от
                    </div>
                    <input type="text" value="<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>" name="Price[from]" class="minCost custom-range__input js-filter-shutter">
                    <div class="sidebar-f-range-services-before">
                      до
                    </div>
                    <input type="text" value="<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>" name="Price[to]" class="maxCost custom-range__input js-filter-shutter">
                  </div>
                  <div class="range-ui custom-range__slider"></div>
                </div>
              </div>
            </div>
          </div>
          <?php if (!empty($shopsList)): ?>
            <div class="form-group-block -mt accordion">
              <div class="form-group__title <?= !empty($shops) ? 'active' : ''?>">
                <span>Наличие в магазинах</span>
                  <span class="btn-form-group">
                    <svg>
                      <use xlink:href="#single-arrow"></use>
                    </svg>
                  </span>
              </div>
              <div class="form-group__content" <?= !empty($shops) ? 'style="display: block;"' : ''?>>
                <?php $i=0; foreach ($shopsList as $paramKey => $shop): ?>
                  <?
                    $checked = false;
                    //Проверка на чекание
                    if (in_array($shop['shop_id'], $shops) )
                      $checked = true;
                  ?>

                  <div class="custom-check custom-check_color">
                    <div class="check-check_block">
                      <input type="checkbox" value="<?= $shop['shop_id'] ?>" name="shops[]" id="propShop-<?= $paramKey ?>" class="check-check_input js-filter-shutter" <?= $checked ? ' checked' : '' ?>>
                      <div class="custom-check_shadow"></div>
                    </div>
                    <label for="propShop-<?= $paramKey ?>" class="custom-check_label"><?= $shop['name'] ?></label>
                  </div>
                <? endforeach ?>
              </div>
            </div>
          <?php endif; ?>

          <?php if (is_array($filtersDB)) foreach ($filtersDB as $diId => $filter): ?>
            <? if(!is_array($filter)) continue; ?>
            <div class="form-group-block <?=(isset($filter['range']) && is_array($filter['range'])) ? 'form-group-block_range' : '-mt accordion'?>">
              <div class="form-group__title <?=$isOpen = isset($filters[$diId]) ? 'active' : ''?>">
                <span><?=$filter['nameCategory']?><?//= (preg_replace("/[^a-zA-ZА-Яа-я\s]/u", "", array_shift($filter)['value'])) ?></span>
                <? if (isset($filter['range']) && is_array($filter['range'])) :?>
                <? else :?>
                  <span class="btn-form-group">
                    <svg>
                      <use xlink:href="#single-arrow"></use>
                    </svg>
                  </span>
                <? endif ?>
              </div>
              <?php if (isset($filter['range']) && is_array($filter['range'])): ?>
                <?
                  if($filter['nameCategory']=='Количество')//Для данного фильтра применять дробные числа бессмысленно
                    $dataFraction=1;
                  else
                    $dataFraction=10;// Делитель
                ?>
                <div class="form-group__content -range">
                  <div class="sidebar-f-accord-cont custom-range">
                    <div class="range sidebar-f-range"
                      data-min="<?= $dataFraction*($filter['range']['min']) ?>"
                      data-initminval="<?= $dataFraction*($filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min']) ?>"
                      data-initmaxval="<?= $dataFraction*($filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max']) ?>"
                      data-max="<?= $dataFraction*($filter['range']['max']) ?>"
                      data-step="1"
                      data-dimension="false"
                      data-fraction="<?=$dataFraction?>"
                    >
                      <div class="sidebar-f-range-services custom-range__number">
                        <input type="text" value="<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>" name="filters[<?= $diId ?>][from]" class="minCost custom-range__input  js-filter-shutter">
                        <input type="text" value="<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>" name="filters[<?= $diId ?>][to]" class="maxCost custom-range__input  js-filter-shutter">
                      </div>
                      <div class="range-ui custom-range__slider"></div>
                    </div>
                  </div>
                </div>
              <? else :?>
                <div class="form-group__content" <?= $isOpen ? 'style="display: block;"' : ''?>>
                  <?php $i=0; foreach ($filter as $paramKey => $filterParam): ?>
                    <?
                      if ($paramKey == "nameCategory") continue;
                      if(!$filterParam['countProducts']) continue;
                      $checked = false;
                      $i++; //Считаем отдельно, так как некоторые параметры могут не показываться
                      //Проверка на чекание
                      if(isset($filters[$diId]) && is_array($filters[$diId]))
                        if (array_search($paramKey, $filters[$diId]) !== false)
                          $checked = true;
                    ?>

                    <div class="custom-check custom-check_color">
                      <div class="check-check_block">
                        <input type="checkbox" value="<?= $paramKey ?>" name="filters[<?= $diId ?>][]" id="propMaterial-<?= $paramKey ?>" class="check-check_input js-filter-shutter" <?= $checked ? ' checked' : '' ?>>
                        <div class="custom-check_shadow"></div>
                      </div>
                      <label for="propMaterial-<?= $paramKey ?>" class="custom-check_label"><?= $filterParam['value'] ?></label>
                    </div>
                  <? endforeach ?>
                </div>
              <? endif ?>
            </div>
            <?/*
            <div class="sidebar-f-accord <?= (0 >= $j-- ) ? '' : '-up'?><?=($isNewby && $filter['nameCategory']=='Для кого' ) ? '-isNewby -up' : ''?>">
              <div class="sidebar-f-accord-plate">
                <?= $filter['nameCategory'] ?>
              </div>
              <div class="sidebar-f-accord-up-block">
                <?php if (isset($filter['range']) && is_array($filter['range'])): ?>
                  <?
                    if($filter['nameCategory']=='Количество')//Для данного фильтра применять дробные числа бессмысленно
                      $dataFraction=1;
                    else
                      $dataFraction=10;// Делитель
                  ?>
                  <div class="sidebar-f-accord-cont">
                    <div  class="range sidebar-f-range"
                          data-min="<?= $dataFraction*($filter['range']['min']) ?>"
                          data-initminval="<?= $dataFraction*($filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min']) ?>"
                          data-initmaxval="<?= $dataFraction*($filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max']) ?>"
                          data-max="<?= $dataFraction*($filter['range']['max']) ?>"
                          data-step="1"
                          data-dimension="false"
                          data-fraction="<?=$dataFraction?>"
                    >
                      <div class="range-ui"></div>
                      <div class="sidebar-f-range-services">
                        <div class="sidebar-f-range-services-from">
                          от
                        </div>
                        <input name="filters[<?= $diId ?>][from]"  type="text" value="<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>" class="minCost">
                        <div class="sidebar-f-range-services-before">
                          до
                        </div>
                        <input name="filters[<?= $diId ?>][to]"  type="text" value="<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>" class="maxCost">
                        <div class="sidebar-f-range-services-dimension">
                          <?= (preg_replace("/[^a-zA-ZА-Яа-я\s]/u", "", array_shift($filter)['value'])) ?>
                        </div>
                      </div>
                    </div>
                  </div>
                <? else :?>
                  <div class="sidebar-f-accord-cont" id="prop<?= $diId ?>">
                    <div class="sidebar-f-prop">
                      <?php $i=0; foreach ($filter as $paramKey => $filterParam): ?>
                        <?
                          if ($paramKey == "nameCategory") continue;
                          $checked = false;
                          $i++; //Считаем отдельно, так как некоторые параметры могут не показываться
                          //Проверка на чекание
                          if(isset($filters[$diId]) && is_array($filters[$diId]))
                            if (array_search($paramKey, $filters[$diId]) !== false)
                              $checked = true;
                        ?>
                        <div class="sidebar-f-prop-row">
                          <input type="checkbox" id="propMaterial-<?= $paramKey ?>"  name="filters[<?= $diId ?>][]" value="<?= $paramKey ?>" class="styleCH" <?= $checked ? ' checked' : '' ?>>
                          <label for="propMaterial-<?= $paramKey ?>">
                            <?
                              $isColor=isset($filterParam['filename']);
                              $image='/uploads/dopinfo/thumbnails/'.$filterParam['filename'];//Ищем иконку
                              if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)){
                                $image='/uploads/dopinfo/'.$filterParam['filename'];//берем полное
                                if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)) $isColor=false; //значит иконки нет
                              }
                            ?>
                            <?= $isColor ?
                              '<span class="sidebar-f-prop-color" style="background: url('.$image.') 50%;"> &nbsp; </span>'
                              : ''
                            ?>
                            <?= $filterParam['value'] ?>
                            <span>(<?= $filterParam['countProducts'] ?>)</span>
                          </label>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <?php if ($i>5): ?>
                      <div class="sidebar-f-prop-all">
                        <a href="#prop<?= $diId ?>" data-all="Посмотреть все" data-up="Свернуть все"></a>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>*/?>
          <?php endforeach; ?>
          <? if(!empty($price) || !empty($filters)) :?>
            <noindex><br><a href="" class="btn-full btn-full_rad">Сбросить</a></noindex>
          <? endif ?>
        </div>
      </div>

      <? //if(get_slot('wrap_aside')) echo $catalogSidebar; ?>

      <?/*
      <div class="form-group-block form-group-block_range">
        <div class="form-group__title">
          <span>Длина, см</span>
        </div>
        <div class="form-group__content -range">
          <div class="sidebar-f-accord-cont custom-range">
            <div class="range sidebar-f-range" data-min="3" data-initminval="3" data-initmaxval="50" data-max="50" data-step="1" data-dimension="true">
              <div class="sidebar-f-range-services custom-range__number">
                <input type="text" value="3" name="Price[from]" class="minCost custom-range__input">
                <input type="text" value="50" name="Price[to]" class="maxCost custom-range__input">
              </div>
              <div class="range-ui custom-range__slider"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group-block form-group-block_range">
        <div class="form-group__title">
          <span>Диаметр, см</span>
        </div>
        <div class="form-group__content -range">
          <div class="sidebar-f-accord-cont custom-range">
            <div class="range sidebar-f-range" data-min="0.3" data-initminval="0.3" data-initmaxval="12" data-max="12" data-step="1" data-dimension="true" d>
              <div class="sidebar-f-range-services custom-range__number">
                <input type="text" value="0.3" name="Price[from]" class="minCost custom-range__input">
                <input type="text" value="12" name="Price[to]" class="maxCost custom-range__input">
              </div>
              <div class="range-ui custom-range__slider"></div>
            </div>
          </div>
        </div>
      </div>
      */?>
      <?/*
      <div class="form-group-block accordion -mt">
        <div class="form-group__title">
          <span>Дополнительно</span>
          <span class="btn-form-group">
            <svg>
              <use xlink:href="#single-arrow"></use>
            </svg>
          </span>
        </div>
        <div class="form-group__content">
          <div class="custom-check custom-check_color">
            <div class="check-check_block">
              <input type="checkbox" name="" id="" class="check-check_input">
              <div class="custom-check_shadow"></div>
            </div>
            <label for="" class="custom-check_label">Без вибрации</label>
          </div>
          <div class="custom-check custom-check_color">
            <div class="check-check_block">
              <input type="checkbox" name="" id="" class="check-check_input">
              <div class="custom-check_shadow"></div>
            </div>
            <label for="" class="custom-check_label">Водонепроницаемы</label>
          </div>
          <div class="custom-check custom-check_color">
            <div class="check-check_block">
              <input type="checkbox" name="" id="" class="check-check_input">
              <div class="custom-check_shadow"></div>
            </div>
            <label for="" class="custom-check_label">Мультискоростной</label>
          </div>
        </div>
      </div>
      */?>
      <?/*//Готовые подборки?>
      <div class="form-group-block -mt">
        <div class="form-group__title">
          <span>Готовые подборки</span>
        </div>
        <div class="form-group__content tag">
          <div class="custom-tag-set"><input type="radio" name="ready-set" id="set-1"><label for="set-1">Недорогие</label></div>
          <div class="custom-tag-set"><input type="radio" name="ready-set" id="set-2"><label for="set-2">Латексные</label></div>
          <div class="custom-tag-set"><input type="radio" name="ready-set" id="set-3"><label for="set-3">Для новичков</label></div>
          <div class="custom-tag-set"><input type="radio" name="ready-set" id="set-4"><label for="set-4">Популярные</label></div>
          <div class="custom-tag-set"><input type="radio" name="ready-set" id="set-5"><label for="set-5">Новинки</label></div>
        </div>
      </div>*/?>
    </div>
  </form>
  <a href="/tinkoff" class="tinkoff_link">
    <img src="/frontend/images/tinkoff/desk_aside.png">
  </a>
</aside>

<?/*
<hr> <hr> <hr>
<aside class="sidebar">
  <? if($isFullCatalog):?>
    <? include_component(
      'page',
      'catalogSidebarFull',
      array(
        'sf_cache_key' => "catalogSidebar-isFullCatalog"
      )
    ); ?>
  <? endif ?>
  <form action="" method="POST" class="js-filters-form">
    <input type="hidden" name="page" value="<?= $page ? $page : 1 ?>" id="form-filter-page">
    <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="form-filter-sort">
    <input type="hidden" name="direction" value="<?= $direction ?>" id="form-filter-direction">
    <?//<input type="hidden" name="isStock" value="<?= $isStock ?>" id="isStockFilter">?>
    <input type="hidden" name="novice" value="<?= isset($isNovice) ? $isNovice : '' ?>" id="form-filter-is-novice">
    <div class="sidebar-mob-but-filtr">
      <svg>
        <use xlink:href="#filtrIcon" />
      </svg>
      фильтры
    </div>
    <div class="sidebar-f">
      <div class="top-panel-f">
        <p>Фильтры</p>
        <span class="close-btn">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M0.29301 16.2929C-0.0975142 16.6834 -0.0975142 17.3166 0.29301 17.7071C0.683534 18.0976 1.3167 18.0976 1.70722 17.7071L0.29301 16.2929ZM17.7072 1.70711C18.0977 1.31658 18.0977 0.683418 17.7072 0.292894C17.3167 -0.0976307 16.6835 -0.0976307 16.293 0.292894L17.7072 1.70711ZM1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L1.70711 0.292893ZM16.2929 17.7071C16.6834 18.0976 17.3166 18.0976 17.7071 17.7071C18.0976 17.3166 18.0976 16.6834 17.7071 16.2929L16.2929 17.7071ZM1.70722 17.7071L17.7072 1.70711L16.293 0.292894L0.29301 16.2929L1.70722 17.7071ZM0.292893 1.70711L16.2929 17.7071L17.7071 16.2929L1.70711 0.292893L0.292893 1.70711Z" fill="#1A1A1A"/>
          </svg>
        </span>
      </div>
      <? include_component(
        'page',
        'catalogSidebar',
        array(
          'isCat' => $isCat,
          'isShowFull' => $isSale,
          'filter_service_category' => $filter_service_category,
          'sf_cache_key' => "catalogSidebar".$isCat.'|'.$isSale.'|'.$filter_service_category,
        )
      ); ?>
      <div class="sidebar-f-accord-wrapper">
        <div class="sidebar-f-accord -up">
          <div class="sidebar-f-accord-plate">
            Цена
          </div>
          <div class="sidebar-f-accord-up-block">
            <div class="sidebar-f-accord-cont">
              <div class="range sidebar-f-range" data-min="<?= $filtersCategory['minPrice'] ?>" data-initminval="<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>" data-initmaxval="<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>" data-max="<?= $filtersCategory['maxPrice'] ?>" data-step="10" data-dimension="true">
                <div class="range-ui"></div>
                <div class="sidebar-f-range-services">
                  <div class="sidebar-f-range-services-from">
                    от
                  </div>
                  <input type="text" value="<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>" name="Price[from]" class="minCost">
                  <div class="sidebar-f-range-services-before">
                    до
                  </div>
                  <input type="text" value="<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>" name="Price[to]" class="maxCost">
                  <div class="sidebar-f-range-services-dimension">
                    руб.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <? $j=3; ?>
        <?php if (is_array($filtersDB)) foreach ($filtersDB as $diId => $filter): ?>
          <? if(!is_array($filter)) continue; ?>
          <div class="sidebar-f-accord <?= (0 >= $j-- ) ? '' : '-up'?><?=($isNewby && $filter['nameCategory']=='Для кого' ) ? '-isNewby -up' : ''?>">
            <div class="sidebar-f-accord-plate">
              <?= $filter['nameCategory'] ?>
            </div>
            <div class="sidebar-f-accord-up-block">
              <?php if (isset($filter['range']) && is_array($filter['range'])): ?>
                <?
                  if($filter['nameCategory']=='Количество')//Для данного фильтра применять дробные числа бессмысленно
                    $dataFraction=1;
                  else
                    $dataFraction=10;// Делитель
                ?>
                <div class="sidebar-f-accord-cont">
                  <div  class="range sidebar-f-range"
                        data-min="<?= $dataFraction*($filter['range']['min']) ?>"
                        data-initminval="<?= $dataFraction*($filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min']) ?>"
                        data-initmaxval="<?= $dataFraction*($filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max']) ?>"
                        data-max="<?= $dataFraction*($filter['range']['max']) ?>"
                        data-step="1"
                        data-dimension="false"
                        data-fraction="<?=$dataFraction?>"
                  >
                    <div class="range-ui"></div>
                    <div class="sidebar-f-range-services">
                      <div class="sidebar-f-range-services-from">
                        от
                      </div>
                      <input name="filters[<?= $diId ?>][from]"  type="text" value="<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>" class="minCost">
                      <div class="sidebar-f-range-services-before">
                        до
                      </div>
                      <input name="filters[<?= $diId ?>][to]"  type="text" value="<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>" class="maxCost">
                      <div class="sidebar-f-range-services-dimension">
                        <?= (preg_replace("/[^a-zA-ZА-Яа-я\s]/u", "", array_shift($filter)['value'])) ?>
                      </div>
                    </div>
                  </div>
                </div>
              <? else :?>
                <div class="sidebar-f-accord-cont" id="prop<?= $diId ?>">
                  <div class="sidebar-f-prop">
                    <?php $i=0; foreach ($filter as $paramKey => $filterParam): ?>
                      <?
                        if ($paramKey == "nameCategory") continue;
                        $checked = false;
                        $i++; //Считаем отдельно, так как некоторые параметры могут не показываться
                        //Проверка на чекание
                        if(isset($filters[$diId]) && is_array($filters[$diId]))
                          if (array_search($paramKey, $filters[$diId]) !== false)
                            $checked = true;
                      ?>
                      <div class="sidebar-f-prop-row">
                        <input type="checkbox" id="propMaterial-<?= $paramKey ?>"  name="filters[<?= $diId ?>][]" value="<?= $paramKey ?>" class="styleCH" <?= $checked ? ' checked' : '' ?>>
                        <label for="propMaterial-<?= $paramKey ?>">
                          <?
                            $isColor=isset($filterParam['filename']);
                            $image='/uploads/dopinfo/thumbnails/'.$filterParam['filename'];//Ищем иконку
                            if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)){
                              $image='/uploads/dopinfo/'.$filterParam['filename'];//берем полное
                              if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)) $isColor=false; //значит иконки нет
                            }
                          ?>
                          <?= $isColor ?
                            '<span class="sidebar-f-prop-color" style="background: url('.$image.') 50%;"> &nbsp; </span>'
                            : ''
                          ?>
                          <?= $filterParam['value'] ?>
                          <span>(<?= $filterParam['countProducts'] ?>)</span>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <?php if ($i>5): ?>
                    <div class="sidebar-f-prop-all">
                      <a href="#prop<?= $diId ?>" data-all="Посмотреть все" data-up="Свернуть все"></a>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <br><input type="submit" name="" value="Применить" class="but">
    </div>
  </form>
  <? if(!$isFullCatalog):?>
    <?php include_component( 'page', 'leftBanners',
      array(
        'sf_cache_key' => 'left_banners'
      )
    ); ?>
  <? endif ?>
</aside>
*/?>
