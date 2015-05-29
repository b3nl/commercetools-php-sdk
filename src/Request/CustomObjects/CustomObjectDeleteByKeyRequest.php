<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Sphere\Core\Request\CustomObjects;


use Sphere\Core\Client\HttpMethod;
use Sphere\Core\Client\HttpRequest;

/**
 * Class CustomObjectsDeleteByKeyRequest
 * @package Sphere\Core\Request\CustomObjects
 * @link http://dev.sphere.io/http-api-projects-custom-objects.html#delete-custom-object
 */
class CustomObjectDeleteByKeyRequest extends AbstractCustomObjectRequest
{
    /**
     * @return HttpRequest
     * @internal
     */
    public function httpRequest()
    {
        return new HttpRequest(HttpMethod::DELETE, $this->getPath());
    }
}