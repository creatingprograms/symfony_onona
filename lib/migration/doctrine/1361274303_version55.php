<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version55 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('article', 'rating', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('article', 'rating');
    }
}