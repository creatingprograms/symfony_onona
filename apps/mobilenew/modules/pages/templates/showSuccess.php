<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', $page['title'] == '' ? $page['name'] : $page['title']);
slot('metaKeywords', $page['keywords'] == '' ? $page['name'] : $page['keywords']);
slot('metaDescription', $page['description'] == '' ? $page['name'] : $page['description']);
?>

<div class="info-box">
    <div style="width:100%; text-align: center;"><h1 class="title"><?php echo $page['name'] ?></h1></div></div>
<div id="pageShow"<?=$page['categorypage_id']=="1"?' class="shopPage"':''?>>
    <?php
    echo $page['content_mobile'] != "" ? $page['content_mobile'] : $page['content'];
    ?>
</div>