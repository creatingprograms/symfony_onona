function addToCartAnim(action, idName, product){


    //  летающая картинка
    var cart = $('.card-btn');
    /*if(!product.length)
        product=false;*/
    if(!idName.length)
        idName='#photoimg';
    var src = $(idName);
    var target = (action == 'Jel' ? $('.desire') : cart);

    if(src.length){
        var from = src.offset();
        var to = target.offset();

        //console.log(src);
        
        var animateElem = $.browser.safari ? 'body' : 'html';
        if(product){
            appendAddr='body';
            var flyImg = src.clone().appendTo(appendAddr).css({
                position: 'absolute',
                zIndex:'9999',
                top: from.top,
                left: from.left
            });
    
            //leftToEl=($(animateElem).width()/2)+300;
             leftToEl=to.left;
            TopToEl=to.top;
        }else{
            appendAddr='body .popup-holder .popup .item-box .img-holder a';
            var src = $('body .popup-holder .popup .item-box .img-holder a '+idName);
            //console.log(src);
            var from = src.offset();
            
            var flyImg = src.clone().appendTo(appendAddr).css({
                position: 'absolute',
                zIndex:'9999'
            //top: from.top,
            //left: from.left
            });
    
            leftToEl=200;
            TopToEl=100;
        }
        //console.log(to);
        // определяем текущую прокрутку экрана
        var scrollInit = $(document).scrollTop();
        //console.log(leftToEl);
        
        if(scrollInit > to.top){
            $(animateElem).animate({
                //scrollTop: 0
                }, 'fast', function(){
      
                    flyImg.animate(
                    {
                        top: TopToEl, 
                        left: leftToEl, 
                        width: '100px'
                    },
                    function(){
                        $(this).remove();
                    //setTimeout(function(){$(animateElem).animate({scrollTop: scrollInit}, 'fast')}, 400);
                    }
                    );
      
                });
        }
        else{
            

            flyImg.animate(
            {
                top: TopToEl, 
                left: leftToEl, 
                width: '50px'
            },
            function(){
                $(this).remove();
            // setTimeout(function(){$(animateElem).animate({scrollTop: scrollInit}, 'fast')}, 400);
            }
            );
    
        }
  
  
    }

    // Затемнение фона
    //$("body").fadeOut();
    $("#TextAddProductToCard").css('top',(document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight)/2);
    $("#TextAddProductToCard").fadeIn(); //эфект jQuery который делает плавное появление div'a с идентификатором svet (id="svet").
    $("#BackgrounAddProductToCard").fadeIn(); //эфект jQuery который делает плавное появление div'a с идентификатором svet (id="svet").
    setTimeout(function(){
        $("#TextAddProductToCard").fadeOut();
        $("#BackgrounAddProductToCard").fadeOut();
    },1000);
    

    setTimeout(function(){
       
            $(".card-btn").load("/mobile.php/cart/cartinfoheader");
        
    },500);
}


