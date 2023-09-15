
<?php if (sfContext::getInstance()->getRequest()->getCookie('age18') != true and !get_slot('18ageNo')) {
    ?>
    <!--noindex-->
        <div style="opacity: 0.5; position: absolute; width:100%; height: 100%; background-color: #cdcdcd; z-index: 64999;" id="18ageBG"></div>
        <div style="position: absolute; width:1113px; height: 987px;background: url('/images/18+new.png'); z-index: 65000;left: 50%;margin-left: -556px;" id="18age">
            <a href="/18age/yes">
                <div style="position: relative; width:200px; height: 35px; z-index: 65001; top: 390px; left: 379px;float: left;">&nbsp;</div>
            </a>
            <a href="/18age/no">
                <div style="position: relative; width:200px; height: 35px; z-index: 65001; top: 390px; left: 589px;"> </div>
            </a>
            <a href="/support">
                <div style="position: relative; height: 35px; z-index: 65001; float: left; top: 413px; left: 502px; width: 293px;"> </div>
            </a>
        </div>
    <!--/noindex-->
<? }
?>
