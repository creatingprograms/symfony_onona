<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version85 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('page', 'content_mobile', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('page', 'content_mobile');
    }
}