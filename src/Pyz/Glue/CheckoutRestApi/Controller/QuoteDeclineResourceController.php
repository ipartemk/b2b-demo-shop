<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi\Controller;

use Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Pyz\Glue\CheckoutRestApi\CheckoutRestApiFactory getFactory()
 */
class QuoteDeclineResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Decline quote by approver"
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "X-Anonymous-Customer-Unique-Id",
     *                  "in": "header",
     *                  "required": false,
     *                  "description": "Guest customer unique ID."
     *              }
     *          ],
     *          "responses": {
     *              "200": "Expected response to a valid request.",
     *              "400": "Bad Response.",
     *              "422": "Unprocessable entity."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestQuoteDeclineResponseAttributesTransfer",
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createQuoteDecliner()
            ->decline($restRequest, $restQuoteDeclineAttributesTransfer);
    }
}
