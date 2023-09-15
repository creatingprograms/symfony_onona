<?php if ($field->isPartial()): ?>
    <?php include_partial('product/' . $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
    <?php include_component('product', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else:
    $timer = sfTimerManager::getTimer('_form_field: '.$name);
    ?>
    <div class="<?php echo $class ?><?php $form[$name]->hasError() and print ' errors' ?>">
        <?php echo $form[$name]->renderError() ?>
        <div>
            <?php echo $form[$name]->renderLabel($label) ?> <?php
        if ($label == "Фотоальбомы") {
            echo "<div onClick=\"$('.sf_admin_form_field_Photoalbums .content').toggle(1000)\" style=\"cursor: pointer;\">Скрыть/показать</div>";
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
<?php 
        $timer->addTime();
endif; ?>
