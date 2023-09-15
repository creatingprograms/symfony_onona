/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!
/***************************/

$(document).ready(function() {
  if ($('.captchasu').length) $('.captchasu').attr('src', $('.captchasu').attr('data-original'));
    //global vars
    var form = $("#processOrder1");
    var form2 = $("#processOrder2");
    var email = $("input[name=user_mail]");
    var emailInfo = $("input[name=user_mail]").next();
    var email2 = $("#sf_guard_user_email_address");
    var emailInfo2 = $("#sf_guard_user_email_address").next();
    var name = $("input[name=user_name]");
    var nameInfo = $("input[name=user_name]").next();
    var phone = $("input[name=user_phone]");
    var phoneInfo = $("input[name=user_phone]").next();
    var phone2 = $("#sf_guard_user_phone");
    var phoneInfo2 = $("#sf_guard_user_phone").next();
    var lastname2 = $("#sf_guard_user_last_name");
    var lastnameInfo2 = $("#sf_guard_user_last_name").next();
    $.mask.definitions["~"] = "[78]";
    phone.mask("+7(999)999-9999");
    $("#sf_guard_user_phone").mask("+7(999)999-9999");

    //On Submitting
    form.submit(function() {
        if (validateEmail() & validateName() & validatePhone() & validate18_1() & validateAccept() & validateCaptcha(1))
            return true
        else
            return false;
    });
    form2.submit(function() {
        if (validateLastname2() & validateEmail2() & validatePhone2() & validate18_2() & validateAccept2() & validateCaptcha(2))
            return true
        else
            return false;
    });

    //validation functions
    function validateEmail() {
        //testing regular expression
        var a = $("input[name=user_mail]").val();
        $("input[name=user_mail]").val($.trim(a));
        var a = $("input[name=user_mail]").val();
        var filter = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/;
        //if it's valid email
        if (filter.test(a)) {
            email.removeClass("error");
            emailInfo.text("");
            emailInfo.removeClass("error");
            return true;
        }
        //if it's NOT valid
        else {
            email.addClass("error");
            emailInfo.text(" Введите реальную почту.");
            emailInfo.addClass("error");
            $.post('/logvalidmail', {
                mail: a,
                form: 1
            });
            return false;
        }
    }
    function validateEmail2() {
        //testing regular expression
        var a = $("#sf_guard_user_email_address").val();
        $("#sf_guard_user_email_address").val($.trim(a));
        var a = $("#sf_guard_user_email_address").val();
        var filter = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/;
        //if it's valid email
        if (filter.test(a)) {
            email2.removeClass("error");
            emailInfo2.text("");
            emailInfo2.removeClass("error");
            return true;
        }
        //if it's NOT valid
        else {
            email2.addClass("error");
            emailInfo2.text(" Введите реальную почту.");
            emailInfo2.addClass("error");
            $.post('/logvalidmail', {
                mail: a,
                form: 2
            });
            return false;
        }
    }
    function validateName() {
        //if it's NOT valid
        if (name.val().length < 2) {
            name.addClass("error");
            nameInfo.text(" Укажите своё имя");
            nameInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            name.removeClass("error");
            nameInfo.text("");
            nameInfo.removeClass("error");
            return true;
        }
    }
    function validateLastname2() {
        //if it's NOT valid
        if (lastname2.attr('required') == "required") {
            if (lastname2.val().length < 2) {
                lastname2.addClass("error");
                lastnameInfo2.text("Обязательное поле для доставки почтой России. Укажите свою фамилию.");
                lastnameInfo2.addClass("error");
                return false;
            }
            //if it's valid
            else {
                lastname2.removeClass("error");
                lastnameInfo2.text("");
                lastnameInfo2.removeClass("error");
                return true;
            }
        } else {
            lastname2.removeClass("error");
            lastnameInfo2.text("");
            lastnameInfo2.removeClass("error");
            return true;
        }
    }
    function validatePhone() {
        //if it's NOT valid
        if (phone.val().length < 6) {
            phone.addClass("error");
            phoneInfo.text(" Укажите свой телефон");
            phoneInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            phone.removeClass("error");
            phoneInfo.text("");
            phoneInfo.removeClass("error");
            return true;
        }
    }
    function validatePhone2() {
        //if it's NOT valid
        if (phone2.val().length < 6) {
            phone2.addClass("error");
            phoneInfo2.text(" Укажите свой телефон");
            phoneInfo2.addClass("error");
            return false;
        }
        //if it's valid
        else {
            phone2.removeClass("error");
            phoneInfo2.text("");
            phoneInfo2.removeClass("error");
            return true;
        }
    }
    function validateCaptcha(num){
      // console.log('cap validation');
      var capClass='.js-captcha-'+num;
      if($(capClass).length){
        if($(capClass).val()==''){
          $(capClass).parent().find('span').html("<br />Необходимо ввести код!");
          $(capClass).parent().find('span').addClass("error");
        }
        else{
          $(capClass).parent().find('span').html("");
          $(capClass).parent().find('span').removeClass("error");
          return true;
          /*
          $.ajax({
            url: '/captcha_check',
            data: {
              sucaptcha: $(capClass).val(),
            },
            type: 'post',
            success: function (resp){

            }
          })*/
        }
      }
      else return true;
    }
    function validateAccept() {

        //if it's NOT valid
        if (!$("#personal-accept-1").prop("checked")) {
            $("#personal-accept-1").next('span').html("<br />Необходимо принять пользовательское соглашение!");
            $("#personal-accept-1").next('span').addClass("error");
            return false;
        } else {
            $("#personal-accept-1").next('span').text("");
            $("#personal-accept-1").next('span').removeClass("error");
            return true;
        }
    }
    function validateAccept2() {
        //if it's NOT valid
        if (!$("#personal-accept-2").prop("checked")) {

            $("#personal-accept-2").next('span').html("<br />Необходимо принять пользовательское соглашение!");
            $("#personal-accept-2").next('span').addClass("error");
            return false;
        } else {

            $("#personal-accept-2").next('span').text("");
            $("#personal-accept-2").next('span').removeClass("error");
            return true;
        }
    }
    function validate18_1() {
        //if it's NOT valid
        if (!$("#18form1").prop("checked")) {

            $("#18form1").next('span').html("<br />Заказ доступен только лицам, достигшим 18 лет.");
            $("#18form1").next('span').addClass("error");
            return false;
        } else {

            $("#18form1").next('span').text("");
            $("#18form1").next('span').removeClass("error");
            return true;
        }
    }
    function validate18_2() {
        //if it's NOT valid
        if (!$("#18form2").prop("checked")) {

            $("#18form2").next('span').html("<br />Заказ доступен только лицам, достигшим 18 лет.");
            $("#18form2").next('span').addClass("error");
            return false;
        } else {

            $("#18form2").next('span').text("");
            $("#18form2").next('span').removeClass("error");
            return true;
        }
    }
    function validatePass1() {
        var a = $("#password1");
        var b = $("#password2");

        //it's NOT valid
        if (pass1.val().length < 5) {
            pass1.addClass("error");
            pass1Info.text("Ey! Remember: At least 5 characters: letters, numbers and '_'");
            pass1Info.addClass("error");
            return false;
        }
        //it's valid
        else {
            pass1.removeClass("error");
            pass1Info.text("At least 5 characters: letters, numbers and '_'");
            pass1Info.removeClass("error");
            validatePass2();
            return true;
        }
    }
    function validatePass2() {
        var a = $("#password1");
        var b = $("#password2");
        //are NOT valid
        if (pass1.val() != pass2.val()) {
            pass2.addClass("error");
            pass2Info.text("Passwords doesn't match!");
            pass2Info.addClass("error");
            return false;
        }
        //are valid
        else {
            pass2.removeClass("error");
            pass2Info.text("Confirm password");
            pass2Info.removeClass("error");
            return true;
        }
    }
    function validateMessage() {
        //it's NOT valid
        if (message.val().length < 10) {
            message.addClass("error");
            return false;
        }
        //it's valid
        else {
            message.removeClass("error");
            return true;
        }
    }
});
