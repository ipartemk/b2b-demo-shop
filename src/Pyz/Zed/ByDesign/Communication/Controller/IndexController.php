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
                "itemSku": "concrete product SKU value",
                "itemStatus": "status value",
                "stateEvent": "OMS Event name"
            },
            {
                "itemSku": "concrete product SKU value",
                "itemStatus": "status value",
                "stateEvent": "OMS Event name"
            }
            ...
        ]
    }
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $salesFacade = $this->getFactory()->getSalesFacade();
        $omsFacade = $this->getFactory()->getOmsFacade();
//        $data = json_decode($request->getContent(), true);
        $data = $this->mockData();

        $orderReference = $data['orderId'];
        $customerReference = $data['customerId'];

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setOrderReference($orderReference);
        $orderTransfer->setCustomerReference($customerReference);
        $orderTransfer = $salesFacade->getCustomerOrderByOrderReference($orderTransfer);

        // @todo handle if Order didn't found
        foreach ($data['items'] as $item) {
            $itemSku = $item['itemSku'];

            $salesOrderItemIds = $this->getSalesOrderItemIdsBySku($orderTransfer, $itemSku);
            $event = $item['stateEvent'];

            // Note: we do not MAP Events for now.
            // We agreed for presentation SAP and Spryker Shop have the same States and Events
            $omsFacade->triggerEventForOrderItems($event, $salesOrderItemIds);
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $itemSku
     *
     * @return array
     */
    protected function getSalesOrderItemIdsBySku(OrderTransfer $orderTransfer, $itemSku): array
    {
        $salesOrderItemIds = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $itemSku) {
                $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
            }
        }

        return $salesOrderItemIds;
    }

    /**
     * @return array
     */
    protected function mockData(): array
    {
        return [
            "orderId" => "DE--3",
            "customerId" => "DE--22",
            "items" =>
            [
                [
                    "itemSku" => "424270",
                    "itemStatus" => "paid",
                    "stateEvent" => "pay",
                ], [
                    "itemSku" => "104524",
                    "itemStatus" => "paid",
                    "stateEvent" => "pay",
                ],
            ],
        ];
    }
}
