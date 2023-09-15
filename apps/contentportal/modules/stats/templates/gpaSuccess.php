<?php
use_helper('I18N', 'Date');
mb_internal_encoding('UTF-8');
?>
<?php if (count($statsComments) > 0): ?>
    <b>Комментарии: </b>
    <table>
        <thead>
            <tr>
                <th>Менеджер</th>
                <?php
                foreach (array_keys($statsComments) as $mounth) {
                    echo "<th>" . $mounth . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($statsCommentsUser as $u_id => $user) {
                echo "<tr class=\"" . (fmod( ++$i, 2) ? 'odd' : 'even') . "\"><td>" . $user['first_name'] . " " . $user['last_name'] . " (" . $user['email_address'] . ")</td>";
                foreach ($statsComments as $mounth => $stat) {
                    if (isset($stat[$u_id])) {
                        echo "<td>" . round($stat[$u_id]['avg'], 2) . " / " . $stat[$u_id]['countRecord'] . "</td>";
                    } else {
                        echo "<td>0 / 0</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
            </tr>
        </tbody>
    </table>
<?php endif; ?>



<?php if (count($statsPhotos) > 0): ?>
    <b>Фото: </b>
    <table>
        <thead>
            <tr>
                <th>Менеджер</th>
                <?php
                foreach (array_keys($statsPhotos) as $mounth) {
                    echo "<th>" . $mounth . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($statsPhotosUser as $u_id => $user) {
                echo "<tr class=\"" . (fmod( ++$i, 2) ? 'odd' : 'even') . "\"><td>" . $user['first_name'] . " " . $user['last_name'] . " (" . $user['email_address'] . ")</td>";
                foreach ($statsPhotos as $mounth => $stat) {
                    if (isset($stat[$u_id])) {
                        echo "<td>" . round($stat[$u_id]['avg'], 2) . " / " . $stat[$u_id]['countRecord'] . "</td>";
                    } else {
                        echo "<td>0 / 0</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
            </tr>
        </tbody>
    </table>
<?php endif; ?>




<?php if (count($statsVideos) > 0): ?>
    <b>Видео: </b>
    <table>
        <thead>
            <tr>
                <th>Менеджер</th>
                <?php
                foreach (array_keys($statsVideos) as $mounth) {
                    echo "<th>" . $mounth . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($statsVideosUser as $u_id => $user) {
                echo "<tr class=\"" . (fmod( ++$i, 2) ? 'odd' : 'even') . "\"><td>" . $user['first_name'] . " " . $user['last_name'] . " (" . $user['email_address'] . ")</td>";
                foreach ($statsVideos as $mounth => $stat) {
                    if (isset($stat[$u_id])) {
                        echo "<td>" . round($stat[$u_id]['avg'], 2) . " / " . $stat[$u_id]['countRecord'] . "</td>";
                    } else {
                        echo "<td>0 / 0</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
            </tr>
        </tbody>
    </table>
<?php endif; ?>


