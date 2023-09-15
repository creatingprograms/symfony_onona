// JavaScript Document
var isLeft = true;
var isAppend = false;
var isMove = false;
var isRight = false;
var interval;
jQuery(document).ready(function(){
	
	//левое меню
	$(".categoria ol span").click(function(){
		if($(this).next().css("display")=="none"){
			$(this).next().slideDown(300);
			$(this).css("background-position","0 10px")
		}
		else {
			$(this).next().slideUp(300);
			$(this).css("background-position","0 -71px")
		}
	});
	
	$('#showForm').click(function(){
		$('#hiden').fadeIn(300);
		return false;
	});
	$('#close').click(function(){
		$('#hiden').fadeOut(300);
		return false;
	});
	
	$('#showFormReg').click(function(){
		$('#hidenReg').fadeIn(300);
                $("#BackgrounAddProductToCard").fadeIn();
		return false;
	});
	$('#closeReg').click(function(){
		$('#hidenReg').fadeOut(300);
                $("#BackgrounAddProductToCard").fadeOut();
		return false;
	});
        
        
	
	$('#showLK').click(function(){
		$('#hidenLK').fadeIn(300);
		return false;
	});
        $('#closeLK').click(function(){
		$('#hidenLK').fadeOut(300);
		return false;
	});
	
	if($('#scroller').is('div')){
        $('#scroller ul').css('height',$('#scroller li').length*280);
		moveGalery(-280);
    }
	
	$('#next').click(function(){
        if(!isMove) {
            clearInterval(interval);
            moveGalery(280);
		}
        return false;
    });
	$('.video a').click(function(){
		return false;
	});
    $('#prev').click(function(){
        if(!isMove){ 
            clearInterval(interval);
            moveGalery(-280);
        }
        return false;
    });
	jQuery(".niceRadio").each(
	/* при загрузке страницы меняем обычные на стильные radio */
	function() {
	 
		changeRadioStart(jQuery(this));
 
	});
});
function moveGalery(direction){
    isMove = true;
	var galery = $('#scroller ul');
	var position = galery.position();
	if (position.top==(-1*(galery.height()-560)) && direction < 0 ){
		var clon = $('#scroller li:first-child').clone();
		galery.css('height', galery.height()+280);
		galery.append(clon);
		clon=null;
		isAppend=true;
	}
	else if (!isRight && direction > 0 ){
		var clon = $('#scroller li:last-child').clone();
		$('#scroller li:last-child').remove();
		galery.css({"top": position.top-280});
		galery.prepend(clon);
		clon=null;

	}
	position = galery.position();
    galery.animate({top: position.top + direction}, 1000, 
        function(){
            position = galery.position();
            if (position.top==0) isRight=false;
            else isRight=true;
            if (position.top==(-1*(galery.height()-560))) isLeft=false;
            else isLeft=true;
			if (isAppend){
					$('#scroller li:first-child').remove();	
					galery.css({"top": position.top+280,"height": galery.height()-280});	
				isAppend=false;
			}
			isMove = false;
			interval = setTimeout(function(){moveGalery(direction);},5000);
        });  
	 
}



function changeRadio(el)
/* 
	функция смены вида и значения radio при клике на контейнер
*/
{

	var el = el,
		input = el.find("input").eq(0);
	var nm=input.attr("name");
		
	jQuery(".niceRadio input").each(
	
	function() {
     
		if(jQuery(this).attr("name")==nm)
		{
			jQuery(this).parent().removeClass("radioChecked");
		}
	   
	   
	});					  
	
	
	if(el.attr("class").indexOf("niceRadioDisabled")==-1)
	{	
		el.addClass("radioChecked");
		input.attr("checked", true);
	}
	
    return true;
}

function changeVisualRadio(input)
{
/*
	меняем вид radio при смене значения
*/
	var wrapInput = input.parent();
	var nm=input.attr("name");
		
	jQuery(".niceRadio input").each(
	
	function() {
     
		if(jQuery(this).attr("name")==nm)
		{
			jQuery(this).parent().removeClass("radioChecked");
		}
	   
	   
	});

	if(input.attr("checked")) 
	{
		wrapInput.addClass("radioChecked");
	}
}

function changeRadioStart(el)
/* 
	новый контрол выглядит так <span class="niceRadio"><input type="radio" name="[name radio]" id="[id radio]" [checked="checked"] /></span>
	новый контрол получает теже name, id и другие атрибуты что и были у обычного
*/
{

try
{
var el = el,
	radioName = el.attr("name"),
	radioId = el.attr("id"),
	radioChecked = el.attr("checked"),
	radioDisabled = el.attr("disabled"),
	radioTab = el.attr("tabindex"),
	radioValue = el.attr("value");
	if(radioChecked)
		el.after("<span class='niceRadio radioChecked'>"+
			"<input type='radio'"+
			"name='"+radioName+"'"+
			"id='"+radioId+"'"+
			"checked='"+radioChecked+"'"+
			"tabindex='"+radioTab+"'"+
			"value='"+radioValue+"' /></span>");
	else
		el.after("<span class='niceRadio'>"+
			"<input type='radio'"+
			"name='"+radioName+"'"+
			"id='"+radioId+"'"+
			"tabindex='"+radioTab+"'"+
			"value='"+radioValue+"' /></span>");
	
	/* если контрол disabled - добавляем соответсвующий класс для нужного вида и добавляем атрибут disabled для вложенного radio */		
	if(radioDisabled)
	{
		el.next().addClass("niceRadioDisabled");
		el.next().find("input").eq(0).attr("disabled","disabled");
	}
	
	/* цепляем обработчики стилизированным radio */		
	el.next().bind("mousedown", function(e) { changeRadio(jQuery(this)) });
	el.next().find("input").eq(0).bind("change", function(e) { changeVisualRadio(jQuery(this)) });
	if(jQuery.browser.msie)
	{
		el.next().find("input").eq(0).bind("click", function(e) { changeVisualRadio(jQuery(this)) });	
	}
	el.remove();
}
catch(e)
{
	// если ошибка, ничего не делаем
}

    return true;
}
