<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
    <?php foreach ($fields as $name => $field): ?>
        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
        <?php

        $attributes = $field->getConfig('attributes', array());
        $label = $field->getConfig('label');
        $help = $field->getConfig('help');
        $form = $form;
        $field = $field;
        $class = 'sf_admin_form_row sf_admin_' . strtolower($field->getType()) . ' sf_admin_form_field_' . $name;
        if ($label == "Фотоальбомы") {
            ?>
            <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>

            <?

        }
        ?>

    <?php endforeach; ?>
<?php endforeach; ?>