<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version34 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('product', 'step', 'enum', '', array(
             'values' => 
             array(
              0 => '',
              1 => '1 сутки',
              2 => '2 суток',
              3 => '3 суток',
              4 => '4 суток',
              5 => '5 суток',
             ),
             ));
    }

    public function down()
    {

    }
}