
<?php if($comments->getProduct()->getName()!=""):?>
<b>Товар:</b> <a href="/product/<?php
echo $comments->getProduct()->getSlug();
?>"><?php
echo $comments->getProduct()->getName();
?></a>
<?php elseif($comments->getArticle()->getName()!=""):?>
<b>Статья: </b><a href="/sexopedia/<?php
echo $comments->getArticle()->getSlug();
?>"><?php
echo $comments->getArticle()->getName();
?></a>
<?php endif; ?>
