<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version61 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('category', 'positionloveprice', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('category', 'positionloveprice');
    }
}