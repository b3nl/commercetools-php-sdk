<?php
/**
 * @author @jayS-de <jens.schulze@commercetools.de>
 * @created: 27.01.15, 14:53
 */

namespace Commercetools\Core\Model\Common;

use Commercetools\Core\Model\Cart\CartReference;
use Commercetools\Core\Model\CartDiscount\CartDiscountReference;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Model\Channel\ChannelReference;
use Commercetools\Core\Model\Customer\CustomerReference;
use Commercetools\Core\Model\CustomerGroup\CustomerGroupReference;
use Commercetools\Core\Model\CustomObject\CustomObjectReference;
use Commercetools\Core\Model\DiscountCode\DiscountCodeReference;
use Commercetools\Core\Model\Order\OrderReference;
use Commercetools\Core\Model\Payment\PaymentReference;
use Commercetools\Core\Model\Product\ProductReference;
use Commercetools\Core\Model\ProductDiscount\ProductDiscountReference;
use Commercetools\Core\Model\ProductType\ProductTypeReference;
use Commercetools\Core\Model\ShippingMethod\ShippingMethodReference;
use Commercetools\Core\Model\State\StateReference;
use Commercetools\Core\Model\TaxCategory\TaxCategoryReference;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Model\Zone\ZoneReference;

/**
 * @package Commercetools\Core\Model\Common
 * @link https://dev.commercetools.com/http-api-types.html#reference
 * @method string getTypeId()
 * @method string getId()
 * @method Reference setTypeId(string $typeId = null)
 * @method Reference setId(string $id = null)
 * @method JsonObject getObj()
 * @method Reference setObj(JsonObject $obj = null)
 * @method string getKey()
 * @method Reference setKey(string $key = null)
 */
class Reference extends ResourceIdentifier
{
    const OBJ = 'obj';

    const TYPE_CLASS = '\Commercetools\Core\Model\Common\JsonObject';

    public function fieldDefinitions()
    {
        $fieldDefinitions = parent::fieldDefinitions();
        $fieldDefinitions[static::OBJ] = [static::TYPE => static::TYPE_CLASS];

        return $fieldDefinitions;
    }

    /**
     * @param array $data
     * @param Context|callable $context
     * @return static
     */
    public static function fromArray(array $data, $context = null)
    {
        if (get_called_class() == 'Commercetools\Core\Model\Common\Reference' && isset($data[static::TYPE_ID])) {
            $className = static::referenceType($data[static::TYPE_ID]);
            if (class_exists($className)) {
                return new $className($data, $context);
            }
        }
        return new static($data, $context);
    }

    protected static function referenceType($typeId)
    {
        $types = [
            CartReference::TYPE_CART => '\Commercetools\Core\Model\Cart\CartReference',
            CartDiscountReference::TYPE_CART_DISCOUNT => '\Commercetools\Core\Model\CartDiscount\CartDiscountReference',
            CategoryReference::TYPE_CATEGORY => '\Commercetools\Core\Model\Category\CategoryReference',
            ChannelReference::TYPE_CHANNEL => '\Commercetools\Core\Model\Channel\ChannelReference',
            CustomerReference::TYPE_CUSTOMER => '\Commercetools\Core\Model\Customer\CustomerReference',
            CustomObjectReference::TYPE_CUSTOM_OBJECT => '\Commercetools\Core\Model\CustomObject\CustomObjectReference',
            CustomerGroupReference::TYPE_CUSTOMER_GROUP =>
                '\Commercetools\Core\Model\CustomerGroup\CustomerGroupReference',
            DiscountCodeReference::TYPE_DISCOUNT_CODE => '\Commercetools\Core\Model\DiscountCode\DiscountCodeReference',
            CustomObjectReference::TYPE_CUSTOM_OBJECT => '\Commercetools\Core\Model\CustomObject\CustomObjectReference',
            PaymentReference::TYPE_PAYMENT => '\Commercetools\Core\Model\Payment\PaymentReference',
            ProductReference::TYPE_PRODUCT => '\Commercetools\Core\Model\Product\ProductReference',
            ProductDiscountReference::TYPE_PRODUCT_DISCOUNT =>
                '\Commercetools\Core\Model\ProductDiscount\ProductDiscountReference',
            ProductTypeReference::TYPE_PRODUCT_TYPE => '\Commercetools\Core\Model\ProductType\ProductTypeReference',
            OrderReference::TYPE_ORDER => '\Commercetools\Core\Model\Order\OrderReference',
            ShippingMethodReference::TYPE_SHIPPING_METHOD =>
                '\Commercetools\Core\Model\ShippingMethod\ShippingMethodReference',
            StateReference::TYPE_STATE => '\Commercetools\Core\Model\State\StateReference',
            TaxCategoryReference::TYPE_TAX_CATEGORY => '\Commercetools\Core\Model\TaxCategory\TaxCategoryReference',
            TypeReference::TYPE_TYPE => '\Commercetools\Core\Model\Type\TypeReference',
            ZoneReference::TYPE_ZONE => '\Commercetools\Core\Model\Zone\ZoneReference',
        ];
        return isset($types[$typeId]) ? $types[$typeId] : '\Commercetools\Core\Model\Common\Reference';
    }
}
