<?php if ($field->isPartial()): ?>
    <?php include_partial('bonus/' . $name, array('type' => 'filter', 'form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
    <?php include_component('bonus', $name, array('type' => 'filter', 'form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
    <tr class="<?php echo $class ?>">
        <td>
            <?php echo $form[$name]->renderLabel($label) ?>
        </td>
        <td>
            <?php echo $form[$name]->renderError() ?>
            <?php if ($name == "user_id") {
                /*?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#searchProduct').keyup(function(event) {
                            if(event.keyCode!='17' && event.keyCode!='13' && event.keyCode!='18' && event.keyCode!='16' && event.keyCode!='116'){
                                var none=true;
                                if ($('#searchProduct').val().length >= 1) { //search only after 3 chars are entered.
                                    search = $('#searchProduct').val();
                                    $('#bonus_filters_user_id option').each(function(){
                                        if ($(this).text().indexOf(search) > -1) {
                                            $(this).attr('selected', 'yes');
                                            none=false;
                                        }
                                    });
                                    if(none){
                                        alert("Такого пользователя не найдено");
                                    }
                                }
                            }
                        });
                    });
                </script>
                <input type="text" size="20" id="searchProduct" value="" name="search"><br />
                <?*/
            }
            ?>
                
            <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>

            <?php if ($help || $help = $form[$name]->renderHelp()): ?>
                <div class="help"><?php echo __($help, array(), 'messages') ?></div>
            <?php endif; ?>
        </td>
    </tr>
<?php endif; ?>
