<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version162 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('shops', 'id1c', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('shops', 'id1c');
    }
}