function addToCartAnim(action, idName){


//  летающая картинка
  var cart = $('.login');
if(!idName.length)
	idName='#photoimg';
  var src = $(idName);
  var target = (action == 'Jel' ? cart.find('a#compareBlock') : cart.find('.backetWrap'));

  if(src.length){
    var from = src.offset();
    var to = target.offset();
    var flyImg = src.clone().appendTo('body').css({
      position: 'absolute',
      zIndex:'9999',
      top: from.top,
      left: from.left
    });
    
    // определяем текущую прокрутку экрана
    var scrollInit = $(document).scrollTop();
    var animateElem = $.browser.safari ? 'body' : 'html';
    
    if(scrollInit > to.top){
      $(animateElem).animate({scrollTop: 0}, 'fast', function(){
      
        flyImg.animate(
          {top: to.top, left: to.left+50, width: '100px'},
          function(){
            $(this).remove();
            //setTimeout(function(){$(animateElem).animate({scrollTop: scrollInit}, 'fast')}, 400);
          }
        );
      
      });
    }
    else{
    
      flyImg.animate(
        {top: to.top, left: to.left+50, width: '50px'},
        function(){
          $(this).remove();
         // setTimeout(function(){$(animateElem).animate({scrollTop: scrollInit}, 'fast')}, 400);
        }
      );
    
    }
  
  
  }

// Затемнение фона
//$("body").fadeOut();
$("#TextAddProductToCard").fadeIn(); //эфект jQuery который делает плавное появление div'a с идентификатором svet (id="svet").
$("#BackgrounAddProductToCard").fadeIn(); //эфект jQuery который делает плавное появление div'a с идентификатором svet (id="svet").
    setTimeout(function(){$("#TextAddProductToCard").fadeOut(); $("#BackgrounAddProductToCard").fadeOut();},1000);
}
