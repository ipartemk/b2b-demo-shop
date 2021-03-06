<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRole;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Pyz\Zed\CompanyUser\Communication\Plugin\Permission\SeeCompanyMenuPermissionPlugin;
use Spryker\Shared\CheckoutPermissionConnector\Plugin\Permission\PlaceOrderWithAmountUpToPermissionPlugin;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Shared\CompanyUserInvitation\Plugin\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\CartPermissionConnector\Communication\Plugin\Cart\AlterCartUpToAmountPermissionPlugin;
use Spryker\Zed\CompanyRole\CompanyRoleConfig as SprykerCompanyRoleConfig;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;
use SprykerShop\Shared\CompanyPage\Plugin\CompanyUserStatusChangePermissionPlugin;

class CompanyRoleConfig extends SprykerCompanyRoleConfig
{
    protected const BUYER_ROLE_NAME = 'Buyer';

    /**
     * @return string[]
     */
    public function getAdminRolePermissionKeys(): array
    {
        return [
            AddCompanyUserPermissionPlugin::KEY,
            ManageCompanyUserInvitationPermissionPlugin::KEY,
            CompanyUserStatusChangePermissionPlugin::KEY,
            SeeCompanyMenuPermissionPlugin::KEY,
        ];
    }

    /**
     * @return string[]
     */
    protected function getBuyerRolePermissionKeys(): array
    {
        return [
            AddCartItemPermissionPlugin::KEY,
            ChangeCartItemPermissionPlugin::KEY,
            RemoveCartItemPermissionPlugin::KEY,
            PlaceOrderWithAmountUpToPermissionPlugin::KEY,
            AlterCartUpToAmountPermissionPlugin::KEY,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer[]
     */
    public function getPredefinedCompanyRoles(): array
    {
        $companyRoleTransfers = parent::getPredefinedCompanyRoles();
        $companyRoleTransfers[] = $this->getBuyerRole();

        return $companyRoleTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    protected function getBuyerRole(): CompanyRoleTransfer
    {
        return (new CompanyRoleTransfer())
            ->setName(static::BUYER_ROLE_NAME)
            ->setPermissionCollection($this->createPermissionCollectionFromPermissionKeys(
                $this->getBuyerRolePermissionKeys()
            ));
    }
}
