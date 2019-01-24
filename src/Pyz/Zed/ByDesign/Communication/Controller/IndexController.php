<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ByDesign\Business\ByDesignFacadeInterface getFacade()
 * @method \Pyz\Zed\ByDesign\Communication\ByDesignCommunicationFactory getFactory()
 * @method \Pyz\Zed\ByDesign\ByDesignConfig getConfig()
 */
class IndexController extends AbstractController
{
    /**
     * Made for presentation ByDesign SAP communications
     *
     * ByDesign REST API access point
     * No Auth for test purposes
     *
     * Update Items state
     *
     * JSON Request:
    {
        "orderId": "order Reference Value",
        "customerId": "customer Reference Value",
        "items":
        [
            {
                "itemId": "IdSalesOrderItem value",
                "itemSku": "concrete product SKU value",
                "itemStatus": "status value",
                "stateEvent": "OMS Event name"
            },
            {
                "itemId": "IdSalesOrderItem value",
                "itemSku": "concrete product SKU value",
                "itemStatus": "status value",
                "stateEvent": "OMS Event name"
            }
            ...
        ]
    }
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $salesFacade = $this->getFactory()->getSalesFacade();
        $omsFacade = $this->getFactory()->getOmsFacade();
        $data = json_decode($request->getContent(), true);
//        $data = $this->mockData();

        $orderReference = $data['orderId'];
        $customerReference = $data['customerId'];

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setOrderReference($orderReference);
        $orderTransfer->setCustomerReference($customerReference);
        $orderTransfer = $salesFacade->getCustomerOrderByOrderReference($orderTransfer);

        // @todo handle if Order didn't found

        foreach($data['items'] as $item) {
            $idSalesOrderItem = $item['itemId'];
            $event = $item['stateEvent'];
            // Note: we do not MAP Events for now.
            // We agreed for presentation SAP and Spryker Shop have the same States and Events

            $omsFacade->triggerEventForOneOrderItem($event, $idSalesOrderItem);
        }

        // Note: For now agreed no Exceptions or Errors. Always success!
        return $this->jsonResponse([
            'status' => 'OK',
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function testApiAction(Request $request)
    {
        $salesFacade = $this->getFactory()->getSalesFacade();
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder(2);
        $this->getFacade()->postOrder($orderTransfer);

        return $this->jsonResponse([
            'status' => 'OK',
        ]);
    }

    /**
     * @return array
     */
    protected function mockData()
    {
        return [
            "orderId" => "DE--3",
            "customerId" => "DE--22",
            "items" =>
            [
                [
                    "itemId" => "7",
                    "itemSku" => "424270",
                    "itemStatus" => "paid",
                    "stateEvent" => "pay"
                ],[
                    "itemId" => "9",
                    "itemSku" => "104524",
                    "itemStatus" => "paid",
                    "stateEvent" => "pay"
                ],
            ]
        ];
    }
}
