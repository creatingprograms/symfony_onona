<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version18 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('dop_info_category', 'is_compare', 'boolean', '25', array(
             'default' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('dop_info_category', 'is_compare');
    }
}