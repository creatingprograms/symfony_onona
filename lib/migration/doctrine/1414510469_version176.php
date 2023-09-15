<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version176 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('qiwi', 'qiwi_city_id_city_id', array(
             'name' => 'qiwi_city_id_city_id',
             'local' => 'city_id',
             'foreign' => 'id',
             'foreignTable' => 'city',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('qiwi', 'qiwi_city_id', array(
             'fields' => 
             array(
              0 => 'city_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('qiwi', 'qiwi_city_id_city_id');
        $this->removeIndex('qiwi', 'qiwi_city_id', array(
             'fields' => 
             array(
              0 => 'city_id',
             ),
             ));
    }
}