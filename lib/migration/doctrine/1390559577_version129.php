<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version129 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('orders', 'comment_1c', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('orders', 'comment_1c');
    }
}