var rrPartnerId = "550fdc951e99461fc47c015c";
var rrApi = {};
var rrApiOnReady = rrApiOnReady || [];
rrApi.addToBasket = rrApi.order = rrApi.categoryView = rrApi.view =
        rrApi.recomMouseDown = rrApi.recomAddToCart = function () {
        };
(function (d) {
    var ref = d.getElementsByTagName('script')[0];
    var apiJs, apiJsId = 'rrApi-jssdk';
    if (d.getElementById(apiJsId))
        return;
    apiJs = d.createElement('script');
    apiJs.id = apiJsId;
    apiJs.async = true;
    apiJs.src = "//cdn.retailrocket.ru/content/javascript/api.js";
    ref.parentNode.insertBefore(apiJs, ref);
}(document));
