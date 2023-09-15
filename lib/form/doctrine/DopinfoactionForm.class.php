<?php

/**
 * Dopinfoaction form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DopinfoactionForm extends BaseDopinfoactionForm
{
  public function configure()
  {
    $this->setWidget('endaction', new sfWidgetFormDateJQueryUI(array("culture" => "ru", "change_month" => true, "change_year" => true)));
    $this->setWidget('startaction', new sfWidgetFormDateJQueryUI(array("culture" => "ru", "change_month" => true, "change_year" => true)));

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $choiseDopinfo = $q->execute("SELECT id, CONCAT(`name`, ' - ', `value`) as name FROM  `dop_info` WHERE `name`='Производитель' OR `name`='Коллекция' ORDER BY `name`")
            ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $this->setWidget('dopinfo_id', new sfWidgetFormDoctrineChoiceSearchArray(array('choises' => $choiseDopinfo, 'add_empty' => true)));
  }
}
