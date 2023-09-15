<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version157 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->dropForeignKey('horoscopesovm', 'horoscopesovm_horoscope_m_id_horoscope_id');
        $this->createForeignKey('horoscopesovm', 'horoscopesovm_horoscope_g_id_horoscope_id', array(
             'name' => 'horoscopesovm_horoscope_g_id_horoscope_id',
             'local' => 'horoscope_g_id',
             'foreign' => 'id',
             'foreignTable' => 'horoscope',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('horoscopesovm', 'horoscopesovm_horoscope_g_id', array(
             'fields' => 
             array(
              0 => 'horoscope_g_id',
             ),
             ));
    }

    public function down()
    {
        $this->createForeignKey('horoscopesovm', 'horoscopesovm_horoscope_m_id_horoscope_id', array(
             'name' => 'horoscopesovm_horoscope_m_id_horoscope_id',
             'local' => 'horoscope_m_id',
             'foreign' => 'id',
             'foreignTable' => 'horoscope',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->dropForeignKey('horoscopesovm', 'horoscopesovm_horoscope_g_id_horoscope_id');
        $this->removeIndex('horoscopesovm', 'horoscopesovm_horoscope_g_id', array(
             'fields' => 
             array(
              0 => 'horoscope_g_id',
             ),
             ));
    }
}