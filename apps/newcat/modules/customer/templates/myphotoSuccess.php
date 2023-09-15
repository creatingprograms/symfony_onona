<section class="wellCome">

    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>
            <a href="/lk">личный кабинет</a>
        </li>
        <li>
            мои фотографии
        </li>
    </ul>
    <div class="wrapwer">
      <h1 class="mobile-only">Мои фотографии</h1>
        <? If ($userPhotos->count() > 0) { ?>
            Вы добавили фото всего: <?= $userPhotos->count() ?><br />
            <ul class="photosUserLK">
                <? foreach ($userPhotos as $numPhotos => $photo) {
                    ?><li><span>
                            <table class="noBorder" style="padding: 0;"><tbody><tr><td style="width: 180px;height: 178px;text-align: center;vertical-align: middle;padding: 0;line-height: 0;font-size: 0;">

                                            <img src="/uploads/photouser/thumbnails/<?= $photo->getFilename() ?>" style="max-width: 180px; max-height: 180px;" onclick='$(".UserPhotosPreShow li span").each(function (i) {
                                                        $(this).removeClass("activePhotosPreShow")
                                                    });
                                                    $(this).parent().addClass("activePhotosPreShow");
                                                    $("<div/>").click(function (e) {
                                                        if (e.target != this)
                                                            return;
                                                        $(this).remove();
                                                    }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockShowUserPhoto").appendTo("body");
                                                    $.post("/product/showuserphotos/" + <?= $photo->getId() ?>, {photosKeys: "<?= implode(",", $userPhotos->getPrimaryKeys()) ?>"},
                                                    function (data) {
                                                        $(".blockShowUserPhoto").html(data);
                                                    });
                                                    $(document).keyup(function (e) {

                                                        if (e.keyCode == 27) {
                                                            $(".blockShowUserPhoto").remove();
                                                        }   // esc
                                                    });'>
                                        </td></tr></tbody></table>

                        </span>
                        Дата: <?= date("d.m.Y H:i", strtotime($photo->getCreatedAt())) ?><br/>
                        Товар: <a href="/product/<?= $photo->getProduct()->getSlug() ?>"><?= $photo->getProduct()->getName() ?></a></li><?
                }
                ?>
            </ul>
        <? } else { ?>
            Вы еще не добавили ни одной фотографии к товарам. <br/>

            <a class="goToProductsButton" style="margin-top:30px;" href="/catalog/sex-igrushki-dlja-par"></a>
        <? } ?>
    </div>
</section>
