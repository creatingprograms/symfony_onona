<meta name="yandex-verification" content="5486b08711caa9ab" />
<meta name="yandex-verification" content="1b488de790d13fa1" />
<meta name="yandex-verification" content="c7b2835e0967fbb7" />
<meta name="yandex-verification" content="e342656c9b01c07c" />
<meta name="google-site-verification" content="8I6-KWXS9MVbfe3DT0ZOX5HJbY193_ujFTvv0ZvyEl0" />
<script id="advcakeAsync">
  (function(a) {
    var b = a.createElement("script");
    b.async = 1;
    b.src = "//code.w4h5ae.ru/";
    a = a.getElementsByTagName("script")[0];
    a.parentNode.insertBefore(b, a)
  })(document);
</script>

<!-- Google Tag Manager -->
<script>
  (function(w, d, s, l, i) {
    w[l] = w[l] || [];
    w[l].push({
      'gtm.start': new Date().getTime(),
      event: 'gtm.js'
    });
    var f = d.getElementsByTagName(s)[0],
      j = d.createElement(s),
      dl = l != 'dataLayer' ? '&l=' + l : '';
    j.async = true;
    j.src =
      'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
    f.parentNode.insertBefore(j, f);
  })(window, document, 'script', 'dataLayer', 'GTM-KBNZ6ZH');
</script>
<!-- End Google Tag Manager -->

<!-- rr -->
<script>
  var rrPartnerId = "550fdc951e99461fc47c015c";
  var rrApi = {};
  var rrApiOnReady = rrApiOnReady || [];
  rrApi.addToBasket = rrApi.order = rrApi.categoryView = rrApi.view =
    rrApi.recomMouseDown = rrApi.recomAddToCart = function() {};
  (function(d) {
    var ref = d.getElementsByTagName('script')[0];
    var apiJs, apiJsId = 'rrApi-jssdk';
    if (d.getElementById(apiJsId)) return;
    apiJs = d.createElement('script');
    apiJs.id = apiJsId;
    apiJs.async = true;
    apiJs.src = "//cdn.retailrocket.ru/content/javascript/tracking.js";
    ref.parentNode.insertBefore(apiJs, ref);
  }(document));
</script>
<!-- rr -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
  (function(m, e, t, r, i, k, a) {
    m[i] = m[i] || function() {
      (m[i].a = m[i].a || []).push(arguments)
    };
    m[i].l = 1 * new Date();
    k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
  })
  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(144683, "init", {
    clickmap: true,
    trackLinks: true,
    accurateTrackBounce: true,
    webvisor: true,
    ecommerce: "dataLayer"
  });
</script>
<noscript>
  <div><img src="https://mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->
<?/*<!-- Yandex.Metrika counter -->
  <script>
      (function (d, w, c) {
          (w[c] = w[c] || []).push(function() {
              try {
                  w.yaCounter144683 = new Ya.Metrika({
                      id:144683,
                      clickmap:true,
                      trackLinks:true,
                      accurateTrackBounce:true,
                      webvisor:true,
                      triggerEvent:true,
                      ecommerce:"dataLayer"
                  });
              } catch(e) { }
          });

          var n = d.getElementsByTagName("script")[0],
              s = d.createElement("script"),
              f = function () { n.parentNode.insertBefore(s, n); };
          s.type = "text/javascript";
          s.async = true;
          s.src = "https://mc.yandex.ru/metrika/watch.js";

          if (w.opera == "[object Opera]") {
              d.addEventListener("DOMContentLoaded", f, false);
          } else { f(); }
      })(document, window, "yandex_metrika_callbacks");
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
*/ ?>
<script>
  //rrWelcome
  function sendRRWelcome(email) {
    // console.log('sendRR  '+email);
    $.ajax({
      data: 'email=' + email,
      dataType: 'json',
      type: 'get',
      url: '/checkemail',
      success: function(response) {
        if (response.result == 'true') {
          // console.log('sendRR  '+email);
          // rrApi.welcomeSequence(email)
          rrApi.setEmail(email)
          rrApi.welcomeSequence(email);
        } else {
          // console.log('not sendRR '+email);
        }
      }
    });
  }
</script>
<!-- Carrot quest BEGIN -->
<script>
  ! function() {
    function t(t, e) {
      return function() {
        window.carrotquestasync.push(t, arguments)
      }
    }
    if ("undefined" == typeof carrotquest) {
      var e = document.createElement("script");
      e.type = "text/javascript", e.async = !0, e.src = "//cdn.carrotquest.app/api.min.js", document.getElementsByTagName("head")[0].appendChild(e), window.carrotquest = {}, window.carrotquestasync = [], carrotquest.settings = {};
      for (var n = ["connect", "track", "identify", "auth", "oth", "onReady", "addCallback", "removeCallback", "trackMessageInteraction"], a = 0; a < n.length; a++) carrotquest[n[a]] = t(n[a])
    }
  }(), carrotquest.connect("39015-2725872062d7439619dc23ec20");
</script>
<!-- Carrot quest END -->
<!-- ADMITAD -->
<script src="https://www.artfut.com/static/tagtag.min.js?campaign_code=f0be12d9c8" async onerror='var self = this;window.ADMITAD=window.ADMITAD||{},ADMITAD.Helpers=ADMITAD.Helpers||{},ADMITAD.Helpers.generateDomains=function(){for(var e=new Date,n=Math.floor(new Date(2020,e.getMonth(),e.getDate()).setUTCHours(0,0,0,0)/1e3),t=parseInt(1e12*(Math.sin(n)+1)).toString(30),i=["de"],o=[],a=0;a<i.length;++a)o.push({domain:t+"."+i[a],name:t});return o},ADMITAD.Helpers.findTodaysDomain=function(e){function n(){var o=new XMLHttpRequest,a=i[t].domain,D="https://"+a+"/";o.open("HEAD",D,!0),o.onload=function(){setTimeout(e,0,i[t])},o.onerror=function(){++t<i.length?setTimeout(n,0):setTimeout(e,0,void 0)},o.send()}var t=0,i=ADMITAD.Helpers.generateDomains();n()},window.ADMITAD=window.ADMITAD||{},ADMITAD.Helpers.findTodaysDomain(function(e){if(window.ADMITAD.dynamic=e,window.ADMITAD.dynamic){var n=function(){return function(){return self.src?self:""}}(),t=n(),i=(/campaign_code=([^&]+)/.exec(t.src)||[])[1]||"";t.parentNode.removeChild(t);var o=document.getElementsByTagName("head")[0],a=document.createElement("script");a.src="https://www."+window.ADMITAD.dynamic.domain+"/static/"+window.ADMITAD.dynamic.name.slice(1)+window.ADMITAD.dynamic.name.slice(0,1)+".min.js?campaign_code="+i,o.appendChild(a)}});'></script>
<script>
  var cookie_name = 'utm_source';
  var channel_name = 'utm_source';
</script>
<!-- ADMITAD -->