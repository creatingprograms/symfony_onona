<?php

/**
 * MailTemplates form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MailTemplatesForm extends BaseMailTemplatesForm
{
  public function configure()
  {
            $this->widgetSchema['text'] = new sfWidgetFormCKEditor();
  }
}
