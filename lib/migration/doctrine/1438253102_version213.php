<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version213 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('sf_guard_user', 'activephone', 'boolean', '25', array(
             'default' => '0',
             ));
        $this->addColumn('sf_guard_user', 'activephonecode', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('sf_guard_user', 'activephone');
        $this->removeColumn('sf_guard_user', 'activephonecode');
    }
}