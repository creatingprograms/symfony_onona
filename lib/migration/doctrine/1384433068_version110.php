<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version110 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('shops', 'indescription');
        $this->addColumn('shops', 'description', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->addColumn('shops', 'indescription', 'clob', '', array(
             ));
        $this->removeColumn('shops', 'description');
    }
}