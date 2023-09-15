<? global $isNew; ?>
<? if(!$isNew) :?>
  <main class="wrapper">Письмо, для восстановления пароля, отправлено вам на почту.</main>
<? else :?>
  <?
    $h1='Письмо отправлено';
    slot('breadcrumbs', [
      // ['text' => 'Личный кабинет', 'link'=>'/lk'],
      ['text' => $h1],
    ]);
    slot('h1', $h1);
  ?>
  <div class="wrap-block wrap-block-reg">
    <div class="container">
      <div class="block-content">
        <p>Письмо, для восстановления пароля, отправлено вам на почту.</p>
      </div>
    </div>
  </div>
<? endif ?>
