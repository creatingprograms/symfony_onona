<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version82 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('oprosnik', 'dataans', 'clob', '', array(
             ));
    }

    public function down()
    {

    }
}