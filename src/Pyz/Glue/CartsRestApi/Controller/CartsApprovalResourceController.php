<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Pyz\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsApprovalResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a cart for Approve by id."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "404": "Cart not found.",
     *              "403": "Missing access token."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves list of all carts for Approve."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuidQuote = $restRequest->getResource()->getId();

        if ($uuidQuote !== null) {
            return $this->getFactory()->createCartApprovalReader()->getQuoteApprovalByUuid($uuidQuote, $restRequest);
        }

        return $this->getFactory()->createCartApprovalReader()->getAllQuoteApprovals($restRequest);
    }
}
