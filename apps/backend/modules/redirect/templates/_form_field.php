<?php if ($field->isPartial()): ?>
    <?php include_partial('redirect/' . $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
    <?php include_component('redirect', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
    <div class="<?php echo $class ?><?php $form[$name]->hasError() and print ' errors' ?>">
        <?php echo $form[$name]->renderError() ?>
        <div>
            <?php
            if ($label == "Откуда") {?>
            <label for="redirect_inurl">Откуда<br/><span style="font-size: 12px;">Должно иметь вид: "onona.ru/***"</span></label><?
            } else {
                echo $form[$name]->renderLabel($label);
            }
            ?>

            <div class="content"><?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?></div>

            <?php if ($help): ?>
                <div class="help"><?php echo __($help, array(), 'messages') ?></div>
    <?php elseif ($help = $form[$name]->renderHelp()): ?>
                <div class="help"><?php echo $help ?></div>
    <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
