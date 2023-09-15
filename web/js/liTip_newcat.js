
    (function($) {
        $.fn.liTip = function(params) {
            var params = $.extend({
                themClass: 'liTipBlack',
                timehide: 200,
                posY: 'top',
                radius: '3px',
                maxWidth: '400px',
                content: false,
                tipEvent: 'mouseenter'
            }, params);
            return this.each(function() {
                var tipTag = $(this)/*.css({whiteSpace: 'nowrap'}*/,
                        wW = $(window).width(),
                        wH = $(window).height(),
                        themClass = params.themClass,
                        timehide = params.timehide,
                        maxWidth = params.maxWidth,
                        posY = params.posY,
                        tipFuncId = false,
                        tipF = false,
                        radius = params.radius,
                        tipEvent = params.tipEvent,
                        liTipContent = $('<div>').css({borderRadius: radius, maxWidth: maxWidth}).addClass('liTipContent liTipHide ' + themClass).appendTo('body'),
                        content = params.content,
                        liTipClass = 'liTipPos' + posY,
                        tipContent = '',
                        tipTagLeft = tipTag.offset().left,
                        tipTagTop = tipTag.offset().top,
                        tipTagWidth = tipTag.outerWidth(),
                        tipTagHeight = tipTag.outerHeight(),
                        tipTagCenter = tipTagLeft + tipTagWidth / 2,
                        liTipInner = $('<div>').addClass('liTipInner').html(tipContentFunc()).appendTo(liTipContent),
                        liTipCone = $('<div>').addClass('liTipCone').appendTo(liTipContent),
                        liTipContentWidth = liTipContent.outerWidth(),
                        liTipContentHeight = liTipContent.outerHeight(),
                        liTipContentCenter = liTipContentWidth / 2,
                        coneLeft = 0;

                function tipContentFunc() {
                    if (content == false) {
                        tipContent = tipTag.attr('title');
                        tipTag.attr('title', '');
                    } else {
                        tipTag.attr('title', '');
                        tipContent = content;
                    }
                    ;
                    return tipContent;
                }
                ;

                tipTag.on(tipEvent, function(e) {
                    var eX = e.pageX;
                    var eY = e.pageY;
                    tipLeft = tipTagCenter - liTipContentCenter;
                    coneLeft = 0;
                    if (tipLeft < 0) {
                        tipLeft = 5;
                        coneLeft = (tipTagCenter - liTipContentCenter) - 5;
                    }
                    ;
                    if (tipLeft > (wW - liTipContentWidth)) {
                        tipLeft = (wW - (liTipContentWidth + 5));
                        coneLeft = (tipTagCenter - liTipContentCenter) - (wW - (liTipContentWidth + 5));
                    }
                    ;
                    liTipCone.css({marginLeft: coneLeft - 6 + 'px'});
                    if (posY == 'top') {
                        tipTop = tipTagTop - (liTipContentHeight + 5);
                        if (tipTop < $(window).scrollTop()) {
                            tipTop = (tipTagTop + tipTagHeight + 5);
                            liTipClass = 'liTipPosbottom';
                        }
                    }
                    ;
                    if (posY == 'bottom') {
                        tipTop = (tipTagTop + tipTagHeight + 5);
                        if ((tipTop + liTipContentHeight) > $(window).scrollTop() + wH) {
                            tipTop = tipTagTop - (liTipContentHeight + 5);
                            liTipClass = 'liTipPostop';
                        }
                    }
                    if (posY == 'uprCenoyProduct') {
                        tipTop = (tipTagTop );
                        if ((tipTop + liTipContentHeight) > $(window).scrollTop() + wH) {
                            tipTop = tipTagTop - (liTipContentHeight + 5);
                            liTipClass = 'liTipPostop';
                        }
                        tipLeft=tipLeft-95;
                    }
                    if (posY == 'PreShowProd') {
                        tipTop = (tipTagTop )-1;
                       /* if ((tipTop + liTipContentHeight) > $(window).scrollTop() + wH) {
                            tipTop = tipTagTop - (liTipContentHeight + 5);
                            liTipClass = 'liTipPostop';
                        }*/
                        
                        /*console.log(tipTagLeft);*/
                       /* tipTop = tipTagTop-1;
                        tipLeft=tipTagLeft-1;*/
                        
                        tipTop = tipTag.offset().top-1;
                        tipLeft=tipTag.offset().left-1;
                        liTipContent.css({width: 320, height: 430});
                    }
                    if (posY == 'PreShowCard') {
                        tipTop = (tipTagTop )+43;
                       /* if ((tipTop + liTipContentHeight) > $(window).scrollTop() + wH) {
                            tipTop = tipTagTop - (liTipContentHeight + 5);
                            liTipClass = 'liTipPostop';
                        }*/
                        tipLeft=tipTag.offset().left-182;
                        liTipContent.css({width: 400, 'max-width': 400, padding: 0});
                    }
                    if (posY == 'PreShowProdAction') {
                        tipTop = (tipTagTop )-1;
                       /* if ((tipTop + liTipContentHeight) > $(window).scrollTop() + wH) {
                            tipTop = tipTagTop - (liTipContentHeight + 5);
                            liTipClass = 'liTipPostop';
                        }*/
                        /*tipTop = tipTagTop-1;
                        tipLeft=tipTagLeft-1;*/
                        
                        tipTop = tipTag.offset().top-1;
                        tipLeft=tipTag.offset().left-1;
                        liTipContent.css({width: 320, height: 430});
                    }
                    if (posY == 'ButtonImgPreshow') {
                        tipTop = (tipTagTop )-1;
                        if ((tipTop + liTipContentHeight) > $(window).scrollTop() + wH) {
                            tipTop = tipTagTop - (liTipContentHeight + 5);
                            liTipClass = 'liTipPostop';
                        }
                        tipLeft=tipLeft-40;
                    }
                    if (posY == 'uprCenoyPreshowProd') {
                        tipTop = $(".imgUprPrice").offset().top;

                        tipLeft=$(".imgUprPrice").offset().left-140;
                    }
                    if (posY == 'lk') {
                        tipTop = $('#header').offset().top + 35;

                        tipLeft=$('#header').offset().left + $('#header').width() - 228;
                    }
                    if (posY == 'productShowItems') {
                        tipTop = tipTag.offset().top;

                        tipLeft=tipTag.offset().left;
                        liTipContent.css({width: 320, height: 430});
                    }
                    ;
                    liTipContent.find("img.thumbnails").each(function(){
                        $(this).attr("src",$(this).attr("data-original"));
                    })
                    //console.log(liTipContent);
                    liTipContent.removeClass('liTipPostop').removeClass('liTipPosbottom').addClass(liTipClass).css({left: tipLeft, top: tipTop});
                    clearTimeout(tipFuncId);
                    if (tipEvent == 'click') {
                        return false;
                    }
                    ;
                }).on('mouseleave', function() {
                    tipF = function tipFunc() {
                        liTipContent.css({left: '-99999px', top: '-99999px'});
                    };
                    clearTimeout(tipFuncId);
                    tipFuncId = setTimeout(tipF, timehide);
                });
                liTipContent.on('mouseenter', function() {
                    clearTimeout(tipFuncId);
                }).on('mouseleave', function() {
                    clearTimeout(tipFuncId);
                    tipFuncId = setTimeout(tipF, timehide);
                });
                $(window).resize(function() {
                    wW = $(window).width();
                    wH = $(window).height();
                    tipTagLeft = tipTag.offset().left;
                    tipTagTop = tipTag.offset().top;
                    tipTagWidth = tipTag.outerWidth();
                    tipTagHeight = tipTag.outerHeight();
                    tipTagCenter = tipTagLeft + tipTagWidth / 2;
                    liTipContentWidth = liTipContent.outerWidth();
                    liTipContentHeight = liTipContent.outerHeight();
                    liTipContentCenter = liTipContentWidth / 2;
                    liTipClass = 'liTipPos' + posY;
                })
            });
        };
    })(jQuery);
