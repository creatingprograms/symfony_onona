<?php

/**
 * Product form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MProductForm extends BaseProductForm {

    protected $numbersToDelete = array();
    protected $photoToDelete = array();

    public function configure() {
        unset($this['code'],$this['slug'], $this['content'], $this['count'], $this['video'], $this['views_count'], $this['votes_count'], $this['rating'], $this['is_related'], $this['is_public'], $this['title'], $this['keywords'], $this['description'], $this['parents_id'], $this['id1c'], $this['generalcategory_id'], $this['adult'], $this['position'], $this['positionrelated'], $this['created_at'], $this['user'], $this['moder'], $this['moderuser'], $this['sync'], $this['h1']);

        $this->setValidator("name", new sfValidatorString(array('max_length' => 255, 'required' => true)));

        $this->setValidator("price", new sfValidatorString(array('max_length' => 255, 'required' => true)));
    }

    protected function doBind(array $values) {
        // step 3.1

        /* print_r(array_keys($values['Photoalbums'][0]['newPhoto']));
          exit; */
        parent::doBind($values);
    }

    public function savePhotoalbumsList($con = null) {
        
    }

    public function saveDopInfoProductsList($con = null) {
        
    }

    public function saveCategoryProductsList($con = null) {
        
    }

    protected function doUpdateObject($values) {
        // step 4.4
        if ($values['discount'] != "" and $values['old_price'] == "") {
            $values['old_price'] = $values['price'];
            $values['price'] = round($values['price'] - ($values['price'] * $values['discount'] / 100));
        } elseif ($values['discount'] != "" and $values['old_price'] != "") {
            $values['price'] = round($values['old_price'] - ($values['old_price'] * $values['discount'] / 100));
        }


        parent::doUpdateObject($values);
    }

    public function saveEmbeddedForms($con = null, $forms = null) {
        
    }

}
