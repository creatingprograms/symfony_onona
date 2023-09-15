<?php

/**
 * Orders form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OrdersForm extends BaseOrdersForm
{
  public function configure()
  {
        unset($this['custombonusper'],$this['sms_id'],$this['sms_price'],$this['sms_currency']);
  }
}
