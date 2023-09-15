<?php
echo $product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0;
?>
