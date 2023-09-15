<?php

require_once dirname(__FILE__) . '/../lib/ordersGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/ordersGeneratorHelper.class.php';

/**
 * orders actions.
 *
 * @package    Magazin
 * @subpackage orders
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ordersActions extends autoOrdersActions {

    public function executeIndex(sfWebRequest $request) {
        ini_set("max_execution_time", "600");
        set_time_limit(600);
        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        $this->pager = $this->getPager();

        $filters = $this->getFilters();
        if (count($filters) > 0) {
            $this->pager = $this->getPager(100);
        } else {
            $this->pager = $this->getPager(50);
        }
        $this->sort = $this->getSort();
        $this->filtersCount = count($filters);
        if (count($filters) > 0) {
            $tableMethod = $this->configuration->getTableMethod();
            if (null === $this->filters) {
                $this->filters = $this->configuration->getFilterForm($this->getFilters());
            }

            $this->filters->setTableMethod($tableMethod);

            $query = $this->filters->buildQuery($this->getFilters());


            $event = $this->dispatcher->filter(new

                    sfEvent($this, 'admin.build_query'), $query);
            $query = $event->getReturnValue();

            $pager2 = new sfDoctrinePager('Orders', 10000);
            $pager2->setQuery($query);
            $pager2->setPage($this->getPage());
            $pager2->init();
            foreach ($pager2->getResults() as $orders) {

                $TotalSumm = $orders->getFirsttotalcost() + $orders->getBonuspay();
                /*
                  $products_old = unserialize($orders->getFirsttext());
                  if (is_array($products_old))
                  foreach ($products_old as $key => $productInfo):
                  $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']));


                  endforeach; */
                $this->TotalAllOrdersSumm = $this->TotalAllOrdersSumm + $TotalSumm;
                $this->TotalAllOrdersSummBonus = $this->TotalAllOrdersSummBonus + $orders->getBonuspay();
                $this->TotalAllOrdersSummMinBon = $this->TotalAllOrdersSummMinBon + $TotalSumm - $orders->getBonuspay();
                //echo round(($orders->getBonuspay() / $TotalSumm) * 100);
                if ((@($orders->getBonuspay() / $TotalSumm) * 100) == 100) {
                    @$arrayPer[100]['count'] = $arrayPer[100]['count'] + 1;
                    @$arrayPer[100]['TotalSumm'] = $arrayPer[100]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[100]['TotalSummBonus'] = $arrayPer[100]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 90) {
                    @$arrayPer[90]['count'] = $arrayPer[90]['count'] + 1;
                    @$arrayPer[90]['TotalSumm'] = $arrayPer[90]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[90]['TotalSummBonus'] = $arrayPer[90]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 80) {
                    @$arrayPer[80]['count'] = $arrayPer[80]['count'] + 1;
                    @$arrayPer[80]['TotalSumm'] = $arrayPer[80]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[80]['TotalSummBonus'] = $arrayPer[80]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 70) {
                    @$arrayPer[70]['count'] = $arrayPer[70]['count'] + 1;
                    @$arrayPer[70]['TotalSumm'] = $arrayPer[70]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[70]['TotalSummBonus'] = $arrayPer[70]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 60) {
                    @$arrayPer[60]['count'] = $arrayPer[60]['count'] + 1;
                    @$arrayPer[60]['TotalSumm'] = $arrayPer[60]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[60]['TotalSummBonus'] = $arrayPer[60]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 50) {
                    @$arrayPer[50]['count'] = $arrayPer[50]['count'] + 1;
                    @$arrayPer[50]['TotalSumm'] = $arrayPer[50]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[50]['TotalSummBonus'] = $arrayPer[50]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 40) {
                    @$arrayPer[40]['count'] = $arrayPer[40]['count'] + 1;
                    @$arrayPer[40]['TotalSumm'] = $arrayPer[40]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[40]['TotalSummBonus'] = $arrayPer[40]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 30) {
                    @$arrayPer[30]['count'] = $arrayPer[30]['count'] + 1;
                    @$arrayPer[30]['TotalSumm'] = $arrayPer[30]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[30]['TotalSummBonus'] = $arrayPer[30]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 20) {
                    @$arrayPer[20]['count'] = $arrayPer[20]['count'] + 1;
                    @$arrayPer[20]['TotalSumm'] = $arrayPer[20]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[20]['TotalSummBonus'] = $arrayPer[20]['TotalSummBonus'] + $orders->getBonuspay();
                } elseif ((@($orders->getBonuspay() / $TotalSumm) * 100) > 10) {
                    @$arrayPer[10]['count'] = $arrayPer[10]['count'] + 1;
                    @$arrayPer[10]['TotalSumm'] = $arrayPer[10]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[10]['TotalSummBonus'] = $arrayPer[10]['TotalSummBonus'] + $orders->getBonuspay();
                } else {
                    @$arrayPer[0]['count'] = $arrayPer[0]['count'] + 1;
                    @$arrayPer[0]['TotalSumm'] = $arrayPer[0]['TotalSumm'] + $TotalSumm;
                    @$arrayPer[0]['TotalSummBonus'] = $arrayPer[0]['TotalSummBonus'] + $orders->getBonuspay();
                }
            }
            krsort($arrayPer);
            $this->arrayPer = $arrayPer;
        }
    }

    protected function getPager($MaxPerPage = 150) {
        $pager = $this->configuration->getPager('Orders');
        $pager->setQuery($this->buildQuery());
        $pager->setPage($this->getPage());
        $pager->setMaxPerPage($MaxPerPage);
        $pager->init();

        return $pager;
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('created_at DESC');
    }

    protected function buildQuery() {
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters) {
            $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);


        $filters = $this->getFilters();
        if (isset($filters['customer_id'])) {
            $users = sfGuardUserTable::getInstance()->createQuery()->where("id = '" . $filters ['customer_id'] . "' or email_address like '%" . $filters['customer_id'] . "%'")->fetchArray();
            if (count($users) > 0) {
                $filters['customer_id'] = array();
                foreach ($users
                as $user) {
                    $filters['customer_id'][] = $user['id'];
                }
            } else
                $filters['customer_id'] = "769853268540";
        }

        $query = $this->filters->buildQuery($filters);

        $this->addSortQuery($query);

        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();

        return $query;
    }

}
