
<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_copy">
        <div>
      <label for="copy">Данные для копирования</label>
      <div class="content">Почта: <?php
echo $comments->getSfGuardUser()->getEmailAddress();
?><br />
ID пользователя: <?php
echo $comments->getSfGuardUser()->getId();
?>
      </div>

          </div>
  </div>