<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version229 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('product', 'startaction', 'date', '25', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('product', 'startaction');
    }
}