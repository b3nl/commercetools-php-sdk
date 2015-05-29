<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Sphere\Core\Request\TaxCategories;


use Sphere\Core\Model\Common\Context;
use Sphere\Core\Request\AbstractDeleteByIdRequest;

/**
 * Class TaxCategoryDeleteByIdRequest
 * @package Sphere\Core\Request\TaxCategories
 * @link http://dev.sphere.io/http-api-projects-taxCategories.html#delete-tax-category
 */
class TaxCategoryDeleteByIdRequest extends AbstractDeleteByIdRequest
{
    protected $resultClass = '\Sphere\Core\Model\TaxCategory\TaxCategory';

    /**
     * @param string $id
     * @param int $version
     * @param Context $context
     */
    public function __construct($id, $version, Context $context = null)
    {
        parent::__construct(TaxCategoriesEndpoint::endpoint(), $id, $version, $context);
    }
}