<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version40 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('article', 'precontent', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('article', 'precontent');
    }
}