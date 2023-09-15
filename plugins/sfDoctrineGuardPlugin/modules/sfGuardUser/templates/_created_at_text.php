<?
if($form['id']->getValue()!=""){
$user=  sfGuardUserTable::getInstance()->findOneById($form['id']->getValue());

?>
<div class="sf_admin_form_row sf_admin_date sf_admin_form_field_created_at">
        <div>
      <label for="sf_guard_user_birthday">Дата регистрации</label>
      <div class="content"><?=$user->getCreatedAt()?></div>
      

          </div>
  </div><?}