<?php

require_once dirname(__FILE__) . '/../lib/manproductGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/manproductGeneratorHelper.class.php';

/**
 * product actions.
 *
 * @package    Magazin
 * @subpackage product
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class manproductActions extends autoManProductActions {

    public function executeIndex(sfWebRequest $request) {

        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        $filters = $this->getFilters();
        $pager = $this->configuration->getPager('Product');
        if (isset($filters['category_products_list']))
            $pager = new sfDoctrinePager('Product', 1000);
        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        if ($filters['code']['text'] != "") {
            $addWhere = "code like '%" . $filters['code']['text'] . "%' ";
        } else {
            // $addWhere = "user = '".$this->getUser()->getGuardUser()->getId()."'";
            $addWhere = "id >0 ";
        }
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                        ->addWhere($addWhere)
                        ->orderBy("(id=16683 or id=16684) ASC")
                        ->addOrderBy("new_product < " . csSettings::get('logo_new') . " Desc")
                        ->addOrderBy("(count>0) DESC")
                        ->addOrderBy("position ASC"));

        /* $pager->setQuery($this->buildQuery()
          ->select("*")
          ->addSelect($selectDate)
          ->addWhere('parents_id IS NULL ')
          ->addOrderBy("(count>0) DESC")
          ->addOrderBy("new_product < ".csSettings::get('logo_new')." Desc")); */


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
    }

    public function executeIndexrelated(sfWebRequest $request) {

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }


        $pager = new sfDoctrinePager('Product', 500);

        $pager->setQuery(Doctrine_Core::getTable('Product')
                        ->createQuery('a')
                        ->select("*")
                        ->addWhere('parents_id IS NULL ')
                        ->addWhere('is_related=1 ')
                        ->addOrderBy("positionrelated ASC"));


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        //$this->sort = $this->getSort();
    }

    public function executeCodeisset(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->artNot = array();
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {
                $handle = fopen($this->file['tmp_name'], "r");
                while (!feof($handle)) {
                    $buffer = fgets($handle, 4096);
                    $product2 = ProductTable::getInstance()->findOneByCode(trim($buffer));

                    //  echo $buffer.$product2."<br/>";
                    if (!$product2) {
                        $this->artNot[] = $buffer;
                    } else {

                    }
                    $product2 = null;
                    //echo $buffer;
                }
                fclose($handle);
            }
        }
    }

    /* public function executeCreate(sfWebRequest $request) {
      $this->form = $this->configuration->getForm();
      $this->product = $this->form->getObject();
      $valuesIsPublic = $this->product->getIsPublic();
      $this->processForm($request, $this->form);

      $this->form->setDefault("is_public", $valuesIsPublic);
      //echo $valuesIsPublic;
      //$this->form->UpdateObject();
      $this->setTemplate('new');
      } */

    public function executeNew(sfWebRequest $request) {
        $this->form = new ManProductForm();
        $this->product = $this->form->getObject();
    }

    public function executeCreate(sfWebRequest $request) {
        /*$this->product = $this->getRoute()->getObject();
        $this->form = new ManProductForm();*/

        $this->form = new ManProductForm();
        $this->product = $this->form->getObject();
        /* $this->form['user']->setValue($this->getUser()->getGuardUser()->getId());
          $this->form['moder']->setValue( 1); */
        $productArr = $request->getParameter($this->form->getName());
        $productArr['user'] = $this->getUser()->getGuardUser()->getId();
        $productArr['moder'] = 1;
        $request->setParameter("product", $productArr);
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->product = $this->getRoute()->getObject();
        $this->form = new ManProductForm($this->product);
        unset($this->form['user'], $this->form['moder']);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->product = $this->getRoute()->getObject();
        $this->form = new ManProductForm($this->product);

        unset($this->form['user'], $this->form['moder']);
        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }

}
