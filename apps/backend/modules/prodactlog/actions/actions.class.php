<?php

require_once dirname(__FILE__) . '/../lib/prodactlogGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/prodactlogGeneratorHelper.class.php';

/**
 * prodactlog actions.
 *
 * @package    test
 * @subpackage prodactlog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class prodactlogActions extends autoProdactlogActions {

    protected function buildQuery() {
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters) {
            $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);
        $filters = $this->getFilters();
        //print_r($filters);
        if (isset($filters['prodid']['text'])) {
            $users = ProductTable::getInstance()->createQuery()->where("id = '" . $filters['prodid']['text'] . "' or name like '%" . $filters['prodid']['text'] . "%' or code like '%" . $filters['prodid']['text'] . "%'")->fetchArray();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $filters['prodid']['text'] = $user['id'];
                }
            } else
                $filters['prodid']['text'] = "769853268540";
        }
        
        $query = $this->filters->buildQuery($filters);
        //$query = $this->filters->buildQuery($this->getFilters());

        $this->addSortQuery($query);

        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();

        return $query;
    }

}
