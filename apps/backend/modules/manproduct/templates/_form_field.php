<?php if ($field->isPartial()): ?>
    <?php include_partial('manproduct/' . $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
    <?php include_component('manproduct', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
    <div class="<?php echo $class ?><?php $form[$name]->hasError() and print ' errors' ?>">
        <?php echo $form[$name]->renderError() ?>
        <div>
            <?php echo $form[$name]->renderLabel($label) ?> <?php
        if ($label == "Фотоальбомы") {
            echo "<div onClick=\"$('.sf_admin_form_field_Photoalbums .content').toggle(1000)\" style=\"cursor: pointer;\">Скрыть/показать</div>";
        }
            ?>
            <?php if ($label == "Родитель") {
                ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#searchProduct').keyup(function() {
                            if ($('#searchProduct').val().length >= 3) { //search only after 3 chars are entered.
                                search = $('#searchProduct').val();
                                $('#product_parents_id option').each(function(){
                                    if ($(this).text().indexOf(search) > -1) {
                                        $(this).attr('selected', 'yes');
                                    }
                                });
                            }
                        });
                    });
                </script>
                <input type="text" size="20" id="searchProduct" value="" name="search">
                <?
            }
            ?>
            <div class = "content"<?php
        if ($label == "Фотоальбомы") {
            echo " style=\"display: none;\"";
        }
            ?>><?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?></div>

            <?php if ($help): ?>
                <div class="help"><?php echo __($help, array(), 'messages') ?></div>
            <?php elseif ($help = $form[$name]->renderHelp()): ?>
                <div class="help"><?php echo $help ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
