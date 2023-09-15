<?
//print_r($filtersProbe);
$category = CategoryTable::getInstance()->findOneBySlug($sf_request->getParameter('slug'));
$filters = unserialize($category->get('filters'));
$filtersToSend = array();
foreach ($filters as $diId => $filter) {
    foreach ($filter as $filterParam) {
        if ($diId != 22 or true) {
            if (isset($filtersProbe[$diId])) {
                // print_r($filtersProbe[$diId]);
                if (@$filtersProbe[$diId][$filterParam] != "") {
                    $countFilter = (int) $filtersProbe[$diId][$filterParam];
                } else {
                    $countFilter = 0;
                }
                $filtersToSend[$diId][$filterParam] = $countFilter;
            }
        }
    }
}
//print_r($filtersToSend);
echo json_encode($filtersToSend);
exit;
?>
<script>
    $(document).ready(function() {
        var filter = jQuery.parseJSON( '<?= json_encode($filtersToSend); ?>');

        for(did in filter){
            for(valuefilter in filter[did]){
                countprod=filter[did][valuefilter];
                $("#params"+did+" input[value='"+valuefilter+"']").parent().children("div").text(" - "+valuefilter+" ("+countprod+")");
                console.log(valuefilter);
                console.log(filter[did][valuefilter]);
            }
        }

    });
</script>

<div id="filters">
    <script src="/js/jquery.ui-slider.js"></script>
    <script>
        // wait for the DOM to be loaded
        $(document).ready(function() {
            // bind 'myForm' and provide a simple callback function
            $('#categoryFilters').ajaxForm(function(result) {
                $("#content").html(result);
                initPopup();
                viravnivanie()
                /*$("div.c div.notcount").hover(function(){
                    $(this).stop().animate({"opacity": 1});
                },function(){
                    $(this).stop().animate({"opacity": 0.3});
                });*/
<?
if ($this->getModuleName() == "category" or get_slot('sexshop') or true) {
    $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
//print_r($products_old);
    if (is_array($products_old))
        foreach ($products_old as $key => $product) {
            echo "changeButtonToGreen(" . $product["productId"] . ");";
        }
//changeButtonToGreen(id)
}
?>
            $("img.thumbnails").lazyload();
            //console.log(result);
        });
    });
    </script>
    <form action="/category/filters/<?= $sf_request->getParameter('slug') ?>" id="categoryFilters" method="POST">
        <input type="hidden" name="page" value="1" id="pageFromFilter"><?php
$category = CategoryTable::getInstance()->findOneBySlug($sf_request->getParameter('slug'));
$filters = unserialize($category->get('filters'));
/* Фильтр по цене */
$categoryChildrens = $category->getChildren();
$idChildrenCategory = implode(',', $categoryChildrens->getPrimaryKeys());
if ($idChildrenCategory != "")
    $idCategorysForQuery = $category->getId() . "," . $idChildrenCategory;
else
    $idCategorysForQuery = $category->getId();
$rangePrice = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("max(price) as maxPrice")->addSelect("min(price) as minPrice")
                ->leftJoin('p.CategoryProduct c')
                ->addWhere('parents_id IS NULL')
                ->addWhere("(c.category_id IN (" . $idCategorysForQuery . "))")
                ->addWhere('is_public = \'1\'')
                ->fetchOne()->toArray();
//print_r($rangePrice);
$minPrice = $rangePrice['minPrice'];
$maxPrice = $rangePrice['maxPrice'];
//echo "<b>Цена</b>:<br/>";

echo "<div class=\"paramFilter\" style=\"margin-top: 3px;position: relative;\"><div class=\"headerFilter\">Цена<img src=\"/images/filters/silver-str-bottom.png\" style=\"position: absolute; right: 10px; top: 10px;\" onclick=\"$('#paramsPrice').toggle();if($(this).attr('src')=='/images/filters/silver-str-bottom.png'){ $(this).attr('src','/images/filters/red-str-right.png');}else{ $(this).attr('src','/images/filters/silver-str-bottom.png');}\"></div>";
echo '<div id="paramsPrice" class="params">
                            от<input type="text" class="textParam" id="minCostPrice" value="' . ((float) $minPrice) . '" name="Price[from]" style="width: 57px;margin-right: 0px;"/> до<input type="text" class="textParam" id="maxCostPrice" value="' . ((float) $maxPrice) . '" name="Price[to]" style="width: 57px;margin-right: 0px;"/> <span style="font-family: \'PT Sans Narrow\', sans-serif;">&#8381;</span>
<input type="hidden" id="rangeChangePrice" value="0" name="Price[rangeChange]"/>
<div id="sliderPrice" class="filter-slider"></div>
                                <script type="text/javascript">
jQuery("#sliderPrice").slider({
	min: ' . ((float) $minPrice) . ',
        animate: true,
	max: ' . ((float) $maxPrice) . ',
            step: 0.1,
	values: [' . ((float) $minPrice) . ',' . ((float) $maxPrice) . '],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCostPrice").val(jQuery("#sliderPrice").slider("values",0));
		jQuery("input#maxCostPrice").val(jQuery("#sliderPrice").slider("values",1));
                                        if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}
 jQuery("#categoryFilters").submit();
    },
    slide: function(event, ui){
		jQuery("input#minCostPrice").val(jQuery("#sliderPrice").slider("values",0));
		jQuery("input#maxCostPrice").val(jQuery("#sliderPrice").slider("values",1));
                    if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}

    }

});
jQuery("input#minCostPrice").change(function(){
	var value1=jQuery("input#minCostPrice").val();
	var value2=jQuery("input#maxCostPrice").val();

    if(parseFloat(value1) > parseFloat(value2)){
		value1 = value2;
		jQuery("input#minCostPrice").val(value1);
	}
    if(parseFloat(value1) < parseFloat(' . ((float) $minPrice) . ')){
		value1 = ' . ((float) $minPrice) . ';
		jQuery("input#minCostPrice").val(value1);
	}
	jQuery("#sliderPrice").slider("values",0,value1);
             if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}
 jQuery("#categoryFilters").submit();
});


jQuery("input#maxCostPrice").change(function(){
	var value1=jQuery("input#minCostPrice").val();
	var value2=jQuery("input#maxCostPrice").val();

	if (parseFloat(value2) > parseFloat(' . ((float) $maxPrice) . ')) { value2 = ' . ((float) $maxPrice) . '; jQuery("input#maxCostPrice").val(' . ((float) $maxPrice) . ')}

	if(parseFloat(value1) > parseFloat(value2)){
		value2 = value1;
		jQuery("input#maxCostPrice").val(value2);
	}
	jQuery("#sliderPrice").slider("values",1,value2);
             if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}
 jQuery("#categoryFilters").submit();
});

jQuery("input#minCostPrice").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;

		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;

		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);

		if(!/\d/.test(keyChar))	return false;

	});

jQuery("input#maxCostPrice").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;

		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;

		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);

		if(!/\d/.test(keyChar))	return false;

	});

</script></div></div>
';
foreach ($filters as $diId => $filter) {
    $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($diId);
    echo "<div class=\"paramFilter\"  style=\"position: relative;\"><div class=\"headerFilter\">" . $dopInfoCategory . "<span id=\"clearparams" . $diId . "\" style=\"display: none;   top: 7px;color: #C3060E; position: absolute; right: 30px; font-size: 10px; cursor: pointer; \" onClick=\"clearParams" . $diId . "()\"><u>Сбросить</u></span> <img src=\"/images/filters/silver-str-bottom.png\" style=\"position: absolute; right: 10px; top: 10px;\" onclick=\"$('#paramsForHide" . $diId . "').toggle();if($(this).attr('src')=='/images/filters/silver-str-bottom.png'){ $(this).attr('src','/images/filters/red-str-right.png');}else{ $(this).attr('src','/images/filters/silver-str-bottom.png');}\"></div><div  id=\"paramsForHide" . $diId . "\">";
    natsort($filter);
    if ((float) str_replace(",", ".", $filter[0]) > 0 and (float) end($filter) > 0) {
        $i = 0;
        $filterNew = array();
        foreach ($filter as $filtersNew) {
            if (substr_count($filtersNew, 'x') == 0 and substr_count($filtersNew, 'х') == 0) {
                $filterNew[$i] = $filtersNew;
                $i++;
            }
        }
        $filter = $filterNew;
    }
    /*   if ($dopInfoCategory->getName() == "Цвет") {
      foreach ($filter as $filterParam) {
      echo '<input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams"> - ' . $filterParam . '<br />';

      }
      } else if ($dopInfoCategory->getName() == "Длина") { */
    if ((float) str_replace(",", ".", $filter[0]) > 0 and (float) end($filter) > 0 and $dopInfoCategory->getName() != "Размер") {


        echo '<div id="params' . $diId . '" class="params">
                <script>
                 //
                 // Функция кнопки сбросить
                 //
             function clearParams' . $diId . '(){
jQuery("#slider' . $diId . '").slider({
	min: ' . ((float) str_replace(",", ".", $filter[0])) . ',
        animate: true,
	max: ' . ((float) str_replace(",", ".", end($filter))) . ',
            step: 0.1,
	values: [' . ((float) str_replace(",", ".", $filter[0])) . ',' . ((float) str_replace(",", ".", end($filter))) . '],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                                        if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();

}
 jQuery("#categoryFilters").submit();
    },
    slide: function(event, ui){
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                    if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}

    }

});
		jQuery("input#minCost' . $diId . '").val(' . ((float) str_replace(",", ".", $filter[0])) . ');
		jQuery("input#maxCost' . $diId . '").val(' . ((float) str_replace(",", ".", end($filter))) . ');
$("#clearparams' . $diId . '").fadeOut();
  jQuery("input#rangeChange' . $diId . '").val(0);


 jQuery("#categoryFilters").submit();
}

</script>
                            от<input type="text" class="textParam" id="minCost' . $diId . '" value="' . ((float) str_replace(",", ".", $filter[0])) . '" name="' . $diId . '[from]" style="width: 57px;margin-right: 0px;"/> до<input type="text" class="textParam" id="maxCost' . $diId . '" value="' . ((float) str_replace(",", ".", end($filter))) . '" name="' . $diId . '[to]" style="width: 57px;margin-right: 0px;"/>' . (preg_replace("/[^a-zA-ZА-Яа-я\s]/u", "", $filter[0])) . '
<input type="hidden" id="rangeChange' . $diId . '" value="0" name="' . $diId . '[rangeChange]"/>
<div id="slider' . $diId . '" class="filter-slider"></div>
                                <script type="text/javascript">


                 //
                 // Создание слайдера
                 //
jQuery("#slider' . $diId . '").slider({
	min: ' . ((float) str_replace(",", ".", $filter[0])) . ',
        animate: true,
	max: ' . ((float) str_replace(",", ".", end($filter))) . ',
            step: 0.1,
	values: [' . ((float) str_replace(",", ".", $filter[0])) . ',' . ((float) str_replace(",", ".", end($filter))) . '],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                                        if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();

}
 jQuery("#categoryFilters").submit();
    },
    slide: function(event, ui){
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                    if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}

    }

});


                 //
                 // Изменение в форме
                 //
jQuery("input#minCost' . $diId . '").change(function(){
	var value1=jQuery("input#minCost' . $diId . '").val();
	var value2=jQuery("input#maxCost' . $diId . '").val();

    if(parseFloat(value1) > parseFloat(value2)){
		value1 = value2;
		jQuery("input#minCost' . $diId . '").val(value1);
	}
    if(parseFloat(value1) < parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ')){
		value1 = ' . ((float) str_replace(",", ".", $filter[0])) . ';
		jQuery("input#minCost' . $diId . '").val(value1);
	}
	jQuery("#slider' . $diId . '").slider("values",0,value1);
             if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}
 jQuery("#categoryFilters").submit();
});


jQuery("input#maxCost' . $diId . '").change(function(){
	var value1=jQuery("input#minCost' . $diId . '").val();
	var value2=jQuery("input#maxCost' . $diId . '").val();

	if (parseFloat(value2) > parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')) { value2 = ' . ((float) str_replace(",", ".", end($filter))) . '; jQuery("input#maxCost' . $diId . '").val(' . ((float) str_replace(",", ".", end($filter))) . ')}

	if(parseFloat(value1) > parseFloat(value2)){
		value2 = value1;
		jQuery("input#maxCost' . $diId . '").val(value2);
	}
	jQuery("#slider' . $diId . '").slider("values",1,value2);
             if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}
 jQuery("#categoryFilters").submit();
});


                 //
                 // Проверка формы
                 //
jQuery("input#minCost' . $diId . '").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;

		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;

		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);

		if(!/\d/.test(keyChar))	return false;

	});

jQuery("input#maxCost' . $diId . '").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;

		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;

		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);

		if(!/\d/.test(keyChar))	return false;

	});

</script>
';
    } else {
        echo "
             <script>
             function issetParams" . $diId . "(){
                 var checkedParams=false;
$('div#params" . $diId . " div').each(function(i,elem) {
if($(elem).children('input').prop(\"checked\")){
checkedParams=true;
$(elem).css('color', '#C3060E');
}else{
$(elem).css('color', '#424141');
}
});
if(checkedParams){
$('#clearparams" . $diId . "').fadeIn();
    }else{
$('#clearparams" . $diId . "').fadeOut();
    }

                 }

             function clearParams" . $diId . "(){
$('div#params" . $diId . " div').each(function(i,elem) {
    $(elem).children('input').removeAttr(\"checked\");
$(elem).css('color', '#424141');
$(elem).children('img').removeClass('colorFilterButton-select').addClass('colorFilterButton');
});
$('#clearparams" . $diId . "').fadeOut();
 jQuery('#categoryFilters').submit();

                 }
             </script>

<input tupe=\"text\" id=\"textParam" . $diId . "\" class=\"textParam\" onkeyup=\"

$('div#params" . $diId . " div').each(function(i,elem) {
     if ($(elem).text().toLowerCase().replace('ё', 'е').search($('#textParam" . $diId . "').val().toLowerCase().replace('ё', 'е'))!=-1) {

$(elem).css('display', 'block')	;
 } else {
$(elem).css('display', 'none')	;
 }


});
\"><div id=\"params" . $diId . "\" class=\"params\"" . ($dopInfoCategory->getName() == "Цвет" ? 'style="padding: 3px;"' : '') . ">";
        foreach ($filter as $filterParam) {
            if ($dopInfoCategory->getName() == "Цвет") {
                $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($diId);
                $dopInfoCategoryFull = DopInfoCategoryFullTable::getInstance()->createQuery()->where("name=?", $filterParam)->addWhere("id in (" . implode(',', $dopInfoCategory->getCategory()->getPrimaryKeys()) . ")")->fetchOne();

                echo '<div style="display: inline-block;margin: 5px;"><input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams" onChange="jQuery(\'#categoryFilters\').submit();issetParams' . $diId . '();" style="opacity:0; position:absolute; left:9999px;" id="' . $diId . $filterParam . '"><img src="/uploads/dopinfo/thumbnails/' . ($dopInfoCategoryFull->getFilename()) . '" alt="' . $filterParam . '" title="' . $filterParam . '" class="colorFilterButton" width="28" onClick="console.log($(this).prev(\'input\')); if ( $(this).hasClass(\'colorFilterButton\') ) {$(this).removeClass(\'colorFilterButton\').addClass(\'colorFilterButton-select\');$(this).prev(\'input\').attr(\'checked\', \'checked\');}else{$(this).removeClass(\'colorFilterButton-select\').addClass(\'colorFilterButton\');$(this).prev(\'input\').removeAttr(\'checked\');} jQuery(\'#categoryFilters\').submit();issetParams' . $diId . '();" style="cursor: pointer;"></div>';
            } else {
                // print_r($filtersProbe[$diId]);
                echo '<div><input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams" onChange="jQuery(\'#categoryFilters\').submit();issetParams' . $diId . '();"><div style="display: inline;"> - ' . $filterParam . '</div></div>';
            }
        }
    }
    /*  }  else {
      foreach ($filter as $filterParam) {

      echo '<input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams"> - ' . $filterParam . '<br />';
      }
      } */
    echo "</div></div></div>";
}
//print_r($filters);
//echo phpinfo();
// include_component('category', 'catalogDev', array('slug' => $sf_request->getParameter('slug'), 'slot' => get_slot('category_slug')));
?></form>
</div>
