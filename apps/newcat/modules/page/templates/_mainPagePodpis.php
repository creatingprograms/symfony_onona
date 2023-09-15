<?php
if (!$sf_user->isAuthenticated() and false) {
    ?><div style="clear: both;"></div><div class="separator" style="margin-top: 20px;">
                &nbsp;</div>


            <script>
                function validateEmailRassilka() {
                    var email = $("input[name=user_mail]");
                    var emailInfo = $(".statusValidMail");
                    //testing regular expression
                    var a = $("input[name=user_mail]").val();
                    $("input[name=user_mail]").val($.trim(a));
                    var a = $("input[name=user_mail]").val();
                    var filter = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/;
                    //if it's valid email
                    if (filter.test(a)) {
                        $('#firstVisitYesNews').submit()
                        return true;
                    }
                    //if it's NOT valid
                    else {
                        email.addClass("error");
                        emailInfo.html(" <br>Введите реальную почту.");
                        emailInfo.addClass("error");
                        $.post('/logvalidmail', {
                            mail: a,
                            form: 'rassilka'
                        });
                        return false;
                    }
                }</script>
            <div style="width: 100%; height: 130px;">
                <form action="<?= url_for('firstvisit/yes/') ?>" id="firstVisitYesNews" method="POST">
                    <div style="text-align: center; width: 100%"><span style="font-size: 18px;">ПОДПИШИТЕСЬ НА НОВОСТИ</span><br />Будьте всегда в курсе наших специальных предложений!</div>
                    <br />
                    <input type="text" value="Имя" name="user_name" style="width: 288px; margin-left: 346px;  padding: 0px 0px 0px 10px; border: 1px solid rgb(153, 153, 153); height: 24px;" onClick="if ($(this).val() == 'Имя')
                                                $(this).val('');" /><br /><br />
                    <input type="text" value="E-mail" name="user_mail" style="width: 288px; margin-left: 346px; float: left; padding: 0px 0px 0px 10px; border: 1px solid rgb(153, 153, 153); height: 24px;"  onClick="if ($(this).val() == 'E-mail')
                                                $(this).val('');" />
                    <div onclick="validateEmailRassilka()" class="buttonSignUp" style="float: left;"></div>
                    <div style="clear: both;"></div>
                    <div class="statusValidMail" style="margin-left: 346px;"></div>

                </form>
                <br /><br /><br />
            </div>
            <?
        }
