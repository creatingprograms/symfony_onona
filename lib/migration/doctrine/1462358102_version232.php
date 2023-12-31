<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version232 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addIndex('city', 'city_sluggable', array(
             'fields' => 
             array(
              0 => 'slug',
             ),
             'type' => 'unique',
             ));
    }

    public function down()
    {
        $this->removeIndex('city', 'city_sluggable', array(
             'fields' => 
             array(
              0 => 'slug',
             ),
             'type' => 'unique',
             ));
    }
}