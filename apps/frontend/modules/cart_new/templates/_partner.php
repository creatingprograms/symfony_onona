
<?/* //117612 - onona.ru - Проверка обновления трекинг-кода
if ($order->getReferal() == "2801045062") {

    $productOrder = $order->getText();
    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
    ?><script type="text/javascript">

        (function (d, w) {

            w._admitadPixel = {
                response_type: 'img',
                action_code: '2',
                campaign_code: 'f0be12d9c8'

            };

            w._admitadPositions = w._admitadPositions || [];

            w._admitadPositions.push({
                uid: '<?= sfContext::getInstance()->getRequest()->getCookie('uidAdmitad') ?>',
                order_id: '<?= $orderId ?>',
                position_id: '1',
                client_id: '',
                tariff_code: '1',
                currency_code: '',
                position_count: '1',
                price: '<?= $priceNotDelivery ?>',
                quantity: '1',
                product_id: '',
                screen: '',
                tracking: '',
                old_customer: '',
                coupon: '',
                payment_type: 'sale'

            });

            var id = '_admitad-pixel';

            if (d.getElementById(id)) {
                return;
            }

            var s = d.createElement('script');

            s.id = id;

            var r = (new Date).getTime();

            var protocol = (d.location.protocol === 'https:' ? 'https:' : 'http:');

            s.src = protocol + '//cdn.asbmit.com/static/js/pixel.min.js?r=' + r;

            d.head.appendChild(s);

        })(document, window)

    </script>

    <noscript>

    <img src="//ad.admitad.com/r?campaign_code=f0be12d9c8&action_code=2&response_type=img&uid=<?= sfContext::getInstance()->getRequest()->getCookie('uidAdmitad') ?>&order_id=<?= $orderId ?>&position_id=1&tariff_code=1&currency_code=&position_count=1&price=<?= $priceNotDelivery ?>&quantity=1&product_id=&coupon=&payment_type=sale" width="1" height="1" alt="">

    </noscript>
  <? } */?>

<? /* if ($order->getReferal() == "1245533220") { ?>
  <img src="http://t.leadtrade.ru/58.png?lttracking=<?= $_COOKIE['lttracking'] ?>&ltid=<?= $orderId ?>&ltamount=<?= $price ?>.00" width="1" height="1" alt="" />
  <? } */ ?>

<? /* if ($order->getReferal() == "2666852606") { ?>
  <img src="http://leads24.ru/pixels/ononaru/l24pixel.php?id=<?= $orderId ?>&order_cost=<?= $price ?>" />
  <? } */ ?>




<script type="text/javascript">
    var ad_order = "<?= $orderId ?>";    // required
    var ad_amount = "<?= $price ?>";

    window._retag = window._retag || [];
    window._retag.push({code: "9ce8886732", level: 4});
    (function () {
        var s = document.createElement("script");
        s.async = true;
        s.src = (document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
        var a = document.getElementsByTagName("script")[0]
        a.parentNode.insertBefore(s, a);
    })()
</script>



<?/*
<script type="text/javascript">function readCookie(name) {if (document.cookie.length > 0) { offset = document.cookie.indexOf(name + "="); if (offset != -1) { offset = offset + name.length + 1; tail = document.cookie.indexOf(";", offset); if (tail == -1) tail = document.cookie.length; return unescape(document.cookie.substring(offset, tail)); } } return null; } var url='https://track.leadhit.ru/stat/lead_form?f_orderid=';

//Вместо {{order_id}} должен подставляться номер заказа.
url += "<?= $orderId ?>";

url += '&url='; url += encodeURIComponent(window.location.href); url += '&action=lh_orderid'; url += '&uid=' + readCookie('_lhtm_u'); url += '&vid='; url += readCookie('_lhtm_r').split('|')[1]; url += '&ref=direct&f_cart_sum=&clid='+'5604fd6fbbddbd5e6a27efb2'; var sc = document.createElement("script"); sc.type = 'text/javascript'; var headID = document.getElementsByTagName("head")[0]; sc.src = url; headID.appendChild(sc); </script>
*/?>
