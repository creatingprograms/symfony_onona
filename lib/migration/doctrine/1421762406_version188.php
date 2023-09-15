<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version188 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('notification', 'notification_user_id_sf_guard_user_id', array(
             'name' => 'notification_user_id_sf_guard_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('notification', 'notification_product_id_product_id', array(
             'name' => 'notification_product_id_product_id',
             'local' => 'product_id',
             'foreign' => 'id',
             'foreignTable' => 'product',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('notification', 'notification_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->addIndex('notification', 'notification_product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('notification', 'notification_user_id_sf_guard_user_id');
        $this->dropForeignKey('notification', 'notification_product_id_product_id');
        $this->removeIndex('notification', 'notification_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->removeIndex('notification', 'notification_product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
             ));
    }
}