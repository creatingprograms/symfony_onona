<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version93 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('product', 'id1c', 'string', '255', array(
             ));
    }

    public function down()
    {

    }
}