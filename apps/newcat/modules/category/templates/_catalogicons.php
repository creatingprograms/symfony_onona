<? if (sizeof($icons)) : ?>
  <?
    $isDebug=isset($_GET['debug']);
    // $isDebug=true;
    if($isDebug)
      echo '<pre>'.
        print_r([
          'icons'=>$icons,
          'sql'=> $sql,
        ], true)
        .'</pre>';
  ?>

  <p><span style="font-size:18px;"><span style="color: rgb(255, 91, 0);">Категории раздела</span></span></p>
  <div class="catalog-wpapper new">
    <? foreach ($icons as  $icon) :?>
      <a class="catalog-element" href="/category/<?=mb_strtolower($icon['slug'], 'utf-8')?>">
        <img alt="<?=$icon['name']?> в интим магазине Он и Она" src="/uploads/catalog_icons/<?=$icon['img']?>" /> <?=$icon['icon_name'] ? $icon['icon_name'] : $icon['name']?>
      </a>
    <? endforeach?>
  </div>
<? else :?>
  <? // возможно это как-то нужно обрабатывать?>
<? endif ?>
