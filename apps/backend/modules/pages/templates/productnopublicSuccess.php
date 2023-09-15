<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 

<? foreach ($products as $product) {
    ?>
    <?=$product['code']?> <a href="https://onona.ru/product/<?=$product['slug']?>">https://onona.ru/product/<?=$product['slug']?></a><br />
    <?

}
?>
        