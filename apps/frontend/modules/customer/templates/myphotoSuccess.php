<?
global $isTest;
$h1='Мои фотографии';
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
slot('h1', $h1);
?>
<main class="wrapper -action">

    <div class="wrapwer">
        <? if ($userPhotos->count() > 0) { ?>
            Вы добавили фото всего: <?= $userPhotos->count() ?><br />
            <ul class="photosUserLK">
                <? foreach ($userPhotos as $numPhotos => $photo) {
                    ?><li><span>
                            <table class="noBorder" ><tbody><tr><td>

                                            <img src="/uploads/photouser/thumbnails/<?= $photo->getFilename() ?>" onclick='$(".UserPhotosPreShow li span").each(function (i) {
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

            <a class="goToProductsButton" href="/catalog/sex-igrushki-dlja-par"></a>
        <? } ?>
    </div>
</main>
