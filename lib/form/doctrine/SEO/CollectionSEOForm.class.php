<?php

/**
 * Collection form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CollectionSEOForm extends BaseCollectionForm
{
  public function configure()
  {
      unset($this['is_public'],$this['subid']);
  }
}
