
<?
if (
    $this->getModuleName() == "category" && (
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show" ||
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_showNovice" ||
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_for_her" ||
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show" ||
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show_page"
     )
  ){


    $filtersDB = unserialize($filtersCategory['filters']);
    $price = unserialize(get_slot("filtersPrice"));
    $page = unserialize(get_slot("filtersPage"));
    $filtersCountProducts = unserialize(get_slot("filtersCountProducts"));

    ?>
    <script src="/js/jquery.ui-slider.js"></script>
    <script>
      function showFilters(){
        $('.js-show-filters').addClass('active');
        $('.filters-hided-block').addClass('active');
      }
      function clearFilters(){
        document.location.href = '<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>';
      }
      /*
       $(document).ready(function () {

         $("#categoryFilters").ajaxForm(function (result) {
           var queryString = $('#categoryFilters').formSerialize();
           var redirect = '<?//= explode("?", $_SERVER['REQUEST_URI'])[0] ?>?' + queryString;
           history.pushState('', '', redirect);

           $("#content").html(result);

           $("#content").find("img.thumbnails").each(function () {
               $(this).attr("src", $(this).attr("data-original"));
           });
           initPopup();
           viravnivanie();

           if ($("div").is("#filtersCountProducts")) {
               $filtersCountProducts = JSON.parse($("#filtersCountProducts").html());
               $("input.filteParams").each(function (index) {
                   if ($filtersCountProducts[$(this).val()] !== undefined) {
                       $(this).parent().find("div.countProd").html($filtersCountProducts[$(this).val()]["count"]);
                   } else {
                       $(this).parent().find("div.countProd").html(0);
                   }
               });
           }
           else {
               $("input.filteParams").each(function (index) {
                   $(this).parent().find("div.countProd").html($(this).parent().find("div.countProd").data("default"));

               });
           }
         });
       });*/
    </script>
    <div class="js-show-filters show-filters-button" onclick="showFilters();">показать фильтры</div>
    <div class="filters-hided-block">
      <div class="filters-block-name">Фильтры</div>
      <div id="filters">
          <form action="" id="categoryFilters" method="POST">

              <input type="hidden" name="page" value="<?= $page ? $page : 1 ?>" id="pageFromFilter">
              <input type="hidden" name="loadProducts" value="0" id="pageFromFilter">
              <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="sortOrderFilter">
              <input type="hidden" name="direction" value="<?= $direction ?>" id="directionFilter">
              <input type="hidden" name="isStock" value="<?= $isStock ?>" id="isStockFilter">
              <input type="hidden" name="novice" value="<?= isset($isNovice) ? $isNovice : '' ?>" id="isNovice">
              <ul class="list filtersLi">
                  <li>
                    <div class="paramFilter">
                        <div class="headerFilter js-filters-toogle" data-id="#paramsPrice">
                          Цена
                        </div>
                        <div class="value js-filters-value" id="paramsPrice">
                            от <input type="text" class="textParam" id="minCostPrice" value="<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>" name="Price[from]" data-value="<?= $filtersCategory['minPrice'] ?>" />
                            до <input type="text" class="textParam" id="maxCostPrice" value="<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>" name="Price[to]" data-value="<?= $filtersCategory['maxPrice'] ?>" /> <span>руб.</span>

                            <div id="sliderPrice" class="filter-slider"></div>
                            <script type="text/javascript">
                                jQuery("#sliderPrice").slider({
                                    min: <?= /*$price['from'] != "" ? $price['from'] : */$filtersCategory['minPrice'] ?>,
                                    animate: true,
                                    max: <?= /*$price['to'] != "" ? $price['to'] :*/ $filtersCategory['maxPrice'] ?>,
                                    step: 0.1,
                                    values: [<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>, <?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>],
                                    range: true,
                                    stop: function (event, ui) {
                                        jQuery("input#minCostPrice").val(jQuery("#sliderPrice").slider("values", 0));
                                        jQuery("input#maxCostPrice").val(jQuery("#sliderPrice").slider("values", 1));
                                        if (parseFloat(jQuery("input#minCostPrice").val()) == parseFloat(<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>) && parseFloat(jQuery("input#maxCostPrice").val()) == parseFloat(<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>)) {
                                            jQuery("input#rangeChangePrice").val(0);
                                        } else {
                                            jQuery("input#rangeChangePrice").val(1);
                                        }
                                        // jQuery("#categoryFilters").submit();
                                    },
                                    slide: function (event, ui) {
                                        jQuery("input#minCostPrice").val(jQuery("#sliderPrice").slider("values", 0));
                                        jQuery("input#maxCostPrice").val(jQuery("#sliderPrice").slider("values", 1));
                                        if (parseFloat(jQuery("input#minCostPrice").val()) == parseFloat(<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>) && parseFloat(jQuery("input#maxCostPrice").val()) == parseFloat(<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>)) {
                                            jQuery("input#rangeChangePrice").val(0);
                                        } else {
                                            jQuery("input#rangeChangePrice").val(1);
                                        }

                                    }

                                });
                                jQuery("input#minCostPrice").change(function () {
                                    var value1 = jQuery("input#minCostPrice").val();
                                    var value2 = jQuery("input#maxCostPrice").val();

                                    if (parseFloat(value1) > parseFloat(value2)) {
                                        value1 = value2;
                                        jQuery("input#minCostPrice").val(value1);
                                    }
                                    if (parseFloat(value1) < parseFloat(<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>)) {
                                        value1 = <?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>;
                                        jQuery("input#minCostPrice").val(value1);
                                    }
                                    jQuery("#sliderPrice").slider("values", 0, value1);
                                    if (parseFloat(jQuery("input#minCostPrice").val()) == parseFloat(<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>) && parseFloat(jQuery("input#maxCostPrice").val()) == parseFloat(<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>)) {
                                        jQuery("input#rangeChangePrice").val(0);
                                    } else {
                                        jQuery("input#rangeChangePrice").val(1);
                                    }
                                    // jQuery("#categoryFilters").submit();
                                });


                                jQuery("input#maxCostPrice").change(function () {
                                    var value1 = jQuery("input#minCostPrice").val();
                                    var value2 = jQuery("input#maxCostPrice").val();

                                    if (parseFloat(value2) > parseFloat(<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>)) {
                                        value2 = <?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>;
                                        jQuery("input#maxCostPrice").val(<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>)
                                    }

                                    if (parseFloat(value1) > parseFloat(value2)) {
                                        value2 = value1;
                                        jQuery("input#maxCostPrice").val(value2);
                                    }
                                    jQuery("#sliderPrice").slider("values", 1, value2);
                                    if (parseFloat(jQuery("input#minCostPrice").val()) == parseFloat(<?= $price['from'] != "" ? $price['from'] : $filtersCategory['minPrice'] ?>) && parseFloat(jQuery("input#maxCostPrice").val()) == parseFloat(<?= $price['to'] != "" ? $price['to'] : $filtersCategory['maxPrice'] ?>)) {
                                        jQuery("input#rangeChangePrice").val(0);
                                    } else {
                                        jQuery("input#rangeChangePrice").val(1);
                                    }
                                    // jQuery("#categoryFilters").submit();
                                });

                                jQuery("input#minCostPrice").keypress(function (event) {
                                    var key, keyChar;
                                    if (!event)
                                        var event = window.event;

                                    if (event.keyCode)
                                        key = event.keyCode;
                                    else if (event.which)
                                        key = event.which;

                                    if (key == null || key == 0 || key == 8 || key == 13 || key == 9 || key == 46 || key == 37 || key == 39)
                                        return true;
                                    keyChar = String.fromCharCode(key);

                                    if (!/\d/.test(keyChar))
                                        return false;

                                });

                                jQuery("input#maxCostPrice").keypress(function (event) {
                                    var key, keyChar;
                                    if (!event)
                                        var event = window.event;

                                    if (event.keyCode)
                                        key = event.keyCode;
                                    else if (event.which)
                                        key = event.which;

                                    if (key == null || key == 0 || key == 8 || key == 13 || key == 9 || key == 46 || key == 37 || key == 39)
                                        return true;
                                    keyChar = String.fromCharCode(key);

                                    if (!/\d/.test(keyChar))
                                        return false;

                                });

                            </script>
                        </div>
                    </div>
                  </li>
                  <?php if (is_array($filtersDB))
                      foreach ($filtersDB as $diId => $filter) : ?>
                        <li>
                          <?php
                              if (@is_array($filter['range'])) { ?>
                                  <div class="paramFilter">
                                      <div class="headerFilter js-filters-toogle" data-id="#paramsForHide<?= $diId ?>"><?= $filter['nameCategory'] ?></div>
                                      <div class="value js-filters-value" id="paramsForHide<?= $diId ?>">
                                          от <input type="text" class="textParam" id="minCost<?= $diId ?>" value="<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>" name="filters[<?= $diId ?>][from]" data-value="<?= $filter['range']['min'] ?>"/>
                                          до <input type="text" class="textParam" id="maxCost<?= $diId ?>"  value="<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>" name="filters[<?= $diId ?>][to]" data-value="<?= $filter['range']['max'] ?>" /> <?= (preg_replace("/[^a-zA-ZА-Яа-я\s]/u", "", array_shift($filter)['value'])) ?>

                                          <div id="slider<?= $diId ?>" class="filter-slider"></div>
                                          <script type="text/javascript">


                                              //
                                              // Создание слайдера
                                              //
                                              jQuery("#slider<?= $diId ?>").slider({
                                                  min: <?= /*$filters[$diId]['from'] != "" ? $filters[$diId]['from'] :*/ $filter['range']['min'] ?>,
                                                  animate: true,
                                                  max: <?= /*$filters[$diId]['to'] != "" ? $filters[$diId]['to'] :*/ $filter['range']['max'] ?>,
                                                  step: 0.1,
                                                  values: [<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>, <?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>],
                                                  range: true,
                                                  stop: function (event, ui) {
                                                      jQuery("input#minCost<?= $diId ?>").val(jQuery("#slider<?= $diId ?>").slider("values", 0));
                                                      jQuery("input#maxCost<?= $diId ?>").val(jQuery("#slider<?= $diId ?>").slider("values", 1));
                                                      if (parseFloat(jQuery("input#minCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>) && parseFloat(jQuery("input#maxCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>)) {
                                                          jQuery("input#rangeChange<?= $diId ?>").val(0);
                                                          $("#clearparams<?= $diId ?>").fadeOut();
                                                      } else {
                                                          jQuery("input#rangeChange<?= $diId ?>").val(1);
                                                          $("#clearparams<?= $diId ?>").fadeIn();

                                                      }
                                                      // jQuery("#categoryFilters").submit();
                                                  },
                                                  slide: function (event, ui) {
                                                      jQuery("input#minCost<?= $diId ?>").val(jQuery("#slider<?= $diId ?>").slider("values", 0));
                                                      jQuery("input#maxCost<?= $diId ?>").val(jQuery("#slider<?= $diId ?>").slider("values", 1));
                                                      if (parseFloat(jQuery("input#minCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>) && parseFloat(jQuery("input#maxCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>)) {
                                                          jQuery("input#rangeChange<?= $diId ?>").val(0);
                                                          $("#clearparams<?= $diId ?>").fadeOut();
                                                      } else {
                                                          jQuery("input#rangeChange<?= $diId ?>").val(1);
                                                          $("#clearparams<?= $diId ?>").fadeIn();
                                                      }

                                                  }

                                              });


                                              //
                                              // Изменение в форме
                                              //
                                              jQuery("input#minCost<?= $diId ?>").change(function () {
                                                  var value1 = jQuery("input#minCost<?= $diId ?>").val();
                                                  var value2 = jQuery("input#maxCost<?= $diId ?>").val();

                                                  if (parseFloat(value1) > parseFloat(value2)) {
                                                      value1 = value2;
                                                      jQuery("input#minCost<?= $diId ?>").val(value1);
                                                  }
                                                  if (parseFloat(value1) < parseFloat(<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>)) {
                                                      value1 = <?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>;
                                                      jQuery("input#minCost<?= $diId ?>").val(value1);
                                                  }
                                                  jQuery("#slider<?= $diId ?>").slider("values", 0, value1);
                                                  if (parseFloat(jQuery("input#minCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>) && parseFloat(jQuery("input#maxCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>)) {
                                                      jQuery("input#rangeChange<?= $diId ?>").val(0);
                                                      $("#clearparams<?= $diId ?>").fadeOut();
                                                  } else {
                                                      jQuery("input#rangeChange<?= $diId ?>").val(1);
                                                      $("#clearparams<?= $diId ?>").fadeIn();
                                                  }
                                                  // jQuery("#categoryFilters").submit();
                                              });


                                              jQuery("input#maxCost<?= $diId ?>").change(function () {
                                                  var value1 = jQuery("input#minCost<?= $diId ?>").val();
                                                  var value2 = jQuery("input#maxCost<?= $diId ?>").val();

                                                  if (parseFloat(value2) > parseFloat(<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>)) {
                                                      value2 = <?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>;
                                                      jQuery("input#maxCost<?= $diId ?>").val(<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>)
                                                  }

                                                  if (parseFloat(value1) > parseFloat(value2)) {
                                                      value2 = value1;
                                                      jQuery("input#maxCost<?= $diId ?>").val(value2);
                                                  }
                                                  jQuery("#slider<?= $diId ?>").slider("values", 1, value2);
                                                  if (parseFloat(jQuery("input#minCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>) && parseFloat(jQuery("input#maxCost<?= $diId ?>").val()) == parseFloat(<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>)) {
                                                      jQuery("input#rangeChange<?= $diId ?>").val(0);
                                                      $("#clearparams<?= $diId ?>").fadeOut();
                                                  } else {
                                                      jQuery("input#rangeChange<?= $diId ?>").val(1);
                                                      $("#clearparams<?= $diId ?>").fadeIn();
                                                  }
                                                  // jQuery("#categoryFilters").submit();
                                              });


                                              //
                                              // Проверка формы
                                              //
                                              jQuery("input#minCost<?= $diId ?>").keypress(function (event) {
                                                  var key, keyChar;
                                                  if (!event)
                                                      var event = window.event;

                                                  if (event.keyCode)
                                                      key = event.keyCode;
                                                  else if (event.which)
                                                      key = event.which;

                                                  if (key == null || key == 0 || key == 8 || key == 13 || key == 9 || key == 46 || key == 37 || key == 39)
                                                      return true;
                                                  keyChar = String.fromCharCode(key);

                                                  if (!/\d/.test(keyChar))
                                                      return false;

                                              });

                                              jQuery("input#maxCost<?= $diId ?>").keypress(function (event) {
                                                  var key, keyChar;
                                                  if (!event)
                                                      var event = window.event;

                                                  if (event.keyCode)
                                                      key = event.keyCode;
                                                  else if (event.which)
                                                      key = event.which;

                                                  if (key == null || key == 0 || key == 8 || key == 13 || key == 9 || key == 46 || key == 37 || key == 39)
                                                      return true;
                                                  keyChar = String.fromCharCode(key);

                                                  if (!/\d/.test(keyChar))
                                                      return false;

                                              });

                                          </script>
                                      </div>
                                  </div>
                                  <?php
                              } else {
                                  ?>
                                  <div class="paramFilter">
                                    <div class="headerFilter js-filters-toogle" data-id="#paramsForHide<?= $diId ?>"><?= $filter['nameCategory'] ?></div>
                                  </div>
                                  <div class="valueParams js-filters-value"  id="paramsForHide<?= $diId ?>">
                                      <?php
                                      if (is_array($filter))
                                          foreach ($filter as $paramKey => $filterParam) {
                                              if ($paramKey !== "nameCategory") {
                                                  $checked = "";
                                                  if (@is_array($filters[$diId])) {
                                                      if (array_search($paramKey, $filters[$diId]) !== false) {
                                                          $checked = "checked=\"checked\"";
                                                          $imgClass = "class=\"colorFilterButton-select\"";
                                                      } else {
                                                          $imgClass = "class=\"colorFilterButton\"";
                                                      }
                                                  } else {
                                                      $imgClass = "class=\"colorFilterButton\"";
                                                  }
                                                  if (isset($filterParam['filename'])) {
                                                      echo '<div style="display: inline-block;margin: 5px;"><input type="checkbox" ' . $checked . /*' onClick="jQuery(\'#categoryFilters\').submit();" '.*/'name="filters[' . $diId . '][]" value="' . $paramKey . '" class="filteParams" style="opacity:0; position:absolute; left:9999px;"><img src="/uploads/dopinfo/thumbnails/' . $filterParam['filename'] . '" alt="' . $filterParam['value'] . '" title="' . $filterParam['value'] . '" ' . $imgClass . ' width="28" onClick=" if ( $(this).hasClass(\'colorFilterButton\') ) {$(this).removeClass(\'colorFilterButton\').addClass(\'colorFilterButton-select\');$(this).prev(\'input\').attr(\'checked\', \'checked\');}else{$(this).removeClass(\'colorFilterButton-select\').addClass(\'colorFilterButton\');$(this).prev(\'input\').removeAttr(\'checked\');} jQuery(\'#categoryFilters\').submit();" style="cursor: pointer;"></div>';
                                                  } else {
                                                      echo '<div><label><input type="checkbox" ' . $checked /*. ' onClick="jQuery(\'#categoryFilters\').submit();"'*/. ' name="filters[' . $diId . '][]" value="' . $paramKey . '" class="filteParams"><div style="display: inline;"> - ' . $filterParam['value'] . ' (<div style="display: inline;" class="countProd" data-default="' . $filterParam['countProducts'] . '">' . (@is_array($filtersCountProducts) ? (@$filtersCountProducts[$paramKey]['count'] != "" ? @$filtersCountProducts[$paramKey]['count'] : '0') : $filterParam['countProducts']) . '</div>)</div></label></div>';
                                                  }
                                              }
                                          }
                                      ?> </div>
                                  <?php
                              }
                              ?>
                          </li>
                          <?php
                      endforeach;
                  ?>
              </ul>
              <div class="blockSort">
                <?/*<div>*/?>
                  Сортировать по:
                  <select class="js-sort-order">
                    <option value="sortorder"<?= $sortOrder == "sortorder" ? "selected" : "" ?>>Популярные</option>
                    <option value="date"<?= $sortOrder == "date" ? "selected" : "" ?>>Новинки</option>
                    <option value="actions"<?= $sortOrder == "actions" ? "selected" : "" ?>>Акции и скидки</option>
                    <option value="price" <?= $sortOrder == "price" && $direction=="desc" ? "selected" : "" ?> data-direction="desc">Цена ↓</option>
                    <option value="price" <?= $sortOrder == "price" && $direction=="asc" ? "selected" : "" ?> data-direction="asc">Цена ↑</option>
                    <option value="comments"<?= $sortOrder == "comments" ? "selected" : "" ?>>Отзывы</option>
                  </select>
                  <?/*
                </div>
                <div>
                <label>
                <input type="checkbox" class="js-is-stock" value="1"<?= $isStock ? " checked" : ''?>>
                <span>Скрыть</span> отсутствующие товары
                </label>
                </div>*/?>
                <div class="filters-apply" onclick="$('#categoryFilters').submit()">Применить</div>
                <?/*<div class="paramFilter">*/?>
                  <div id="clearFilters" class="clearFilters" onClick="clearFilters()">Очистить</div>
                <?/*</div>*/?>
              </div>
          </form>


      </div>
    </div>
<? } ?>
