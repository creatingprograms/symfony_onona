
<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_copy">
        <div>
      <label for="copy">Справочные данные:</label>
      <div class="content">Почта пользователя: <?php
echo $comments->getSfGuardUser()->getEmailAddress();
?><br />
ID пользователя: <?php
echo $comments->getSfGuardUser()->getId();?>


<? if ($comments->getProduct()->getId()):?>
<br /> Товар: <?= $comments->getProduct()->getName();
endif;?>

<?if ($comments->getShops()->getId()):?>
<br /> Магазин: <?= $comments->getShops()->getName();
endif ?>

<?if ($comments->getArticle()->getId()):?>
<br /> Статья: <?= $comments->getArticle()->getName();
endif ?>

<?if ($comments->getPage()->getId()):?>
<br /> Страница: <?= $comments->getPage()->getName();
endif ?>

      </div>

          </div>
  </div>
