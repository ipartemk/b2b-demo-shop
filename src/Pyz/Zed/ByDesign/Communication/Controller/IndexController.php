<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Business\SalesFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Pyz\Zed\ByDesign\Business\ByDesignFacadeInterface getFacade()
 * @method \Pyz\Zed\ByDesign\Communication\ByDesignCommunicationFactory getFactory()
 * @method \Pyz\Zed\ByDesign\ByDesignConfig getConfig()
 */
class IndexController extends AbstractController
{
    /**
     * ByDesign REST API access point
     * No Auth for test purposes
     * Made for presentation
     *
     * Update Items state
     *
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        return $this->jsonResponse([
            'status' => 'OK',
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function testApiAction(Request $request)
    {
        $salesFacade = $this->getFactory()->getSalesFacade();
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder(2);
        $this->getFacade()->postOrder($orderTransfer);

        echo "OK"; exit;
    }
}
