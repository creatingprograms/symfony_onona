<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_endaction<?php $form['endaction']->hasError() and print ' errors' ?>">
         <?php echo $form['endaction']->renderError() ?>
    <div>
        <?php echo $form['endaction']->renderLabel('Категории') ?> 

        <div class = "content"><?php echo $form['endaction']->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?></div>
    </div>
</div>