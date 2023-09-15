<?php
echo $article->getRating() > 0 ? @round($article->getRating() / $article->getVotesCount()) : 0;
?>
