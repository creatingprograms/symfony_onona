<script>
    var digiScript = document.createElement('script');
    digiScript.src = 'https://cdn.diginetica.net/538/client.js?ts=' + Date.now();
    digiScript.defer = true;
    digiScript.async = true;
    document.body.appendChild(digiScript);
</script>
<?/*<script src="https://regmarkets.ru/js/r17.js" async type="text/javascript"></script>*/ ?>
<?/* mango
<div class="mango-callback" data-settings='{"type":"", "id": "MTAwMDY4OTI=","autoDial" : "0", "lang" : "ru-ru", "host":"widgets.mango-office.ru/", "errorMessage": "В данный момент наблюдаются технические проблемы и совершение звонка невозможно"}'></div>
<script>!function(t){function e(){i=document.querySelectorAll(".button-widget-open");for(var e=0;e<i.length;e++)"true"!=i[e].getAttribute("init")&&(options=JSON.parse(i[e].closest('.'+t).getAttribute("data-settings")),i[e].setAttribute("onclick","alert('"+options.errorMessage+"(0000)'); return false;"))}function o(t,e,o,n,i,r){var s=document.createElement(t);for(var a in e)s.setAttribute(a,e[a]);s.readyState?s.onreadystatechange=o:(s.onload=n,s.onerror=i),r(s)}function n(){for(var t=0;t<i.length;t++){var e=i[t];if("true"!=e.getAttribute("init")){options=JSON.parse(e.getAttribute("data-settings"));var o=new MangoWidget({host:window.location.protocol+'//'+options.host,id:options.id,elem:e,message:options.errorMessage});o.initWidget(),e.setAttribute("init","true"),i[t].setAttribute("onclick","")}}}host=window.location.protocol+"//widgets.mango-office.ru/";var i=document.getElementsByClassName(t);o("link",{rel:"stylesheet",type:"text/css",href:host+"css/widget-button.css"},function(){},function(){},e,function(t){document.documentElement.insertBefore(t,document.documentElement.firstChild)}),o("script",{type:"text/javascript",src:host+"widgets/mango-callback.js"},function(){("complete"==this.readyState||"loaded"==this.readyState)&&n()},n,e,function(t){document.documentElement.appendChild(t)})}("mango-callback");</script>
*/ ?>

<!-- Roistat Begin -->
<script>
    (function(w, d, s, h, id) {
        w.roistatProjectId = id;
        w.roistatHost = h;
        var p = d.location.protocol == "https:" ? "https://" : "http://";
        var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init";
        var js = d.createElement(s);
        js.charset = "UTF-8";
        js.async = 1;
        js.src = p + h + u;
        var js2 = d.getElementsByTagName(s)[0];
        js2.parentNode.insertBefore(js, js2);
    })(window, document, 'script', 'cloud.roistat.com', 'db0589994125cd9a8e5ec05d4d57bbc5');
</script>
<script>
    jQuery(document).on('submit', '.retailrocket-subscribe-form', function() {
        var email = jQuery(this).find('#rr-onona-popup-email').val();
        var emailRegexp = new RegExp(/^[^\s]+[@][^\s]+$/g);
        if (email.match(emailRegexp)) {
            roistatGoal.reach({
                leadName: 'Заявка с сайта (Акция)',
                email: email,
                fields: {
                    'orderCreationMethod': 'Акция',
                    'utmSource': '{utmSource}',
                    'utmMedium': '{utmMedium}',
                    'utmCampaign': '{utmCampaign}',
                    'utmTerm': '{utmTerm}',
                    'utmContent': '{utmContent}'
                }
            });
        }
    });
</script>
<!-- Roistat END -->
<?/* if (!$hideMess) :?>
  <!-- BEGIN JIVOSITE INTEGRATION WITH ROISTAT -->
  <script>
      var getCookie = window.getCookie = function (name) {
          var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
          return matches ? decodeURIComponent(matches[1]) : undefined;
      };
      function jivo_onLoadCallback() {
          jivo_api.setUserToken(getCookie('roistat_visit'));
      }
  </script>
  <!-- END JIVOSITE INTEGRATION WITH ROISTAT -->
  <?// jivisite ?>
  <!-- BEGIN JIVOSITE CODE {literal} -->
  <script async type='text/javascript'>
      (function(){ var widget_id = 'lHhK9V8ak4';var d=document;var w=window;function l(){var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
  </script>
  <!-- {/literal} END JIVOSITE CODE -->
<? endif */ ?>
<script src="//get4click.ru/wrapper.php?method=container&shopId=1029" type="text/javascript"></script>
<script type="text/javascript"> (function ab(){ var request = new XMLHttpRequest(); request.open('GET', "https://scripts.botfaqtor.ru/one/40306", false); request.send(); if(request.status == 200) eval(request.responseText); })(); </script>