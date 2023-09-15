
            <form action="<?php echo url_for('@customer_mydata') ?>" method="post" id="mydata">
                <?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>
                <div class="rulesAndButton">
                    <span style="color: #c3060e;">*</span> - Поля, обязательны для заполнения.<br /> 
                    <span style="color: #c3060e;">**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить Ваш заказ.<br /> <br /> 

                    <div style="margin: 20px;">
                        <a class="redButton" href="#" onClick="$('#mydata').submit()"><span>Сохранить</span></a>
                    </div>   
                </div>
            </form>