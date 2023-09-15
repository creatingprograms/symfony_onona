
<?php if($photos_user->getProduct()->getName()!=""):?>
<b>Товар:</b> <a href="/product/<?php
echo $photos_user->getProduct()->getSlug();
?>"><?php
echo $photos_user->getProduct()->getName();
?></a>
<?php endif; ?>
