<?php

foreach ($desires as $desire) {
    ?><a href="/desire/<?=md5($desire->getCreatedAt())?>">Список <?=$desire->getSfGuardUser()->getFirstName()?>: <?=count(unserialize($desire->getProducts()))?>товаров
    </a>
    <?
}