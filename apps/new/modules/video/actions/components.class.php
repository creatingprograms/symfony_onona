<?php

class videoComponents extends sfComponents {
  public function executeVideosBlock(sfWebRequest $request) {

  }

  public function executeVideosSideBar(sfWebRequest $request) {
    $tags = VideoTable::getInstance()->createQuery()->where("is_public='1'")->fetchArray();
    $arrayTags['Новое'] = 0;
    $arrayTags['Рекомендованное'] = 0;
    foreach ($tags as $tag) {
        if ($tag['is_related']) {
            $arrayTags['Рекомендованное'] = $arrayTags['Рекомендованное'] + 1;
        }
        if (strtotime($tag['created_at']) > (time() - 30 * 24 * 60 * 60)) {
            $arrayTags['Новое'] = $arrayTags['Новое'] + 1;
        }
        $thisTags = explode(";", $tag['tag']);
        foreach ($thisTags as $thisTag) {
            $arrayTags[trim($thisTag)] = $arrayTags[trim($thisTag)] + 1;
        }
    }
    $this->arrayTags=$arrayTags;
  }

}
?>
