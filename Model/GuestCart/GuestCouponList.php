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

namespace MageINIC\CouponList\Model\GuestCart;

use MageINIC\CouponList\Api\CouponListInterface;
use MageINIC\CouponList\Api\GuestCouponListInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Coupon list class for guest carts.
 */
class GuestCouponList implements GuestCouponListInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    private QuoteIdMaskFactory $quoteIdMaskFactory;

    /**
     * @var CouponListInterface
     */
    private CouponListInterface $couponList;

    /**
     * @param CouponListInterface $couponList
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        CouponListInterface $couponList,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->couponList = $couponList;
    }

    /**
     * @inheritdoc
     */
    public function get($cartId)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->couponList->get($quoteIdMask->getQuoteId());
    }
}
