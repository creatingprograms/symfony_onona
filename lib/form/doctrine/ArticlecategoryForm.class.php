<?php

/**
 * Articlecategory form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ArticlecategoryForm extends BaseArticlecategoryForm
{
  public function configure()
  {
      unset($this['position'],$this['category_articles_list'],$this['categoryarticle_catalogs_list']);
  }
}
