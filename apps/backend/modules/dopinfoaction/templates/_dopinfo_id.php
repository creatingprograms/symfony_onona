<?
if($dopinfoaction->getDopinfoId()){
  $sqlBody="SELECT `name`, `value` FROM `dop_info` WHERE `id`=".$dopinfoaction->getDopinfoId();
  $q= Doctrine_Manager::getInstance()->getCurrentConnection();
  $res=$q->execute($sqlBody)->Fetch(Doctrine_Core::FETCH_ASSOC);
  echo $res['name'].' - <strong>'.$res['value'].'</strong></pre>';
  // echo '<pre>'.print_r($res, true).'</pre>';
}
else echo '';
