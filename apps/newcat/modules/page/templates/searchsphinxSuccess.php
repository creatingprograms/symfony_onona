searchsphinx
<?php foreach ($products as $k => $v) {
  echo '<a href="/product/'.$v['slug'].'" target="_blank">'.$v['name']."</a><br />";
}
?>