<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version122 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('menu', 'target_blank', 'boolean', '25', array(
             'default' => '0',
             ));
    }

    public function down()
    {
        $this->removeColumn('menu', 'target_blank');
    }
}