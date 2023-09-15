<?php

class BasesfGuardAuthComponents extends sfComponents
{
  public function executeSignin_form()
  {
    $class = sfConfig::get('app_sf_guard_plugin_signin_form', 'sfGuardFormSignin');
    // die(__FILE__.$class);
    $this->form = new $class();
  }
}
