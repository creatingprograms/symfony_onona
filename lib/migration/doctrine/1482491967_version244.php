<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version244 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('orders', 'status', 'string', '255', array(
             ));
    }

    public function down()
    {

    }
}