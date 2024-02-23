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

namespace MageINIC\CouponList\Api;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Coupon list interface for guest carts.
 *
 * @api
 *
 * @since 1.0.0
 */
interface GuestCouponListInterface
{
    /**
     * Return list of valid coupon in a specified cart.
     *
     * @param string $cartId the cart ID
     *
     * @return string[] the coupon list data
     *
     * @throws NoSuchEntityException the specified cart does not exist
     */
    public function get(string $cartId);
}
