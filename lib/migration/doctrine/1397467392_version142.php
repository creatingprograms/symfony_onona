<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version142 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('category', 'h1', 'string', '255', array(
             ));
        $this->addColumn('product', 'h1', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('category', 'h1');
        $this->removeColumn('product', 'h1');
    }
}