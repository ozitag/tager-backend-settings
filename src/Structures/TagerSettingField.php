<?php

namespace OZiTAG\Tager\Backend\Settings\Structures;

use OZiTAG\Tager\Backend\Fields\Base\Field;

class TagerSettingField
{
    /** @var Field */
    private $field;

    /** @var mixed */
    private $value;

    /** @var boolean */
    private $private;

    public function __construct(Field $field, $value = null, $private = false)
    {
        $this->field = $field;

        $this->value = $value;

        $this->private = $private;
    }

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }
}
