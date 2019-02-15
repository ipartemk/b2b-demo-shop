<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
    public const FIRST_REQUESTED_ITEM_SCHEDULE_LINE = 'FirstRequestedItemScheduleLine';
    public const QUANTITY = 'Quantity';
    public const UNIT_CODE = 'unitCode';
    public const UNIT_CODE_EA = 'EA';

    public const BUYER_PARTY = 'BuyerParty';
    public const PARTY_KEY = 'PartyKey';
    public const PARTY_ID = 'PartyID';
    public const MAIN_INDICATOR = 'MainIndicator';

    public const GROUPED_ITEM = 'item';
    public const GROUPED_QUANTITY = 'quantity';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function convert(OrderTransfer $orderTransfer): array
    {
        $items = [];
        $lineNumber = 1;
        $groupedItems = $this->groupItems($orderTransfer->getItems());

        foreach ($groupedItems as $groupedItem) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $groupedItem[static::GROUPED_ITEM];
            $groupedItemQuantity = $groupedItem[static::GROUPED_QUANTITY];

            $item = [];
            $item[static::ITEM_ID] = $lineNumber * 10;
//            $item[static::ITEM_DESCRIPTION] = '';
            $item[static::ITEM_EXTERNAL_ID] = $itemTransfer->getAbstractSku();
            $item[static::PRODUCT] = [
                static::PRODUCT_KEY => [
                    static::PRODUCT_ID => $itemTransfer->getSku(),
                ],
            ];
            $item[static::FIRST_REQUESTED_ITEM_SCHEDULE_LINE] = [
                static::QUANTITY => $groupedItemQuantity,
                static::UNIT_CODE => static::UNIT_CODE_EA,

            ];
            $lineNumber++;

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
                        static::PARTY_ID => $orderTransfer->getCustomerReference(),
                    ],
                    static::MAIN_INDICATOR => 'true',
                ],
            ],
        ];

        return $parameters;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return array
     */
    protected function groupItems($itemTransfers): array
    {
        $groupedItems = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!isset($groupedItems[$itemTransfer->getSku()])) {
                $groupedItems[$itemTransfer->getSku()] = [
                    static::GROUPED_ITEM => $itemTransfer,
                    static::GROUPED_QUANTITY => 0,
                ];
            }
            $groupedItems[$itemTransfer->getSku()][static::GROUPED_QUANTITY] += $itemTransfer->getQuantity();
        }
        return $groupedItems;
    }
}
