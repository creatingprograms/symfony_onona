<?
if (is_object($page)) {
  $title = $page->getTitle() == '' ? $page->getName() : $page->getTitle();
  $keyw = $page->getKeywords() == '' ? $page->getName() : $page->getKeywords();
  $descr = $page->getDescription() == '' ? $page->getName() : $page->getDescription();
  slot('metaTitle', $title);
  slot('metaKeywords', $keyw);
  slot('metaDescription', $descr);
  slot('catalog-class', $page->getClass());
  slot('breadcrumbs', [
    ['text' => $page->getName()],
  ]);
  slot('h1', $page->getName());
  $content = $page->getContent();
}
ob_start();
$i = 0;
$vacs = $pager->getResults();
?>
<? if (sizeof($vacs)) : ?>
  <div class="vacancies-text">
    <div class="vacancies-text-h">
      Актуальные вакансии:</div>
    <div class="vacancies-detaillist">
      <? foreach ($vacs as $vac) : ?>
        <div class="vacancies-element">
          <div class="vacancies-header js-hide-show-next">
            <?= $vac->getName() ?></div>
          <div class="vacancies-detail<?= $i++ ? ' mfp-hide' : '' ?>">
            <?= $vac->getContent() ?>
          </div>
        </div>
      <? endforeach ?>
    </div>
  </div>
<? endif ?>
<?= '</div></div></div>' ?>
<div class="vacs-wrapper wrap-block wrap-block-form">
  <div class="container">
    <div class="block-content">

      <div class="h2">Присоединяйтесь к команде Он и Она</div>
      <form class="form form-edit vacs-form js-ajax-form" action="/vakansii">
        <div class="col">
          <div class="form-edit__content">
            <div class="field field-default">
              <label for="">Имя</label>
              <input type="text" placeholder="Иван" value="" name="name">
            </div>
            <div class="field field-default">
              <label for="">E-mail</label>
              <input type="email" name="email" placeholder="mail@mail.ru">
            </div>
          </div>
        </div>
        <div class="col">
          <div class="form-edit__content">
            <div class="field field-default">
              <label for="">Телефон</label>
              <input type="tel" placeholder="+7 950 000 123" class="js-phone-mask" name="phone">
            </div>
            <div class="field field-default">
              <label for="">Интересующая вакансия</label>
              <div class="wrap-custom-select">
                <select class="custom-select" name="vac-id">
                  <? foreach ($vacs as $vac) : ?>
                    <option value="<?= $vac->getId() ?>"><?= $vac->getName() ?></option>
                  <? endforeach ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="wrap-btn">
          <button class="btn-full btn-full_rad js-submit-button">Отправить заявку</button>
          <div class="custom-check">
            <div class="check-check_block" style="display: none;">
              <input type="checkbox" name="agreement" id="vacs_agreement" class="check-check_input" checked="">
              <div class="custom-check_shadow"></div>
            </div>
            <label for="vacs_agreement" class="custom-check_label">Нажимая отправить, вы даете <a href="/personal_accept">согласие</a> на обработку персональных данных.</label>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?= '<div class="container"><div class="block-content"><div class="vacancies"><div class="vacancies-text">' ?>
<?

$data = ob_get_clean();

$content = str_replace('{vacs}', $data, $content) . '</div>';

echo $content;
