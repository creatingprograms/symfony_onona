<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version24 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('menu', 'menu_parents_id_menu_id', array(
             'name' => 'menu_parents_id_menu_id',
             'local' => 'parents_id',
             'foreign' => 'id',
             'foreignTable' => 'menu',
             ));
        $this->addIndex('menu', 'menu_parents_id', array(
             'fields' => 
             array(
              0 => 'parents_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('menu', 'menu_parents_id_menu_id');
        $this->removeIndex('menu', 'menu_parents_id', array(
             'fields' => 
             array(
              0 => 'parents_id',
             ),
             ));
    }
}