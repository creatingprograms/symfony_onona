<div class="sf_admin_list">
    <?php if (!$pager->getNbResults()): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?></p>
    <?php else: ?>       <?php use_javascript('/js/jquery.dnd.js') ?> 
        <script language="javascript">
            $(document).ready(function() { 
                $("#mcategorysTable").tableDnD(
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
                            url: "/backend_dev.php/mcategory/sortChange",

                            timeout: 5000,

                            data: "table=mcategory&rowId=" + row.id + "&nextSortOrder=" + next + "&currentSortOrder=" + row.title + "&rowsList=" + rowsList,
                            success: function(data){$("div.sf_admin_list").html(data); },

                            error: function(data){$("div#info").html("Error" + data);}
                        });

                    }
                });
            });
            function changeAdult(id){     
                $.ajax(
                {
                    type: "POST",
                    url: "/backend_dev.php/mcategory/adultChange",

                    timeout: 5000,

                    data: "id=" + id,
                    success: function(data){$(".sf_admin_list_td_adult_"+id).html(data); },

                    error: function(data){$(".sf_admin_list_td_adult_"+id).html("Error" + data);}
                });
            }
        </script>
        <table cellspacing="0" id="mcategorysTable">
            <thead>
                <tr>
                    <th>#</th>

                    <th><img width="16" height="16" title="Порядок сортировки элементов" alt="Порядок сортировки элементов" src="/images/icons/updown.png"></th>

                    <th id="sf_admin_list_batch_actions"><input id="sf_admin_list_batch_checkbox" type="checkbox" onclick="checkAll();" /></th>
                    <?php include_partial('mcategory/list_th_tabular', array('sort' => $sort)) ?>
                    <? /*<th id="sf_admin_list_th_actions"><?php echo __('Actions', array(), 'sf_admin') ?></th>*/?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <?php if ($pager->haveToPaginate()): ?>
                            <?php include_partial('mcategory/pagination', array('pager' => $pager)) ?>
                        <?php endif; ?>

                        <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'sf_admin') ?>
                        <?php if ($pager->haveToPaginate()): ?>
                            <?php echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?>
                        <?php endif; ?>
                    </th>
                </tr>
            </tfoot>
            <tbody>
                <?php $t=0; foreach ($pager->getResults() as $i => $mcategory): $odd = fmod(++$i, 2) ? 'odd' : 'even'; $t++ ?>
                    <tr class="sf_admin_row <?php echo $odd ?>" id="<?= $mcategory->getId() ?>" title="<?= $mcategory->get("positionloveprice") ?>">
                        <td><?= $t ?></td>

                        <td width="20" valign="middle" background="/images/icons/shading.png" align="center" style="background-repeat:no-repeat; background-position:center center" class="dragable">&nbsp;</td>
                        <?php include_partial('mcategory/list_td_batch_actions', array('category' => $mcategory, 'helper' => $helper)) ?>
                        <?php include_partial('mcategory/list_td_tabular', array('mcategory' => $mcategory)) ?>
                        <?php /*include_partial('mcategory/list_td_actions', array('category' => $mcategory, 'helper' => $helper)) */?>
                    </tr>
                    <?php /*foreach ($mcategory->getChildren() as $i => $mcategory): $odd = fmod(++$i, 2) ? 'odd' : 'even'; $t++ ?>
                        <tr class="sf_admin_row <?php echo $odd ?>" id="<?= $mcategory->getId() ?>" title="<?= $mcategory->get("positionloveprice") ?>">
                            <td><?= $t ?></td>

                            <td width="20" valign="middle" background="/images/icons/shading.png" align="center" style="background-repeat:no-repeat; background-position:center center" class="dragable">&nbsp;</td>

                            <?php include_partial('mcategory/list_td_batch_actions', array('category' => $mcategory, 'helper' => $helper)) ?>
                            <?php include_partial('mcategory/list_td_tabular', array('mcategory' => $mcategory)) ?>
                        </tr>

                    <?php endforeach; */?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<script type="text/javascript">
    /* <![CDATA[ */
    function checkAll()
    {
        var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
    }
    /* ]]> */
</script>
