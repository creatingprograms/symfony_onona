<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version226 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('actions_discount', 'discount', 'integer', '8', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {

    }
}