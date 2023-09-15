<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?php use_stylesheet('/newdis/css/jquery-ui-1.10.3.custom.css', 'first') ?>
<?php use_javascript('/newdis/js/jquery-ui-1.10.3.custom.js'); ?>
<?php use_javascript('/newdis/js/i18n/jquery-ui-i18n.js'); ?>
<script>$(function(){
  $.datepicker.setDefaults(
        $.extend($.datepicker.regional["ru"])
  );
  $("#datepicker").datepicker();
  $("#datepicker2").datepicker();
});</script>
<form method="post">
    Период:      <input  id="datepicker" name="fromDate" value="<?=isset($_POST['fromDate'])? $_POST['fromDate'] : ''?>"> - <input  id="datepicker2" name="to" value="<?=isset($_POST['to']) ? $_POST['to'] : ''?>">
<br />Запрос: <input id="search" name="search" value="<?=isset($_POST['search']) ? $_POST['search'] :''?>"><br />
        <?/*
    <select name="search_log[created_at][from][day]" id="search_log_created_at_from_day">
        <?
        for ($i = 1; $i <= 31; $i++) {
            ?><option <?
        if ($searchLog['created_at']['from']['day'] == $i) {
            echo " selected=\"selected\"";
        }
            ?> value="<?= $i ?>"><?= $i ?></option><?
        }
        ?>
    </select>.<select name="search_log[created_at][from][month]" id="search_log_created_at_from_month">
        <?
        for ($i = 1; $i <= 12; $i++) {
            ?><option <?
            if ($searchLog['created_at']['from']['month'] == $i) {
                echo " selected=\"selected\"";
            }
            ?> value="<?= $i ?>"><?= $i ?></option><?
    }
        ?>
    </select>.<select name="search_log[created_at][from][year]" id="search_log_created_at_from_year">
            <?
            for ($i = 2012; $i <= 2020; $i++) {
                ?><option <?
            if ($searchLog['created_at']['from']['year'] == $i) {
                echo " selected=\"selected\"";
            }
            ?> value="<?= $i ?>"><?= $i ?></option><?
        }
            ?>
    </select>

    -

    <select name="search_log[created_at][to][day]" id="search_log_created_at_to_day">
            <?
            for ($i = 1; $i <= 31; $i++) {
                ?><option <?
        if ($searchLog['created_at']['to']['day'] == $i) {
            echo " selected=\"selected\"";
        }
                ?> value="<?= $i ?>"><?= $i ?></option><?
        }
            ?>
    </select>.<select name="search_log[created_at][to][month]" id="search_log_created_at_to_month">
        <?
        for ($i = 1; $i <= 12; $i++) {
            ?><option <?
        if ($searchLog['created_at']['to']['month'] == $i) {
            echo " selected=\"selected\"";
        }
            ?> value="<?= $i ?>"><?= $i ?></option><?
        }
        ?>
    </select>.<select name="search_log[created_at][to][year]" id="search_log_created_at_to_year">
<?
for ($i = 2012; $i <= 2020; $i++) {
    ?><option <?
    if ($searchLog['created_at']['to']['year'] == $i) {
        echo " selected=\"selected\"";
    }
    ?> value="<?= $i ?>"><?= $i ?></option><?
}
?>
    </select> */?>
    <input type="submit" value="Отправить запрос">
    <input type="submit" value="Скачать" name="download">
</form>
<?php
$result = $pager->getResults();
if ($result->count() > 0):
    ?>
    <div id="sf_admin_container">
        <br /><br />
        <h1><?php echo __("Результат", array(), 'messages') ?></h1>
        <div id="sf_admin_header">
        </div>

        <div id="sf_admin_content"><div class="sf_admin_list">
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th class="sf_admin_text sf_admin_list_th_name">
                                <?php if ('text' == $sort[0]): ?>
                                    <?php echo link_to(__('Текст', array(), 'messages'), '@searchLog', array('query_string' => 'sort=text&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
        <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir') . '/images/' . $sort[1] . '.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
    <?php else: ?>
        <?php echo link_to(__('Текст', array(), 'messages'), '@searchLog', array('query_string' => 'sort=text&sort_type=asc')) ?>
    <?php endif; ?></th>
                            <th class="sf_admin_text sf_admin_list_th_slug">

                                <?php if ('countquery' == $sort[0]): ?>
                                    <?php echo link_to(__('Количество', array(), 'messages'), '@searchLog', array('query_string' => 'sort=countquery&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
                                    <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir') . '/images/' . $sort[1] . '.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
                                <?php else: ?>
                                    <?php echo link_to(__('Количество', array(), 'messages'), '@searchLog', array('query_string' => 'sort=countquery&sort_type=asc')) ?>
                                <?php endif; ?> </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="2">

                        <?php if ($pager->haveToPaginate()): ?>
        <?php include_partial('pages/paginationSL', array('pager' => $pager)) ?>
                                <?php endif; ?>

    <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'sf_admin') ?>
                                <?php if ($pager->haveToPaginate()): ?>
                                    <?php echo __('(searchLog %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?>
    <?php endif; ?>                                          </th>
                        </tr>
                    </tfoot>
                    <tbody>

    <?php foreach ($result as $i => $log): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                            <tr class="sf_admin_row <?php echo $odd ?>">
                                <td class="sf_admin_text sf_admin_list_td_name">
        <?= $log->getText(); ?>
                                </td>
                                <td class="sf_admin_text sf_admin_list_td_slug">
        <?= $log->getCountquery(); ?>
                                </td>

                            </tr>
    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div id="sf_admin_footer">
        </div>
    </div>
    <br />
    <br />
    <?
endif;
?>
