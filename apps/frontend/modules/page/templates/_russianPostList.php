<?
mb_internal_encoding('UTF-8');
?>
<?/*<div class="rp-calculator">
  <h2>Калькулятор стоимости доставки почтой России</h2>
    <input type="hidden" name="weight" value="998">
  <div class="input-container">
    <div class="input-name">Стоимость посылки, руб.</div>
    <input type="text" name="price">
    <label><input type="checkbox" name="isoffline"> Наложенный платеж</label>
  </div>
  <div class="input-container">
    <div class="input-name">Город доставки</div>
    <input type="text" name="city">
  </div>
  <div class="input-container">
    <div class="input-name">Улица</div>
    <input type="text" name="street">
  </div>
  <div class="input-container">
    <div class="input-name">Дом</div>
    <input type="text" name="house">
  </div>
  <div class="js-result">Заполните все поля и нажмите "Рассчитать"</div>
  <div class="js-rp-calc redButtonSmallUserSend" onclick="calcPR();">Рассчитать</div>
</div>
*/?>
<?/*
<script>
  function calcPR(){
    var freeDeliveryLimit=<?= csSettings::get('free_deliver') ?>;
    var $form=$('.rp-calculator');
    var weight=$form.find('input[name=weight]').val(),
        cartTotalCost=$form.find('input[name=price]').val(),
        city=$form.find('input[name=city]').val(),
        street=$form.find('input[name=street]').val(),
        house=$form.find('input[name=house]').val();
        paymentId=$form.find('input[name="isoffline"]:checked').val();
    if(cartTotalCost>freeDeliveryLimit) {
      $form.find('.js-result').text('Ваша посылка будет доставлена бесплатно!');
      return;
    }
    var onlinePayment=true;
    if (paymentId){
      onlinePayment=false;
    }
    var needCalc=weight + '|' + city + '|' + street+ '|'  + house+ '|' +  onlinePayment+ '|' + cartTotalCost;


    if(//Проверяем изменилось ли что-то  (могла ли измениться цена доставки)
      needCalc != $form.data('need-calc')
    ){
      // console.log('need to calc');
      $form.data('need-calc', needCalc);
      $.ajax({
        url: '/russianpost_get_delivery_price',
        data: {
          'weight': weight,
          'city': city,
          'street': street,
          'house': house,
          'pay-online': onlinePayment,
          'order-price': cartTotalCost
        },
        type: 'post',
        success: function(response){
          // console.log(response);
          $form.find('.js-result').text(response.comment);
          // if(response.status) {
          //   price=1*response.price;
          //   $('#PriceDeliverTable').html(price);
          //   $('#PriceAllTable').html(price+cartTotalCost);
          //   $('#deliveryPriceSend').val(price);
          // }
        }
      });
    }
    else{
      // console.log('russian post calc not needed');
    }

    // console.log('pr calc:' + needCalc);

  }
</script>*/?>
<?/*
<div id="PostPages">
    <script>
        function changeCity(button, letter) {
            $("#PostPages .letter").each(function (index) {
                $(this).children("div").css("color", "#424141");
            });
            $(button).css("color", "#c3060e");

            $("#PostPages .citys").each(function (index) {
                $(this).fadeOut(0);
                $(this).removeClass('active');
            });
            $("#letter-" + letter).fadeIn(0);
            $("#letter-" + letter).addClass('active');
        }
        function showFullList(){
          $('.pickpoint-cities-list.active').addClass('full');
        }

    </script>

    <ul style="list-style: none;" class="letters"><?php
        foreach ($alf as $num => $letter) {
            ?><li style="float: left;margin: 7px;" class="letter"><div onclick="changeCity(this,'<?= $letter['alf'] ?>')" style="cursor: pointer; <?= $num == 0 ? " color: #c3060e;" : "" ?>"><b><?= $letter['alf'] ?></b></div></li>
            <?
        }
        ?></ul>
    <div style="clear: both;"></div>
    <ul style="list-style: none;"><?php
        foreach ($citys as $num => $city) {
            if ($oldLetter != mb_substr($city['city'], 0, 1)) {
                ?></ul>
            <ul style="list-style: none;<?= $num != 0 ? " display: none;" : "" ?>" class="citys pickpoint-cities-list<?= !$num ? " active" : "" ?>" id="letter-<?= mb_substr($city['city'], 0, 1) ?>"><?
                $oldLetter = mb_substr($city['city'], 0, 1);
            }
            ?><li class="pickpoint-element"><a href="/russianpost/<?= $city['slug'] ?>"><?= $city['city'] ?></a></li>
                <?
            }
            ?></ul>
    <?//<div class="show-more-cities" onclick="showFullList();">Весь список</div>
    <div style="clear: both;"></div>?>
</div>
*/?>
