<? if(sizeof($params)) foreach ($params as $key => $paramBlock):?>
  <div class="product-card-color-block">
    <div class="product-card-color-title">
      <?= $key ?>:
    </div>
    <div class="product-card-color-label-block">
      <?php foreach ($paramBlock as $param): ?>
        <? if($key=='Цвет' && $param['filename']) $isColor=true; else $isColor=false; ?>
        <?//= ('<pre>'.print_r($param, true));?>
        <?
          if ($isColor){
            $image='/uploads/dopinfo/thumbnails/'.$param['filename'];//Ищем иконку
            if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)){
              $image='/uploads/dopinfo/'.$param['filename'];//берем полное
              if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)) $isColor=false; //значит иконки нет
            }
          }
        ?>
        <? if($param['is_public']) :?>
          <?/*<!-- Цвета вставлять через "style='background-color'" -->*/?>
          <a href="/product/<?=$param['prod_slug']?>"
             title="<?=$param['value'] ?>"
             class="<?=$param['prod_slug']==$slug ? 'active' : ''?> <?=$isColor ? 'product-card-color-label' : 'product-card-sku'?>"
             <?= $isColor ? 'style="background:url('.$image.') 50%;"' : ''?>
          >
             <?= $isColor ? '' : $param['value'] ?>
          </a>
        <? endif ?>
        <? /*if($param['prod_slug']==$slug)*/ $squKeys[$key][$param['value']]=[
          'link' => "/product/".$param['prod_slug'],
          'active' => $param['prod_slug']==$slug,
          'value'=>$param['value']
        ]; ?>
      <?php endforeach; ?>
    </div>
  </div>
<? endforeach ?>
<? if (!isset($squKeys)) $squKeys[0]=''; ?>
<? slot('squ', $squKeys); ?>
