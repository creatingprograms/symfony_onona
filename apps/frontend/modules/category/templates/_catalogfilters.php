<?//= '<pre>~~~--'.print_r( $filters, true).'||---~</pre>';?>

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
    <?/*<input type="hidden" name="loadProducts" value="0" id="pageFromFilter">Видимо для аякса*/?>
    <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="form-filter-sort">
    <input type="hidden" name="direction" value="<?= $direction ?>" id="form-filter-direction">
    <?/*<input type="hidden" name="isStock" value="<?= $isStock ?>" id="isStockFilter">*/?>
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
