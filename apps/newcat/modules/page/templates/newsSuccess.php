<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', $news->getName());
slot('metaKeywords', $news->getName());
slot('metaDescription', $news->getName());
    slot('rightBlock', true);
?>

<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a></li>
    <li>
        <?php echo $news->getName() ?></li>
</ul>

<div class="info-box adaptive">
    <div style="width:100%; text-align: center;"><h1 class="title"><?php echo $news->getName() ?></h1></div></div>

<?php
    echo $news->getContent();
?>
