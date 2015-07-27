<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 * @created: 26.01.15, 17:02
 */

namespace Sphere\Core\Request\Carts;

use Sphere\Core\Model\Common\Context;
use Sphere\Core\Request\AbstractDeleteRequest;
use Sphere\Core\Model\Cart\Cart;
use Sphere\Core\Response\ApiResponseInterface;

/**
 * @package Sphere\Core\Request\Carts
 * @link http://dev.sphere.io/http-api-projects-carts.html#delete-cart
 * @method Cart mapResponse(ApiResponseInterface $response)
 */
class CartDeleteRequest extends AbstractDeleteRequest
{
    protected $resultClass = '\Sphere\Core\Model\Cart\Cart';

    /**
     * @param string $id
     * @param int $version
     * @param Context $context
     */
    public function __construct($id, $version, Context $context = null)
    {
        parent::__construct(CartsEndpoint::endpoint(), $id, $version, $context);
    }

    /**
     * @param string $id
     * @param int $version
     * @param Context $context
     * @return static
     */
    public static function ofIdAndVersion($id, $version, Context $context = null)
    {
        return new static($id, $version, $context);
    }
}