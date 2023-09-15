<?php

/**
 * Coupons form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CouponsForm extends BaseCouponsForm
{
  public function configure()
  {
    // echo '<pre>~!'; var_dump($this->getObject()->getConditions());echo '!~</pre>';
    // die('----------------configure-------------');
  }

  /*protected function doSave($con = null) {
    var_dump($this->values);
    die('----------------doSave-------------');
    parent::doSave($con);
  }*/
  protected function doBind(array $values) {
    $values['conditions']=serialize($values['conditions']);
    parent::doBind($values);
  }
  public function getConditionsState(){
    return unserialize($this->getObject()->getConditions());
  }

  public function getConditions(){
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sqlBody=
      "SELECT `id`, `name`, `value` "
      ."FROM `dop_info` "
      ."WHERE `name`='Коллекция' "
      ."";
    $collectionsTmp=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($collectionsTmp as $value) {
      $collections[$value['id']]=$value;
    }
    unset($collectionsTmp);

    $sqlBody=
      "SELECT `id`, `name`, `value` "
      ."FROM `dop_info` "
      ."WHERE `name`='Производитель' "
      ."ORDER BY `name` ASC "
      ."";
    $manufacturersTmp=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($manufacturersTmp as $value) {
      $manufacturers[$value['id']]=$value;
    }
    unset($manufacturersTmp);

    $sqlBody=
      "SELECT `id`, `name`, `value` "
      ."FROM `dop_info` "
      ."WHERE `name`='Для кого' "
      ."";
    $suitableForTmp=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($suitableForTmp as $value) {
      $suitableFor[$value['id']]=$value;
    }
    unset($suitableForTmp);

    $sqlBody=
      "SELECT `id`, `name`, `parents_id` "
      ."FROM `category` "
      ."WHERE `is_public`=1 "
      ."ORDER BY `parents_id` ASC, `name` ASC "
      ."";
    $cats=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    if(sizeof($cats)) foreach ($cats as $key => $cat) {
      $catsNames[$cat['id']]=$cat['name'];
      if($cat['parents_id'] ) {
        if(!isset($newCats[$cat['parents_id']])) continue;
        $cat['name']='---'.$cat['name'];
        $newCats[$cat['parents_id']]['CHILDS'][]=$cat;
      }
      else{
        $newCats[$cat['id']]=$cat;
      }
      unset($cats[$key]);
    }

    return [
      'collections' => $collections,
      'brands' => $manufacturers,
      'cats' => $newCats,
      'catsNames' => $catsNames,
      'suitable-for' => $suitableFor,
    ];
  }
}
