<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version45 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('article', 'img', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('article', 'img');
    }
}