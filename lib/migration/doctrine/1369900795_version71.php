<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version71 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('category', 'filters', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('category', 'filters');
    }
}