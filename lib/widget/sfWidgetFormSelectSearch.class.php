<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSelect represents a select HTML tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSelect.class.php 30762 2010-08-25 12:33:33Z fabien $
 */
class sfWidgetFormSelectSearch extends sfWidgetFormChoiceBase {

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * choices:  An array of possible choices (required)
     *  * multiple: true if the select tag must allow multiple selections
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetFormChoiceBase
     */
    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);

        $this->addOption('multiple', false);
    }

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value selected in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        if ($this->getOption('multiple')) {
            $attributes['multiple'] = 'multiple';

            if ('[]' != substr($name, -2)) {
                $name .= '[]';
            }
        }

        $choices = $this->getChoices();
        if (!isset($attributes['id']) && isset($name)) {
            $attributes['id'] = $this->generateId($name, isset($attributes['value']) ? $attributes['value'] : null);
        }
        $searchTag = "<div>
            <script type='text/javascript'>
                    $(document).ready(function() {
                        $('#search_" . $attributes['id'] . "').keyup(function() {
                            //if ($('#search_" . $attributes['id'] . "').val().length >= 3) { //search only after 3 chars are entered.
                                search = $('#search_" . $attributes['id'] . "').val();
                                $('#" . $attributes['id'] . " option').each(function(){
                                    if ($(this).text().toLowerCase().indexOf(search.toLowerCase()) == -1) {
                                        //$(this).attr('selected', 'yes');
                                        $(this).attr('hidden','hidden');
                                    }else{
                                        $(this).removeAttr('hidden');
                                    }
                                });
                            //}
                        });
                    });
                </script>
                <input type='text' size='20' id='search_" . $attributes['id'] . "' value='' name='search' style='margin-bottom: 5px;'>
                    </div>";

        return $searchTag . $this->renderContentTag('select', "\n" . implode("\n", $this->getOptionsForSelect($value, $choices)) . "\n", array_merge(array('name' => $name), $attributes));
    }

    /**
     * Returns an array of option tags for the given choices
     *
     * @param  string $value    The selected value
     * @param  array  $choices  An array of choices
     *
     * @return array  An array of option tags
     */
    protected function getOptionsForSelect($value, $choices) {
        $mainAttributes = $this->attributes;
        $this->attributes = array();

        if (!is_array($value)) {
            $value = array($value);
        }

        $value = array_map('strval', array_values($value));
        $value_set = array_flip($value);

        $options = array();
        foreach ($choices as $key => $option) {
            if (is_array($option)) {
                $options[] = $this->renderContentTag('optgroup', implode("\n", $this->getOptionsForSelect($value, $option)), array('label' => self::escapeOnce($key)));
            } else {
                $attributes = array('value' => self::escapeOnce($key));
                if (isset($value_set[strval($key)])) {
                    $attributes['selected'] = 'selected';
                }

                $options[] = $this->renderContentTag('option', self::escapeOnce($option), $attributes);
            }
        }

        $this->attributes = $mainAttributes;

        return $options;
    }

}
