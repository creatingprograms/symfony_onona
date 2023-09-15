<?php
echo $test->getRating() > 0 ? @round($test->getRating() / $test->getVotesCount()) : 0;
?>
