<?php

/**
 * BasecsSetting
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property clob $widget_options
 * @property clob $value
 * @property string $setting_group
 * @property clob $setting_default
 * 
 * @method integer   getId()              Returns the current record's "id" value
 * @method string    getName()            Returns the current record's "name" value
 * @method string    getType()            Returns the current record's "type" value
 * @method clob      getWidgetOptions()   Returns the current record's "widget_options" value
 * @method clob      getValue()           Returns the current record's "value" value
 * @method string    getSettingGroup()    Returns the current record's "setting_group" value
 * @method clob      getSettingDefault()  Returns the current record's "setting_default" value
 * @method csSetting setId()              Sets the current record's "id" value
 * @method csSetting setName()            Sets the current record's "name" value
 * @method csSetting setType()            Sets the current record's "type" value
 * @method csSetting setWidgetOptions()   Sets the current record's "widget_options" value
 * @method csSetting setValue()           Sets the current record's "value" value
 * @method csSetting setSettingGroup()    Sets the current record's "setting_group" value
 * @method csSetting setSettingDefault()  Sets the current record's "setting_default" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasecsSetting extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cs_setting');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('type', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'input',
             'length' => 255,
             ));
        $this->hasColumn('widget_options', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('value', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('setting_group', 'string', 255, array(
             'type' => 'string',
             'default' => '',
             'length' => 255,
             ));
        $this->hasColumn('setting_default', 'clob', null, array(
             'type' => 'clob',
             'default' => '',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'fields' => 
             array(
              0 => 'name',
             ),
             'builder' => 
             array(
              0 => 'csSettings',
              1 => 'settingize',
             ),
             ));
        $this->actAs($sluggable0);
    }
}