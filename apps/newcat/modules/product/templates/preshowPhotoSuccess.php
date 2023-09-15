<?
$photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
/* ХЗ зачем это здесь было
$photosUser = PhotosUserTable::getInstance()->createQuery()->where("product_id=" . $product->getId())->addWhere("is_public = '1'")->execute();
if ($product->getParent() != "")
    $productProp = $product->getParent();
else
    $productProp = $product;
$i = 0;
$childrens = $productProp->getChildren();
$childrensId = $childrens->getPrimaryKeys();
$childrensId[] = $productProp->getId();
//print_r()

$comments = Doctrine_Core::getTable('Comments')
        ->createQuery('c')
        ->where("is_public = '1'")
        ->addWhere('product_id in (' . implode(',', $childrensId) . ')')
        ->orderBy('created_at desc')
        ->execute();
$count_comm = $comments->count();
*/
?>
<div class="album-preview-wrapper">
  <script type="text/javascript" src="/js/jquery.lbslider.js"></script>
  <div class="album-preview">
    <div class="centerProductPreShow">
      <div onClick="$('.blockPreShow').remove();" class='close'></div>
      <table class="tablePreShow">
        <tbody>
          <tr>
            <td>
              <div class="img-holder">
                <a href="/product/<?= $product->getSlug() ?>">
                  <img id="photoimg_pr_<?= $product->getId() ?>" src="/uploads/photo/<?= $photos[0]['filename'] ?>" alt="image description" class="thumbnails" />
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <? if ($photos->count() > 4) { ?>
                  <script>
                    $(document).ready(function () {
                      $('.SliderProductPreShow').lbSlider({
                        leftBtn: '.sa-left',
                        rightBtn: '.sa-right',
                        visible: 4,
                        autoPlay: false,
                        autoPlayDelay: 5,
                        cyclically: false
                      });
                    });
                  </script>
                  <div class="SliderProductPreShow">
                    <ul class="photosPreShow">
                      <? foreach ($photos as $numPhotos => $photo) { ?>
                        <li>
                          <span<?= !$numPhotos ? ' class="activePhotosPreShow"' : ''?>>
                            <img src="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>"
                              onclick='$(".photosPreShow li span").each(function (i) {
                                          $(this).removeClass("activePhotosPreShow")
                                        });
                                        $(this).parent().addClass("activePhotosPreShow");
                                        $("#photoimg_pr_<?= $product->getId() ?>").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");'>
                          </span>
                        </li>
                        <? } ?>
                      </ul>
                  </div>
                  <a href="#" class="slider-arrow sa-left"></a>
                  <a href="#" class="slider-arrow sa-right"></a>
              <? }
              else { ?>
                <div class="SliderProductPreShow">
                  <ul class="photosPreShow">
                    <? foreach ($photos as $numPhotos => $photo) { ?>
                      <li>
                        <span<?= !$numPhotos ? ' class="activePhotosPreShow"' : ''?>>
                          <img src="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>"
                            onclick='$(".photosPreShow li span").each(function (i) {
                                        $(this).removeClass("activePhotosPreShow")
                                     });
                                     $(this).parent().addClass("activePhotosPreShow");
                                     $("#photoimg_pr_<?= $product->getId() ?>").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");'>
                        </span>
                      </li>
                    <? } ?>
                  </ul>
                </div>
              <? } ?>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
</div>
