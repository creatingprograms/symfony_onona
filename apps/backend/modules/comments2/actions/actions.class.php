<?php

/**
 * comments2 actions.
 *
 * @package    test
 * @subpackage comments2
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class comments2Actions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $pageCount=50;
    $pageNum=($request->getParameter('page') ? $request->getParameter('page') : 1);
    $commentss = Doctrine_Core::getTable('Comments')
      ->createQuery('a')->orderBy('created_at DESC');

    if ($request->getParameter('item'))
        $commentss = $commentss -> where('product_id = '.$request->getParameter('item'));
    if ($request->getParameter('shop'))
        $commentss = $commentss -> where('shops_id = '.$request->getParameter('shop'));
    if ($request->getParameter('article'))
        $commentss = $commentss -> where('article_id = '.$request->getParameter('article'));
    if ($request->getParameter('page_id'))
        $commentss = $commentss -> where('page_id = '.$request->getParameter('page_id'));

    $count=$commentss->count();
    $this->pageNum=$pageNum;
    $this->filter='';
    $this->pagesCount=ceil($count/$pageCount);
    $this->count=$count;
    $this->commentss = $commentss->offset($pageCount*($pageNum-1))->limit($pageCount)->execute();
    $pages=$products=$articles=[0];
    /*
    foreach ($this->commentss as $comment) {
      if($comment->getPageId()) $pages[]=$comment->getPageId();
      if($comment->getArticleId()) $articles[]=$comment->getArticleId();
      if($comment->getProductId()) $products[]=$comment->getProductId();
      if($comment->getShopsId()) $shops[]=$comment->getShopsId();
    }*/

    $pagesQuery = Doctrine_Core::getTable('Page')
      ->createQuery('b')
      // ->where('id IN('.implode(', ', $pages).')')
      ->execute();
    foreach ($pagesQuery as $cat) {
      $pagesArr[$cat->getId()]=$cat->getName();
    }
    // $pagesArr[0]='Все';
    $pagesQuery = Doctrine_Core::getTable('Article')
      ->createQuery('d')
      // ->where('id IN('.implode(', ', $articles).')')
      ->execute();
    foreach ($pagesQuery as $cat) {
      $articlesArr[$cat->getId()]=$cat->getName();
    }
    // $articlesArr[0]='Все';
    $pagesQuery = Doctrine_Core::getTable('Shops')
      ->createQuery('e')
      // ->where('id IN('.implode(', ', $shops).')')
      ->execute();
    foreach ($pagesQuery as $cat) {
      $shopsArr[$cat->getId()]=$cat->getName();
    }
    // $shopsArr[0]='Все';
    // $debug=true;
    if (isset($debug)) die('<pre>'.print_r([
      '$pages'=>$pages,
      '$products'=>$products,
      '$shopsArr'=>$shopsArr,
      '$articles'=>$articles
    ], true).'</pre>');
    /*
    $pagesQuery = Doctrine_Core::getTable('Product')
      ->createQuery('c')
      ->where('id IN('.implode(', ', $products).')')
      ->execute();
    foreach ($pagesQuery as $cat) {
      $productsArr[$cat->getId()]=$cat->getName();
    }
    $productsArr[0]='Все';*/
    $this->shopsArr=$shopsArr;
    $this->articlesArr=$articlesArr;
    $this->pagesArr=$pagesArr;
    $dbId=@mysql_connect('localhost', 'i9s', 'EgjadDev');
    @mysql_query('SET NAMES UTF8');
    @mysql_select_db('p702d');
    $query="SELECT id, name from product";
    $resourseId = @mysql_query($query, $dbId);
    // die(@mysql_error());
    // $k=0;
    while ($rowOne = mysql_fetch_assoc($resourseId)) {
      $productsArr[$rowOne['id']] = $rowOne['name'];
      // $k++;
    }
    asort($productsArr);
    @mysql_free_result($resourseId);
    /*for ($i=0; $i<10500; $i++)
      $testArray[$i]="fooo $i baaaar ".md5($i);*/
      // die('<pre>'.print_r($row).'</pre>');
    $this->productsArr=$productsArr;
    $this->filter=
      ($request->getParameter('item') ? '&item='.$request->getParameter('item') : '').
      ($request->getParameter('shop') ? '&shop='.$request->getParameter('shop') : '').
      ($request->getParameter('article') ? '&article='.$request->getParameter('article') : '').
      ($request->getParameter('page_id') ? '&page_id='.$request->getParameter('page_id') : '').
      ''
    ;
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->comments = Doctrine_Core::getTable('Comments')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->comments);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CommentsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CommentsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($comments = Doctrine_Core::getTable('Comments')->find(array($request->getParameter('id'))), sprintf('Object comments does not exist (%s).', $request->getParameter('id')));
    $this->form = new CommentsForm($comments);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($comments = Doctrine_Core::getTable('Comments')->find(array($request->getParameter('id'))), sprintf('Object comments does not exist (%s).', $request->getParameter('id')));
    $this->form = new CommentsForm($comments);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($comments = Doctrine_Core::getTable('Comments')->find(array($request->getParameter('id'))), sprintf('Object comments does not exist (%s).', $request->getParameter('id')));
    $comments->delete();

    $this->redirect('comments2/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $comments = $form->save();

      $this->redirect('comments2/edit?id='.$comments->getId());
    }
  }
}
