<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version192 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('video', 'link', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('video', 'link');
    }
}