<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CartCode\Business;

use Spryker\Zed\CartCode\Business\CartCodeBusinessFactory as SprykerCartCodeBusinessFactory;
use Pyz\Zed\CartCode\Business\Operation\CartCodeAdder;
use Spryker\Zed\CartCode\Business\Operation\CartCodeAdderInterface;
use Spryker\Zed\CartCode\Business\Operation\CartCodeClearer;
use Spryker\Zed\CartCode\Business\Operation\CartCodeClearerInterface;
use Pyz\Zed\CartCode\Business\Operation\CartCodeRemover;
use Spryker\Zed\CartCode\Business\Operation\CartCodeRemoverInterface;
use Spryker\Zed\CartCode\Business\Operation\QuoteOperationChecker;
use Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface;
use Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessor;
use Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessorInterface;
use Spryker\Zed\CartCode\CartCodeDependencyProvider;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

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
