<?php

/**
 * product module configuration.
 *
 * @package    Magazin
 * @subpackage product
 * @author     Belfegor
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productGeneratorConfiguration extends BaseProductGeneratorConfiguration
{

  public function getFilterDisplay()
  {
      if(substr_count(@$_SERVER['HTTP_REFERER'], 'moder')>0 or substr_count(@$_SERVER['REQUEST_URI'], 'moder')>0){
      return array(  0 => 'id',  1 => 'user',  2 => 'created_at'/*, 3 => 'code'*/);

      }else{
          return array(  0 => 'id',  1 => 'name',  2 => 'code',  3 => 'slug',  4 => 'is_public',  5 => 'is_related',  6 => 'sync',  7 => 'category_products_list',  8 => 'dop_info_products_list',  9 => 'count_range',);

      }
    }
  public function getFilterFormClass()
  {
      if(substr_count(@$_SERVER['HTTP_REFERER'], 'moder')>0 or substr_count(@$_SERVER['REQUEST_URI'], 'moder')>0){
          return 'ProductModerFormFilter';
      }else{
          return 'ProductFormFilter';
      }

  }
}
