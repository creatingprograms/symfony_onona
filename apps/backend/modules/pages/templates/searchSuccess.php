<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 


<?php if ($categorys->count() > 0):
    ?>
    <div id="sf_admin_container">
        <h1><?php echo __('Категории', array(), 'messages') ?></h1>

        <div id="sf_admin_header">
        </div>

        <div id="sf_admin_content"><div class="sf_admin_list">
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th class="sf_admin_text sf_admin_list_th_name">Название </th>
                            <th class="sf_admin_text sf_admin_list_th_slug">Ссылка </th>
                            <th class="sf_admin_text sf_admin_list_th_count"> Количество товара</th>
                            <th class="sf_admin_boolean sf_admin_list_th_is_public">Доступна  </th>
                            <th id="sf_admin_list_th_actions">Действия</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="5">

                                <?= $categorys->count() ?> результата(ов)                                                                               </th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php foreach ($categorys as $i => $category): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                            <tr class="sf_admin_row <?php echo $odd ?>">
                                <td class="sf_admin_text sf_admin_list_td_name">
                                    <a href="/backend.php/product/setFilterTag/<?= $category->getId(); ?>"><?= $category->getName(); ?></a>
                                </td>
                                <td class="sf_admin_text sf_admin_list_td_slug">
                                    <?= $category->getSlug(); ?></td>
                                <td class="sf_admin_text sf_admin_list_td_slug">

                                    <?php echo $category->getCategoryProducts()->count() ?></td>
                                <td class="sf_admin_boolean sf_admin_list_td_is_public">

                                    <?php echo get_partial('category/list_field_boolean', array('value' => $category->getIsPublic())) ?>
                                </td>
                                <td>
                                    <ul class="sf_admin_td_actions">
                                        <li class="sf_admin_action_promote">
                                            <a href="/backend.php/category/<?= $category->getId(); ?>/promote">Вверх</a>    </li>
                                        <li class="sf_admin_action_demote">
                                            <a href="/backend.php/category/<?= $category->getId(); ?>/demote">Вниз</a>    </li>
                                        <li class="sf_admin_action_edit"><a href="/backend.php/category/<?= $category->getId(); ?>/edit">Редактировать</a></li>    
                                        <li class="sf_admin_action_delete">
                                            <?php echo link_to(__('Delete', array(), 'sf_admin'), 'category_delete', $category, array('method' => 'delete', 'confirm' => __('Are you sure?', array(), 'sf_admin'))); ?>
                                        </li>
                                    </ul>
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
    <?php
endif;
if ($products->count() > 0):
    ?>
    <script language="javascript">

        function changePublic(id) {
            $.ajax(
                    {
                        type: "POST",
                        url: "/backend_dev.php/product/publicChange",
                        timeout: 5000,
                        data: "id=" + id,
                        success: function (data) {
                            $(".sf_admin_list_td_is_public_" + id).html(data);
                        },
                        error: function (data) {
                            $(".sf_admin_list_td_is_public_" + id).html("Error" + data);
                        }
                    });
        }
        function changeRelated(id) {
            $.ajax(
                    {
                        type: "POST",
                        url: "/backend_dev.php/product/relatedChange",
                        timeout: 5000,
                        data: "id=" + id,
                        success: function (data) {
                            $(".sf_admin_list_td_is_related_" + id).html(data);
                        },
                        error: function (data) {
                            $(".sf_admin_list_td_is_related_" + id).html("Error" + data);
                        }
                    });
        }
    </script>
    <div id="sf_admin_container">
        <h1><?php echo __('Товары', array(), 'messages') ?></h1>

        <div id="sf_admin_header">
        </div>

        <div id="sf_admin_content"><div class="sf_admin_list">
                <table cellspacing="0"><thead>
                        <tr>
                            <th class="sf_admin_text sf_admin_list_th_name">Название  </th>
                            <th class="sf_admin_text sf_admin_list_th_code">Артикул </th>
                            <th class="sf_admin_text sf_admin_list_th_slug">Ссылка </th>
                            <th class="sf_admin_boolean sf_admin_list_th_is_public">Доступна </th>
                            <th class="sf_admin_boolean sf_admin_list_th_is_related">Рек. товар  </th>
                            <th class="sf_admin_text sf_admin_list_th_price">Цена  </th>
                            <th class="sf_admin_text sf_admin_list_th_count">Кол-во на складе  </th>
                            <th class="sf_admin_text sf_admin_list_th_manager">Менеджер  </th>
                            <th id="sf_admin_list_th_actions">Действия</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="9">

                                <?= $products->count() ?> результата(ов)                                                                               </th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php foreach ($products as $i => $product): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                            <tr class="sf_admin_row <?php echo $odd ?>">
                                <td class="sf_admin_text sf_admin_list_td_name"><a href="https://onona.ru/product/<?= $product->getSlug(); ?>"><?= $product->getName(); ?></a> </td>
                                <td class="sf_admin_text sf_admin_list_td_code">
                                    <?= $product->getCode(); ?></td>
                                <td class="sf_admin_text sf_admin_list_td_slug">
                                    <?= $product->getSlug(); ?></td>
                                <td class="sf_admin_boolean sf_admin_list_td_is_public_<?php echo $product->getId() ?>">

                                    <a href="#" onClick="changePublic(<?php echo $product->getId() ?>);
                                                    return false;"><?php echo get_partial('product/list_field_boolean', array('value' => $product->getIsPublic())) ?></a>
                                </td>
                                <td class="sf_admin_boolean sf_admin_list_td_is_related_<?php echo $product->getId() ?>">
                                    <a href="#" onClick="changeRelated(<?php echo $product->getId() ?>);
                                                    return false;"><?php echo get_partial('product/list_field_boolean', array('value' => $product->getIsRelated())) ?></a>
                                </td><td class="sf_admin_text sf_admin_list_td_price">
                                    <?php echo $product->getPrice() ?> руб.
                                </td>
                                <td class="sf_admin_text sf_admin_list_td_code">
                                    <?php echo $product->getCount() ?>
                                </td>
                                <td class="sf_admin_text sf_admin_list_td_manager">
                                    <?php
                                    if ($product->getUser() != "") {
                                        $user = sfGuardUserTable::getInstance()->findOneById($product->getUser());
                                        if ($user)
                                            echo $user->getEmailAddress ();
                                    }
                                    ?>
                                </td>
                                <td>
                                    <ul class="sf_admin_td_actions">
                                        <li class="sf_admin_action_edit"><a href="/backend.php/product/<?= $product->getId(); ?>/edit">Редактировать</a></li>    
                                        <li class="sf_admin_action_delete">
                                            <?php echo link_to(__('Delete', array(), 'sf_admin'), 'product_delete', $product, array('method' => 'delete', 'confirm' => __('Are you sure?', array(), 'sf_admin'))); ?>
                                        </li>
                                    </ul>
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
    <?php
endif;
if ($pages->count() > 0):
    ?>
    <div id="sf_admin_container">
        <h1><?php echo __('Страницы', array(), 'messages') ?></h1>

        <div id="sf_admin_header">
        </div>

        <div id="sf_admin_content"><div class="sf_admin_list">
                <table cellspacing="0"><thead>
                        <tr>
                            <th class="sf_admin_text sf_admin_list_th_name">Название </th>
                            <th class="sf_admin_text sf_admin_list_th_slug">Ссылка </th>
                            <th class="sf_admin_boolean sf_admin_list_th_is_public">Доступна  </th>
                            <th id="sf_admin_list_th_actions">Действия</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="5">

                                <?= $pages->count() ?> результата(ов)                                                                               </th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php foreach ($pages as $i => $page): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                            <tr class="sf_admin_row <?php echo $odd ?>">
                                <td class="sf_admin_text sf_admin_list_td_name"><?= $page->getName(); ?> </td>
                                <td class="sf_admin_text sf_admin_list_td_slug">
                                    <?= $page->getSlug(); ?></td>
                                <td class="sf_admin_boolean sf_admin_list_td_is_public">

                                    <?php echo get_partial('pages/list_field_boolean', array('value' => $page->getIsPublic())) ?>
                                </td>
                                <td>
                                    <ul class="sf_admin_td_actions">
                                        <li class="sf_admin_action_edit"><a href="/backend.php/pages/<?= $page->getId(); ?>/edit">Редактировать</a></li>    
                                        <li class="sf_admin_action_delete">
                                            <?php echo link_to(__('Delete', array(), 'sf_admin'), 'page_delete', $page, array('method' => 'delete', 'confirm' => __('Are you sure?', array(), 'sf_admin'))); ?>
                                        </li>
                                    </ul>
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
    <?php






    
endif;