<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version221 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('sf_guard_user', 'personal_recomendation', 'clob', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('sf_guard_user', 'personal_recomendation');
    }
}