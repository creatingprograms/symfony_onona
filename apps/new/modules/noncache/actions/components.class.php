<?php

class noncacheComponents extends sfComponents {

  public function executeCaltat(sfWebRequest $request) {
  }
  public function executeShareblock(sfWebRequest $request) {

  }
  public function executeAdvcake(sfWebRequest $request) {
  }

  public function executeReviewsFilter(sfWebRequest $request) {//Фильтр отзывов
    $sqlBody="SELECT `subid` as `id`, `name` FROM `manufacturer` WHERE `is_public`=1 ORDER BY `name`";
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $this->brands=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    $sqlBody="SELECT `slug` as `id`, `menu_name` as `name` FROM `catalog` WHERE `is_public`=1";
    $this->catalog=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    // die('<pre>'.print_r($this->brands, true));

  }
  public function executeHoversignal(sfWebRequest $request) {//Колесо фортуны
    if(!isset($_COOKIE['show_hover_fortune']) || $_COOKIE['show_hover_fortune']!='N'){
      $this->showWheel=true;
      setcookie('show_hover_fortune', 'N', time()+60*24*60*60, '/', 'onona.ru');
    }
    else{
      $this->showWheel=false;
    }
  }

  public function executeTimeAction(sfWebRequest $request) {
    $coupon = CouponsTable::getInstance()
      ->createQuery()
      ->select("*")
      ->where("`is_active` = 1 AND `is_promo` = 1")
      ->addWhere('`startaction` < "'.date('Y-m-d H:i:s').'"')
      ->addWhere('`endaction` > "'.date('Y-m-d H:i:s').'"')
      ->fetchOne();
    $this->coupon=$coupon;
  }

  public function executeTimeBanner(sfWebRequest $request) {
    $coupon = BottombannerTable::getInstance()
      ->createQuery()
      ->select("*")
      ->where("`is_active` = 1")
      ->addWhere('`startaction` < "'.date('Y-m-d H:i:s').'"')
      ->addWhere('`endaction` > "'.date('Y-m-d H:i:s').'"')
      ->fetchOne();
    $this->coupon=$coupon;
  }

  public function executeBonuscount(sfWebRequest $request) {//Отображает в шапке количество бонусных баллов
    $bonusSum = BonusTable::getInstance()->createQuery()->select("sum(bonus) as bonus")->where("user_id = ?", sfContext::getInstance()->getUser()->getGuardUser()->getId())->fetchOne();
    $this->bonusCount=$bonusSum->getBonus();
  }

  public function executeNotificationCategory(sfWebRequest $request) {
    if(sfContext::getInstance()->getUser()->isAuthenticated())
        $this->notificationCategory = NotificationCategoryTable::getInstance()
            ->createQuery()
            ->where("user_id=?", sfContext::getInstance()->getUser()->getGuardUser()->getId())
            ->addWhere("category_id=?", $this->collectionId)->addWhere("is_enabled='1'")
            ->fetchOne();
  }

  // Пагинация
  public function executePagination(sfWebRequest $request) {

  }

  public function executePaginationCatalog(sfWebRequest $request) {

  }

  // Хлебные крошки
  public function executeBreadcrumbs(sfWebRequest $request) {
    $breadcrumbs=array_merge([['text' => 'Главная', 'link' => '/']], $this->breadcrumbs);
     $this->breadcrumbs= $breadcrumbs;
  }

}
?>
