<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version207 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('video', 'is_related', 'boolean', '25', array(
             'default' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('video', 'is_related');
    }
}