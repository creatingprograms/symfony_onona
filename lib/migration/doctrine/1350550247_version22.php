<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version22 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('category', 'adult', 'boolean', '25', array(
             'default' => '1',
             ));
        $this->addColumn('product', 'adult', 'boolean', '25', array(
             'default' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('category', 'adult');
        $this->removeColumn('product', 'adult');
    }
}