<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?php use_stylesheet('/newdis/css/jquery-ui-1.10.3.custom.css', 'first') ?> 
<?php use_javascript('/newdis/js/jquery-ui-1.10.3.custom.js'); ?>
<?php use_javascript('/newdis/js/i18n/jquery-ui-i18n.js'); ?>
<script>$(function () {
        $.datepicker.setDefaults(
                $.extend($.datepicker.regional["ru"])
                );
        $("#datepicker").datepicker();
        $("#datepicker2").datepicker();
    });</script>
<form action="/backend.php/pages/usersendstats/action" method="POST">

    Период:      <input  id="datepicker" name="fromDate" value="<?= @$_POST['fromDate'] ?>"> - <input  id="datepicker2" name="to" value="<?= @$_POST['to'] ?>">     <input type="submit" value="Отправить запрос">
</form><br />

<div id="sf_admin_container">
    <table cellspacing="0" width="100%">

        <tbody>
            <?
            foreach ($productsUsersend as $product) {
                unset($usersSend);
                if (count($managerstats) > 0) {
                    $usersSend = SenduserTable::getInstance()->createQuery()->where("product_id=?", $product['product_id'])->addWhere("created_at > ?", $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day'])->addWhere("created_at < ?", $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day'])->orderBy("created_at DESC")->execute();
                } else {

                    $usersSend = SenduserTable::getInstance()->createQuery()->where("product_id=?", $product['product_id'])->orderBy("created_at DESC")->execute();
                }
                ?>
                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="3" onClick="
                                $('.prodId-<?= $product['product_id'] ?>').each(function (index) {
                                    $(this).toggle();
                                });" style="cursor: pointer;">
                        <?= $product['code'] ?>     <?= $product['prodname'] ?> (<?= $product['countPod'] ?>)</th>
                </tr><? foreach ($usersSend as $userSend) { ?>
                    <tr class="sf_admin_row odd prodId-<?= $product['product_id'] ?>" style="display: none;">
                        <td class="sf_admin_text sf_admin_list_td_name">
                            <?= $userSend->getCreatedAt(); ?> <?= $userSend->getName(); ?><?php if ($userSend->getIsSend() or $userSend->getIsManager()): ?>
                                <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir') . '/images/tick.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('Checked', array(), 'sf_admin'))) ?>
                                <?= $userSend->getUpdatedAt(); ?>
                            <?php else: ?>

                                <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir') . '/images/delete.png', array('alt' => __('No Checked', array(), 'sf_admin'), 'title' => __('No Checked', array(), 'sf_admin'))) ?>

                            <?php endif; ?>

                        </td>
                        <td class="sf_admin_text sf_admin_list_td_value">
                            <b style="cursor: pointer;" onClick="$(this).load('/backend.php/pages/usersendstatsmail', {user: <?= $userSend->getId() ?>});">Показать контакты</b>
                        </td>  
                        <td class="sf_admin_text sf_admin_list_td_value">
                            <?php if ($userSend->getIsManager()): ?>
                            <input type="checkbox" checked="checked" onclick="$(this).load('/backend_dev.php/pages/usersendsetsend', {user: <?= $userSend->getId() ?>});">
                            <?php else: ?>

                            <input type="checkbox" onclick="$(this).load('/backend_dev.php/pages/usersendsetsend', {user: <?= $userSend->getId() ?>});">
                            <?php endif; ?>
                        </td>     
                    </tr>
                    <?
                }
            }
            ?>
        </tbody>
    </table>
</div>