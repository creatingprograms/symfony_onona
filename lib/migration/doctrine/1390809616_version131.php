<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version131 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('orders', 'firsttotalcost', 'integer', '8', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('orders', 'firsttotalcost');
    }
}