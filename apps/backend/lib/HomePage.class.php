<?php

class HomePage extends sfGuardSecurityUser
{
    static public function redirectHomePage($user,$thisParent) {
        if (!$user->hasPermission("All")) {
            if ($user->hasPermission("Модерирование товаров")) {
                $thisParent->redirect("product/moder");
            }

            if ($user->hasPermission("Просмотр фото и видео товаров")) {
                $thisParent->redirect("@product");
            }

            if ($user->hasPermission("Bonus")) {
                $thisParent->redirect("@bonus");
            }

            if ($user->hasPermission("Обновление товаров Лидеры продаж")) {
                $thisParent->redirect("pages/setBestSellers");
            }

            if ($user->hasPermission("Обновление товаров не входящих в Перс. Рекоменд.")) {
                $thisParent->redirect("pages/setNoPersonalRecomendation");
            }

            if ($user->hasPermission("Manager category")) {
                $thisParent->redirect("@category_mcategory");
            }

            if ($user->hasPermission("Manager product")) {
                $thisParent->redirect("@product_mproduct");
            }

            if ($user->hasPermission("Manager add product")) {
                $thisParent->redirect("@product_manproduct");
            }

            if ($user->hasPermission("Manager article")) {
                $thisParent->redirect("@article");
            }

            if ($user->hasPermission("Manager orders")) {
                $thisParent->redirect("@orders");
            }

            if ($user->hasPermission("Manager oprosnik")) {
                $thisParent->redirect("@oprosnik");
            }

            if ($user->hasPermission("Manager Sravnenie Article")) {
                $thisParent->redirect("product/codeisset");
            }

            if ($user->hasPermission("Manager Product non count")) {
                $thisParent->redirect("pages/usersendstats");
            }

            if ($user->hasPermission("Manager Статистика по всем товарам")) {
                $thisParent->redirect("pages/statsProduct");
            }

            if ($user->hasPermission("Менеджер тестов")) {
                $thisParent->redirect("@tests");
            }

        }
    }
}
