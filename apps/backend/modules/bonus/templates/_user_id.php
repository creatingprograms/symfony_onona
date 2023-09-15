
<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_copy">
        <div>
      <label for="copy">Данные для копирования</label>
      <div class="content">Почта: <?php
echo $bonus->getSfGuardUser()->getEmailAddress();
?><br />
ID пользователя: <?php
echo $bonus->getSfGuardUser()->getId();
?>
      </div>

          </div>
  </div>