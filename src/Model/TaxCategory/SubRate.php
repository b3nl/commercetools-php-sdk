<?php
/**
 * @author @jayS-de <jens.schulze@commercetools.de>
 */

namespace Commercetools\Core\Model\TaxCategory;

use Commercetools\Core\Model\Common\JsonObject;

/**
 * @package Commercetools\Core\Model\TaxCategory
 *
 * @method string getName()
 * @method SubRate setName(string $name = null)
 * @method float getAmount()
 * @method SubRate setAmount(float $amount = null)
 */
class SubRate extends JsonObject
{
    public function fieldDefinitions()
    {
        return [
            'name' => [self::TYPE => 'string'],
            'amount' => [self::TYPE => 'float'],
        ];
    }
}