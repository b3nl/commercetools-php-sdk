<?php
/**
 * @author @jayS-de <jens.schulze@commercetools.de>
 */

namespace Commercetools\Core\Model\Common;

/**
 * @package Commercetools\Core\Model\Common
 * @link https://dev.commercetools.com/http-api-projects-products.html#attribute
 * @link https://dev.commercetools.com/http-api-projects-productTypes.html#localizedenumvalue
 * @method LocalizedString getLabel()
 * @method LocalizedEnum setLabel(LocalizedString $label = null)
 * @method string getKey()
 * @method LocalizedEnum setKey(string $key = null)
 */
class LocalizedEnum extends JsonObject
{
    /**
     * @return array
     */
    public function fieldDefinitions()
    {
        return [
            'label' => [static::TYPE => '\Commercetools\Core\Model\Common\LocalizedString'],
            'key' => [static::TYPE => 'string']
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLabel()->__toString();
    }
}
