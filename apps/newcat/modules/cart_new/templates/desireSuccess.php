<?
if ($sf_user->isAuthenticated()) {
    $desiresPublic = CompareTable::getInstance()->createQuery()->where("rule='Публичный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
    $desiresCompleted = CompareTable::getInstance()->createQuery()->where("rule='Исполненый'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
}

$sortOrder = sfContext::getInstance()->getRequest()->getParameter("sortOrder");
$direction = sfContext::getInstance()->getRequest()->getParameter("direction");
?>
<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>
        Мои желания
    </li>
</ul>

<h1 class="title">Список желаний</h1>

<script>
    function settingDesire($id, $p) {
        $('<div/>').click(function (e) {
            if (e.target != this)
                return;
            $(this).remove();
        }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingDesire" + $id).addClass("blockSettingDesire").appendTo('body');
        $('.blockSettingDesire' + $id).html($(".SettingDesireBlock" + $p + $id).html());
        $(document).keyup(function (e) {

            if (e.keyCode == 27) {
                $('.blockSettingDesire' + $id).remove();
            }   // esc
        });
    }
    function setRate($prodId, $rate) {

        $('div.tab').each(function (indexTab) {
            $(this).find('.redButtonAddToDesire' + $prodId).each(function (index) {
                if (index < $rate) {
                    $(this).css("background", 'url(/images/newcat/heart.png)');
                } else {
                    $(this).css("background", 'url(/images/newcat/heart-non.png)');
                }
            });
        });
        $.post("/desire/rate", {productId: $prodId, value: $rate})
    }
    function setRatePopUp($prodId, $rate) {
        $('.blockSettingDesire' + $prodId + ' .redButtonAddToDesirePopUp' + $prodId).each(function (index) {
            if (index < $rate) {
                $(this).css("background", 'url(/images/newcat/heart.png)');
            } else {
                $(this).css("background", 'url(/images/newcat/heart-non.png)');
            }
        });
        $.post("/desire/rate", {productId: $prodId, value: $rate})
    }
</script>
<div class="CustomersDesire">
    Список желаний «Он и Она» – это удивительно полезный сервис, который позволит вам не забывать о понравившихся
    товарах и своих тайных желаниях. <br />
    Для того чтобы пополнить этот список достаточно в понравившемся товаре нажать на иконку «сердце» рядом с кнопкой
    «В корзину». Свой список желаний вы можете разгруппировать по разделам «Личный» и «Хочу в подарок». <br /><br />
    Вкладка «Личный» доступен только вам, здесь вы можете хранить товары, которые вам понравились, но вы еще не готовы
    их приобрести или хотите приобрести их в будущем.<br />
    Вкладка «Хочу в подарок» содержит список товаров, которым вы хотели бы поделиться с любимым или друзьями. Его можно
    опубликовать в социальной сети, рассказав о своих желаниях. Публикуя свой список желаний «Хочу в подарок» вы можете
    быть уверенны в том, что у ваших друзей (любимого) больше не будет сомнений по поводу хорошего и, главное, нужного
    подарка для вас на любой праздник.  <br />
    Вкладка «Исполненный» содержит товары, которые были в вашем списке желаний и уже подарены или куплены вами.<br /><br />

    Исполняйте больше своих желаний!

    <div class="tabset tabset--desire">
        <ul class="tab-control">
            <li<? if (sfContext::getInstance()->getRequest()->getParameter("act") == "") echo ' class="active"'; ?>>
              <a href="#">
                <span>Личный (<?= count($products_jel) ?>)</span>
              </a>
            </li>
            <? if ($desiresPublic) { ?>
              <li<? if (sfContext::getInstance()->getRequest()->getParameter("act") == "public") echo ' class="active"'; ?>>
                <a href="#">
                  <span>Хочу в подарок (<?= count(unserialize($desiresPublic->getProducts())) ?>)</span>
                </a>
              </li>
            <? } ?>
            <? if ($desiresCompleted) { ?>
              <li<? if (sfContext::getInstance()->getRequest()->getParameter("act") == "completed") echo ' class="active"'; ?>>
                <a href="#">
                  <span>Исполненый (<?= count(unserialize($desiresCompleted->getProducts())) ?>)</span>
                </a>
              </li>
            <? } ?>
        </ul>
        <div class="tab" style="<? if (sfContext::getInstance()->getRequest()->getParameter("act") == "") echo 'display:block;'; ?>">
          <div>
            <table>
              <tr>
                <td colspan="2">Сортировать по:</td>
                <td>
                  <a href="/desire?sortOrder=price&direction=<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>">Цена
                    <span><?= $sortOrder == "price" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span>
                  </a>
                </td>
                <td>
                  <a href="/desire?sortOrder=time&direction=<?= ($direction == "asc" and $sortOrder == "time") ? 'desc' : 'asc' ?>">Дата добавления
                    <span><?= $sortOrder == "time" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span>
                  </a>
                </td>
                <td>
                  <a href="/desire?sortOrder=rate&direction=<?= ($direction == "asc" and $sortOrder == "rate") ? 'desc' : 'asc' ?>">Рейтинг
                    <span><?= $sortOrder == "rate" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span>
                  </a>
                </td>
                <td>
                  <a href="/desire?sortOrder=comment&direction=<?= ($direction == "asc" and $sortOrder == "comment") ? 'desc' : 'asc' ?>">Комментарий
                    <span><?= $sortOrder == "comment" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span>
                  </a>
                </td>
                <td></td>
              </tr>
              <?php
                if ($products_desire_private) {
                  foreach ($products_jel as $productInfo):
                      foreach (unserialize($products_desire_private->getProductsinfo()) as $c => $productInfoFull) {
                        if (is_array($productInfo)) {
                            if ($productInfoFull['productId'] == $productInfo['productId']) {
                                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                                if ($productInfoFull['time'] != "")
                                    $arrayProdPrivate[$productInfoFull['productId']]['time'] = $productInfoFull['time'];
                                if ($productInfoFull['rate'] != "")
                                    $arrayProdPrivate[$productInfoFull['productId']]['rate'] = $productInfoFull['rate'];
                                if ($productInfoFull['comment'] != "")
                                    $arrayProdPrivate[$productInfoFull['productId']]['comment'] = $productInfoFull['comment'];
                                if ($product->getBonuspay() > 0) {
                                    $arrayProdPrivate[$productInfoFull['productId']]['price'] = number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                                } else {
                                    $arrayProdPrivate[$productInfoFull['productId']]['price'] = $product->getPrice();
                                }
                            }
                          }
                          else {
                            if ($productInfoFull['productId'] == $productInfo) {
                                $product = ProductTable::getInstance()->findOneById($productInfo);
                                if ($productInfoFull['time'] != "")
                                    $arrayProdPrivate[$productInfoFull['productId']]['time'] = $productInfoFull['time'];
                                if ($productInfoFull['rate'] != "")
                                    $arrayProdPrivate[$productInfoFull['productId']]['rate'] = $productInfoFull['rate'];
                                if ($productInfoFull['comment'] != "")
                                    $arrayProdPrivate[$productInfoFull['productId']]['comment'] = $productInfoFull['comment'];
                                if ($product->getBonuspay() > 0) {
                                    $arrayProdPrivate[$productInfoFull['productId']]['price'] = number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                                } else {
                                    $arrayProdPrivate[$productInfoFull['productId']]['price'] = $product->getPrice();
                                }
                            }
                          }
                      }
                  endforeach;
                }
                if ($arrayProdPrivate) {
                  foreach ($arrayProdPrivate as $c => $productInfoFull) {
                      if (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "time") {
                          $sort_numcie[$c] = $productInfoFull['time'];
                          if ($productInfoFull['time'] == "")
                              $sort_numcie[$c] = 0;
                      }
                      elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "rate") {
                          $sort_numcie[$c] = $productInfoFull['rate'];
                          if ($productInfoFull['rate'] == "")
                              $sort_numcie[$c] = 0;
                      }
                      elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "comment") {
                          $sort_numcie[$c] = $productInfoFull['comment'];
                          if ($productInfoFull['comment'] == "")
                              $sort_numcie[$c] = 0;
                      }
                      elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "price") {

                          $sort_numcie[$c] = $productInfoFull['price'];
                          if ($productInfoFull['price'] == "")
                              $sort_numcie[$c] = 0;
                      }
                  }
                }
                if (is_array($sort_numcie)) {
                  array_multisort($sort_numcie, sfContext::getInstance()->getRequest()->getParameter("direction") == "asc" ? SORT_ASC : SORT_DESC, $products_jel);
                }
                foreach ($products_jel as $productInfo):
                    if (is_array($productInfo))
                        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    else
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                    /* if ($products_desire_private)
                      foreach (unserialize($products_desire_private->getProductsinfo()) as $productInfoFull) {
                      if ($productInfoFull['productId'] == $product->getId()) {
                      $productDopInfo = $productInfoFull;
                      }
                      } */
                    $productDopInfo = $arrayProdPrivate[$product->getId()];
                    $photoalbums = $product->getPhotoalbums();
                    $photos = $photoalbums[0]->getPhotos();
                    ?>

                    <tr>
                        <td>
                          <a class="desire-image-link" href="/product/<?= $product->getSlug() ?>">
                            <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>">
                          </a>
                        </td>
                        <td>
                          <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                          <div>
                            <?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
                                <?
                                  $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
                                  if (is_array($products_old))
                                      foreach ($products_old as $key => $productCart) {
                                          $arrayProdCart[] = $productCart['productId'];
                                      }
                                  if (in_array($product->getId(), $arrayProdCart) === true)
                                      $prodInCart = true;
                                  else
                                      $prodInCart = false;

                                  if ($prodInCart) {
                                      ?>
                                      <a href="/cart" class="greenButtonAddToCartIco"></a>
                                  <?}
                                  else { ?>
                                      <div class="redButtonAddToCartIco" onClick="
                                          tagDiv = this;
                                          $.ajax({
                                              url: '/cart/addtocart/<?= $product->getId() ?>',
                                              cache: false
                                          }).done(function (html) {
                                              addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                                              $(tagDiv).after($('<a class=\'greenButtonAddToCartIco\'>').attr('href', '/cart')).remove();
                                          });">
                                      </div>
                                  <? } ?>
                            <?php endif;


                            $products_compare = $sf_user->getAttribute('products_to_compare');
                            $products_compare = $products_compare != '' ? unserialize($products_compare) : '';
                            if (in_array($product->getId(), $products_compare) === true)
                              $prodInCompare = true;
                            else
                              $prodInCompare = false;
                            ?>

                            <div class="greenButtonDelToDesire" onClick='$("#main #content").load("/desire/delete/<?= $product->getId() ?>");'></div>
                              <?if ($prodInCompare === true) { ?>
                                  <a class="greenButtonAddToCompare" href='/compare'></a>
                              <?}
                              else { ?>
                                <script>
                                  function settingCompare() {
                                      $('<div/>').click(function (e) {
                                          if (e.target != this)
                                              return;
                                          $(this).remove();
                                      }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingCompare").appendTo('body');
                                      $('.blockSettingCompare').html($(".SettingCompareBlock").html());
                                      $('.blockSettingCompare').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockSettingCompare').children().height() / 2))
                                      $(document).keyup(function (e) {

                                          if (e.keyCode == 27) {
                                              $('.blockSettingCompare').remove();
                                          }   // esc
                                      });
                                  }
                                </script>
                                <div class="SettingCompareBlock" style="display: none;">
                                  <div class="form-popup-wrapper">
                                    <div class="form-popup-content">
                                      <div onClick="$('.blockSettingCompare').remove();" class='close'></div>

                                      <div class="header">Добавление товара в сравнение</div><br/>
                                      <div class="settingCompareContent">
                                        <? if (!$sf_user->isAuthenticated()) { ?>
                                          <div class="notification-buttons">
                                            Добавляйте товар в сравнение. Это позволит с легкостью сравнить характеристики товара и выбрать для себя самый лучший.<br/><br/>
                                            Чтобы добавить товар в сравнение, вы должны быть авторизированы.<br/><br/>
                                            <div><a class="AuthCompareButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                            <div><a class="RegCompareButton" href="/register"></a>Для новых клиентов</div>
                                          </div>

                                        <? }
                                        else { ?>
                                          Товар добавлен в сравнение.<br /><br />
                                          Для сравнения вы добавили всего:
                                          <?
                                          echo count($products_compare) + 1;
                                          ?>
                                          товара<br /><br />
                                          <div class="notification-buttons">
                                            <div>
                                              <a class="CompareEnableButton" href="/compare"></a>
                                            </div>
                                            <div>
                                              <div class="CompareLastButton" onClick="$('.blockSettingCompare').remove();"></div>
                                            </div>
                                          </div>

                                        <? } ?>
                                        <div style="clear: both"></div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <a href="" class="redButtonAddToCompare" onClick='javascript:
                                  <? if ($sf_user->isAuthenticated()) { ?>
                                      $(this).after($("<a class=\"greenButtonAddToCompare\">").attr("href", "/compare")).remove();
                                      // addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                                      $.post("/cart/addtocompare/<?= $product->getId() ?>");
                                  <? } ?>
                                      settingCompare();
                                      return false;'>
                                </a>
                              <? } ?>
                          </div>
                        </td>
                        <td>
                          <? if ($product->getBonuspay() > 0) {

                              $totalCostPreShow += number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                              $bonusIsPay += round(number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                              ?>

                              <div>
                                <span class="old-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> р.</span><br/>
                                <span class="main-price"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> р.</span>
                              </div>

                            <? }
                          elseif ($product->getDiscount() > 0) {

                            $totalCostPreShow += $product->getPrice();
                            $bonusIsPay += round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                            ?>

                            <div>
                              <span class="old-price"><?= $product->getOldPrice() ?> р.</span><br/>
                              <span class="main-price"><?= $product->getPrice() ?> р.</span></div>

                          <? }
                          else {

                              $totalCostPreShow += $product->getPrice();
                              $bonusIsPay += round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                              ?>

                              <div>
                                <span class="main-price"><?= $product->getPrice() ?> р.</span>
                              </div>


                          <? } ?>

                            <div>
                              <? if ($product->getBonuspay() > 0) { ?>
                                <img alt="Управляй ценой" src="/images/bonDisc/podarok.png">
                              <? } elseif ($product->getDiscount() > 0) { ?>
                                <img alt="Лучшая цена" src="/newdis/images/loveprice.png">
                              <? } ?>
                            </div>
                        </td>
                        <td><?= $productDopInfo['time'] > 0 ? date("d.m.Y", $productDopInfo['time']) : '' ?></td>
                        <td>
                          <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct1<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct1<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRate(<?= $product->getId() ?>, 1)"></div>
                          <div style=" position: relative;">
                              <div id="rateProduct1<?= $product->getId() ?>" class="desire-heart desire-heart--1">
                                  Заинтересовал
                              </div>
                          </div>

                          <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct2<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct2<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRate(<?= $product->getId() ?>, 2)"></div>
                          <div style=" position: relative;">
                              <div id="rateProduct2<?= $product->getId() ?>" class="desire-heart desire-heart--2">
                                  Очень хочу
                              </div>
                          </div>

                          <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct3<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct3<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRate(<?= $product->getId() ?>, 3)"></div>
                          <div style=" position: relative;">
                              <div id="rateProduct3<?= $product->getId() ?>" class="desire-heart desire-heart--3">
                                  Должен быть моим
                              </div>
                          </div>
                        </td>
                        <td><?= $productDopInfo['comment'] ?></td>
                        <td>
                          <div class="SettingDesireBlock<?= $product->getId() ?>" style="display: none;">
                                <div class="form-popup-wrapper">
                                    <div class="form-popup-content form-popup-content--desire">

                                        <div onClick="$('.blockSettingDesire<?= $product->getId() ?>').remove();" class='close'></div>
                                        <div class="redButtonAddToDesire"></div>
                                        <div class="header">Редактирование товара в списке желаний</div><br/>
                                        <div class="settingDesireContent<?= $product->getId() ?>">
                                            Добавляйте товар в список своих желаний. Это позволит не забыть о понравившемся товаре и вернуться к покупке немного позже или же поделиться своими желаниями с близкими людьми и помочь им с выбором подарка для вас.
                                            <form action="/setdesire" class="setDesire<?= $product->getId() ?>" method="POST">
                                                <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                                <br />
                                                <span style="color: #42b039">Выберите список: </span>
                                                <?
                                                if ($sf_user->isAuthenticated()) {
                                                    $desiresPublic = CompareTable::getInstance()->createQuery()->where("rule='Публичный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                                    unset($productInPublic, $products_jelId);
                                                    if ($desiresPublic) {
                                                        $products_jelId = $desiresPublic->getProducts() != '' ? unserialize($desiresPublic->getProducts()) : array();

                                                        if (in_array($product->getId(), $products_jelId) === true) {
                                                            $productInPublic = true;
                                                        } else {
                                                            $productInPublic = false;
                                                        }
                                                    }
                                                    $desiresPrivate = CompareTable::getInstance()->createQuery()->where("rule='Личный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                                    unset($productInPrivate, $products_jelId);
                                                    if ($desiresPrivate) {
                                                        $products_jelId = $desiresPrivate->getProducts() != '' ? unserialize($desiresPrivate->getProducts()) : array();

                                                        if (in_array($product->getId(), $products_jelId) === true) {
                                                            $productInPrivate = true;
                                                        } else {
                                                            $productInPrivate = false;
                                                        }
                                                    }
                                                    $desiresComleted = CompareTable::getInstance()->createQuery()->where("rule='Исполненый'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                                    unset($products_jelId, $products_jelId);
                                                    if ($desiresComleted) {
                                                        $products_jelId = $desiresComleted->getProducts() != '' ? unserialize($desiresComleted->getProducts()) : array();

                                                        if (in_array($product->getId(), $products_jelId) === true) {
                                                            $productInComleted = true;
                                                        } else {
                                                            $productInComleted = false;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <? /* <input type="checkbox" name="private"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="public"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="completed"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый

                                                 */ ?>
                                                <div class="dinb radio-wrapper">
                                                  <input type="radio" value="private" name="selCat"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>
                                                <div class="dinb radio-wrapper">
                                                  <input type="radio" value="public" name="selCat"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>
                                                <div class="dinb radio-wrapper">
                                                  <input type="radio" value="completed" name="selCat"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый
                                                </div>

                                                <br />
                                                <br />
                                                <div style="float: left; margin-right: 5px;"><span style="color: #42b039">Степень желания: </span></div>

                                                <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 1)"></div>
                                                <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 2)"></div>
                                                <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 3)"></div>

                                                <br /><div style="clear: both"></div>
                                                <span style="float: left;color: #42b039; margin-top: 10px;display: block;">Комментарий: </span>
                                                <textarea name="comment"><?= $productDopInfo['comment'] ?></textarea>

                                                <div style="float: left; width: 100%;text-align: center;    margin-top: 10px;">
                                                  <a class="ButtonSave" href="" onclick="$('.blockSettingDesire<?= $product->getId() ?> .setDesire<?= $product->getId() ?>').ajaxForm(function (result) {
                                                    $('.blockSettingDesire<?= $product->getId() ?> .settingDesireContent<?= $product->getId() ?>').html(result);
                                                    });
                                                    $('.blockSettingDesire<?= $product->getId() ?> .setDesire<?= $product->getId() ?>').submit();
                                                    /*  var search = window.location.search.substr(1),
                                                     keys = {};

                                                     search.split('&').forEach(function (item) {
                                                     item = item.split('=');
                                                     keys[item[0]] = item[1];
                                                     });

                                                     $('div#content').load('http://onona.ru/desire?sortOrder=' + keys['sortOrder'] + '&direction=' + keys['direction']);
                                                     *///* $('#content').load('/desire');*/
                                                    return false;">
                                                  </a>
                                                </div>
                                                <div style="float: left; width: 300px;text-align: center;"><a class="SettingDesireButton" href="/customer/notification"></a></div>
                                                <div style="clear: both;"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="editButton"  onclick="settingDesire(<?= $product->getId() ?>, '')"></div>
                            <div class="deleteButton" onclick='$("#main #content").load("/desire/delete/<?= $product->getId() ?>");'></div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
          </div>
        </div>
        <? if ($desiresPublic) { ?>
          <div class="tab" style="<? if (sfContext::getInstance()->getRequest()->getParameter("act") == "public") echo 'display:block;'; ?>">
            <div>
              <table>
                <tr>
                    <td colspan="2">Сортировать по:</td>
                    <td>
                      <a href="/desire?act=public&sortOrder=price&direction=<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>">Цена <span><?= $sortOrder == "price" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                    </td>
                    <td>
                      <a href="/desire?act=public&sortOrder=time&direction=<?= ($direction == "asc" and $sortOrder == "time") ? 'desc' : 'asc' ?>">Дата добавления <span><?= $sortOrder == "time" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                    </td>
                    <td>
                      <a href="/desire?act=public&sortOrder=rate&direction=<?= ($direction == "asc" and $sortOrder == "rate") ? 'desc' : 'asc' ?>">Рейтинг <span><?= $sortOrder == "rate" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                    </td>
                    <td>
                      <a href="/desire?act=public&sortOrder=comment&direction=<?= ($direction == "asc" and $sortOrder == "comment") ? 'desc' : 'asc' ?>">Комментарий <span><?= $sortOrder == "comment" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                    </td>
                    <td></td>
                </tr>
                <?php
                  $desirePublicArray = unserialize($desiresPublic->getProducts());
                  $sort_numcie = Null;
                  $arrayProdPrivate = null;
                  foreach ($desirePublicArray as $productInfo):
                    foreach (unserialize($desiresPublic->getProductsinfo()) as $c => $productInfoFull) {
                        if (is_array($productInfo)) {
                          if ($productInfoFull['productId'] == $productInfo['productId']) {
                              $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                              if ($productInfoFull['time'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['time'] = $productInfoFull['time'];
                              if ($productInfoFull['rate'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['rate'] = $productInfoFull['rate'];
                              if ($productInfoFull['comment'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['comment'] = $productInfoFull['comment'];
                              if ($product->getBonuspay() > 0) {
                                  $arrayProdPrivate[$productInfoFull['productId']]['price'] = number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                              } else {
                                  $arrayProdPrivate[$productInfoFull['productId']]['price'] = $product->getPrice();
                              }
                          }
                        }
                        else {
                          if ($productInfoFull['productId'] == $productInfo) {
                            $product = ProductTable::getInstance()->findOneById($productInfo);
                            if ($productInfoFull['time'] != "")
                                $arrayProdPrivate[$productInfoFull['productId']]['time'] = $productInfoFull['time'];
                            if ($productInfoFull['rate'] != "")
                                $arrayProdPrivate[$productInfoFull['productId']]['rate'] = $productInfoFull['rate'];
                            if ($productInfoFull['comment'] != "")
                                $arrayProdPrivate[$productInfoFull['productId']]['comment'] = $productInfoFull['comment'];
                            If ($product->getBonuspay() > 0) {
                                $arrayProdPrivate[$productInfoFull['productId']]['price'] = number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                            } else {
                                $arrayProdPrivate[$productInfoFull['productId']]['price'] = $product->getPrice();
                            }
                          }
                        }
                    }
                  endforeach;
                  if ($arrayProdPrivate) {
                      foreach ($arrayProdPrivate as $c => $productInfoFull) {
                          if (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "time") {
                              $sort_numcie[$c] = $productInfoFull['time'];
                              if ($productInfoFull['time'] == "")
                                  $sort_numcie[$c] = 0;
                          }elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "rate") {
                              $sort_numcie[$c] = $productInfoFull['rate'];
                              if ($productInfoFull['rate'] == "")
                                  $sort_numcie[$c] = 0;
                          }elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "comment") {
                              $sort_numcie[$c] = $productInfoFull['comment'];
                              if ($productInfoFull['comment'] == "")
                                  $sort_numcie[$c] = 0;
                          }elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "price") {

                              $sort_numcie[$c] = $productInfoFull['price'];
                              if ($productInfoFull['price'] == "")
                                  $sort_numcie[$c] = 0;
                          }
                      }
                  }
                  if (is_array($sort_numcie)) {
                      array_multisort($sort_numcie, sfContext::getInstance()->getRequest()->getParameter("direction") == "asc" ? SORT_ASC : SORT_DESC, $desirePublicArray);
                  }

                  foreach ($desirePublicArray as $productInfo):

                    if (is_array($productInfo))
                        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    else
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                    /* if ($products_desire_public)
                      foreach (unserialize($products_desire_public->getProductsinfo()) as $productInfoFull) {
                      if ($productInfoFull['productId'] == $product->getId()) {
                      $productDopInfo = $productInfoFull;
                      }
                      } */

                    $productDopInfo = $arrayProdPrivate[$product->getId()];
                    $photoalbums = $product->getPhotoalbums();
                    $photos = $photoalbums[0]->getPhotos();
                    ?>

                    <tr>
                        <td>
                          <a class="desire-image-link" href="/product/<?= $product->getSlug() ?>">
                            <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>">
                          </a>
                        </td>
                        <td>
                          <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                          <div>
                            <?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
                                <?
                                    $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
                                    if (is_array($products_old))
                                        foreach ($products_old as $key => $productCart) {
                                            $arrayProdCart[] = $productCart['productId'];
                                        }
                                    if (in_array($product->getId(), $arrayProdCart) === true)
                                        $prodInCart = true;
                                    else
                                        $prodInCart = false;
                                    if ($prodInCart) { ?>
                                        <a href="/cart" class="greenButtonAddToCartIco"></a>
                                    <? }
                                    else { ?>
                                        <div class="redButtonAddToCartIco" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                                              $.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                                              });
                                          });
                                          addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                                          $(this).after($('<a class=\'greenButtonAddToCartIco\'>').attr('href', '/cart')).remove();

                                          return false;
                                             ">
                                        </div>
                                    <? } ?>
                            <?php endif;
                             $products_compare = $sf_user->getAttribute('products_to_compare');
                             $products_compare = $products_compare != '' ? unserialize($products_compare) : '';
                             if (in_array($product->getId(), $products_compare) === true)
                                 $prodInCompare = true;
                             else
                                 $prodInCompare = false;
                             ?>

                              <div class="greenButtonDelToDesire" onClick='$("#main #content").load("/desire/delete/<?= $product->getId() ?>");'></div>
                              <? if ($prodInCompare === true) { ?>
                                  <a class="greenButtonAddToCompare" href='/compare'></a>
                              <?}
                              else { ?>
                                <script>
                                  function settingCompare() {
                                    $('<div/>').click(function (e) {
                                      if (e.target != this)
                                          return;
                                      $(this).remove();
                                    }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingCompare").appendTo('body');
                                    $('.blockSettingCompare').html($(".SettingCompareBlock").html());
                                    $('.blockSettingCompare').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockSettingCompare').children().height() / 2))
                                    $(document).keyup(function (e) {
                                      if (e.keyCode == 27) {
                                          $('.blockSettingCompare').remove();
                                      }   // esc
                                    });
                                  }
                                </script>
                                  <div class="SettingCompareBlock" style="display: none;">
                                    <div class="form-popup-wrapper">
                                      <div class="form-popup-content">
                                        <div onClick="$('.blockSettingCompare').remove();" class='close'></div>
                                          <div class="header">Добавление товара в сравнение</div><br/>
                                          <div class="settingCompareContent">
                                            <? if (!$sf_user->isAuthenticated()) { ?>
                                              <div class="notification-buttons">
                                                Добавляйте товар в сравнение. Это позволит с легкостью сравнить характеристики товара и выбрать для себя самый лучший.<br/><br/>
                                                Чтобы добавить товар в сравнение, вы должны быть авторизированы.<br/><br/>
                                                <div><a class="AuthCompareButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                                <div><a class="RegCompareButton" href="/register"></a>Для новых клиентов</div>
                                              </div>
                                            <? }
                                            else { ?>
                                                Товар добавлен в сравнение.<br /><br />
                                                Для сравнения вы добавили всего:
                                                <?
                                                echo count($products_compare) + 1;
                                                ?>
                                                товара<br /><br />
                                                <div class="notification-buttons">
                                                  <div><a class="CompareEnableButton" href="/compare"></a></div>
                                                  <div><div class="CompareLastButton" onClick="$('.blockSettingCompare').remove();"></div></div>
                                                </div>
                                            <? } ?>
                                            <div style="clear: both"></div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                  <a href="" class="redButtonAddToCompare" onClick='javascript:
                                  <? if ($sf_user->isAuthenticated()) { ?>
                                            $(this).after($("<a class=\"greenButtonAddToCompare\">").attr("href", "/compare")).remove();
                                            // addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                                            $.post("/cart/addtocompare/<?= $product->getId() ?>");
                                  <? } ?>
                                            settingCompare();
                                            return false;'></a>
                                 <? } ?>

                          </div>
                        </td>
                        <td>
                          <?
                            if ($product->getBonuspay() > 0) {

                                $totalCostPreShow += number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                                $bonusIsPay += round(number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                                ?>

                                <div>
                                  <span class="old-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> р.</span><br/>
                                  <span class="main-price"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> р.</span>
                                </div>

                            <? }
                            elseif ($product->getDiscount() > 0) {

                              $totalCostPreShow += $product->getPrice();
                              $bonusIsPay += round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                              ?>

                              <div>
                                <span class="old-price"><?= $product->getOldPrice() ?> р.</span><br/>
                                <span class="main-price"><?= $product->getPrice() ?> р.</span>
                              </div>

                            <? }
                            else {

                                $totalCostPreShow += $product->getPrice();
                                $bonusIsPay += round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                                ?>

                                <div>
                                  <span class="main-price"><?= $product->getPrice() ?> р.</span>
                                </div>


                            <? } ?>

                            <div style="font-size: 11px;"> <? If ($product->getBonuspay() > 0) { ?>
                                    <img alt="Управляй ценой" src="/images/bonDisc/podarok.png">
                                <? } elseif ($product->getDiscount() > 0) { ?>
                                    <img alt="Лучшая цена" src="/newdis/images/loveprice.png">
                                <? } ?>
                            </div></td>
                        <td><?= $productDopInfo['time'] > 0 ? date("d.m.Y", $productDopInfo['time']) : '' ?></td>
                        <td>
                          <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct1<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct1<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;float: left;width: 24px;height: 22px;margin-right: 2px;cursor: pointer;" onClick="setRate(<?= $product->getId() ?>, 1)"></div>
                          <div style=" position: relative;">
                              <div id="rateProduct1<?= $product->getId() ?>" class="desire-heart desire-heart--1">
                                  Заинтересовал
                              </div>
                          </div>

                          <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct2<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct2<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;float: left;width: 24px;height: 22px;margin-right: 2px;cursor: pointer;" onClick="setRate(<?= $product->getId() ?>, 2)"></div>
                          <div style=" position: relative;">
                              <div id="rateProduct2<?= $product->getId() ?>" class="desire-heart desire-heart--2">
                                  Очень хочу
                              </div>
                          </div>

                          <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct3<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct3<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;float: left;width: 24px;height: 22px;margin-right: 2px;cursor: pointer;" onClick="setRate(<?= $product->getId() ?>, 3)"></div>
                          <div style=" position: relative;">
                              <div id="rateProduct3<?= $product->getId() ?>" class="desire-heart desire-heart--3">
                                  Должен быть моим
                              </div>
                          </div>
                        </td>
                        <td><?= $productDopInfo['comment'] ?></td>
                        <td>
                          <div class="SettingDesireBlockP<?= $product->getId() ?>" style="display: none;">
                            <div class="form-popup-wrapper">
                              <div class="form-popup-content form-popup-content--desire">
                                <div onClick="$('.blockSettingDesire<?= $product->getId() ?>').remove();" class='close'></div>

                                <div class="redButtonAddToDesire"></div>
                                <div class="header">Редактирование товара в списке желаний</div><br/>
                                <div class="settingDesireContent<?= $product->getId() ?>">
                                  Добавляйте товар в список своих желаний. Это позволит не забыть о понравившемся товаре и вернуться к покупке немного позже или же поделиться своими желаниями с близкими людьми и помочь им с выбором подарка для вас.
                                  <form action="/setdesire" class="setDesire<?= $product->getId() ?>" method="POST">
                                      <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                      <br />
                                      <span style="color: #42b039">Выберите список: </span>
                                      <?
                                      if ($sf_user->isAuthenticated()) {
                                          $desiresPublic = CompareTable::getInstance()->createQuery()->where("rule='Публичный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                          unset($productInPublic, $products_jelId);
                                          if ($desiresPublic) {
                                              $products_jelId = $desiresPublic->getProducts() != '' ? unserialize($desiresPublic->getProducts()) : array();

                                              if (in_array($product->getId(), $products_jelId) === true) {
                                                  $productInPublic = true;
                                              } else {
                                                  $productInPublic = false;
                                              }
                                          }
                                          $desiresPrivate = CompareTable::getInstance()->createQuery()->where("rule='Личный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                          unset($productInPrivate, $products_jelId);
                                          if ($desiresPrivate) {
                                              $products_jelId = $desiresPrivate->getProducts() != '' ? unserialize($desiresPrivate->getProducts()) : array();

                                              if (in_array($product->getId(), $products_jelId) === true) {
                                                  $productInPrivate = true;
                                              } else {
                                                  $productInPrivate = false;
                                              }
                                          }
                                          $desiresComleted = CompareTable::getInstance()->createQuery()->where("rule='Исполненый'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                          unset($products_jelId, $products_jelId);
                                          if ($desiresComleted) {
                                              $products_jelId = $desiresComleted->getProducts() != '' ? unserialize($desiresComleted->getProducts()) : array();

                                              if (in_array($product->getId(), $products_jelId) === true) {
                                                  $productInComleted = true;
                                              } else {
                                                  $productInComleted = false;
                                              }
                                          }
                                      }
                                      /* ?>
                                        <input type="checkbox" name="private"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="public"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="completed"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый
                                       */
                                      ?>
                                      <div class="dinb radio-wrapper">
                                        <input type="radio" value="private" name="selCat"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                      </div>
                                      <div class="dinb radio-wrapper">
                                        <input type="radio" value="public" name="selCat"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                      </div>
                                      <div class="dinb radio-wrapper">
                                        <input type="radio" value="completed" name="selCat"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый
                                      </div>

                                      <br />
                                      <br />
                                      <div style="float: left; margin-right: 5px;"><span style="color: #42b039">Степень желания: </span></div>

                                      <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 1)"></div>
                                      <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 2)"></div>
                                      <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 3)"></div>


                                      <br /><div style="clear: both"></div>
                                      <span style="float: left;color: #42b039;margin-top: 10px;display: block;">Комментарий: </span>
                                      <textarea name="comment"><?= $productDopInfo['comment'] ?></textarea>

                                      <div style="float: left; width: 100%;text-align: center;    margin-top: 10px;"><a class="ButtonSave" href="" onclick="$('.blockSettingDesire<?= $product->getId() ?> .setDesire<?= $product->getId() ?>').ajaxForm(function (result) {
                                            $('.blockSettingDesire<?= $product->getId() ?> .settingDesireContent<?= $product->getId() ?>').html(result);
                                        });
                                        $('.blockSettingDesire<?= $product->getId() ?> .setDesire<?= $product->getId() ?>').submit();
                                        /*var search = window.location.search.substr(1),
                                         keys = {};

                                         search.split('&').forEach(function (item) {
                                         item = item.split('=');
                                         keys[item[0]] = item[1];
                                         });


                                         $('div#content').load('http://onona.ru/desire?act=public&sortOrder=' + keys['sortOrder'] + '&direction=' + keys['direction']);
                                         */
                                        //* $('#content').load('/desire');*/
                                        return false;"></a></div>
                                      <div style="float: left; width: 300px;text-align: center;"><a class="SettingDesireButton" href="/customer/notification"></a></div>

                                      <div style="clear: both;"></div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>




                          <div class="editButton"  onclick="settingDesire(<?= $product->getId() ?>, 'P')"></div>
                          <div class="deleteButton" onclick='$("#main #content").load("/desire/delete/<?= $product->getId() ?>");'></div>
                        </td>
                    </tr>

                  <?php endforeach; ?>
              </table>
            </div>
          </div>
        <?} ?>
        <? if ($desiresCompleted) { ?>
          <div class="tab" style="<? if (sfContext::getInstance()->getRequest()->getParameter("act") == "completed") echo 'display:block;'; ?>">
            <div>
              <table>
                <tr>
                  <td colspan="2">Сортировать по:</td>
                  <td>
                    <a href="/desire?act=completed&sortOrder=price&direction=<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>">Цена <span><?= $sortOrder == "price" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                  </td>
                  <td>
                    <a href="/desire?act=completed&sortOrder=time&direction=<?= ($direction == "asc" and $sortOrder == "time") ? 'desc' : 'asc' ?>">Дата добавления <span><?= $sortOrder == "time" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                  </td>
                  <td>
                    <a href="/desire?act=completed&sortOrder=rate&direction=<?= ($direction == "asc" and $sortOrder == "rate") ? 'desc' : 'asc' ?>">Рейтинг <span><?= $sortOrder == "rate" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                  </td>
                  <td>
                    <a href="/desire?act=completed&sortOrder=comment&direction=<?= ($direction == "asc" and $sortOrder == "comment") ? 'desc' : 'asc' ?>">Комментарий <span><?= $sortOrder == "comment" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a>
                  </td>
                  <td></td>
                </tr>
                <?php
                  $desireCompletedArray = unserialize($desiresCompleted->getProducts());
                  $sort_numcie = Null;
                  $arrayProdPrivate = null;
                  foreach ($desireCompletedArray as $productInfo):
                      foreach (unserialize($desiresCompleted->getProductsinfo()) as $c => $productInfoFull) {

                          if (is_array($productInfo)) {
                            if ($productInfoFull['productId'] == $productInfo['productId']) {
                              $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                              if ($productInfoFull['time'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['time'] = $productInfoFull['time'];
                              if ($productInfoFull['rate'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['rate'] = $productInfoFull['rate'];
                              if ($productInfoFull['comment'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['comment'] = $productInfoFull['comment'];
                              if ($product->getBonuspay() > 0) {
                                  $arrayProdPrivate[$productInfoFull['productId']]['price'] = number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                              } else {
                                  $arrayProdPrivate[$productInfoFull['productId']]['price'] = $product->getPrice();
                              }
                            }
                          }
                          else {
                            if ($productInfoFull['productId'] == $productInfo) {
                              $product = ProductTable::getInstance()->findOneById($productInfo);
                              if ($productInfoFull['time'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['time'] = $productInfoFull['time'];
                              if ($productInfoFull['rate'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['rate'] = $productInfoFull['rate'];
                              if ($productInfoFull['comment'] != "")
                                  $arrayProdPrivate[$productInfoFull['productId']]['comment'] = $productInfoFull['comment'];
                              If ($product->getBonuspay() > 0) {
                                  $arrayProdPrivate[$productInfoFull['productId']]['price'] = number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                              } else {
                                  $arrayProdPrivate[$productInfoFull['productId']]['price'] = $product->getPrice();
                              }
                            }
                          }
                      }
                  endforeach;


                  if ($arrayProdPrivate) {
                    foreach ($arrayProdPrivate as $c => $productInfoFull) {
                      if (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "time") {
                          $sort_numcie[$c] = $productInfoFull['time'];
                          if ($productInfoFull['time'] == "")
                              $sort_numcie[$c] = 0;
                      }
                      elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "rate") {
                          $sort_numcie[$c] = $productInfoFull['rate'];
                          if ($productInfoFull['rate'] == "")
                              $sort_numcie[$c] = 0;
                      }
                      elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "comment") {
                          $sort_numcie[$c] = $productInfoFull['comment'];
                          if ($productInfoFull['comment'] == "")
                              $sort_numcie[$c] = 0;
                      }
                      elseif (sfContext::getInstance()->getRequest()->getParameter("sortOrder") == "price") {

                          $sort_numcie[$c] = $productInfoFull['price'];
                          if ($productInfoFull['price'] == "")
                              $sort_numcie[$c] = 0;
                      }
                    }
                  }
                  if (is_array($sort_numcie)) {
                      array_multisort($sort_numcie, sfContext::getInstance()->getRequest()->getParameter("direction") == "asc" ? SORT_ASC : SORT_DESC, $desireCompletedArray);
                  }

                  foreach ($desireCompletedArray as $productInfo):

                    if (is_array($productInfo))
                        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    else
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                    /* if ($products_desire_completed)
                      foreach (unserialize($products_desire_completed->getProductsinfo()) as $productInfoFull) {
                      if ($productInfoFull['productId'] == $product->getId()) {
                      $productDopInfo = $productInfoFull;
                      }
                      } */

                    $productDopInfo = $arrayProdPrivate[$product->getId()];

                    $photoalbums = $product->getPhotoalbums();
                    $photos = $photoalbums[0]->getPhotos();
                    ?>

                    <tr>
                      <td>
                        <a class="desire-image-link" href="/product/<?= $product->getSlug() ?>">
                          <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a>
                      </td>
                      <td>
                        <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                        <div>
                          <?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
                            <?
                                  $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
                                  if (is_array($products_old))
                                      foreach ($products_old as $key => $productCart) {
                                          $arrayProdCart[] = $productCart['productId'];
                                      }
                                  if (in_array($product->getId(), $arrayProdCart) === true)
                                      $prodInCart = true;
                                  else
                                      $prodInCart = false;
                                  if ($prodInCart) {
                                      ?>
                                      <a href="/cart" class="greenButtonAddToCartIco"></a>
                                  <? }
                                  else { ?>
                                    <div class="redButtonAddToCartIco" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                                            $.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                                          });
                                        });
                                        addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                                        $(this).after($('<a class=\'greenButtonAddToCartIco\'>').attr('href', '/cart')).remove();
                                        return false;
                                       ">
                                     </div>
                                  <? } ?>
                          <?php endif;
                           $products_compare = $sf_user->getAttribute('products_to_compare');
                           $products_compare = $products_compare != '' ? unserialize($products_compare) : '';
                           if (in_array($product->getId(), $products_compare) === true)
                               $prodInCompare = true;
                           else
                               $prodInCompare = false;
                          ?>

                              <div class="greenButtonDelToDesire" onClick='$("#main #content").load("/desire/delete/<?= $product->getId() ?>");'></div>
                              <? if ($prodInCompare === true) { ?>
                                <a class="greenButtonAddToCompare" href='/compare'></a>
                              <? }
                              else { ?>
                                <script>
                                    function settingCompare() {
                                        $('<div/>').click(function (e) {
                                            if (e.target != this)
                                                return;
                                            $(this).remove();
                                        }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingCompare").appendTo('body');
                                        $('.blockSettingCompare').html($(".SettingCompareBlock").html());
                                        $('.blockSettingCompare').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockSettingCompare').children().height() / 2))
                                        $(document).keyup(function (e) {

                                            if (e.keyCode == 27) {
                                                $('.blockSettingCompare').remove();
                                            }   // esc
                                        });
                                    }
                                </script>
                                <div class="SettingCompareBlock" style="display: none;">
                                  <div class="form-popup-wrapper">
                                    <div class="form-popup-content">
                                      <div onClick="$('.blockSettingCompare').remove();" class='close'></div>
                                      <div class="header">Добавление товара в сравнение</div><br/>
                                        <div class="settingCompareContent">
                                          <? if (!$sf_user->isAuthenticated()) { ?>
                                            <div class="notification-buttons">
                                              Добавляйте товар в сравнение. Это позволит с легкостью сравнить характеристики товара и выбрать для себя самый лучший.<br/><br/>
                                              Чтобы добавить товар в сравнение, вы должны быть авторизированы.<br/><br/>
                                              <div><a class="AuthCompareButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                              <div><a class="RegCompareButton" href="/register"></a>Для новых клиентов</div>
                                            </div>
                                          <? }
                                          else { ?>
                                              Товар добавлен в сравнение.<br /><br />
                                              Для сравнения вы добавили всего:
                                              <?
                                              echo count($products_compare) + 1;
                                              ?>
                                              товара<br /><br />
                                            <div class="notification-buttons">
                                              <div><a class="CompareEnableButton" href="/compare"></a></div>
                                              <div><div class="CompareLastButton" onClick="$('.blockSettingCompare').remove();"></div></div>
                                            </div>
                                          <? } ?>
                                          <div style="clear: both"></div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <a href="" class="redButtonAddToCompare" onClick='javascript:
                                  <? if ($sf_user->isAuthenticated()) { ?>
                                                          $(this).after($("<a class=\"greenButtonAddToCompare\">").attr("href", "/compare")).remove();
                                                          // addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                                                          $.post("/cart/addtocompare/<?= $product->getId() ?>");
                                  <? } ?>
                                                      settingCompare();
                                                      return false;'>
                                </a>
                              <? } ?>

                        </div>
                      </td>
                      <td>
                        <?
                          if ($product->getBonuspay() > 0) {

                              $totalCostPreShow += number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ');
                              $bonusIsPay += round(number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                              ?>

                              <div>
                                <span class="old-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> р.</span><br/>
                                <span class="main-price"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> р.</span>
                              </div>

                          <? }
                          elseif ($product->getDiscount() > 0) {

                              $totalCostPreShow += $product->getPrice();
                              $bonusIsPay += round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                              ?>

                              <div>
                                <span class="old-price"><?= $product->getOldPrice() ?> р.</span><br/>
                                <span class="main-price"><?= $product->getPrice() ?> р.</span></div>

                          <? }
                          else {

                              $totalCostPreShow += $product->getPrice();
                              $bonusIsPay += round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                              ?>

                              <div>
                                <span class="main-price"><?= $product->getPrice() ?> р.</span>
                              </div>
                          <? } ?>

                          <div>
                            <?if ($product->getBonuspay() > 0) { ?>
                              <img alt="Управляй ценой" src="/images/bonDisc/podarok.png">
                            <? }
                            elseif ($product->getDiscount() > 0) { ?>
                                <img alt="Лучшая цена" src="/newdis/images/loveprice.png">
                            <? } ?>
                          </div>
                      </td>
                      <td><?= $productDopInfo['time'] > 0 ? date("d.m.Y", $productDopInfo['time']) : '' ?></td>
                      <td>
                        <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct1<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct1<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRate(<?= $product->getId() ?>, 1)"></div>
                        <div style=" position: relative;">
                            <div id="rateProduct1<?= $product->getId() ?>" class="desire-heart desire-heart--1">
                                Заинтересовал
                            </div>
                        </div>

                        <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct2<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct2<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRate(<?= $product->getId() ?>, 2)"></div>
                        <div style=" position: relative;">
                            <div id="rateProduct2<?= $product->getId() ?>" class="desire-heart desire-heart--2">
                                Очень хочу
                            </div>
                        </div>

                        <div class="add-to-desire redButtonAddToDesire<?= $product->getId() ?>" onmouseout="$('#rateProduct3<?= $product->getId() ?>').fadeOut(0)" onmouseover="$('#rateProduct3<?= $product->getId() ?>').fadeIn(0)" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRate(<?= $product->getId() ?>, 3)"></div>
                        <div style=" position: relative;">
                            <div id="rateProduct3<?= $product->getId() ?>" class="desire-heart desire-heart--3">
                                Должен быть моим
                            </div>
                        </div>
                      </td>
                      <td><?= $productDopInfo['comment'] ?></td>
                      <td>
                        <div class="SettingDesireBlockC<?= $product->getId() ?>" style="display: none;">
                          <div class="form-popup-wrapper">
                            <div class="form-popup-content form-popup-content--desire">
                              <div onClick="$('.blockSettingDesire<?= $product->getId() ?>').remove();" class='close'></div>
                              <div class="redButtonAddToDesire"></div>
                              <div class="header">Редактирование товара в списке желаний</div><br/>
                                <div class="settingDesireContent<?= $product->getId() ?>">
                                    Добавляйте товар в список своих желаний. Это позволит не забыть о понравившемся товаре и вернуться к покупке немного позже или же поделиться своими желаниями с близкими людьми и помочь им с выбором подарка для вас.
                                    <form action="/setdesire" class="setDesire<?= $product->getId() ?>" method="POST">
                                        <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                        <br />
                                        <span style="color: #42b039">Выберите список: </span>
                                        <?
                                        if ($sf_user->isAuthenticated()) {
                                            $desiresPublic = CompareTable::getInstance()->createQuery()->where("rule='Публичный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                            unset($productInPublic, $products_jelId);
                                            if ($desiresPublic) {
                                                $products_jelId = $desiresPublic->getProducts() != '' ? unserialize($desiresPublic->getProducts()) : array();

                                                if (in_array($product->getId(), $products_jelId) === true) {
                                                    $productInPublic = true;
                                                } else {
                                                    $productInPublic = false;
                                                }
                                            }
                                            $desiresPrivate = CompareTable::getInstance()->createQuery()->where("rule='Личный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                            unset($productInPrivate, $products_jelId);
                                            if ($desiresPrivate) {
                                                $products_jelId = $desiresPrivate->getProducts() != '' ? unserialize($desiresPrivate->getProducts()) : array();

                                                if (in_array($product->getId(), $products_jelId) === true) {
                                                    $productInPrivate = true;
                                                } else {
                                                    $productInPrivate = false;
                                                }
                                            }
                                            $desiresComleted = CompareTable::getInstance()->createQuery()->where("rule='Исполненый'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                            unset($products_jelId, $products_jelId);
                                            if ($desiresComleted) {
                                                $products_jelId = $desiresComleted->getProducts() != '' ? unserialize($desiresComleted->getProducts()) : array();

                                                if (in_array($product->getId(), $products_jelId) === true) {
                                                    $productInComleted = true;
                                                } else {
                                                    $productInComleted = false;
                                                }
                                            }
                                        }
                                        /* ?>
                                          <input type="checkbox" name="private"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="public"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="completed"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый
                                         */
                                        ?>
                                        <div class="dinb radio-wrapper">
                                          <input type="radio" value="private" name="selCat"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div class="dinb radio-wrapper">
                                          <input type="radio" value="public" name="selCat"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div class="dinb radio-wrapper">
                                          <input type="radio" value="completed" name="selCat"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый
                                        </div>

                                        <br />
                                        <br />
                                        <div style="float: left; margin-right: 5px;"><span style="color: #42b039">Степень желания: </span></div>

                                        <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 1)"></div>
                                        <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 2)"></div>
                                        <div class="add-to-desire redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;" onClick="setRatePopUp(<?= $product->getId() ?>, 3)"></div>

                                        <br /><div style="clear: both"></div>
                                        <span style="float: left;color: #42b039;margin-top: 10px;display: block;">Комментарий: </span>
                                        <textarea name="comment"><?= $productDopInfo['comment'] ?></textarea>
                                        <div style="float: left; width: 100%;text-align: center;    margin-top: 10px;">
                                          <a class="ButtonSave" href="" onclick="$('.blockSettingDesire<?= $product->getId() ?> .setDesire<?= $product->getId() ?>').ajaxForm(function (result) {
                                                $('.blockSettingDesire<?= $product->getId() ?> .settingDesireContent<?= $product->getId() ?>').html(result);
                                            });
                                            $('.blockSettingDesire<?= $product->getId() ?> .setDesire<?= $product->getId() ?>').submit();
                                            //* $('#content').load('/desire');*/
                                            /*var search = window.location.search.substr(1),
                                             keys = {};

                                             search.split('&').forEach(function (item) {
                                             item = item.split('=');
                                             keys[item[0]] = item[1];
                                             });


                                             $('div#content').load('http://onona.ru/desire?act=completed&sortOrder=' + keys['sortOrder'] + '&direction=' + keys['direction']);
                                             */
                                            return false;">
                                          </a>
                                        </div>
                                        <div style="float: left; width: 300px;text-align: center;"><a class="SettingDesireButton" href="/customer/notification"></a></div>

                                        <div style="clear: both;"></div>
                                    </form>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="editButton"  onclick="settingDesire(<?= $product->getId() ?>, 'C')"></div>
                        <div class="deleteButton" onclick='$("#main #content").load("/desire/delete/<?= $product->getId() ?>");'></div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
              </table>
            </div>
          </div>
        <?} ?>
    </div>
    <?php /* if (is_array($products_jel) and count($products_jel) > 0): ?>

      <div align="center"><a href="javascript:history.back();">Продолжить покупки</a></div>
      <?php else: ?>
      <?
      $footer = PageTable::getInstance()->findOneBySlug("pustaya-stranica-spiska-zhelanii");
      echo $footer->getContent();
      ?>
      <?php endif; */ ?>
</div>
