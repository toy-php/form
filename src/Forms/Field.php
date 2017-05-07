<?php

namespace Forms;

/**
 * Class Field
 * @package Forms
 */
class Field
{

    protected $name;
    protected $value;
    protected $errorMessage;
    protected $validate;
    protected $sanitize;
    protected $isValid = false;

    public function __construct($name, $validate = null, $sanitize = null)
    {
        $this->name = $name;
        $this->validate = is_callable($validate)
            ? $validate
            : function () {
                return true;
            };
        $this->sanitize = is_callable($sanitize)
            ? $sanitize
            : function ($value) {
                if(is_array($value)){
                    return filter_var_array($value, FILTER_SANITIZE_STRING);
                }
                return filter_var($value, FILTER_SANITIZE_STRING);
            };
    }

    /**
     * Получить имя поля
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Получить значение поля
     * @return mixed
     */
    public function getValue()
    {
        return $this->isValid() ? $this->value : null;
    }

    /**
     * Получить сообщение ошибки
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Валидность значения поля
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * Валидация значения поля
     * @param $value
     * @return boolean
     */
    public function validate($value)
    {
        try{
            $validate = $this->validate;
            $sanitize = $this->sanitize;
            $this->value = $sanitize($value);
            return $this->isValid ?: $this->isValid = $validate($this->value);
        }catch (ValidateException $exception){
            $this->errorMessage = $exception->getMessage();
            return $this->isValid = false;
        }
    }

}