<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version52 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('delivery_payment', 'created_at', 'timestamp', '25', array(
             'notnull' => '1',
             ));
        $this->addColumn('delivery_payment', 'updated_at', 'timestamp', '25', array(
             'notnull' => '1',
             ));
        $this->addColumn('delivery_payment', 'position', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('delivery_payment', 'created_at');
        $this->removeColumn('delivery_payment', 'updated_at');
        $this->removeColumn('delivery_payment', 'position');
    }
}