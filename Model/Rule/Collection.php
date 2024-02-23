<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_CouponList
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\CouponList\Model\Rule;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\SalesRule\Model\Utility;
use Magento\Store\Model\StoreManagerInterface;
use Magento\SalesRule\Model\ResourceModel\Rule\Collection as CoreCollection;

class Collection
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_collectionFactory;

    /**
     * @var Utility
     */
    protected Utility $_utility;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @param Utility $utility
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Session $customerSession
     */
    public function __construct(
        Utility               $utility,
        CollectionFactory     $collectionFactory,
        StoreManagerInterface $storeManager,
        Session               $customerSession
    ) {
        $this->_utility = $utility;
        $this->_collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
    }

    /**
     * Filter Sales rules with condition and return valid coupons only.
     *
     * @param $quote
     * @return array
     */
    public function getValidCouponList($quote)
    {
        $address = $quote->getShippingAddress();
        $rules = $this->getRulesCollection();
        $ruleArray = [];
        $items = $quote->getAllVisibleItems();
        foreach ($rules as $rule) {
            $validate = $this->_utility->canProcessRule($rule, $address);
            $validAction = false;
            foreach ($items as $item) {
                if ($validAction = $rule->getActions()->validate($item)) {
                    break;
                }
            }
            if ($validate && $validAction) {
                $ruleArray[] = [
                    'name' => $rule->getName(),
                    'description' => $rule->getDescription(),
                    'coupon' => $rule->getCode()
                ];
            }
        }
        return $ruleArray;
    }

    /**
     * Get rules collection for current object state.
     *
     * @return CoreCollection
     * @throws NoSuchEntityException
     */
    public function getRulesCollection()
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $customerGroupId = $this->getCustomerGroupId();
        return $this->_collectionFactory->create()
            ->addWebsiteGroupDateFilter($websiteId, $customerGroupId)
            ->addAllowedSalesRulesFilter()
            ->addFieldToFilter('coupon_type', ['neq' => '1'])
            ->addFieldToFilter('visible_coupon', ['eq' => '1']);
    }

    /**
     * Get customer group id.
     *
     * @return int
     */
    public function getCustomerGroupId()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getGroupId();
        } else {
            return 0;
        }
    }
}
