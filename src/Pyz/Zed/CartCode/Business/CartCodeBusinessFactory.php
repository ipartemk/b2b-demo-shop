<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartCode\Business;

use Pyz\Zed\CartCode\Business\Operation\CartCodeAdder;
use Pyz\Zed\CartCode\Business\Operation\CartCodeRemover;
use Spryker\Zed\CartCode\Business\CartCodeBusinessFactory as SprykerCartCodeBusinessFactory;
use Spryker\Zed\CartCode\Business\Operation\CartCodeAdderInterface;
use Spryker\Zed\CartCode\Business\Operation\CartCodeRemoverInterface;

/**
 * @method \Spryker\Zed\CartCode\CartCodeConfig getConfig()
 */
class CartCodeBusinessFactory extends SprykerCartCodeBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\CartCodeAdderInterface
     */
    public function createCartCodeAdder(): CartCodeAdderInterface
    {
        return new CartCodeAdder(
            $this->getCalculationFacade(),
            $this->createQuoteOperationChecker(),
            $this->createRecalculationResultProcessor(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\CartCodeRemoverInterface
     */
    public function createCartCodeRemover(): CartCodeRemoverInterface
    {
        return new CartCodeRemover(
            $this->getCalculationFacade(),
            $this->createQuoteOperationChecker(),
            $this->createRecalculationResultProcessor(),
            $this->getCartCodePlugins()
        );
    }
}
