<?php

require_once dirname(__FILE__) . '/../lib/ordershopGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/ordershopGeneratorHelper.class.php';

/**
 * ordershop actions.
 *
 * @package    test
 * @subpackage ordershop
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ordershopActions extends autoOrdershopActions {

    protected function addSortQuery($query) {
        if (@$_GET['sort'] == "")
            $query->addOrderBy('date DESC');
        else
            $query->addOrderBy($_GET['sort'] . ' ' . $_GET['sort_type']);
    }

    public function executeStats(sfWebRequest $request) {
        ini_set("max_execution_time", "600");
        set_time_limit(600);
        
        if (@$_POST['fromDate'] != "") {

            $this->getUser()->setAttribute('managerstatsFrom', $_POST['fromDate']);
            $from = explode('.', $_POST['fromDate']);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }
        if (@$_POST['to'] != "") {
            $this->getUser()->setAttribute('managerstatsTo', $_POST['to']);
            $from = explode('.', $_POST['to']);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        if ($this->getUser()->getAttribute('managerstatsFrom') != "") {

            $managerstats = $this->getUser()->getAttribute('managerstatsFrom');
            $_POST['fromDate'] = $managerstats;
            $from = explode('.', $managerstats);
            unset($managerstats);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }

        if ($this->getUser()->getAttribute('managerstatsTo') != "") {
            $searchLog2 = $this->getUser()->getAttribute('managerstatsTo');
            $_POST['to'] = $searchLog2;
            $from = explode('.', $searchLog2);
            //unset($searchLog);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        /*$ordersshop = OrdersShopTable::getInstance()->findAll();
        foreach ($ordersshop as $order) {
            $arrayStats[substr($order->getDopid(), 0, 3)]['count'] = $arrayStats[substr($order->getDopid(), 0, 3)]['count'] + 1;
            if ($order->getActive())
                $arrayStats[substr($order->getDopid(), 0, 3)]['countActive'] = $arrayStats[substr($order->getDopid(), 0, 3)]['countActive'] + 1;
            $arrayStats[substr($order->getDopid(), 0, 3)]['summ'] = $arrayStats[substr($order->getDopid(), 0, 3)]['summ'] + $order->getPrice();
        }*/
                $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT * , COUNT( LEFT( dopid, 2 ) ) AS Count, SUM( price ) AS Summ, SUM( IF( active =1, 1, 0 ) ) AS countActive, SUM( IF( active =1, price, 0 ) ) AS summActive
FROM  `orders_shop` where date > '". $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day']."' and date < '". $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day']."'
GROUP BY LEFT( dopid, 2 )");
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $this->checkStats = $result->fetchAll();
    }

}
