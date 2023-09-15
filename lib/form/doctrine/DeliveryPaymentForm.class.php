<?php

/**
 * DeliveryPayment form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DeliveryPaymentForm extends BaseDeliveryPaymentForm
{
  public function configure()
  {
        unset($this['position']);
  }
}
