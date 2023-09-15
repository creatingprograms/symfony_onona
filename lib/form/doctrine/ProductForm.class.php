<?php

/**
 * Product form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductForm extends BaseProductForm {

    protected $numbersToDelete = array();
    protected $photoToDelete = array();

    public function configure() {
        if (isset($this->widgetSchema['newbie_description']))
            $this->widgetSchema['newbie_description'] = new sfWidgetFormCKEditor();
            
        unset($this['position'], $this['positionrelated']/* , $this['user'] */, $this['id1c'], $this['buywithitem'], $this['user'], $this['moderuser']);
        if (sfContext::getInstance()->getUser()->hasPermission("Просмотр фото и видео товаров")) {
            unset($this['generalcategory_id'], $this['category_products_list'], $this['dop_info_products_list'], $this['newPhotoalbums'], $this['createNewPhotoalbum'], $this['Photoalbums'], $this['photoalbums_list'], $this['parents_id'], $this['content'], $this['price'], $this['old_price'], $this['startaction'], $this['endaction'], $this['step'], $this['discount'], $this['nextdiscount'], $this['bonuspay'], $this['count'], $this['videoenabled'], $this['views_count'], $this['votes_count'], $this['rating'], $this['is_public'], $this['is_related'], $this['sync'], $this['bonus'], $this['created_at'], $this['yamarket'], $this['tags'], $this['adult'], $this['is_visiblechildren'], $this['is_visiblecategory'], $this['user'], $this['moder'], $this['is_notadddelivery'], $this['sortpriority'], $this['yamarket_clothes'], $this['yamarket_color'], $this['yamarket_typeimg'], $this['yamarket_category'], $this['yamarket_sex'], $this['yamarket_model'], $this['h1'], $this['title'], $this['keywords'], $this['description']);
            $this->setWidget('name', new sfWidgetFormPrintText(array('value' => $this->getObject()->getName())));
            $this->setWidget('slug', new sfWidgetFormPrintText(array('value' => $this->getObject()->getSlug())));
            $this->setWidget('code', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCode())));
            $this->setWidget('video', new sfWidgetFormPrintText(array('value' => $this->getObject()->getVideo())));
            $photoalbums = $this->getObject()->getPhotoalbums();

            $this->setWidget('doc1', new sfWidgetFormPrintText(array('value' => $this->getObject()->getDoc1())));
            $this->setWidget('doc2', new sfWidgetFormPrintText(array('value' => $this->getObject()->getDoc2())));
            $this->setWidget('doc3', new sfWidgetFormPrintText(array('value' => $this->getObject()->getDoc3())));
            $this->setWidget('doc4', new sfWidgetFormPrintText(array('value' => $this->getObject()->getDoc4())));
            $this->setWidget('doc5', new sfWidgetFormPrintText(array('value' => $this->getObject()->getDoc5())));

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $photos = $q->execute("SELECT * FROM  photo where album_id in (" . implode(",", $photoalbums->getPrimaryKeys()) . ")")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $photoText = "";
            foreach ($photos as $photo) {
                $photoText.="https://onona.ru/uploads/photo/" . $photo['filename'] . "<br>";
                $photoText.="<img src='https://onona.ru/uploads/photo/thumbnails_250x250/" . $photo['filename'] . "'><br><br><hr><br><br>";
            }
            $this->setWidget('Photoalbums', new sfWidgetFormPrintText(array('value' => $photoText)));
        } else {
            if ($this->isNew) {
                $this->setWidget('video', new sfWidgetFormInputFile());
                $this->setWidget('doc1', new sfWidgetFormInputFile());
                $this->setWidget('doc2', new sfWidgetFormInputFile());
                $this->setWidget('doc3', new sfWidgetFormInputFile());
                $this->setWidget('doc4', new sfWidgetFormInputFile());
                $this->setWidget('doc5', new sfWidgetFormInputFile());
                $this->setWidget('file', new sfWidgetFormInputFile());
            } else {
                $this->widgetSchema['file'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Изображение gif',
                    'file_src' => '/uploads/photo/' . $this->getObject()->getFile(),
                    'is_image' => true,
                    'edit_mode' => $this->getObject()->getFile(),
                    /*'template' => ''.
                    ' <img src="%file%">'.
                    ' <br />%input%<br />%delete% %delete_label%',*/
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
                $this->widgetSchema['video'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Видео',
                    'file_src' => '/uploads/video/' . $this->getObject()->getVideo(),
                    'is_image' => false,
                    'edit_mode' => $this->getObject()->getVideo(),
                    'template' => '<video controls="controls">'.
                    ' <source src="%file%">'.
                    ' </video><br />%input%<br />%delete% %delete_label%',
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
                $this->widgetSchema['doc1'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Инструкция 1',
                    'file_src' => '/uploads/docs/' . $this->getObject()->getDoc1(),
                    'is_image' => false,
                    'edit_mode' => $this->getObject()->getDoc1(),
                    'template' => '%file%<br />%input%<br />%delete% %delete_label%',
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
                $this->widgetSchema['doc2'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Инструкция 2',
                    'file_src' => '/uploads/docs/' . $this->getObject()->getDoc2(),
                    'is_image' => false,
                    'edit_mode' => $this->getObject()->getDoc2(),
                    'template' => '%file%<br />%input%<br />%delete% %delete_label%',
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
                $this->widgetSchema['doc3'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Инструкция 3',
                    'file_src' => '/uploads/docs/' . $this->getObject()->getDoc3(),
                    'is_image' => false,
                    'edit_mode' => $this->getObject()->getDoc3(),
                    'template' => '%file%<br />%input%<br />%delete% %delete_label%',
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
                $this->widgetSchema['doc4'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Инструкция 4',
                    'file_src' => '/uploads/docs/' . $this->getObject()->getDoc4(),
                    'is_image' => false,
                    'edit_mode' => $this->getObject()->getDoc4(),
                    'template' => '%file%<br />%input%<br />%delete% %delete_label%',
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
                $this->widgetSchema['doc5'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Инструкция 5',
                    'file_src' => '/uploads/docs/' . $this->getObject()->getDoc5(),
                    'is_image' => false,
                    'edit_mode' => $this->getObject()->getDoc5(),
                    'template' => '%file%<br />%input%<br />%delete% %delete_label%',
                        // 'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ));
            }

            $this->setValidator('file', new sfValidatorFile(array(
              'mime_types' => ['image/gif'],
              'required' => false,
              'path' => sfConfig::get('sf_upload_dir') . '/photo/',
            )));
            $mime_types = array('video/mpeg', 'video/mp4', 'video/ogg', 'video/quicktime',
                'video/webm', 'video/x-flv', 'video/x-ms-wmv', 'video/3gpp', 'video/3gpp2'
            );
            $this->setValidator('video', new sfValidatorFile(array(
                'mime_types' => $mime_types,
                'required' => false,
                'path' => sfConfig::get('sf_upload_dir') . '/video/',
            )));
            $this->setValidator('doc1', new sfValidatorFile(array(
                // 'mime_types' => $mime_types,
                'required' => false,
                'path' => sfConfig::get('sf_upload_dir') . '/docs/',
            )));
            $this->setValidator('doc2', new sfValidatorFile(array(
                // 'mime_types' => $mime_types,
                'required' => false,
                'path' => sfConfig::get('sf_upload_dir') . '/docs/',
            )));
            $this->setValidator('doc3', new sfValidatorFile(array(
                // 'mime_types' => $mime_types,
                'required' => false,
                'path' => sfConfig::get('sf_upload_dir') . '/docs/',
            )));
            $this->setValidator('doc4', new sfValidatorFile(array(
                // 'mime_types' => $mime_types,
                'required' => false,
                'path' => sfConfig::get('sf_upload_dir') . '/docs/',
            )));
            $this->setValidator('doc5', new sfValidatorFile(array(
                // 'mime_types' => $mime_types,
                'required' => false,
                'path' => sfConfig::get('sf_upload_dir') . '/docs/',
            )));
            $this->validatorSchema['video_delete'] = new sfValidatorPass();
            $this->validatorSchema['file_delete'] = new sfValidatorPass();
            $this->validatorSchema['doc5_delete'] = new sfValidatorPass();
            $this->validatorSchema['doc4_delete'] = new sfValidatorPass();
            $this->validatorSchema['doc3_delete'] = new sfValidatorPass();
            $this->validatorSchema['doc2_delete'] = new sfValidatorPass();
            $this->validatorSchema['doc1_delete'] = new sfValidatorPass();
            //$this->setWidget('video', new sfWidgetFormInputFileEditable());
            /* $this->setWidget('video', new sfWidgetFormInputFileEditable(array(
              'file_src' => sfConfig::get('sf_upload_dir') . '/video/' . $this->getObject()->getVideo(),
              'is_image' => false,
              'with_delete' => false,
              'edit_mode' => !$this->isNew() && $this->getObject()->getVideo(),
              )));

              $mime_types = array('video/mpeg', 'video/mp4', 'video/ogg', 'video/quicktime',
              'video/webm', 'video/x-flv', 'video/x-ms-wmv', 'video/3gpp', 'video/3gpp2'
              );
              $this->setValidator('video', new sfValidatorFile(array(
              'mime_types' => $mime_types,
              'path' => sfConfig::get('sf_upload_dir') . '/video',
              'required' => false,
              )));


              $this->setValidator('video_delete', new sfValidatorBoolean()); */




            $this->setWidget('endaction', new sfWidgetFormDateJQueryUI(array("culture" => "ru", "change_month" => true, "change_year" => true)));
            $this->setWidget('startaction', new sfWidgetFormDateJQueryUI(array("culture" => "ru", "change_month" => true, "change_year" => true)));

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();

            $timerProducts = sfTimerManager::getTimer('Form: Загрузка всех товаров родителей');
            $choiseProduct = $q->execute("SELECT id,name FROM  `product`")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $this->setWidget('parents_id', new sfWidgetFormDoctrineChoiceSearchArray(array('choises' => $choiseProduct, 'add_empty' => true)));
            $timerProducts->addTime();



            $timerPhotoalbum = sfTimerManager::getTimer('Form: Загрузка всех фотоальбомов');
            $choisePhotoalbum = $q->execute("SELECT id,name FROM  `photoalbum`")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $this->setWidget('photoalbums_list', new sfWidgetFormDoctrineChoiceSearchArray(array('choises' => $choisePhotoalbum, 'add_empty' => true)));
            $timerPhotoalbum->addTime();


            ////$this->setWidget('parents_id', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => false, 'model' => $this->getRelatedModelName('Parent'), 'method' => 'getNameForParent', 'order_by' => array('position', 'ASC'), 'add_empty' => true)));
            //$this->setWidget('created_at', new sfWidgetFormDateTime());
            $this->widgetSchema['created_at']->setOption('default', time());
            $this->widgetSchema['rating']->setOption('default', 10);
            $this->widgetSchema['votes_count']->setOption('default', 1);

            $this->setWidget('dop_info_products_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'method' => 'getNameValue', 'order_by' => array('position', 'ASC')), array('style' => 'height:200px')));

            /* $this->setWidget('user', new sfWidgetFormDoctrineChoice(array('multiple' => false, 'model' => 'sfGuardUser', 'query' => sfGuardUserTable::getInstance()
              ->createQuery('u')
              ->where('id in (SELECT sfGuardUserPermission.user_id
              FROM  sfGuardUserPermission
              WHERE  sfGuardUserPermission.permission_id =8)'), 'add_empty' => true), array('style' => 'height:17px'))); */

            $this->setWidget('is_related', new sfWidgetFormInputCheckbox(array(), array('value' => false)));

            $this->setWidget('category_products_list', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => true, 'model' => 'Category', 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC')), array('style' => 'height:200px')));

            $this->setWidget('generalcategory_id', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => false, 'model' => $this->getRelatedModelName('GeneralCategory'), 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC'))));

            // step 2
            /* $newProductPhotoalbums = new ProductPhotoalbum();
              $newProductPhotoalbums->setProduct($this->object);
              $newProductPhotoalbumsForm = new ProductPhotoalbumForm($newProductPhotoalbums);
              $this->embedForm('newProductPhotoalbums', $newProductPhotoalbumsForm); */
            $newPhotoalbumsForm = new PhotoalbumForm();
            $this->embedForm('newPhotoalbums', $newPhotoalbumsForm);

            $this->embedRelation('Photoalbums');

            $this->setValidator("name", new sfValidatorString(array('max_length' => 255, 'required' => true)));

            $this->setValidator("code", new sfValidatorString(array('max_length' => 255, 'required' => true)));

            $this->setValidator("price", new sfValidatorString(array('max_length' => 255, 'required' => true)));
            if ($this->isNew) {

                $this->setWidget('createNewPhotoalbum', new sfWidgetFormInputCheckbox());

                $this->widgetSchema['createNewPhotoalbum']->setOption('default', true);
                $this->setValidator("createNewPhotoalbum", new sfValidatorBoolean(array('required' => false)));
            } else {
                $this->setWidget('createNewPhotoalbum', new sfWidgetFormInputHidden(array(), array('value' => false)));
                $this->setValidator("createNewPhotoalbum", new sfValidatorBoolean(array('required' => false)));
            }
        }
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->isNew()) {
            if (sfContext::hasInstance()) {
                $productFilter = sfContext::getInstance()->getUser()->getAttributeHolder();
                $productFilter = $productFilter->get("product.filters", null, "admin_module");
                if (count($productFilter['category_products_list']) == 1) {
                    $this->widgetSchema['generalcategory_id']->setOption('default', $productFilter['category_products_list'][0]);

                    $this->setDefault('category_products_list', array($productFilter['category_products_list'][0]));

                    // print_r($productFilter);
                }
            }
        }
    }

    protected function doBind(array $values) {
        // step 3.1
        if ('' === trim($values['newPhotoalbums']['name'])) {
            $this->validatorSchema['newPhotoalbums'] = new sfValidatorString();
        }

        if (isset($values['Photoalbums'])) {
            foreach ($values['Photoalbums'] as $key => $photoalbums) {
                if (isset($photoalbums['delete']) && $photoalbums['id']) {
                    $this->numbersToDelete[$key] = $photoalbums['id'];
                }
                foreach ($values['Photoalbums'][$key]['newPhoto'] as $newPhoto) {
                    if ($newPhoto['filename']['name'] != "") {
                        /*   $values['Photoalbums'][0]['newPhoto']['album_id']=$values['Photoalbums'][$key]['id'];
                          $fileinfoArray[]=$values['Photoalbums'][$key]['newPhoto']['filename'];
                          $newPhotoForm= new PhotoForm();
                          $newPhotoForm->bindAndSave($values['Photoalbums'][0]['newPhoto'],$fileinfoArray);
                          print_r($values['Photoalbums'][0]['newPhoto']);
                          exit;
                          $newPhotoForm->save(); */
                        $file = null;
                        $fileMode = 0666;
                        $create = true;
                        $dirMode = 0777;
                        $fileOrigName = $newPhoto['filename']['name'];
                        /* $file_name = sha1($fileOrigName . rand(11111, 99999)); */
                        $file_name = md5(uniqid(rand(), true));
                        $file_name.= (false === $pos = strrpos($fileOrigName, '.')) ? ".jpg" : substr($fileOrigName, $pos);
                        //$img = new sfImage(sfConfig::get('sf_upload_dir') . '/photo/' . $file_name);

                        /*
                         * Эскизы 60х60
                         */
                        $img = new sfImage($newPhoto['filename']['tmp_name']);
                        //echo csSettings::get("width_thumb");exit;
                        $img->thumbnail(60, 60, 'scale', '#bdbdbd');
                        $img->setQuality(85);
                        $img->saveAs(sfConfig::get('sf_upload_dir') . '/photo/thumbnails_60x60/' . $file_name);

                        /*
                         * Эскизы 250x250
                         */
                        $img = new sfImage($newPhoto['filename']['tmp_name']);
                        //echo csSettings::get("width_thumb");exit;
                        $img->thumbnail(250, 250, 'scale', '#bdbdbd');
                        $img->setQuality(85);
                        $img->saveAs(sfConfig::get('sf_upload_dir') . '/photo/thumbnails_250x250/' . $file_name);


                        /*
                         * Основное фото
                         */
                        $img = new sfImage($newPhoto['filename']['tmp_name']);
                        if ($img->getWidth() > csSettings::get("width_photo") or $img->getHeight() > csSettings::get("height_photo"))
                            $img->thumbnail(csSettings::get("width_photo"), csSettings::get("height_photo"), 'scale', '#bdbdbd');
                        // $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/overlay.png'), 'middle-center');
                        $img->setQuality(85);
                        $img->saveAs(sfConfig::get('sf_upload_dir') . '/photo/' . $file_name);


                        /*
                         * Оригинальное фото
                         */
                        //            $img = new sfImage($newPhoto['filename']['tmp_name']);
                        //            /* if ($img->getWidth() > csSettings::get("width_photo") or $img->getHeight() > csSettings::get("height_photo"))
                        //               $img->thumbnail(csSettings::get("width_photo"), csSettings::get("height_photo"), 'scale', '#bdbdbd'); */
                        //            // $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/overlay.png'), 'middle-center');
                        //            $img->setQuality(100);
                        //            $img->saveAs(sfConfig::get('sf_upload_dir') . '/photo/original_photo/' . $file_name);


                        $newPhoto = new Photo();
                        $newPhoto->setAlbumId($values['Photoalbums'][$key]['id']);
                        $newPhoto->setFilename($file_name);
                        $newPhoto->setName($newPhoto['name']);
                        $newPhoto->set("is_public", $newPhoto['is_public']);
                        $newPhoto->save();
                    }
                }

                if (isset($values['Photoalbums'][$key]['Photos'])) {
                    foreach ($values['Photoalbums'][$key]['Photos'] as $key2 => $photo) {
                        if (isset($photo['photodelete']) && $photo['id']) {
                            $photoDeleteArray['id'] = $photo['id'];
                            $photoDeleteArray['album_index'] = $key;
                            $this->photoToDelete[$key2] = $photoDeleteArray;
                        }
                    }
                }
            }
        }
        /* print_r(array_keys($values['Photoalbums'][0]['newPhoto']));
          exit; */
        parent::doBind($values);
    }

    protected function doSave($con = null) {
        //print_r($this->values);
        if ($this->values['Photoalbums'][0]['id'] != $this->values['photoalbums_list'][0])
            unset($this->values['Photoalbums'], $this->values['newPhotoalbums'], $this->values['createNewPhotoalbum'], $this['Photoalbums'], $this['newPhotoalbums'], $this['createNewPhotoalbum']);
        //print_r($this->values);
        //exit;
        parent::doSave($con);
    }

    protected function doUpdateObject($values) {
        if ($values['video'] != "") {
          // $path=str_replace('lib/form/doctrine/', '', __DIR__).'bin/';
            $videoVal = explode(".", $values['video']);
            if (@$videoVal[1] != "webm" and @ file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" . $values['video'])) {
                exec("sh /var/www/ononaru/data/www/encodeVideo.sh /var/www/ononaru/data/www/onona.ru/uploads/video/" . $values['video'] . " /var/www/ononaru/data/www/onona.ru/uploads/video/" . $videoVal[0] . "  > /dev/null &", $test);
                $values['video'] = $videoVal[0] . ".webm";
            }
        }
        // step 4.4
        // print_r($this->getValue('category_products_list'));exit;
        if ($values['discount'] != "" and $values['old_price'] == "") {
            $values['old_price'] = $values['price'];
            $values['price'] = round($values['price'] - ($values['price'] * $values['discount'] / 100));
        } elseif ($values['discount'] != "" and $values['old_price'] != "") {
            $values['price'] = round($values['old_price'] - ($values['old_price'] * $values['discount'] / 100));
        }
        $values['slug'] = str_replace(" ", "-", $values['slug']);
        $values['code'] = trim($values['code']);
        if (@$values['id'] != "") {
            $product = ProductTable::getInstance()->findOneById($values['id']);
            if ($values['moder'] == 0 and $product->getModeruser() == "") {
                $values['moderuser'] = sfContext::getInstance()->getUser()->getGuardUser()->getId();
            }
        }

        if ($values['views_count'] == "")
            $values['views_count'] = rand(200, 800);
        if (count($this->numbersToDelete)) {
            foreach ($this->numbersToDelete as $index => $id) {
                unset($values['Photoalbums'][$index]);
                unset($this->object['Photoalbums'][$index]);
                ProductPhotoalbumTable::getInstance()->findByPhotoalbumId($id)->delete();
                PhotoalbumTable::getInstance()->findOneById($id)->delete();
            }
        }

        if (count($this->photoToDelete)) {
            foreach ($this->photoToDelete as $index => $id) {
                /*  unset($values['Photoalbums'][$id['album_index']]['Photos'][$index]);
                  unset($this->object['Photoalbums'][$id['album_index']]['Photos'][$index]);
                 */
                $idDel = $id['id'];
                //PhotoTable::getInstance()->findOneById($idDel)->delete();
                $photos = PhotoTable::getInstance()->createQuery()->where("id=?", $idDel)->execute();
                foreach ($photos as $photo) {
                    if ($photo->getFilename() != "") {
                        unlink(sfConfig::get('sf_upload_dir') . '/photo/thumbnails_250x250/' . $photo->getFilename());
                        unlink(sfConfig::get('sf_upload_dir') . '/photo/thumbnails_60x60/' . $photo->getFilename());
                        unlink(sfConfig::get('sf_upload_dir') . '/photo/original_photo/' . $photo->getFilename());
                        unlink(sfConfig::get('sf_upload_dir') . '/photo/' . $photo->getFilename());
                    }
                }
                PhotoTable::getInstance()->createQuery()->delete()->where("id=?", $idDel)->execute();
            }
        }
        parent::doUpdateObject($values);
    }

    public function saveEmbeddedForms($con = null, $forms = null) {

        //        foreach ($this->getValue('category_products_list') as $category_id) {
        //            $category = CategoryTable::getInstance()->findOneById($category_id);
        //            $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newdis/*/template/*/all';
        //            $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
        //            $cache->removePattern('/category/show/slug/' . $category->getSlug());
        //        }
        if (null === $con) {
            $con = $this->getConnection();
        }
        $isphotoalbums = $this->getValue('Photoalbums');
        if ($this->isNew and ! $this->oneCreateAlbum and $this->getValue('createNewPhotoalbum')) {
            $name = $this->getValue('code') . " " . $this->getValue('name');
            $photoalbumIsSet = PhotoalbumTable::getInstance()->findOneByName($name);

            if ($photoalbumIsSet)
                $name = $this->getValue('code') . " " . $this->getValue('name') . " - " . rand(10, 99);
            $photoAlbumNew = new Photoalbum();
            $photoAlbumNew->setName($name);
            $photoAlbumNew->save();

            $newProductPhotoalbums = new ProductPhotoalbum();
            $newProductPhotoalbums->setProduct($this->object);
            $newProductPhotoalbums->setPhotoalbum($photoAlbumNew);
            $newProductPhotoalbums->save();
            $this->oneCreateAlbum = 1;

            $this->object->moveToFirst();
        }
        // step 3.2
        if (null === $forms) {
            $photoalbums = $this->getValue('newPhotoalbums');
            $forms = $this->embeddedForms;

            if ('' === trim($photoalbums['name'])) {
                unset($forms['newPhotoalbums']);
            } else {

            }
        } else {
            $photoalbums = $this->getValue('newPhotoalbums');

            if ('' === trim($photoalbums['name'])) {
                unset($forms['newPhotoalbums']);
            } else {

            }
        }
        foreach ($forms as $form) {

            if ($form instanceof sfFormObject) {
                if (!in_array($form->getObject()->getId(), $this->numbersToDelete)) {
                    if ($form->getModelName() == "Photo") {
                        $form->saveEmbeddedForms($con, null, $this->photoToDelete);
                    } else {
                        $form->saveEmbeddedForms($con, null, $this->photoToDelete);
                    }
                    $formPhotoalbum = $form->getObject();
                    if ($formPhotoalbum->getName() != "") {
                        $formPhotoalbum->save($con);
                        $formPhotoalbumCount = ProductPhotoalbumTable::getInstance()->createQuery()->where("product_id = ?", $this->object->getId())->where("photoalbum_id = ?", $formPhotoalbum->getId())->execute();

                        if ($formPhotoalbumCount->count() == 0) {
                            $newProductPhotoalbums = new ProductPhotoalbum();
                            $newProductPhotoalbums->setProduct($this->object);
                            $newProductPhotoalbums->setPhotoalbum($formPhotoalbum);
                            $newProductPhotoalbums->save();
                        }
                    }
                }
            } else {
                $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
            }
        }
    }

    public function savePhotoalbumsList($con = null) {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }

        if (!isset($this->widgetSchema['photoalbums_list'])) {
            // somebody has unset this widget
            return;
        }

        if (null === $con) {
            $con = $this->getConnection();
        }

        $existing = $this->object->Photoalbums->getPrimaryKeys();
        $values = $this->getValue('photoalbums_list');
        if (!is_array($values)) {
            $values = array();
        }

        $unlink = array_diff($existing, $values);

        if (count($unlink)) {
            $this->object->unlink('Photoalbums', array_values($unlink));
            foreach ($unlink as $unlinkProd) {
                //echo $unlinkProd; exit;
                $ProductPhotoalbum = ProductPhotoalbumTable::getInstance()->createQuery()->where('product_id=' . $this->object->getId() . ' and photoalbum_id=' . $unlinkProd)->fetchOne();
                $ProductPhotoalbum->delete();
            }
        }

        $link = array_diff($values, $existing);

        if (count($link)) {
            $this->object->link('Photoalbums', array_values($link));
        }
    }

}
