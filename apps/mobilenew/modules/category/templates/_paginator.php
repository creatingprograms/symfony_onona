<div class="paginator">
    

<?php
JSInPages("function setPages(id){
    
                    $('#pageFromFilter').val(id);
                    
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '". explode("?", $_SERVER['REQUEST_URI'])[0] ."?' + queryString;
                    history.pushState('', '', redirect);
                    
                    jQuery('#categoryFilters').submit();
    
                }");
?>
    <ul>
        <?php
        if ($pagesCount <= 7) {
            for ($i = 1; $i <= $pagesCount; $i++) {
                ?>
                <li>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)">
                        <?= $i ?>
                    </div>
                </li>
                <?php
            }
        } elseif ($page <= 4) {
            for ($i = 1; $i <= 5; $i++) {
                ?>
                <li>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)" class="pageId-<?=$i?>">
                        <?= $i ?>
                    </div>
                </li>
                <?php
            }
            ?>
            <li>
                •••
            </li>
            <li>
                <div onclick="setPages(<?=$pagesCount?>)">
                    <?= $pagesCount ?>
                </div>
            </li>
            <?php
        } elseif ($page < $pagesCount - 3) {
            ?>
            <li>
                <div onclick="setPages(1)" class="pageId-1">
                    1
                </div>
            </li>
            <li>
                •••
            </li>
            <?php
            for ($i = $page-1; $i <= $page+1; $i++) {
                ?>
                <li>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)" class="pageId-<?=$i?>">
                        <?= $i ?>
                    </div>
                </li>
                <?php
            }
            ?>
            <li>
                •••
            </li>
            <li>
                <div onclick="setPages(<?=$pagesCount?>)">
                    <?= $pagesCount ?>
                </div>
            </li>
            <?php
        }else{
            ?>
            <li>
                <div onclick="setPages(1)" class="pageId-1">
                    1
                </div>
            </li>
            <li>
                •••
            </li>
            <?php
            for ($i = $pagesCount-4; $i <= $pagesCount; $i++) {
                ?>
                <li>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)" class="pageId-<?=$i?>">
                        <?= $i ?>
                    </div>
                </li>
                <?php
            }
        
        }
        ?>
    </ul>
</div> 