<?php
echo $comments->getSfGuardUser()->getEmailAddress()!=""?$comments->getSfGuardUser()->getEmailAddress():$comments->getMail();
?> - <?php
echo $comments->getSfGuardUser()->getName()!=""?$comments->getSfGuardUser()->getName():$comments->getUsername();
?>
