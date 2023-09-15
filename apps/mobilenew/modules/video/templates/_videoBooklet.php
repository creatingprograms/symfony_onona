<a href="<?= $video['youtubelink'] ?>" style="border: 0px; display: inline; font-size: 13px;">
    <img src="/uploads/photovideo/<?= $video['photo'] ?>" style="width: 90%;cursor: pointer;" alt='<?= str_replace(array("'", '"'), "", $video['name']) ?>'>
    <p>
        <?= $video['name'] ?>
    </p>
</a>

<?/*<a href="/video/<?= $video['slug'] ?>" style="border: 0px; display: inline; font-size: 13px;">
    <img src="/uploads/photovideo/<?= $video['photo'] ?>" style="width: 90%;cursor: pointer;" alt='<?= str_replace(array("'", '"'), "", $video['name']) ?>'>
    <p>
        <?= $video['name'] ?>
    </p>
</a>
 * */?>
 