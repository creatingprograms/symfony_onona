<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version116 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('news', 'is_public', 'boolean', '25', array(
             'default' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('news', 'is_public');
    }
}