
<?php if (sfContext::getInstance()->getRequest()->getCookie('age18') != true and ! get_slot('18ageNo')) {
    ?>
    <!--noindex-->
    <!--<div style="opacity: 0.5; position: absolute; width:100%; height: 100%; background-color: #cdcdcd; z-index: 64999;" id="18ageBG"></div>-->
    <div style="<? /* position: absolute; width:1113px; height: 987px;background: url('/images/18+new.png'); z-index: 65000;left: 50%;margin-left: -556px; */ ?>position: fixed;bottom: 0; z-index: 65000; left: 50%; background: url('/images/18+new.png') repeat scroll -270px -240px transparent; height: 195px; width: 563px; margin-left: -280px;" id="18age">
        <a href="javascript:setCookie('age18', true, 'Mon, 01-Jan-2101 00:00:00 GMT', '/'); $('#18age').hide(); void(0);">
            <div style="position: relative; width: 200px; height: 35px; z-index: 65001; float: left; top: 150px; left: 108px;">&nbsp;</div>
        </a> 
        <a href="javascript:$('#18age').hide(); void(0);">
            <div style="position: relative; width: 200px; height: 35px; z-index: 65001; top: 150px; left: 319px;"> </div>
        </a> 
        <? /* <a href="/support">
          <div style="position: relative; height: 35px; z-index: 65001; float: left; top: 413px; left: 502px; width: 293px;"> </div>
          </a> */ ?>
    </div>
    <!--/noindex-->
<? }
?>