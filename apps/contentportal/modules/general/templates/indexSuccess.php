<?php
use_helper('I18N', 'Date');
mb_internal_encoding('UTF-8');
?>
<div class="infoBlock" style="width: 32%;">
    <div class="nameInfoBlock">Лучшие комментарии</div>
    <div class="content">
        <table cellspacing="0">
            <thead>
                <tr>
                    <th id="sf_admin_list_batch_actions">Дата</th>
                    <th id="sf_admin_list_batch_actions">Текст</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="2"><?php echo link_to('Все комментарии', 'comments') ?>       </th>
                </tr>
            </tfoot>
            <?php
            foreach ($comments as $comment['id'] => $comment) {
                ?>
                <tbody>
                    <tr class="sf_admin_row odd">
                        <td>
    <?= $comment['created_at'] ?>
                        </td>
                        <td class="sf_admin_text sf_admin_list_td_id">
    <?php echo link_to(__(mb_substr($comment['text'], 0, 50), array(), 'messages'), 'comments/edit?id=' . $comment['id'], array()) ?></td>

                    </tr>
                </tbody>

                <?php
                //print_r($comment);
            }
            ?>

        </table>
    </div>
</div>

<div class="infoBlock" style="width: 32%;">
    <div class="nameInfoBlock">Лучшие фотографии</div>
    <div class="content"><table cellspacing="0" style="width: 100%">
            <thead>
                <tr>
                    <th id="sf_admin_list_batch_actions">Дата</th>
                    <th id="sf_admin_list_batch_actions">Фото</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="2"><?php echo link_to('Все фото', 'photos_user') ?>       </th>
                </tr>
            </tfoot>
            <?php
            foreach ($photos as $photo['id'] => $photo) {
                ?>
                <tbody>
                    <tr class="sf_admin_row odd">
                        <td>
    <?= $photo['created_at'] ?>
                        </td>
                        <td class="sf_admin_text sf_admin_list_td_id">
    <?php echo link_to(__('<img src="/uploads/photouser/thumbnails/' . $photo['filename'] . '" height="100">', 0, 50, array(), 'messages'), 'userphotos/edit?id=' . $photo['id'], array()) ?>
                        </td>

                    </tr>
                </tbody>

                <?php
                //print_r($photo);
            }
            ?>

        </table></div>
</div>

<div class="infoBlock" style="width: 32%;">
    <div class="nameInfoBlock">Лучшие видео</div>
    <div class="content"><table cellspacing="0" style="width: 100%">
            <thead>
                <tr>
                    <th id="sf_admin_list_batch_actions">Дата</th>
                    <th id="sf_admin_list_batch_actions">Текст</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th colspan="2"><?php echo link_to('Все видео', 'video') ?>       </th>
                </tr>
            </tfoot>
            <?php
            foreach ($videos as $video['id'] => $video) {
                ?>
                <tbody>
                    <tr class="sf_admin_row odd">
                        <td>
    <?= $video['created_at'] ?>
                        </td>
                        <td class="sf_admin_text sf_admin_list_td_id">
    <?php echo link_to(__($video['name'], array(), 'messages'), 'video/edit?id=' . $video['id'], array()) ?></td>

                    </tr>
                </tbody>

                <?php
                //print_r($video);
            }
            ?>

        </table></div>
</div>