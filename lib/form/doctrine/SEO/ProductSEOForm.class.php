<?php

/**
 * Product form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductSEOForm extends BaseProductForm {

    public function configure() {
        unset($this['position'], $this['positionrelated'], $this['id1c'], $this['buywithitem'], 
                $this['newPhotoalbums'], $this['generalcategory_id'], $this['category_products_list'], $this['createNewPhotoalbum'], $this['name'], $this['slug'], $this['code'], $this['price'], $this['bonus'], $this['bonuspay'], $this['old_price'], $this['discount'], $this['count'], $this['video'], $this['videoenabled'], $this['views_count'], $this['votes_count'], $this['rating'], $this['is_public'], $this['is_visiblechildren'], $this['is_visiblecategory'], $this['sync'], $this['parents_id'], $this['adult'], $this['endaction'], $this['step'], $this['pointcreate'], $this['moder'], $this['moderuser'], $this['user'], $this['stock'], $this['countsell'], $this['sortpriority'], $this['created_at'], $this['dop_info_products_list'], $this['photoalbums_list'], $this['is_related'], $this['yamarket'], $this['yamarket_clothes'], $this['yamarket_color'], $this['yamarket_typeimg'], $this['yamarket_category'], $this['yamarket_sex'], $this['yamarket_model']);

    }

}
