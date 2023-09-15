<div class="sf_admin_list">
    <?php if (!$pager->getNbResults()): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?></p>
    <?php else: ?>

        <?php use_javascript('/js/jquery.dnd.js') ?>
        <script language="javascript">
            $(document).ready(function() {
                $("#productsTable").tableDnD(
                {
                    onDragClass: "myDragClass",

                    dragHandle: "dragable",
                    onDrop: function(table, row)
                    {
                        var rows = table.tBodies[0].rows;

                        var next = "";
                        var rowsList = "";
                        for (var i = 0; i < rows.length; i++)
                        {

                            if (rows[i].id == row.id)
                            {
                                if (i < rows.length-1)
                                {
                                    next = rows[i+1].title;

                                }
                            }
                            rowsList += rows[i].title + ",";
                        }


                        $.ajax(
                        {
                            type: "POST",
                            url: "/backend_dev.php/product/sortChange",

                            timeout: 5000,

                            data: "table=products&rowId=" + row.id + "&nextSortOrder=" + next + "&currentSortOrder=" + row.title + "&rowsList=" + rowsList,
                            success: function(data){$("div.sf_admin_list").html(data); },

                            error: function(data){$("div#info").html("Error" + data);}
                        });

                    }
                });
            });
            function changeRelated(id){
                $.ajax(
                {
                    type: "POST",
                    url: "/backend_dev.php/product/relatedChange",

                    timeout: 5000,

                    data: "id=" + id,
                    success: function(data){$(".sf_admin_list_td_is_related_"+id).html(data); },

                    error: function(data){$(".sf_admin_list_td_is_related_"+id).html("Error" + data);}
                });
            }
            function changePublic(id){
                $.ajax(
                {
                    type: "POST",
                    url: "/backend_dev.php/product/publicChange",

                    timeout: 5000,

                    data: "id=" + id,
                    success: function(data){$(".sf_admin_list_td_is_public_"+id).html(data); },

                    error: function(data){$(".sf_admin_list_td_is_public_"+id).html("Error" + data);}
                });
            }
            function changeAdult(id){
                $.ajax(
                {
                    type: "POST",
                    url: "/backend_dev.php/product/adultChange",

                    timeout: 5000,

                    data: "id=" + id,
                    success: function(data){$(".sf_admin_list_td_adult_"+id).html(data); },

                    error: function(data){$(".sf_admin_list_td_adult_"+id).html("Error" + data);}
                });
            }
            function changeModer(id){
                $.ajax(
                {
                    type: "POST",
                    url: "/backend_dev.php/product/moderChange",

                    timeout: 5000,

                    data: "id=" + id,
                    success: function(data){$(".sf_admin_list_td_moder_"+id).html(data); },

                    error: function(data){$(".sf_admin_list_td_moder_"+id).html("Error" + data);}
                });
            }
        </script>
        <table cellspacing="0" id="productsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th id="sf_admin_list_batch_actions"><input id="sf_admin_list_batch_checkbox" type="checkbox" onclick="checkAll();" /></th>
                    <?php include_partial('product/list_th_tabular', array('sort' => $sort)) ?>
                    <th id="sf_admin_list_th_actions">Промодерирован</th>
                    <th id="sf_admin_list_th_actions">Менеджер</th>
                    <th id="sf_admin_list_th_actions"><?php echo __('Actions', array(), 'sf_admin') ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th colspan="6">
                        <?php if ($pager->haveToPaginate()): ?>
                            <?php include_partial('product/pagination_moder', array('pager' => $pager)) ?>
                        <?php endif; ?>

                        <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'sf_admin') ?>
                        <?php if ($pager->haveToPaginate()): ?>
                            <?php echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?>
                        <?php endif; ?>
                    </th>
                </tr>
            </tfoot>
            <tbody>
              <? //die('<pre>!'.print_r(['product'=>$product, 'pager'=>$pager], true).'</pre>'); ?>
                <?php $t=0; foreach ($pager->getResults() as $i => $product): $odd = fmod(++$i, 2) ? 'odd' : 'even'; $t++ ?>

                    <tr class="sf_admin_row <?php echo $odd ?>" id="<?= $product->getId() ?>" title="<?= $product->get("position") ?>">
                        <td><?=$t?></td>
                        <?php include_partial('product/list_td_batch_actions', array('product' => $product, 'helper' => $helper)) ?>
                        <?php include_partial('product/list_td_tabular_moder', array('product' => $product)) ?>
                        <?php include_partial('product/list_td_actions', array('product' => $product, 'helper' => $helper)) ?>
                    </tr>
                    <?php foreach ($product->getChildren() as $y => $product): $odd = fmod(++$i, 2) ? 'odd' : 'even'; $t++  ?>
                        <tr class="sf_admin_row <?php echo $odd ?>">
                        <td><?=$t?></td>
                        <?// throw new Doctrine_Table_Exception('DEBUG <pre/>~|'.print_r(   $query, true).'|~</pre>');?>
                            <?php include_partial('product/list_td_batch_actions', array('product' => $product, 'helper' => $helper)) ?>
                            <?php /*lebed.irina вываливается в фильтрах тут*/ include_partial('product/list_td_tabular_moder', array('product' => $product)) ?>
                            <?php include_partial('product/list_td_actions', array('product' => $product, 'helper' => $helper)) ?>
                        </tr>

                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php


        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $managers = $q->execute("SELECT user, count(user), sum(moder), us.email_address FROM `product` left Join sf_guard_user as us on us.id=product.user where user!='' group by user")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
?>
<table><tbody><tr>
            <th>Менеджер верх</th>
            <th>Количество товаров</th>
            <th>Не промодерированы</th></tr>
        <?php foreach($managers as $manager){?>
        <tr>
            <td><?=$manager['email_address']?></td>
            <td><?=$manager['count(user)']?></td>
            <td><?=$manager['sum(moder)']?></td></tr>
        <?}?>
</tbody></table>
<?php
  $moders = $q->execute(
    "SELECT moderuser,
      count(moderuser),
      sum(moder),
      us.email_address
      FROM `product`
      left Join sf_guard_user as us on us.id=product.moderuser
      where moderuser!='' group by moderuser"
  )->fetchAll(Doctrine_Core::FETCH_UNIQUE);
?>
<table><tbody><tr>
            <th>Модератор</th>
            <th>Промодерировано товаров</th>
            <th>Одобрено товаров</th></tr>
        <?php foreach($moders as $moder){?>
        <tr>
            <td><?=$moder['email_address']?></td>
            <td><?=$moder['count(moderuser)']?></td>
            <td><?=$moder['count(moderuser)']-$moder['sum(moder)']?></td></tr>
        <?}?>
    </tbody></table>
    <?php

?>
<script type="text/javascript">
    /* <![CDATA[ */
    function checkAll()
    {
        var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
    }
    /* ]]> */
</script>
