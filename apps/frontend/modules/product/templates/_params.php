<?//='<pre>'.print_r($newParams, true).'</pre>';?>
<?
  $arNeedToBlur=[
    'PD3882-24',
    '984002',
    'SE-2712-55-3',
    '99088',
    'Bai-Bl-01-4084',
    'PD3662-21',
    'PD3384-21',
    'PD3956-23',
    'PD4429-23',
    '5214340000',
    'LT70003',
    'PD3377-21',
    'PD3662-21',
    'PD3376-21',
    'PD3374-29',
    'PD3373-21',
    'Bai-BW-022037-1002',
    'PD3373-21',
    '964001',
    '5227320000',
    '117015',
    'SE-1233-03-2',
    'DJ0284-01CD',
    '5834640000',
    '5781690000',
    '5215070000',
    'XRTF1373',
    'XRTF1932',
    '5847380000',
    '5037970000',
    'GP-2K237BX',
    '5126560000',
    'SE-6909-01-2',
    'SE-6870-20-3',
    'SE-0387-14-2',
    'SE-1233-04-2',
    'XRTF3710',
    'XRTF3907',
    '5131480000',
    'GP-7199PMB',
    'SE-1233-14-2',
    'SE-6875-00-3',
    '702002',
    '701003',
    '708006',
    '5276450000',
    '701006',
    '708005',
    '704003',
    '704010',
    '704007',
    '702005',
  ];

  $i=0;
  $oldName='';
  $productColor=$productProps=$productManuf=$productMater='';
  $squKeys=get_slot('squ');

  if(in_array($product->getCode(), $arNeedToBlur)) slot('is-blur', true);
?>

<? if(sizeof($newParams)):?>
  <div class="product-card-param">
    <table id="productCardChar">
      <?php foreach ($newParams as $key=>$property): ?>
        <tr>
          <td>
            <?= $key?>
          </td>
          <td>
          <?php foreach ($property as $propertyLine): ?>
            <? if(isset($squ[$key]) && $propertyLine['value']!=$squ[$key]) continue; ?>
            <?php
              if ($key == "Таблица размеров" and $propertyLine['value'] == 1){
                slot('tableSize', true);
                continue;
              }
              if ($key == 'Цвет') $productColor.=' '.$propertyLine['value'];
              if ($key == 'Материал') $productMater.=' '.$propertyLine['value'];
              if ($key == "Производитель") $productManuf.=' '.$propertyLine['value'];
              if ($key == 'Свойство' || $key == 'Особенность') $productProps.=' '.$propertyLine['value'];

            ?>
                <?php if (isset($propertyLine['url'])): ?>
                  <p><a href="<?=$propertyLine['url']?>"><?=$propertyLine['value']?></a></p>
                  <?
                    if(in_array($propertyLine['url'],
                      [
                        // '/manufacturer/baci-lingerie-ssha',
                        '/manufacturer/baci-lingerie-white-collection-ssha',
                        '/manufacturer/baci-lingerie-black-label-ssha',
                        '/manufacturer/envy-ssha',
                        '/collection/vivid-raw',
                      ]
                    ))
                    slot('is-blur', true);
                    ?>
                <? else :?>
                  <p><?=/*$property['prod_slug'].'|'.$slug.'|'.*/$propertyLine['value']?></p>
                <?php endif ?>
          <?php endforeach ?>
        </td>
      </tr>
      <? $i++;?>
      <?php endforeach ?>
    </table>
    <? if($i > 7) :?>
      <div class="product-card-param-more">
        <a href="#productCardChar" class="allButJS" data-text="Показать все параметры" data-up="Скрыть параметры">
          <svg>
            <use xlink:href="#arrowMoreIcon" />
          </svg>
        </a>
      </div>
    <? endif ?>
  </div>
<? endif ?>
<?//Будут нужны для составления продуктовых мет по умолчанию
  if($productColor!='') slot('product_color',  $productColor);
  if($productMater!='') slot('product_material',  $productMater);
  if($productManuf!='') slot('product_manufacturer',  $productManuf);
  if($productProps!='') slot('product_property',  $productProps);
?>
