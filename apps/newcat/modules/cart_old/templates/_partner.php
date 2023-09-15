<? if ($order->getReferal() == "2764355315") { ?>
    <img src="http://a47.myragon.ru/track/id/<?= $orderId ?>/key/<?= md5('604' . $orderId) ?>/target/t1">
<? } ?>
    
<? if ($order->getReferal() == "2507129324") { ?>
    <script>
        var univar1='<?= $orderId ?>';
        document.write('<img src="http://mixmarket.biz/uni/tev.php?id=1294929771&r='+escape(document.referrer)+'&t='+(new Date()).getTime()+'&a1='+univar1+'" width="1" height="1"/>');</script>
    <noscript><img src="http://mixmarket.biz/uni/tev.php?id=1294929771&a1=<?= $orderId ?>" width="1" height="1"/></noscript>
<? } ?>
    
<? if ($order->getReferal() == "2801045062") { ?>
    <img src="http://ad.admitad.com/register/<? /* 2ae86bf52a */ ?>f0be12d9c8/script_type/img/payment_type/sale/product/1/cart/<?= $price ?>/order_id/<?= $orderId ?>/uid/<?= sfContext::getInstance()->getRequest()->getCookie('uidAdmitad') ?>/" width="1" height="1" alt="" />
<? } ?>
    
<? if ($order->getReferal() == "1245533220") { ?>
    <img src="http://t.leadtrade.ru/58.png?lttracking=<?=$_COOKIE['lttracking']?>&ltid=<?= $orderId ?>&ltamount=<?= $price ?>.00" width="1" height="1" />
<? } ?>




<script type="text/javascript">
    var ad_order = "<?= $orderId ?>";    // required
    var ad_amount = "<?= $price ?>";

    window._retag = window._retag || [];
    window._retag.push({code: "9ce8886732", level: 4});
    (function () {
        var s=document.createElement("script");
        s.async=true;
        s.src=(document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
        var a=document.getElementsByTagName("script")[0]
        a.parentNode.insertBefore(s, a);
    })()
</script>



<? /*<script type='text/javascript' src='http://myads.ru/c?q=PdpSAvk8m45EamQvZahIDQ'></script>*/?>






<? /* <script>
  var univar1='<?= $orderId ?>';
  document.write('<img src="http://mixmarket.biz/uni/tev.php?id=1294929771&r='+escape(document.referrer)+'&t='+(new Date()).getTime()+'&a1='+univar1+'" width="1" height="1"/>');</script>
  <noscript><img src="http://mixmarket.biz/uni/tev.php?id=1294929771&a1=<?= $orderId ?>" width="1" height="1"/></noscript>

  <!--Трэкер "Покупка"-->
  <script>document.write('<img src="http://mixmarket.biz/tr.plx?e=3779408&r='+escape(document.referrer)+'&t='+(new Date()).getTime()+'" width="1" height="1"/>');</script>
  <!--Трэкер "Покупка"--> */ ?>


<? /* if ($order->getReferal() == "347146658") { ?>
  <img src="https://t.gameleads.ru/<?= $orderId ?>/q1/<?= md5('1776' . $orderId) ?>">

  <?
  $content = file_get_contents("http://cityads.ru/service/postback?id=" . $orderId . "&prx=" . $order->getPrxcityads());
  } */
?>

<? /* <img src="https://t.gameleads.ru/<? echo $orderId; ?>/q1/<? echo md5("1776" . $orderId); ?>" /> */ ?>
