<?php

namespace Forms;

use Template\ViewModel;

/**
 * Class Form
 * @package Forms
 *
 */
class Form extends ViewModel
{
    protected $fields = [];
    protected $isValid = false;

    public function configure(array $config)
    {
        foreach ($config as $name => $handlers) {
            $handlers = array_merge([
                'validate' => null,
                'sanitize' => null
            ], $handlers);
            $this->addField($name, $handlers['validate'], $handlers['sanitize']);
        }
    }

    /**
     * Получить поле формы
     * @param $name
     * @return Field|null
     */
    public function __get($name)
    {
        return isset($this->fields[$name])
            ? $this->fields[$name]
            : null;
    }

    /**
     * Валидация формы
     * @return bool
     */
    public function isValid()
    {
        if ($this->isValid) {
            return true;
        }
        $this->isValid = true;
        /** @var Field $field */
        foreach ($this->fields as $name => $field) {
            if (!$field->validate(parent::__get($name))) {
                if ($this->isValid == true) {
                    $this->isValid = false;
                }
            }
        }
        return $this->isValid;
    }

    /**
     * Добавить поле
     * @param $name
     * @param null $validate
     * @param null $sanitize
     */
    public function addField($name, $validate = null, $sanitize = null)
    {
        $this->fields[$name] = new Field($name, $validate, $sanitize);
    }

}