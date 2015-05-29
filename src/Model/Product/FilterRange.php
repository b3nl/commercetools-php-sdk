<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Sphere\Core\Model\Product;

use Sphere\Core\Model\Common\DateTimeDecorator;
use Sphere\Core\Model\Common\JsonObject;
use Sphere\Core\Model\Common\OfTrait;

/**
 * Class FilterRange
 * @package Sphere\Core\Model\Product
 * @method getFrom()
 * @method FilterRange setFrom($from = null)
 * @method getTo()
 * @method FilterRange setTo($to = null)
 */
class FilterRange extends JsonObject
{
    use OfTrait;

    protected $valueType;

    public function __construct($valueType = null)
    {
        if (is_null($valueType)) {
            $valueType = 'int';
        }
        $this->valueType = $valueType;
    }

    public function getFields()
    {
        return [
            'from' => [static::TYPE => $this->valueType],
            'to' => [static::TYPE => $this->valueType]
        ];
    }

    protected function valueToString($value)
    {
        if (is_null($value)) {
            return '*';
        }
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        if (is_string($value)) {
            return '"' . $value . '"';
        }
        if ($value instanceof \DateTime) {
            $decorator = new DateTimeDecorator($value);
            return '"' . $decorator->jsonSerialize() . '"';
        }
        return (string)$value;
    }

    public function __toString()
    {
        return sprintf('(%s to %s)', $this->valueToString($this->getFrom()), $this->valueToString($this->getTo()));
    }
}