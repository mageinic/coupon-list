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

namespace MageINIC\CouponList\Model;

use MageINIC\CouponList\Api\CouponListInterface;
use MageINIC\CouponList\Model\Rule\Collection;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Coupon list object.
 */
class CouponList implements CouponListInterface
{
    /**
     * @var CartRepositoryInterface
     */
    protected CartRepositoryInterface $quoteRepository;

    /**
     * @var Collection
     */
    protected Collection $ruleCollection;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param Collection $ruleCollection
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        Collection $ruleCollection
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->ruleCollection = $ruleCollection;
    }

    /**
     * @inheritdoc
     */
    public function get($cartId)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        return $this->ruleCollection->getValidCouponList($quote);
    }
}
