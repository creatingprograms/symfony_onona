<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version189 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('compare', 'productsinfo', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('compare', 'productsinfo');
    }
}