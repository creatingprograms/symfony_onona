<?php

require_once dirname(__FILE__) . '/../lib/categoryGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/categoryGeneratorHelper.class.php';

/**
 * category actions.
 *
 * @package    Magazin
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categoryActions extends autoCategoryActions {

    public function __construct($context, $moduleName, $controllerName) {
        parent::__construct($context, $moduleName, $controllerName);
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")) {
            if ($controllerName != "index" and $controllerName != "editseo" and $controllerName != "filter" and $controllerName != "update")
                $this->redirect("@category");
        }else{
            HomePage::redirectHomePage(sfContext::getInstance()->getUser(), $this);
        }
    }

    public function executeEditseo(sfWebRequest $request) {
        $this->category = $this->getRoute()->getObject();
        $this->form = new CategorySEOForm($this->category);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->category = $this->getRoute()->getObject();
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")) {
            $this->form = new CategorySEOForm($this->category);
        } else {
            $this->form = $this->configuration->getForm($this->category);
        };
        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeProdstats(sfWebRequest $request) {
        //echo $request->getParameter('id');exit;
        $products = ProductTable::getInstance()->createQuery()->where("generalcategory_id= ?", $request->getParameter('id'))->execute();
        $where = "";
        foreach ($products->getPrimaryKeys() as $prodId) {
            //echo strlen($prodId)." ";
            $where.="text like '%\"product_id\";s:" . strlen($prodId) . ":\"" . $prodId . "\";%' or ";
        }
        $where = trim($where, " or ");
        if ($where != "") {
            $orders = OrdersTable::getInstance()->createQuery()->where($where)->execute();
            //$where = implode("\";%' or text like '%:\"", $products->getPrimaryKeys());
            $productsArray = array();
            foreach ($orders as $order) {
                foreach (unserialize($order->getText()) as $product) {
                    if ($product['productId'] > 0)
                        @$productsArray[$product['productId']] = $productsArray[$product['productId']] + 1;
                    else
                        @$productsArray[$product['product_id']] = $productsArray[$product['product_id']] + 1;
                }
            }
            arsort($productsArray);
            $this->productsArray = $productsArray;
            /* foreach ($productsArray as $prodId => $prodCount) {
              if ($prodId > 0) {
              $product = ProductTable::getInstance()->findOneById($prodId);
              if ($product)
              echo $product->getName() . " - " . $prodCount . "<br />";
              }
              } */
        }
    }

    public function executeIndex(sfWebRequest $request) {
        if (sfContext::getInstance()->getUser()->hasPermission("Bonus"))
            $this->redirect("@bonus");
        if (sfContext::getInstance()->getUser()->hasPermission("Manager category"))
            $this->redirect("@category_mcategory");
        if (sfContext::getInstance()->getUser()->hasPermission("Manager product"))
            $this->redirect("@product_mproduct");
        if (sfContext::getInstance()->getUser()->hasPermission("Manager article"))
            $this->redirect("@article");
        if (sfContext::getInstance()->getUser()->hasPermission("Manager orders"))
            $this->redirect("@orders");
        if (sfContext::getInstance()->getUser()->hasPermission("Manager add product"))
            $this->redirect("@product_manproduct");


        /* if (!sfContext::getInstance()->getUser()->hasPermission("Enter backend"))
          $this->redirect("/"); */

        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        $pager = $this->configuration->getPager('Category');
        $pager = new sfDoctrinePager('Product', 10000);
        $pager->setQuery($this->buildQuery()->addWhere('parents_id IS NULL '));
        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
        $this->items = $pager->getResults();

        $q = Doctrine_Manager::getInstance()->getCurrentConnection()->execute(<<<SQL
SELECT t.category_id, t.total, IFNULL(a.available,0) available
FROM (
  SELECT c.id category_id, count(product_id) total 
  FROM category c LEFT JOIN category_product cp ON c.id = cp.category_id
  GROUP BY c.id
  ORDER BY null
) t LEFT JOIN (
  SELECT cp.category_id, count(p.id) available 
  FROM category_product cp, product p 
  WHERE p.id = cp.product_id AND p.count > 0 AND p.moder=0 and p.is_public=1 
  GROUP BY cp.category_id 
  ORDER BY null
) a ON t.category_id = a.category_id
SQL
);
        $this->stats = array();
        while ($r = $q->fetch()) {
            $this->stats[$r['category_id']] = array($r['available'], $r['total']);
        }
    }

    public function executeAdultChange() {
        $object = Doctrine::getTable('Category')->findOneById($this->getRequestParameter('id'));
        $products = ProductTable::getInstance()->findByGeneralcategoryId($this->getRequestParameter('id'));
        foreach ($products as $product) {
            //echo $product->getName();
            $product->setAdult($object->getAdult() ? 0 : 1);
            $product->save();
        }
        foreach ($object->getCategoryProducts() as $product) {
            //echo $product->getName();
            $product->setAdult($object->getAdult() ? 0 : 1);
            $product->save();
        }
        $object->setAdult($object->getAdult() ? 0 : 1);
        $object->save();
        $this->category = $object;
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }

    public function executePromote() {
        $object = Doctrine::getTable('Category')->findOneById($this->getRequestParameter('id'));


        $object->promote();
        $this->redirect("@category");
    }

    public function executeDemote() {
        $object = Doctrine::getTable('Category')->findOneById($this->getRequestParameter('id'));

        $object->demote();
        $this->redirect("@category");
    }

    public function executeSortChange(sfWebRequest $request) {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $categoryEnd = CategoryTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPosition($categoryEnd[0]->getPosition() + 1);
            $category->save();


            $categorysUpSO = CategoryTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($categorysUpSO as $category) {
                $category->setPosition($category->getPosition() - 1);
                $category->save();
            }

            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPosition($rowsList[$last]);
            $category->save();
        }
        if ($currentSortOrder > $nextSortOrder and ! empty($nextSortOrder)) { //	перемещение вверх
            $categorysUpSO = CategoryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
            foreach ($categorysUpSO as $category) {
                $category->setPosition($category->getPosition() + 1);
                $category->save();
            }
            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPosition($nextSortOrder);
            $category->save();
        }
        if ($currentSortOrder < $nextSortOrder and ! empty($nextSortOrder)) { //	перемещение вниз
            $categorysUpSO = CategoryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
            foreach ($categorysUpSO as $category) {
                $category->setPosition($category->getPosition() + 1);
                $category->save();
            }
            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPosition($nextSortOrder);
            $category->save();
        }

        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        $pager = $this->configuration->getPager('Category');
        $pager->setQuery($this->buildQuery()->addWhere('parents_id IS NULL '));
        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {


            sfCacheManager::clearCache('@sf_cache_partial?module=category&action=_catalogDev&sf_cache_key=*');
            sfCacheManager::clearCache('@sf_cache_partial?module=category&action=_paginator&sf_cache_key=*');

            /* sfContext::switchTo('newcat');
              $cacheManager = sfContext::getInstance()->getViewCacheManager();
              if ($cacheManager) {
              $cacheManager->remove('@sf_cache_partial?module=category&action=_catalogDev&sf_cache_key=*');
              $cacheManager->remove('@sf_cache_partial?module=category&action=_paginator&sf_cache_key=*');
              }
              sfContext::switchTo('backend'); */

            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $category = $form->save();
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $category)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@category_new');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'category_edit', 'sf_subject' => $category));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }

}
