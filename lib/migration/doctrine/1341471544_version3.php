<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version3 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('bonus', 'bonus_user_id_sf_guard_user_id', array(
             'name' => 'bonus_user_id_sf_guard_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('bonus', 'bonus_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('bonus', 'bonus_user_id_sf_guard_user_id');
        $this->removeIndex('bonus', 'bonus_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
    }
}