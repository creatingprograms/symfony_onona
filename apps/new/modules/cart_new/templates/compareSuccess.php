<?
global $isTest;
$h1='Сравнение товаров';
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);
?>
<main class="wrapper -action">
  <?php
  if (is_array($products_compare) and count($products_compare) > 0):
      ?>
      <div><div>Всего товаров для сравнения: <?= count($products_compare) ?></div>
          <div><input type="checkbox" id="isDelete"/><div onclick="if ($('#isDelete').prop('checked')) {
                          $('.ScroolBlock').load('/compare/deleteall');
                      } else {
                          $('.DellAllBlock').show();
                      }" class="silverButtonDelAllProducts"></div></div>
          <div class="DellAllBlock">
              <div>
                  <div>
                      Для удаления товаров установите галочку.
                      <div style="clear: both;"></div>
                  </div>
              </div>
          </div>
      </div>
      <div></div>
      <?
      //$whereDopInfo = "product_id = " . $product->getId();
      foreach ($products_compare as $productId) {
          $whereDopInfo.=" OR product_id = " . $productId;
      }
      $whereDopInfo = trim($whereDopInfo, " OR ");
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      //$result = $q->execute("select * from (SELECT*,count(dop_info_id) as count FROM dop_info_product where " . $whereDopInfo . " group by dop_info_id) as params left join dop_info as dp on dp.id=params.dop_info_id");


      $result = $q->execute("select * from (SELECT * FROM dop_info_product where " . $whereDopInfo . ") as params left join dop_info as dp on dp.id=params.dop_info_id left join dop_info_category as dpc on dpc.id=dp.dicategory_id where dpc.name!='Таблица размеров' and dpc.is_compare='1' order by dpc.name");

      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $productsDopInfo = $result->fetchAll();
      //print_r($productsDopInfo);
      foreach ($productsDopInfo as $dopinfo) {
          $arrayDopInfo[$dopinfo['product_id']][$dopinfo['name']][] = $dopinfo['value'];
      }
      //print_r($arrayDopInfo);


      $result = $q->execute("select dpc.name, dpc.namecompare from (SELECT * FROM dop_info_product where " . $whereDopInfo . ") as params left join dop_info as dp on dp.id=params.dop_info_id left join dop_info_category as dpc on dpc.id=dp.dicategory_id where dpc.name!='Таблица размеров' and dpc.is_compare='1' group by dpc.name order by dpc.namecompare");

      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $DopInfoCategory = $result->fetchAll();
      //print_r($DopInfoCategory);
      ?><link href="/css/jquery.mCustomScrollbar.css" rel="stylesheet">
      <script src="/js/jquery.mCustomScrollbar.js"></script>

      <script>
        (function ($) {
          $(window).load(function () {
            $(".ScroolBlock").mCustomScrollbar({
              axis: "x",
              scrollButtons: {enable: true},
              theme: "3d",
              scrollbarPosition: "outside",
              autoExpandScrollbar: true,
              autoDraggerLength: false,
              advanced: {autoExpandHorizontalScroll: true}
            });
          });
          $(".ScroolBlock").mCustomScrollbar({
            axis: "x",
            scrollButtons: {enable: true},
            theme: "3d",
            scrollbarPosition: "outside",
            autoExpandScrollbar: true,
            autoDraggerLength: false,
            advanced: {autoExpandHorizontalScroll: true}
          });
        })(jQuery);
        function CollapseExpand(button, num) {
          if ($(button).find(".blockDescriptionContentProductTurnButtom").text() == "") {
            $(button).find(".blockDescriptionContentProductTurnButtom").text('-');
            $(button).find(".blockDescriptionContentProductTurnButtom").attr('data-minus', '1');
            $(button).find(".blockDescriptionContentProductTurnButtom").css('background', "none repeat scroll 0 0 #c3060e");
            $("#tableCompare" + num).fadeIn(1);
          }
          else {
            $(button).find(".blockDescriptionContentProductTurnButtom").text('');
            $(button).find(".blockDescriptionContentProductTurnButtom").removeAttr('data-minus');
            $(button).find(".blockDescriptionContentProductTurnButtom").css('background', "url('/images/newcat/+.png')");
            $("#tableCompare" + num).fadeOut(0);
          }
        }
      </script>
      <div class="ScroolBlock">

          <table class="tableCompare">
              <tbody><tr>
                      <td>Общая информация</td>

                      <?php
                      foreach ($products_compare as $productInfo):
                          $product = ProductTable::getInstance()->findOneById($productInfo);
                          $photoalbums = $product->getPhotoalbums();
                          $photos = $photoalbums[0]->getPhotos();
                          ?>
                          <td width="188">
                              <div class="close" onClick='$("#main #content").load("/compare/delete/<?= $product->getId() ?>");'></div>
                              <div>
                                  <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                              </div>
                              <div>
                                  <span>
                                      <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>">
                                  </span>
                              </div>
                              <div><?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
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
                                      <? } else { ?>
                                          <div class="redButtonAddToCartIco" onClick="
                                                                  tagDiv = this;
                                                                  $.ajax({
                                                                      url: '/cart/addtocart/<?= $product->getId() ?>',
                                                                      cache: false
                                                                  }).done(function (html) {
                                                                      addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                                                                      $(tagDiv).after($('<a class=\'greenButtonAddToCartIco\'>').attr('href', '/cart')).remove();

                                                                  });
                                               "></div>
                                           <? } ?>
                                           <?php
                                       endif;

                                       $products_desire = $sf_user->getAttribute('products_to_desire');
                                       $products_desire = $products_desire != '' ? unserialize($products_desire) : '';

                                       if (in_array($product->getId(), $products_desire) === true)
                                           $prodInDesire = true;
                                       else
                                           $prodInDesire = false;
                                       ?>
                                       <? if ($prodInDesire === true) {
                                           ?>
                                      <a class="greenButtonAddToDesire" href='/desire' ></a>
                                      <?
                                  } else {
                                      ?>
                                      <div class="redButtonAddToDesire"  onClick='javascript: $(this).after($("<a class=\"greenButtonAddToDesire\">").attr("href", "/desire")).remove();
                                                          addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                                                          $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></div>
                                       <? } ?>

                                  <div class="greenButtonDelToCompare" onClick='$("#main #content").load("/compare/delete/<?= $product->getId() ?>");'></div>


                              </div>
                          </td>

                      <?php endforeach; ?>
                  </tr>
                  <tr>
                      <td><b>Фото:</b></td>
                      <?
                      foreach ($products_compare as $productInfo):
                          $product = ProductTable::getInstance()->findOneById($productInfo);
                          $photoalbums = $product->getPhotoalbums();
                          $photos = $photoalbums[0]->getPhotos();
                          ?>
                          <td >

                              <a href="" onclick="loadPreShowPhoto(<?= $product->getId() ?>);
                                return false;"><?= $photos->count() ?> - посмотреть</a>
                              <script>
                                function loadPreShowPhoto($id) {
                                  $.post("/product/preshowphoto/" + $id,
                                    function (data) {
                                      $('<div/>').click(function (e) {
                                        if (e.target != this)
                                            return;
                                        $(this).remove();
                                      }).attr('id', 'preShow-' + $id).css("padding-top", $(window).scrollTop() + 40).addClass("blockPreShow").appendTo('body');
                                      $('.blockPreShow').html(data);

                                      $(document).keyup(function (e) {
                                        if (e.keyCode == 27) {
                                            $('.blockPreShow').remove();
                                        }   // esc
                                      });
                                    });
                                }
                              </script>

                          </td>

                      <?php endforeach; ?>
                  </tr>
                  <tr>
                      <td><b>Цена:</b></td>
                      <?
                      foreach ($products_compare as $productInfo):
                          $product = ProductTable::getInstance()->findOneById($productInfo);
                          ?>
                          <td >
                              <? if ($product->getBonuspay() > 0) { ?>
                                  <span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= number_format($product->getPrice(), 0, '', ' ') ?> р.</span><span style="font-size: 18px; color: #c3060e; margin-right: 10px;"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> р.</span>
                              <? } elseif ($product->getDiscount() > 0) { ?>
                                  <span><?= $product->getOldPrice() ?> р.</span><span style="font-size: 18px; color: #c3060e; margin-right: 10px;"><?= $product->getPrice() ?> р.</span>
                              <? } else { ?>
                                  <span><?= $product->getPrice() ?> р.</span>

                              <? } ?>

                          </td>

                      <?php endforeach; ?>
                  </tr>
                  <tr>
                      <td><b>Акция:</b></td>
                      <?
                      foreach ($products_compare as $productInfo):
                          $product = ProductTable::getInstance()->findOneById($productInfo);
                          ?>
                          <td>
                              <?php if ($product->getDiscount() > 0) { ?>
                                  Лучшая цена
                              <? } elseif ($product->getBonuspay() > 0) { ?>
                                  Управляй ценой!
                              <? } ?></td>

                      <?php endforeach; ?>
                  </tr>
              <td><b>Категория:</b></td>
              <?
              foreach ($products_compare as $productInfo):
                  $product = ProductTable::getInstance()->findOneById($productInfo);
                  $generalCategory = $product->getGeneralCategory();
                  if ($generalCategory->getParent()) {
                      $categoryParents = $generalCategory->getParent();
                  }
                  ?>
                  <td>
                      <?php
                      if ($categoryParents) {
                          echo $categoryParents->getName() . "<br />";
                      }
                      if ($generalCategory) {
                          echo $generalCategory->getName();
                      }
                      ?></td>

              <?php endforeach; ?>
              </tr><?
              foreach ($DopInfoCategory as $keyNumCategory => $category):
                  if ($category['namecompare'] != $oldCategoryPrint and $category['namecompare'] != "") {
                      $oldCategoryPrint = $category['namecompare'];
                      ?></tbody></table>
                  <div onclick="CollapseExpand(this,<?= $keyNumCategory ?>)">
                      <div class="blockDescriptionContentProductTurnButtom" data-minus="1">-</div>
                      <div class="blockDescriptionContentProductTurnText"><?= $category['namecompare'] ?></div>

                  </div>
                  <table class="tableCompare" id="tableCompare<?= $keyNumCategory ?>">
                      <tbody>
                          <?
                      }
                      ?>
                      <tr>
                          <td><b><?= $category['name'] ?>:</b></td>
                          <?
                          foreach ($products_compare as $productInfo):
                              ?>
                              <td style="text-align: center; width:188px;"><?
                                  foreach ($arrayDopInfo[$productInfo][$category['name']] as $param) {
                                      echo $param . "<br />";
                                  }
                                  ?>
                                  <? /* = $arrayDopInfo[$productInfo][$category['name']] */ ?>
                              </td>

                          <?php endforeach; ?>
                      </tr>
                  <?php endforeach; ?>
              </tbody></table>
          <div onclick="CollapseExpand(this, 'rate')">
              <div class="blockDescriptionContentProductTurnButtom" data-minus="1">-</div>
              <div class="blockDescriptionContentProductTurnText" >Рейтинг и отзывы</div>

          </div>
          <table class="tableCompare"id="tableComparerate">
              <tbody>
                  <tr>
                      <td><b>Рейтинг:</b></td>
                      <?
                      foreach ($products_compare as $productInfo):
                          $product = ProductTable::getInstance()->findOneById($productInfo);
                          ?>
                          <td>
                              <div class="stars">
                                  <span style="left: 0;width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
                              </div>

                          <?php endforeach; ?>
                  </tr>

                  <tr>
                      <td ><b>Отзывы:</b></td>
                      <?
                      foreach ($products_compare as $productInfo):
                          $comments = Doctrine_Core::getTable('Comments')
                                  ->createQuery('c')
                                  ->where("is_public = '1'")
                                  ->addWhere('product_id = ?', $productInfo)
                                  ->orderBy('id ASC')
                                  ->execute();
                          ?>
                          <td>
                              <? if ($comments->count() > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comments->count() ?></a><? } ?>
                          </td>

                      <?php endforeach; ?>
                  </tr>
              </tbody>
          </table>
      </div>
  <?php else: ?>
      У вас нет товаров для сравнения
  <?php endif; ?>
</main>
