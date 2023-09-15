<?php

class sfWidgetFormDoctrineChoiceNestedSet extends sfWidgetFormDoctrineChoice {

    public function getChoices() {
        $choices = array();
        if (false !== $this->getOption('add_empty')) {
            $choices[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
        }

        if (null === $this->getOption('table_method')) {
            $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
            if ($order = $this->getOption('order_by')) {
                $query->addOrderBy($order[0] . ' ' . $order[1]);
            }
            if ($where = $this->getOption('where')) {
                $query->addwhere($where);
            }
            $objects = $query->execute();
        } else {
            $tableMethod = $this->getOption('table_method');
            $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod();

            if ($results instanceof Doctrine_Query) {
                $objects = $results->execute();
            } else if ($results instanceof Doctrine_Collection) {
                $objects = $results;
            } else if ($results instanceof Doctrine_Record) {
                $objects = new Doctrine_Collection($this->getOption('model'));
                $objects[] = $results;
            } else {
                $objects = array();
            }
        }

        $method = $this->getOption('method');

        $keyMethod = $this->getOption('key_method');

        foreach ($objects as $object) {
            if (!$object->getParentsId()) {
                $choices[$object->$keyMethod()] = $object->$method();
                if ($this->getOption('children')) {
                    foreach ($object->getChildren() as $i => $category):
                        $choices[$category->$keyMethod()] = $category->$method();
                    endforeach;
                }
            }
        }

        return $choices;
    }

}

?>
