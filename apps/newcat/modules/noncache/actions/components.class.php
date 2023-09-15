<?php

class noncacheComponents extends sfComponents {

    public function executeTopMenu(sfWebRequest $request) {

    }
    public function executeTopMenuNew(sfWebRequest $request) {

    }

    public function executeBonusCount(sfWebRequest $request) {

    }

    public function executeRelatedProducts(sfWebRequest $request) {

    }
  

    public function executeProductParentNonCount(sfWebRequest $request) {
        $categoryChildrens = $this->category->getChildren();
        $idChildrenCategory = "";
        $idProductsCount0Children = "";
        foreach ($categoryChildrens as $categoryChildren)
            $idChildrenCategory.="," . $categoryChildren->getId();

        $productsCount0Children = ProductTable::getInstance()->createQuery()->leftJoin("Product.Parent par")->where("count>0")->addWhere("par.count=0")->addWhere("is_public=1")
                        ->addWhere('moder = \'0\'')/* ->addWhere("(generalcategory_id IN (" . $this->category->getId() . $idChildrenCategory . "))") */->addWhere("parents_id is not null")->groupBy("parents_id")->execute();

        foreach ($productsCount0Children as $productCount0Children)
            $idProductsCount0Children.="," . $productCount0Children->getParentsId();

        $this->block=$idChildrenCategory."</div><div>".$idProductsCount0Children;
    }

}
?>
