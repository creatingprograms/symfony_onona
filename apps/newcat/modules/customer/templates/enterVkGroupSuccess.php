<?php

if ($userIssetSsidentity) {
    if ($userIssetGroup) {
        echo "Вам начислено ".csSettings::get("entervkgroup")." бонусов";
    } else {
        echo "Вы не вступили в группу";
    }
} else {
    echo "Вы авторизировались не через соцсеть";
}
    