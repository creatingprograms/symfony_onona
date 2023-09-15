<?php

/**
 * sfGuardUserAdminForm for admin generators
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm {

    /**
     * @see sfForm
     */
    public function configure() {
        $years = range(date('Y') - 18, date('Y') - 90);
        $years_list = array_combine($years, $years);
        //$this->widgetSchema['birthday']->setOption('format', '%day%<span style="float: left">.</span>%month%<span style="float: left">.</span>%year%');
        $this->widgetSchema['birthday']->setOption('years', $years_list);        
                $this->widgetSchema['permissions_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission'),array("size"=>"10"));
        
    }

}
