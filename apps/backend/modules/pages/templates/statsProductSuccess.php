<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 

<form enctype="multipart/form-data" action="" method="post">
    Категория: <select name='category' style="
    width: 272px;
">
        <option value=""></option>
        <?foreach($categorys as $category['id']=> $category){
            ?><option value="<?=$category['id']?>"><?=$category['name']?> (<?=@is_array($countProductOnCategory[$category['id']])?$countProductOnCategory[$category['id']]['count']:'0'?>)</option><?
        }
        ?>
        
    </select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    Производитель: <select name='manufacturer'>
        <option value=""></option>
        <?foreach($manufacturers as $manufacturer){
            ?><option value="<?=$manufacturer['subid']?>"><?=$manufacturer['name']?></option><?
        }
        ?>
        
    </select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    Коллекция: <select name='collection'>
        <option value=""></option>
        <?foreach($collections as $collection){
            ?><option value="<?=$collection['subid']?>"><?=$collection['name']?></option><?
        }
        ?>
        
    </select>
    <br />
    <br />
    <input type="submit" name="query" value="Все отсутствующие товары"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="submit" name="query" value="Все скрытые товары"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="submit" name="query" value="Все товары которые в наличии"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="submit" name="query" value="Все товары Управляй ценой"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="submit" name="query" value="Все товары Лучшей цены">
    </form>