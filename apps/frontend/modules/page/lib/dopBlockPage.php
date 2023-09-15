<?php

class dopBlockPage {

    static function getBlockComments($num) {

        mb_internal_encoding('UTF-8');
        $comments = CommentsTable::getInstance()->createQuery()->where("is_public='1' and page_id is NULL and article_id is NULL")->orderBy("rand()")->limit($num)->execute();
        unset($rowCom, $comment, $commentsBlock);
        $comment = array();
        $commentsBlock = "</p><div class=\"column-box" . ($num == 4 ? " four-column" : "") . "\">
	<div class=\"left-deco\">
		&nbsp;</div>
	<div class=\"right-deco\">
		&nbsp;</div>";
        foreach ($comments as $key => $comment2) {
            $product = ProductTable::getInstance()->findOneById($comment2->getProductId());
            $photoalbum = $product->getPhotoalbums();
            $photos = $photoalbum[0]->getPhotos();
            $commentsBlock .="	<div class=\"col\">
		<div class=\"title\">
			<a href=\"/product/" . $product->getSlug() . "\">" . mb_substr($product->getName(), 0, 30) . "... <span class=\"img-box\"><img src=\"/uploads/photo/thumbnails_250x250/" . $photos[0]->getFilename() . "\" alt=\"image description\"></span> </a></div>
		<div class=\"stars\">
                                <span style=\"width:" . ($product->getVotesCount() > 0 ? (($product->getRating() / $product->getVotesCount()) * 10) : "0") . "%;\"></span></div>

			" . mb_substr($comment2->getText(), 0, 30) . "...<br />";
            if ($key == 0)
                $commentsBlock .="<a href=\"/comments\" class=\"more\" style=\"position: absolute; bottom: 15px;font-size:18px;\">Все отзывы</a>";

            $commentsBlock .="</div>";
        }

        $commentsBlock .= "
</div><p>";
        return $commentsBlock;
    }

    static function getBlockCommentsShop($num) {

        mb_internal_encoding('UTF-8');
        $comments = CommentsTable::getInstance()->createQuery('c')
                        ->leftJoin('c.Page p')->where("is_public='1' and page_id is not NULL and p.name not like '%Быстрая, выгодная доставка%'")->orderBy("rand()")->limit($num)->execute();
        unset($rowCom, $comment, $commentsBlock);
        $comment = array();
        $commentsBlock = "</p><div class=\"column-box" . ($num == 4 ? " four-column" : "") . "\">
	<div class=\"left-deco\">
		&nbsp;</div>
	<div class=\"right-deco\">
		&nbsp;</div>";
        foreach ($comments as $key => $comment2) {
            $product = PageTable::getInstance()->findOneById($comment2->getPageId());
            $commentsBlock .="	<div class=\"col\">
		<div class=\"title\">
			<a href=\"/" . $product->getSlug() . "\">" . mb_substr($product->getName(), 0, 60) . "... </a></div>


			" . mb_substr($comment2->getText(), 0, 60) . "...<br />";

            $commentsBlock .="</div>";
        }

        $commentsBlock .= "
</div><p>";
        return $commentsBlock;
    }

    static function getBlockArticle($num) {

        mb_internal_encoding('UTF-8');
        $articles = ArticleTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->limit($num)->execute();
        unset($rowCom, $article, $commentsBlock);
        $article = array();
        $commentsBlock = "</p><div class=\"article-box\">
	<div class=\"article-frame\">
		<div class=\"img-holder left\">
			<a href=\"/sexopedia\"><img width=\"172\" height=\"161\" src=\"/newdis/images/img02.png\" alt=\"image description\"></a></div>
		<ul class=\"article-list\">";
        foreach ($articles as $key => $article) {
            $commentsBlock .="<li>
				<div class=\"title\">
					<a href=\"/sexopedia/" . $article->getSlug() . "\">" . $article->getName() . "</a></div>
				<div class=\"stars\"><span style=\"width:" . ($article->getVotesCount() > 0 ? (($article->getRating() / $article->getVotesCount()) * 10) : "0") . "%;\"></span>
					 </div>
				<p>" . mb_substr(strip_tags($article->getContent()), 0, 130) . "...
					</p>
			</li>	";
        }

        $commentsBlock .= "
	</ul>
	</div>
</div><p>";
        return $commentsBlock;
    }

    static function getBlockBanners() {
        $banners = BannersTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->execute();
        $bannersBlock = '<a class="prev" href="#"></a>
<a class="next" href="#"></a>
<div class="promo-gallery">
<ul>';
        foreach ($banners as $key => $banner) {
            $target = ((substr_count($banner->getHref(), "http://") > 0) ? "target=\"_blank\"" : "");
            $bannersBlock.="<li><a href=\"" . $banner->getHref() . "\" " . $target . " style=\"border: 0px;\"><img src=\"/uploads/banners/" . $banner->getSrc() . "\" width=\"188\" height=\"198\" alt=\"" . $banner->getAlt() . "\" /></a></li>";
        }
        $bannersBlock.="</ul></div>";
        return $bannersBlock;
    }

    static function getBlockNews($page) {
        $news = Doctrine_Core::getTable('News')
                ->createQuery('n')->select("*")
                ->leftJoin('n.NewsPage p')
                ->addwhere("(p.page_id IN (" . $page->getId() . "))")
                ->addWhere('is_public = \'1\'')
                ->addOrderBy("created_at desc")
                ->limit(3)
                ->execute();
        $newsBlock = "";
        if ($news->count() > 0) {
            //$news = $page->getNewsPages();
            $newsBlock =
            '<div class="news-box">'.
              '<div class="news-frame">'.
                '<a href="/newslist" target="_blank" class="inner-news">Новости</a>'.
                // '<div class="img-holder left"><a href="/newslist" target="_blank"><img width="41" height="162" alt="image description" src="/images/news.png"></a></div>'.
                '<ul class="news-list">';
                  foreach ($news as $new) {
                    $newsBlock.=
                      '<li style="color: #707070;">'.
                        '<div class="title">'.
                          '<a href="/news/' . ($new->getSlug()) . '">' . ($new->getName()) . '</a>'.
                        '</div>'.
                        '<span style="font-size:10px">' . /*date("d.m.Y", strtotime($new->getCreatedAt())) .*/ '</span>'.
                        ($new->getPrecontent()) .
                      '</li>';
                  }
                $newsBlock.='</ul>'.
              '</div>'.
            '</div>';
        }
        return $newsBlock;
    }

    public function blockAddComment() {
        if (substr_count($this->page->getContent(), "{commentsMagazBlock}") > 0) {
            if (isset($_POST["cName"])) {
                if ($_POST["cName"] != "") {
                    $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='k'")->fetchOne();
                    $captchaVal = $captcha->getVal();
                    if ($captcha->getVal() == $_POST["cText"]) {
                        $newComments = new Comments();
                        $newComments->setPageId($this->page->getId());
                        $newComments->setUsername($_POST["cName"]);
                        $newComments->setMail($_POST["cEmail"]);
                        $newComments->setText($_POST["cComment"]);
                        $newComments->save();
                    }
                }
            }
            $commMagazBlock = "";
            $comments = CommentsTable::getInstance()->createQuery()->where("is_public='1' and page_id = " . $this->page->getId())->execute();

            $commMagazBlock = "<div style=\"text-align:center;font-size: 16px;\">
	<a href=\"#\" onClick=\"$('#commentDiv').toggle(); return false;\" class=\"red bold\">Написать отзыв о магазине</a>
</div>
 <div class=\"add-coment\" style=\"display:none;width: 500px; margin: 24px auto;\" id=\"commentDiv\">
            <form id=\"commentForm\" method=\"post\" action=\"/" . ($this->page->getSlug()) . "/addcomm\" name=\"comment\">
                <fieldset>

                    <div class=\"descr\">Внимание! Публикация отзывов производится после предварительной модерации.</div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Ваше имя:*</label>
                        </div>
                        <div class=\"input-holder\">
                            <input type=\"text\" name=\"cName\" value=\"" . $_POST["cName"] . "\" />
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Ваш e-mail:</label>
                        </div>
                        <div class=\"input-holder\">
                            <input type=\"text\" name=\"cEmail\" value=\"" . $_POST["cEmail"] . "\" />
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Сообщение:*</label>
                        </div>
                        <div class=\"textarea-holder\">
                            <textarea cols=\"30\" rows=\"5\" name=\"cComment\">" . $_POST["cComment"] . "</textarea>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Укажите код:*</label>
                        </div>
                        <div class=\"capcha-holder\">
                            <img src=\"/captcha/kcaptcha.php?" . session_name() . "=" . session_id() . "\" width=\"139\" height=\"48\" class=\"captchak\" alt=\"captcha\"/>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">&nbsp;</div>
                        <div class=\"input-holder\">
                            <input type=\"text\" name=\"cText\" /></div>
                    </div>";
            if ($captchaVal != $_POST["cText"] and count($_POST) > 0) {
                $commMagazBlock.="
<div align=\"center\" class=\"error\" style=\"color: red;\">
	Неправильно введены контрольные символы. <br>Попробуйте еще раз.

    <script language=\"javascript\">
	$(document).ready(function(){
        $('#commentDiv').show();
	window.scrollTo(0,500);
													   });
	</script></div>
";
            }
            $commMagazBlock.= "
                    <div class=\"required\">* - поля, отмеченные * обязательны для заполнения.</div>
                    <div class=\"btn-holder centr\">
                        <div class=\"red-btn  colorWhite\">
                            <span>Отправить</span>
                            <input type=\"button\" value=\"Отправить\" class=\"red-btn\" onclick=\"$('#commentForm').submit(); return false;\" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>";
            if (isset($_POST["cText"]))
                if (@$captchaVal == $_POST["cText"] and $_POST["cText"] != "") {
                    $commMagazBlock = "Отзыв успешно добавлен. После проверки модератором, он станет доступнен для просмотра. <a href=\"/" . ($this->page->getSlug()) . "\">Вернуться к просмотру магазина</a><br /><br />";
                }
            if ($comments->count() > 0) {

                $commMagazBlock.="<div align=\"center\" style=\"padding:10px;\">Отзывов: " . $comments->count() . " <a href=\"#\" onClick=\"$('#comments').toggle('slow'); return false;\">(показать)</a></div>
<div  id=\"comments\" style=\"display:block\">
<table width=\"760\" border=\"0\" id=\"commentsTable\" align=\"center\" class=\"noBorder\">
    <a name=\"отзывы\"></a>";
                foreach ($comments as $comment2) {

                    $commMagazBlock.="
<tr><td><a name=\"comm" . $comment2->getId() . "\"></a>
<div style=\"border:1px solid #CCC;\">
	<div style=\"padding:5px;\">";
                    if ($comment2->getMail() != "")
                        $commMagazBlock.="<u>";
                    $commMagazBlock.=$comment2->getSfGuardUser()->getName() == "" ? $comment2->getUsername() : $comment2->getSfGuardUser()->getName();
                    if ($comment2->getMail() != "")
                        $commMagazBlock.="</u>";

                    $commMagazBlock.=", " . $comment2->getCreatedAt() . "</div>
	<div style=\"padding:5px;\">" . nl2br($comment2->getText());
                    if ($comment2->getAnswer() != ""):
                        $commMagazBlock.='<div style="margin-left: 15px; margin-top: 15px;"><b>Ответ:</b><p style="margin: 0px;">' . $comment2->getAnswer() . '</p></div>';
                    endif;
                    $commMagazBlock.="</div>
</div>
</td></tr>
";
                }

                $commMagazBlock.="
</table>
</div>
";
            } elseif (@$captchaVal != $_POST["cText"] or $_POST["cText"] == "")
                $commMagazBlock.="
<div align=\"center\" >Вы можете стать первым, кто оставит отзыв к этому магазину.</div>
";

            $this->page->setContent(str_replace(array("{commentsMagazBlock}"), array($commMagazBlock), $this->page->getContent()));
                $this->page->setContentMo(str_replace(array("{commentsMagazBlock}"), array($commMagazBlock), $this->page->getContentMo()));
        }










        if (substr_count($this->page->getContent(), "{commentsPageBlock}") > 0) {
            if (isset($_POST["cName"])) {
                $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='k'")->fetchOne();

                if ($captcha->getVal() == $_POST["cText"]) {
                    $this->page->setContent(PageTable::getInstance()->find(286)->getContent() . '<br /><a href="/' . $this->page->getSlug() . '">Вернуться к просмотру страницы - ' . $this->page->getName() . '.</a>');
                    $this->page->setContentMo(PageTable::getInstance()->find(286)->getContentMo() . '<br /><a href="/' . $this->page->getSlug() . '">Вернуться к просмотру страницы - ' . $this->page->getName() . '.</a>');
                    $newComments = new Comments();
                    $newComments->setPageId($this->page->getId());
                    $newComments->setUsername($_POST["cName"]);
                    $newComments->setMail($_POST["cEmail"]);
                    $newComments->setText($_POST["cComment"]);
                    $newComments->save();
                }
            }
            $commMagazBlock = "";
            $comments = CommentsTable::getInstance()->createQuery()->where("is_public='1' and page_id = " . $this->page->getId())->execute();

            $commMagazBlock = "<div style=\"text-align:center;font-size: 16px;\">
	<a href=\"#\" onClick=\"$('#commentDiv').toggle(); return false;\" class=\"red bold\">Написать отзыв</a>
</div> <div class=\"add-coment\" style=\"display:none;width: 500px; margin: 24px auto;\" id=\"commentDiv\">
            <form id=\"commentForm\" method=\"post\" action=\"\" name=\"comment\">
                <fieldset>

                    <div class=\"descr\">Внимание! Публикация отзывов производится после предварительной модерации.</div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Ваше имя:*</label>
                        </div>
                        <div class=\"input-holder\">
                            <input type=\"text\" name=\"cName\" />
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Ваш e-mail:</label>
                        </div>
                        <div class=\"input-holder\">
                            <input type=\"text\" name=\"cEmail\" />
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Сообщение:*</label>
                        </div>
                        <div class=\"textarea-holder\">
                            <textarea cols=\"30\" rows=\"5\" name=\"cComment\"></textarea>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">
                            <label>Укажите код:*</label>
                        </div>
                        <div class=\"capcha-holder\">
                            <img src=\"/captcha/kcaptcha.php?" . session_name() . "=" . session_id() . "\" width=\"139\" height=\"48\" class=\"captchak\" alt=\"captcha\"/>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"label-holder\">&nbsp;</div>
                        <div class=\"input-holder\">
                            <input type=\"text\" name=\"cText\" /></div>
                    </div>
 ";
            if (isset($_POST["cName"])) {
                if ($captcha->getVal() != $_POST["cText"]) {

                    $commMagazBlock.="
<div align=\"center\" class=\"error\" style=\"color: red;\">
	Неправильно введены контрольные символы. <br>Попробуйте еще раз.

    <script language=\"javascript\">
	$(document).ready(function(){
        $('#commentDiv').show();
	window.scrollTo(0,500);
													   });
	</script>
        </div>
";
                }
            }
            $commMagazBlock.= "
                    <div class=\"required\">* - поля, отмеченные * обязательны для заполнения.</div>
                    <div class=\"btn-holder centr\">
                        <div class=\"red-btn  colorWhite\">
                            <span>Отправить</span>
                            <input type=\"button\" value=\"Отправить\" class=\"red-btn\" onclick=\"$('#commentForm').submit(); return false;\" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>";
            /*  */
            if ($comments->count() > 0) {

                $commMagazBlock.="<div align=\"center\" style=\"padding:10px;\">Отзывов: " . $comments->count() . " <a href=\"#\" onClick=\"$('#comments').toggle('slow'); return false;\">(показать)</a></div>
<div  id=\"comments\" style=\"display:block\">
<table width=\"760\" border=\"0\" id=\"commentsTable\" align=\"center\" class=\"noBorder\">
    <a name=\"отзывы\"></a>";
                foreach ($comments as $comment2) {

                    $commMagazBlock.="
<tr><td><a name=\"comm" . $comment2->getId() . "\"></a>
<div style=\"border:1px solid #CCC;\">
	<div style=\"padding:5px;\">";
                    if ($comment2->getMail() != "")
                        $commMagazBlock.="<u>";
                    $commMagazBlock.=$comment2->getSfGuardUser()->getName() == "" ? $comment2->getUsername() : $comment2->getSfGuardUser()->getName();
                    if ($comment2->getMail() != "")
                        $commMagazBlock.="</u>";
                    $commMagazBlock.="</div>
	<div style=\"padding:5px;\">" . nl2br($comment2->getText());
                    if ($comment2->getAnswer() != ""):
                        $commMagazBlock.='<div style="margin-left: 15px; margin-top: 15px;"><b>Ответ:</b><p style="margin: 0px;">' . $comment2->getAnswer() . '</p></div>';
                    endif;
                    $commMagazBlock.="</div>
</div>
</td></tr>
";
                }

                $commMagazBlock.="
</table>
</div>
";
            } else
                $commMagazBlock.="
<div align=\"center\" >Вы можете стать первым, кто оставит отзыв.</div>
";



            $this->page->setContent(str_replace(array("{commentsPageBlock}"), array($commMagazBlock), $this->page->getContent()));
            $this->page->setContentMo(str_replace(array("{commentsPageBlock}"), array($commMagazBlock), $this->page->getContentMo()));
        }
    }

    static function getProductAction($num) {
        ob_start();
        if (csSettings::get("id_product_action_" . $num) != "") {
            $product = ProductTable::getInstance()->findOneById(csSettings::get("id_product_action_" . $num));
            if ($product) {
                $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
                $action_name = csSettings::get("name_action_" . $num);
                $comments = Doctrine_Core::getTable('Comments')
                        ->createQuery('c')
                        ->where("is_public = '1'")
                        ->addWhere('product_id = ?', $product->getId())
                        ->orderBy('id ASC')
                        ->execute();
                include($_SERVER['DOCUMENT_ROOT'] . '/../apps/newcat/modules/product/templates/tempCartProductActions.php');

                $a = ob_get_contents();
            } else {
                $a = "";
            }
        } else {
            $a = "";
        }
        ob_end_clean();
        return $a;
    }

    static function getBlockActionProduct() {
        return '</p><ul class="item-list">' . dopBlockPage::getProductAction(1) . dopBlockPage::getProductAction(2) . dopBlockPage::getProductAction(3) . '</ul><p>';
    }

}

?>
