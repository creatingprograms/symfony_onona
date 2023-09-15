<?php

/**
 * DopInfo form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DopInfoForm extends BaseDopInfoForm
{
  public function configure()
  {
        unset($this['dop_info_products_list'], $this['product_list'], $this['slug'], $this['name'], $this['position']);
  }
}
