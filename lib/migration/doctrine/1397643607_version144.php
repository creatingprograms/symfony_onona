<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version144 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('horoscope', 'slug', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('horoscope', 'slug');
    }
}