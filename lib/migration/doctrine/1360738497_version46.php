<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version46 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('article', 'views_count', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('article', 'views_count');
    }
}