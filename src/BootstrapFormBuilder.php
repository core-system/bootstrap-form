<?php namespace Core\BootstrapForm;

use Config;
use Collective\Html\FormBuilder as CollectiveFormBuilder;

class BootstrapFormBuilder extends CollectiveFormBuilder
{
    /**
     * An array containing the currently opened form groups.
     *
     * @var array
     */
    protected $groupStack = array();

    /**
     * Open a new form group
     *
     * @param  string  $name
     * @param  mixed   $label
     * @param  array   $options
     * @return string
     */
    public function openGroup($name, $label = null, $options = array())
    {
        $options = $this->appendClassToOptions(Config::get('form-builder.control-class'), $options);

        $this->groupStack[] = $name;

        if ($this->hasErrors($name)) {
            $options = $this->appendClassToOptions(Config::get('form-builder.error-class'), $options);
        }

        $label = $label ? $this->label($name, $label) : '';

        return '<div'.$this->html->attributes($options).'>'.$label;
    }

    /**
     * Close out the opened form group
     *
     * @return string
     */
    public function closeGroup()
    {
        $name = array_pop($this->groupStack);

        $errors = $this->getFormattedErrors($name);

        return $errors.'</div>';
    }

    /**
     * Create a form input
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function input($type, $name, $value = null, $options = array())
    {
        $options = $this->appendClassToOptions(Config::get('form-builder.control-class'), $options);

        return parent::input($type, $name, $value, $options);
    }

    /**
     * Create a select box
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $options
     * @return string
     */
    public function select($name, $list = array(), $selected = null, $options = array())
    {
        $options = $this->appendClassToOptions(Config::get('form-builder.control-class'), $options);

        return parent::select($name, $list, $selected, $options);
    }

    /**
     * Create a plain form
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function plainInput($type, $name, $value = null, $options = array())
    {
        return parent::input($type, $name, $value, $options);
    }

    /**
     * Create a plain select box
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $options
     * @return string
     */
    public function plainSelect($name, $list = array(), $selected = null, $options = array())
    {
        return parent::select($name, $list, $selected, $options);
    }

    /**
     * Create a checkable
     *
     * @param  string  $type
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
    protected function checkable($type, $name, $value, $checked, $options)
    {
        if ($this->getCheckedState($type, $name, $value, $checked)) $options['checked'] = 'checked';

        return parent::input($type, $name, $value, $options);
    }

    /**
     * Create a checkbox
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  mixed   $label
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
    public function checkbox($name, $value = 1, $label = null, $checked = null, $options = array())
    {
        return $this->wrapCheckable(
            $label,
            'checkbox',
            parent::checkbox($name, $value, $checked, $options)
        );
    }

    /**
     * Create a radio button
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  mixed   $label
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
    public function radio($name, $value = null, $label = null, $checked = null, $options = array())
    {
        return $this->wrapCheckable(
            $label,
            'radio',
            parent::radio($name, $value, $checked, $options)
        );
    }

    /**
     * Create an inline checkbox
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  mixed   $label
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
    public function inlineCheckbox($name, $value = 1, $label = null, $checked = null, $options = array())
    {
        return $this->wrapInlineCheckable(
            $label,
            'checkbox',
            parent::checkbox($name, $value, $checked, $options)
        );
    }

    /**
     * Create an inline radio button
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  mixed   $label
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
    public function inlineRadio($name, $value = null, $label = null, $checked = null, $options = array())
    {
        return $this->wrapInlineCheckable(
            $label,
            'radio',
            parent::radio($name, $value, $checked, $options)
        );
    }

    /**
     * Create a textarea
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function textarea($name, $value = null, $options = array())
    {
        return parent::textarea(
            $name,
            $value,
            $this->appendClassToOptions(Config::get('form-builder.control-class'), $options)
        );
    }

    /**
     * Create a plain textarea
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function plainTextarea($name, $value = null, $options = array())
    {
        return parent::textarea($name, $value, $options);
    }

    /**
     * Append the class to the given options array
     *
     * @param  string  $class
     * @param  array   $options
     * @return array
     */
    private function appendClassToOptions($class, array $options = array())
    {
        isset($options['class'])
            ? $options['class'] = $options['class'] . ' ' . $class
            : $options['class'] = $class;

        return $options;
    }

    /**
     * Determine whether the form element with the given name has any validation errors
     *
     * @param  string  $name
     * @return bool
     */
    private function hasErrors($name)
    {
        if (is_null($this->session) || !$this->session->has('errors')) {
            return false;
        }
        // Get errors session
        $errors = $this->getErrorsSession();

        return $errors->has($this->transformKey($name));
    }

    /**
     * Get errors session
     *
     * @return mixed
     */
    private function getErrorsSession()
    {
        return $this->session->get('errors');
    }

    /**
     * Get the formatted errors for the form element with the given name
     *
     * @param  string  $name
     * @return string
     */
    private function getFormattedErrors($name)
    {
        if (!$this->hasErrors($name)) {
            return '';
        }
        // Get errors session
        $errors = $this->getErrorsSession();

        return $errors->first($this->transformKey($name), '<p class="help-block">:message</p>');
    }

    /**
     * Wrap the given checkable in the necessary wrappers
     *
     * @param  mixed   $label
     * @param  string  $type
     * @param  string  $checkAble
     * @return string
     */
    private function wrapCheckable($label, $type, $checkAble)
    {
        return '<div class="'.$type.'"><label>'.$checkAble.' '.$label.'</label></div>';
    }

    /**
     * Wrap the given checkable in the necessary inline wrappers
     *
     * @param  mixed   $label
     * @param  string  $type
     * @param  string  $checkAble
     * @return string
     */
    private function wrapInlineCheckable($label, $type, $checkAble)
    {
        return '<div class="'.$type.'-inline">'.$checkAble.' '.$label.'</div>';
    }

}
