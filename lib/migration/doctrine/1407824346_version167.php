<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version167 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('product', 'bonuspay', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('product', 'bonuspay');
    }
}