<?
mb_internal_encoding('UTF-8');
?><div id="PostPages">
    <script>
        function changeCity(button, letter) {
            $("#PostPages .letter").each(function (index) {
                $(this).children("div").css("color", "#424141");
            });
            $(button).css("color", "#c3060e");

            $("#PostPages .citys").each(function (index) {
                $(this).fadeOut(0);
                $(this).removeClass('active');
            });
            $("#letter-" + letter).fadeIn(0);
            $("#letter-" + letter).addClass('active');
        }
        function showFullPointsList(){
          $('.pickpoint-cities-list-pick.active').addClass('full');
        }
    </script>

    <ul style="list-style: none;"><?php
        foreach ($alf as $num => $letter) {
            ?><li style="float: left;margin: 7px;" class="letter"><div onclick="changeCity(this,'<?= $letter['alf'] ?>')" style="cursor: pointer; <?= $num == 0 ? " color: #c3060e;" : "" ?>"><b><?= $letter['alf'] ?></b></div></li>
            <?
        }
        ?></ul>
    <div style="clear: both;"></div>
    <ul style="list-style: none;"><?php
        foreach ($citys as $num => $city) {
            if ($oldLetter != mb_substr($city['name'], 0, 1)) {
                ?></ul>
            <ul style="list-style: none;<?= $num != 0 ? " display: none;" : "" ?>" class="citys pickpoint-cities-list-pick<?= !$num ? " active" : "" ?>" id="letter-<?= mb_substr($city['name'], 0, 1) ?>"><?
                $oldLetter = mb_substr($city['name'], 0, 1);
            }
            ?><li class="pickpoint-element"><a href="/pickpoint/<?= $city['slug'] ?>"><?= $city['name'] ?></a></li>
                <?
            }
            ?></ul>
            <div class="show-more-cities" onclick="showFullPointsList();">Весь список</div>
    <div style="clear: both;"></div>
</div>
