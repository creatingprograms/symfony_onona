<?php

class articleComponents extends sfComponents {

  public function executeLeftMenu(sfWebRequest $request) {
    $this->ArticleCatalogs = ArticlecatalogTable::getInstance()
      ->createQuery()
      ->where('is_public=1')
      ->orderBy('position ASC')
      ->execute();

  }
  public function executeLeftPreviewBlock(sfWebRequest $request) {
    $this->query = Doctrine_Core::getTable('article')
            ->createQuery('a')->select("*")
            ->where("a.is_public='1'")
            ->addOrderBy("a.position desc")
            ->limit(3)
    ;

    $this->list = $this->query->execute();

  }

  public function executeArticlesBlock(sfWebRequest $request) {

  }
}
