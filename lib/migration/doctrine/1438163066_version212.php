<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version212 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('sf_guard_user', 'oldphone', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('sf_guard_user', 'oldphone');
    }
}