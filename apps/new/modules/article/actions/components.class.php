<?php

class articleComponents extends sfComponents {

  public function executeMore(sfWebRequest $request) {
    $this->query = Doctrine_Core::getTable('article')
            ->createQuery('a')->select("*")
            ->where("a.is_public='1'")
            ->addWhere("a.id<>".$active)
            ->addOrderBy("RAND() desc")
            ->limit(3)
    ;
    $this->list = $this->query->execute();

  }

  public function executeTopMenu(sfWebRequest $request) {
    $this->ArticleCatalogs = ArticlecategoryTable::getInstance()
      ->createQuery()
      ->where('is_public=1')
      ->orderBy('position ASC')
      ->execute();
  }
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

  public function executeTop(sfWebRequest $request) {//Слайдер статей для главной и для разводящих каталога
    if($this->catalog=='main'){
      $this->query = Doctrine_Core::getTable('article')
          ->createQuery('a')->select("*")
          ->where("is_public='1'")
          ->addWhere('is_related=1')
          ->addOrderBy("a.positionrelated desc")
          ->limit(10)
      ;

    }
    if($this->catalog=='inner'){
      $this->query = Doctrine_Core::getTable('article')
          ->createQuery('a')->select("*")
          ->where("is_public='1'")
          ->addWhere("id<>".$this->active)
          ->addOrderBy("RAND() desc")
          ->limit(10)
      ;
    }


    if($this->catalog=='inner' || $this->catalog=='main') $this->list = $this->query->execute();
  }
  public function executeArticlesBlock(sfWebRequest $request) {

  }
}
