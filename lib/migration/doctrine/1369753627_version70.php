<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version70 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('product', 'yamarket', 'boolean', '25', array(
             'default' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('product', 'yamarket');
    }
}