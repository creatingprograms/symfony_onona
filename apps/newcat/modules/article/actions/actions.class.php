<?php

/**
 * article actions.
 *
 * @package    test
 * @subpackage article
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->articles = Doctrine_Core::getTable('Article')
                ->createQuery('a')
                ->execute();
    }

    public function executeAddcomment(sfWebRequest $request) {
        $this->errorCap = false;
        $this->errorTxt = false;
        $this->article = Doctrine_Core::getTable('Article')->findOneBySlug(array($request->getParameter('slug')));
        if ($this->getUser()->isAuthenticated()) {

            if ($request->getParameter('cComment') == "") {
                $this->cComment = $request->getParameter('cComment');
                $this->cName = $request->getParameter('cName');
                $this->cEmail = $request->getParameter('cEmail');
                $this->errorTxt = true;
            } else {
                $comment = new Comments();
                $comment->setText($request->getParameter('cComment'));
                $comment->setCustomerId($this->getUser()->getGuardUser()->getId());
                $comment->setArticleId($this->article->getId());
                $comment->save();
            }
        } else {

            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='k'")->fetchOne();
            if ($request->getParameter('cText') == $captcha->getVal() and $request->getParameter('cComment') != "") {
                $comment = new Comments();
                $comment->setText($request->getParameter('cComment'));
                $comment->setUsername($request->getParameter('cName'));
                $comment->setMail($request->getParameter('cEmail'));
                $comment->setArticleId($this->article->getId());
                $comment->save();
            }
            if ($request->getParameter('cText') != $captcha->getVal()) {
                $this->cComment = $request->getParameter('cComment');
                $this->cName = $request->getParameter('cName');
                $this->cEmail = $request->getParameter('cEmail');
                $this->errorCap = true;
            }
            if ($request->getParameter('cComment') == "") {
                $this->cComment = $request->getParameter('cComment');
                $this->cName = $request->getParameter('cName');
                $this->cEmail = $request->getParameter('cEmail');
                $this->errorTxt = true;
            }
        }
    }

    public function executeShow(sfWebRequest $request) {
        $this->article = Doctrine_Core::getTable('Article')->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->article))
            $this->article = Doctrine_Core::getTable('Article')->findOneById(array($request->getParameter('slug')));
        if (empty($this->article)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Article' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->article = Doctrine_Core::getTable('Article')->findOneById($oldSlug->getDopid());
                return $this->redirect('/sexopedia/' . $this->article->getSlug());
            }
        }
        $this->forward404Unless($this->article);
        $articleLinks = ArticlelinkTable::getInstance()->findAll();
        foreach ($articleLinks as $articleLink) {
            $this->article->setContent(str_replace(array(" " . $articleLink->getWords() . " ", " " . $articleLink->getWords() . ", "), array(" <a href=\"" . $articleLink->getLink() . "\">" . $articleLink->getWords() . "</a> ", " <a href=\"" . $articleLink->getLink() . "\">" . $articleLink->getWords() . "</a>, "), $this->article->getContent()));
        }

        $this->forward404Unless($this->article);

        $category = $this->article->getCategoryArticles();
        $this->category = $category[0]->getArticlecategory();
    }

    public function executeCategory(sfWebRequest $request) {
        $this->pager = new sfDoctrinePager('article', 15);

        if ($request->getParameter('slug') == "recommend") {

            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")
                            ->where('is_related = 1')->addWhere("is_public='1'")->orderBy("positionrelated DESC");
            $this->categoryName = "Рекомендуемые статьи";
        } elseif ($request->getParameter('slug') == "pop") {

            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")->addWhere("is_public='1'")->orderBy("rating/votes_count DESC, votes_count DESC");
            $this->categoryName = "Популярные статьи";
        } elseif ($request->getParameter('slug') == "new") {
            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")->addWhere("is_public='1'")->orderBy("created_at DESC");
            $this->categoryName = "Новые статьи";
        } else {
            $this->category = Doctrine_Core::getTable('Articlecategory')->findOneBySlug(array($request->getParameter('slug')));
            if (empty($this->category)) {
                $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Articlecategory' and oldslug=?",$request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
                if ($oldSlug) {
                    $this->category = Doctrine_Core::getTable('Articlecategory')->findOneById($oldSlug->getDopid());
                    return $this->redirect('/sexopedia/category/' . $this->category->getSlug());
                } else {
                    return $this->redirect('/sexopedia/');
                }
            }

            $this->query = Doctrine_Core::getTable('article')
                    ->createQuery('a')->select("*")
                    ->leftJoin('a.CategoryArticle c')
                    ->where("c.articlecategory_id IN (" . $this->category->getId() . ")")
                    ->addWhere("a.is_public='1'")
                    ->addOrderBy("a.position desc");
        }

        $this->pagerArticles = $this->query->execute();
    }

    public function executeCatalog(sfWebRequest $request) {
      if(isset($_GET['page'])) return $this->redirect('/sexopedia/catalog/' . $request->getParameter('slug'), 301);
        if ($request->getParameter('slug') == "recommend") {

            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")
                            ->where('is_related = 1')->addWhere("is_public='1'")->orderBy("positionrelated DESC");
            $this->categoryName = "Рекомендуемые статьи";
        } elseif ($request->getParameter('slug') == "pop") {

            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")->addWhere("is_public='1'")->orderBy("rating/votes_count DESC, votes_count DESC");
            $this->categoryName = "Популярные статьи";
        } else if ($request->getParameter('slug') == "new") {
            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")->addWhere("is_public='1'")->orderBy("created_at DESC");
            $this->categoryName = "Новые статьи";
        } else if ($request->getParameter('slug') != "") {
            $idArticle = "";
            $this->catalog = ArticlecatalogTable::getInstance()->findOneBySlug($request->getParameter('slug'));
            if (empty($this->catalog)) {
                $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Articlecatalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
                if ($oldSlug) {
                    $this->catalog = Doctrine_Core::getTable('Articlecatalog')->findOneById($oldSlug->getDopid());
                    if ($this->catalog)
                        return $this->redirect('/sexopedia/catalog/' . $this->catalog->getSlug());
                    else
                        return $this->redirect('/sexopedia/');
                }
            }

            $this->forward404Unless($this->catalog);

            $this->articlesCategory = $this->catalog->getCategory();
            foreach ($this->articlesCategory as $category) {
                foreach ($category->getCategoryArticles() as $article) {
                    if ($idArticle != "")
                        $idArticle.=",";
                    $idArticle.=$article->getId();
                }
            }
            $this->query = Doctrine_Core::getTable('article')
                            ->createQuery('a')->select("*")->where("id in (" . $idArticle . ")")->addWhere("is_public='1'")->orderBy("created_at DESC");

            $this->pager = new sfDoctrinePager('article', 15);
            $this->pagerArticles = $this->query->execute();
        }else {
            $this->forward404();
        }
        if ($this->categoryName) {
            $this->pager = new sfDoctrinePager('article', 15);

            $this->pagerArticles = $this->query->execute();
            $this->setTemplate("category");
        } else {

            $this->setTemplate("catalog");
        }
    }

    public function executeRate(sfWebRequest $request) {

        $article = Doctrine_Core::getTable('Article')->findOneById(array($request->getParameter('articleId')));
        $article->setRating($article->getRating() + $request->getParameter('value'));
        $article->setVotesCount($article->getVotesCount() + 1);
        $article->save();
        $this->getResponse()->setCookie("ratear_" . $article->getId(), 1, time() + 60 * 60 * 24 * 365, '/');
        $this->article = $article;
    }

}
