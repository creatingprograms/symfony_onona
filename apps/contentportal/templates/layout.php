<?
//echo phpinfo();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php use_stylesheet('admin.css') ?>
        <?php use_stylesheet('backendmenu.css') ?>
        <?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
        <?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>

    </head>
    <body>
        <div id="container">

            <div id="menu">
                <?php
                if (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Enter contentportal")):
                    ?>
                    <ul id="nav" class="backendnavmenu">
                        <li>
                            <?php echo link_to('Главная', 'homepage') ?>
                        </li>
                        <li>
                            <?php echo link_to('Комментарии', 'comments') ?>
                            <ul>
                                <li><?php echo link_to('Комментарии', 'comments') ?></li>
                            </ul>
                        </li>
                        <li>
                            <?php echo link_to('Фото', 'photos_user') ?>
                            <ul>
                                <li><?php echo link_to('Фото', 'photos_user') ?></li>
                            </ul>
                        </li>
                        <li><?php echo link_to('Видео', 'video') ?>
                            <ul>
                                <li><?php echo link_to('Видео', 'video') ?></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="Статистика">Статистика</a>
                            <ul>
                                <li><?php echo link_to('Статистика среднего балла по месяцам', 'stats/gpa') ?></li>
                            </ul>
                        </li>
                        <li>
                            <?php echo link_to('Выход', '/backend.php/guard/logout') ?>
                        </li>
                    </ul>

                <?php endif; ?>


                <div id="sf_admin_container">
                    <div id="content">
                        <?php
                        echo $sf_content;
                        ?>
                    </div>
                </div>

            </div>
    </body>
</html>
