<?php

namespace Pyz\Zed\ByDesign\Business\Api\Converter;

use Generated\Shared\Transfer\OrderTransfer;

class RequestConverter implements RequestConverterInterface
{
    public const BASIC_MESSAGE_HEADER = 'BasicMessageHeader';
    public const SALES_ORDER = 'SalesOrder';
    public const BUYER_ID = 'BuyerID';
    public const SALES_NAME = 'Name';
    public const ORDER_EXTERNAL_ID = 'Z_ExternalOrderID';

    public const ITEM = 'Item';
    public const ITEM_ID = 'ID';
    public const ITEM_DESCRIPTION = 'Description';
    public const ITEM_EXTERNAL_ID = 'Z_ExternalItemID';
    public const PRODUCT = 'Product';
    public const PRODUCT_KEY = 'ProductKey';
    public const PRODUCT_ID = 'ProductID';
    public const BUYER = 'Name';

    public const BUYER_PARTY = 'BuyerParty';
    public const PARTY_KEY = 'PartyKey';
    public const PARTY_ID = 'PartyID';
    public const MAIN_INDICATOR = 'MainIndicator';


    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function convert(OrderTransfer $orderTransfer)
    {
        $items = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $item = [];
            $item[static::ITEM_ID] = $itemTransfer->getIdSalesOrderItem();
//            $item[static::ITEM_DESCRIPTION] = '';
            $item[static::ITEM_EXTERNAL_ID] = $itemTransfer->getAbstractSku();
            $item[static::PRODUCT] = [
                static::PRODUCT_KEY => [
                    static::PRODUCT_ID => $itemTransfer->getSku()
                ]
            ];

            $items[] = $item;
        }

        $parameters = [
            static::BASIC_MESSAGE_HEADER => '',
            static::SALES_ORDER => [
                static::BUYER_ID => $orderTransfer->getOrderReference(),
                static::SALES_NAME => 'Order: ' . $orderTransfer->getOrderReference(),
                static::ORDER_EXTERNAL_ID => $orderTransfer->getOrderReference(),
                static::ITEM => $items,

                static::BUYER_PARTY => [
                    static::PARTY_KEY => [
                        static::PARTY_ID => $orderTransfer->getCustomerReference()
                    ],
                    static::MAIN_INDICATOR => 'true'
                ],
            ],
        ];

        return $parameters;
    }
}
