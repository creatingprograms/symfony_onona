<?php

if($senduser->getIsSend())
   echo  format_date($senduser->getUpdatedAt(), 'f', 'ru');
    //echo $senduser->getUpdatedAt();
?>
