<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CheckoutRestApi\Business\Checkout\Quote;

use ArrayObject;
use Generated\Shared\Transfer\ApproverDetailsTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer;
use Pyz\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Pyz\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface;

class QuoteDecliner implements QuoteDeclinerInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
     */
    protected $quoteApprovalFacade;

    /**
     * @var \Pyz\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface $quoteApprovalFacade
     * @param \Pyz\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteFacadeInterface $quoteFacade,
        QuoteApprovalFacadeInterface $quoteApprovalFacade,
        CompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteFacade = $quoteFacade;
        $this->quoteApprovalFacade = $quoteApprovalFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer
     */
    public function declineQuote(
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
    ): RestQuoteDeclineResponseTransfer {
        $restCheckoutRequestAttributesTransfer = $this->createRestCheckoutRequestAttributesTransfer($restQuoteDeclineRequestAttributesTransfer);
        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);

        if (!$quoteTransfer) {
            return $this->createCartNotFoundErrorResponse();
        }

        $quoteTransfer = $this->manageQuoteApproval($restQuoteDeclineRequestAttributesTransfer, $quoteTransfer);

        if (!count($quoteTransfer->getQuoteApprovals())) {
            return $this->createQuoteApprovalNotFound();
        }

        $quoteApprovalTransfer = $quoteTransfer->getQuoteApprovals()[0];
        if ($quoteApprovalTransfer->getStatus() === QuoteApprovalConfig::STATUS_DECLINED) {
            return (new RestQuoteDeclineResponseTransfer())
                ->setIsSuccess(true)
                ->setQuote($quoteTransfer);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($quoteApprovalTransfer->getIdQuoteApproval())
            ->setQuote($quoteTransfer)
            ->setApproverCompanyUserId($restQuoteDeclineRequestAttributesTransfer->getApproverDetails()->getCompanyUserId());

        $quoteApprovalResponseTransfer = $this->quoteApprovalFacade->declineQuoteApproval($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteDeclineNotSuccessful();
        }

        $this->quoteFacade->updateQuote($quoteApprovalResponseTransfer->getQuote());

        return (new RestQuoteDeclineResponseTransfer())
            ->setIsSuccess(true)
            ->setQuote($quoteApprovalResponseTransfer->getQuote());
    }

    /**
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer
     */
    protected function createCartNotFoundErrorResponse(): RestQuoteDeclineResponseTransfer
    {
        return (new RestQuoteDeclineResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer
     */
    protected function createQuoteApprovalNotFound(): RestQuoteDeclineResponseTransfer
    {
        return (new RestQuoteDeclineResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_QUOTE_APPROVAL_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer
     */
    protected function createQuoteDeclineNotSuccessful(): RestQuoteDeclineResponseTransfer
    {
        return (new RestQuoteDeclineResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_QUOTE_DECLINE_NOT_SUCCESSFUL)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function manageQuoteApproval(
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (!$restQuoteDeclineRequestAttributesTransfer->getApproverDetails()
            || !$restQuoteDeclineRequestAttributesTransfer->getApproverDetails()->getApproverId()
        ) {
            return $quoteTransfer;
        }
        $approverDetailsTransfer = (new ApproverDetailsTransfer())
            ->fromArray($restQuoteDeclineRequestAttributesTransfer->getApproverDetails()->toArray(), true);

        $companyUserTransfer = $this->companyUserFacade->findActiveCompanyUserByUuid(
            (new CompanyUserTransfer())
                ->setUuid($restQuoteDeclineRequestAttributesTransfer->getApproverDetails()->getApproverId())
        );
        if (!$companyUserTransfer) {
            return $quoteTransfer;
        }

        $currentQuoteApprovalTransfer = $this->getCurrentQuoteApprovalTransfer($quoteTransfer, $companyUserTransfer, $approverDetailsTransfer);
        if ($currentQuoteApprovalTransfer) {
            $quoteTransfer->setQuoteApprovals(new ArrayObject([
                $currentQuoteApprovalTransfer,
            ]));

            return $quoteTransfer;
        }

        $quoteTransfer->setQuoteApprovals(new ArrayObject());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\ApproverDetailsTransfer $approverDetailsTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    protected function getCurrentQuoteApprovalTransfer(
        QuoteTransfer $quoteTransfer,
        CompanyUserTransfer $companyUserTransfer,
        ApproverDetailsTransfer $approverDetailsTransfer
    ): ?QuoteApprovalTransfer {
        $currentQuoteApprovalTransfer = null;
        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            if ($quoteApprovalTransfer->getApprover()->getUuid() === $companyUserTransfer->getUuid()
                && $quoteApprovalTransfer->getUuid() !== null
            ) {
                $currentQuoteApprovalTransfer = $quoteApprovalTransfer;
                $currentQuoteApprovalTransfer->setApproverDetails($approverDetailsTransfer);

                break;
            }
        }

        return $currentQuoteApprovalTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function createRestCheckoutRequestAttributesTransfer(
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        return (new RestCheckoutRequestAttributesTransfer())
            ->setCustomer($restQuoteDeclineRequestAttributesTransfer->getCustomer())
            ->setIdCart($restQuoteDeclineRequestAttributesTransfer->getIdCart());
    }
}
