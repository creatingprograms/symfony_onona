<?
  // $squKeys=get_slot('squ');
  $i=0;
  $oldName='';
  $productColor=$productProps=$productManuf=$productMater='';
  $squKeys=get_slot('squ');
  $videoUrl=$product->getVideo();
  $videoUrlArr=explode('.', $videoUrl);

  if($videoUrlArr[1] == 'webm') $videoUrlArr[1] = 'mp4';
  else $videoUrlArr[1] = 'webm';

  if($videoUrl) $videoUrl='/uploads/video/'.$videoUrl;
  if(!file_exists($_SERVER['DOCUMENT_ROOT'].$videoUrl)) $videoUrl=false;

  if(!$videoUrl && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/video/'.implode('.', $videoUrlArr))) $videoUrl = '/uploads/video/'.implode('.', $videoUrlArr);
  $countryFrom = '';
?>
<?/*='<div style="display: none;">'.print_r($squKeys, true);
var_dump([$videoUrl,$videoUrlArr]);
echo '</div>';*/?>
<? if(sizeof($newParams)):?>
  <div class="specifications__list">
    <?php foreach ($newParams as $key=>$property): ?>
      <? 
        $i=0;
        if($key == 'Страна происхождения'){
          $countryFrom = ', ' . end($property)['value'];
          continue;
        }
      ?>
      <div class="specifications__item">
        <span><?= $key ?></span>
        <span>
          <?php foreach ($property as $propertyLine): ?>
            <?
              if ($key == "Таблица размеров" and $propertyLine['value'] == 1){
                slot('tableSize', true);
                continue;
              }
              if ($key == 'Цвет') $productColor.=' '.$propertyLine['value'];
              if ($key == 'Материал') $productMater.=' '.$propertyLine['value'];
              if ($key == "Производитель" || $key == "Бренд(ы)" || $key == "Бренд") {
                $productManuf.=' '.$propertyLine['value'];
                $propertyLine['value'] .= $countryFrom;
              }
              if ($key == 'Свойство' || $key == 'Особенность') $productProps.=' '.$propertyLine['value'];
              if (isset($squKeys[$key][$propertyLine['value']])) //Если это часть списка - проставляем ссылку
                $propertyLine['url'] = $squKeys[$key][$propertyLine['value']]['link'];
            ?>
            <?= $i++ ? '<br>' : '' ?>
            <?php if (isset($propertyLine['url'])): ?>
              <a <?=(isset($squKeys[$key][$propertyLine['value']]) && $squKeys[$key][$propertyLine['value']]['active']) ? 'style="color: red;"' : ''?> href="<?=mb_strtolower($propertyLine['url'])?>"><?=$propertyLine['value']?></a>
            <? else :?>
              <?=$propertyLine['value']?>
            <?php endif ?>
            <?if (!empty($propertyLine['description'])) :?>
              <span class="js-description specifications__item--description" data-description="<?= $propertyLine['description']?>">?</span>
            <? endif ?>
          <? endforeach ?>
        </span>
      </div>
    <? endforeach ?>
    <?php if ($videoUrl): ?>
      <div class="specifications__item specifications__item--no_border">
        <a class="inlinePopupJS video-review" href="#video-review">Видеообзор</a>
      </div>
      <div id="video-review" class="video-popup-wrapper popup mfp-hide">
        <div class="video-container">
          <video controls="controls">
            <source src="<?=$videoUrl?>">
          </video>
        </div>
      </div>
    <?php endif; ?>
  </div>
<? endif ?>

<?//Будут нужны для составления продуктовых мет по умолчанию
  if($productColor!='') slot('product_color',  $productColor);
  if($productMater!='') slot('product_material',  $productMater);
  if($productManuf!='') slot('product_manufacturer',  $productManuf);
  if($productProps!='') slot('product_property',  $productProps);
?>
<style>.video-container video{max-height: 400px;}</style>
