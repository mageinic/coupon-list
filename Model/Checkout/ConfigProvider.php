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

namespace MageINIC\CouponList\Model\Checkout;

use MageINIC\CouponList\Model\Config;
use MageINIC\CouponList\Model\Rule\Collection;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Collection
     */
    protected Collection $_ruleCollection;

    /**
     * @var Session
     */
    protected Session $_checkoutSession;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param Collection $ruleCollection
     * @param Session $checkoutSession
     * @param Config $config
     */
    public function __construct(
        Collection $ruleCollection,
        Session $checkoutSession,
        Config $config
    ) {
        $this->_ruleCollection = $ruleCollection;
        $this->_checkoutSession = $checkoutSession;
        $this->config = $config;
    }

    /**
     * Provides checkout configurations for coupon code list.
     */
    public function getConfig(): array
    {
        if (!$this->config->isEnabled()) {
            return [];
        }
        $couponList['couponList'] = $this->getListArray();
        return $couponList;
    }

    /**
     * Get List of valid coupon code for active cart.
     *
     * @return string[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getListArray()
    {
        $quote = $this->_checkoutSession->getQuote();

        return $this->_ruleCollection->getValidCouponList($quote);
    }
}
