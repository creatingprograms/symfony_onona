<?php

/**
 * comments2 actions.
 *
 * @package    test
 * @subpackage comments2
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class abandonedbasketsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $pageCount=50;
    $pageNum=($request->getParameter('page') ? $request->getParameter('page') : 1);

    if(isset($_GET['from_timestamp'])) $this->from_timestamp=$_GET['from_timestamp'];
    if(isset($_GET['to_timestamp'])) $this->to_timestamp=$_GET['to_timestamp'];
    if(
      isset($_GET["from"]['day'] ) && isset($_GET["from"]['month']) && isset($_GET["from"]['year'])
      &&
      ($_GET["from"]['day']!='' && $_GET["from"]['month']!='' && $_GET["from"]['year']!='')
    )
      $this->from_timestamp=strtotime($_GET["from"]['year'].'-'.$_GET["from"]['month'].'-'.$_GET["from"]['day']);

    if(
      isset($_GET["to"]['day'] ) && isset($_GET["to"]['month']) && isset($_GET["to"]['year'])
      &&
      ($_GET["to"]['day']!='' && $_GET["to"]['month']!='' && $_GET["to"]['year']!='')
    )
      $this->to_timestamp=strtotime($_GET["to"]['year'].'-'.$_GET["to"]['month'].'-'.$_GET["to"]['day']);
    if(!isset($this->to_timestamp)) $this->to_timestamp=false;
    if(!isset($this->from_timestamp)) $this->from_timestamp=false;
    // die('<pre>'.print_r(['get'=>$_GET, 'to'=>$this->to_timestamp,'from'=>$this->from_timestamp], true).'</pre>');

    // $dbId=@mysql_connect('localhost', 'i9s', 'EgjadDev');
    $dbId=@mysql_connect('localhost', '1ononaru', '2xUr8hepKsKb2Lp4');
    @mysql_query('SET NAMES UTF8');
    @mysql_select_db('1ononaru');
    $query="SELECT id, name from product ORDER BY name ASC;";
    $resourseId = @mysql_query($query, $dbId);
    $k=0;
    while ($rowOne = mysql_fetch_assoc($resourseId)) {
      $productsArr[$rowOne['id']] = $rowOne['name'];
      $k++;
    }
    @mysql_free_result($resourseId);
    $filterWhere = '';
    if ($request->getParameter('email'))
      $filterWhere .= 'and (email_address like "%'.$request->getParameter('email').'%") ';
    if ($request->getParameter('product'))
      $filterWhere .= 'and (cart like \'%"productId";i:'.$request->getParameter('product').'%\') ';
    if ($this->to_timestamp)
      $filterWhere .= 'and (updated_at <= "'.date('Y-m-d H:i', $this->to_timestamp).'") ';
    if ($this->from_timestamp)
      $filterWhere .= 'and (updated_at >= "'.date('Y-m-d H:i', $this->from_timestamp).'") ';

    $query=
      'select count(email_address) as count '.
      'from sf_guard_user '.
      'where (cart is not null and cart<>\'\' and cart<>\'b:0;\' and cart<>\'a:0:{}\' and cart<>\'s:0:"";\') '.
      $filterWhere
      ;

    // die($query);
    $resourseId = mysql_query($query, $dbId);
    $rowOne = mysql_fetch_assoc($resourseId);
    $count=$rowOne['count'];
    @mysql_free_result($resourseId);

    $query=
      'select email_address, cart, updated_at '.
      'from sf_guard_user '.
      'where (cart is not null and cart<>\'\' and cart<>\'b:0;\' and cart<>\'a:0:{}\' and cart<>\'s:0:"";\') '.
      $filterWhere.
      'order by updated_at desc '.
      'limit '.$pageCount.' '.
      'offset '.$pageCount*($pageNum-1).' '.
      '';
      // die ($query);
    $resourseId = mysql_query($query, $dbId);
    $j=0;
    while ($rowOne = mysql_fetch_assoc($resourseId)) {
      $usersArr[$j] = $rowOne;
      $j++;
    }
    // die('dfgdffg-'.$j);
    @mysql_free_result($resourseId);
    $this->productsArr=$productsArr;
    $this->usersArr=$usersArr;
    $this->pagesCount=ceil($count/$pageCount);
    $this->pageNum=$pageNum;
    $this->count=$count;
    $this->filter=
      ($request->getParameter('email') ? '&email='.$request->getParameter('email') : '').
      ($request->getParameter('product') ? '&product='.$request->getParameter('product') : '').
      ($this->to_timestamp ? '&to_timestamp='.$this->to_timestamp : '').
      ($this->from_timestamp ? '&from_timestamp='.$this->from_timestamp : '').
      '';
    // die('<pre>'.print_r($this->usersArr, true).'</pre>');
  }

}
