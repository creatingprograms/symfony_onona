<?php

$user = sfContext::getInstance()->getUser();
if ($user->isAuthenticated()) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT sum(bonus) as sum  FROM `bonus` WHERE `user_id` = " . $user->getGuardUser()->getId());
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $bonusLog = $result->fetchAll();
    echo "<li><a href=\"/customer/bonus\" class=\"bonus\"><span>" . $bonusLog[0]['sum'] . " бонусов</span></a></li>";
}        