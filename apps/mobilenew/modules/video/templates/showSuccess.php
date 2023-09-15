
<div class="info-box">
    <div style="width:100%; text-align: center;"><h1 class="title">Он и Она | TUBE</h1></div></div>
    <ul class="videos">
        <?php
        foreach ($videos as $video['id'] => $video) {
            echo "<li>";

            include_partial("video/videoBooklet", array(
                'sf_cache_key' => $video['id'],
                'video' => $video
                    )
            );
            echo "</li>";
        }
        ?>
    </ul>