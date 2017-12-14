<?php
/**
 *
 * ShipperHQ Shipping Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * Shipper HQ Shipping
 *
 * @category ShipperHQ
 * @package ShipperHQ_Shipping_Carrier
 * @copyright Copyright (c) 2015 Zowta LLC (http://www.ShipperHQ.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @author ShipperHQ Team sales@shipperhq.com
 */
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShipperHQ\Shipper\Model\System\Message;

class Credentials implements \Magento\Framework\Notification\MessageInterface
{
    const SHIPPERHQ_INVALID_CREDENTIALS_SUPPLIED = 'carriers/shipper/invalid_credentials_supplied';
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \ShipperHQ\Shipper\Helper\Data
     */
    private $shipperDataHelper;

    /**
     * @param \ShipperHQ\Shipper\Helper\Data $shipperDataHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \ShipperHQ\Shipper\Helper\Data $shipperDataHelper,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->shipperDataHelper = $shipperDataHelper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve unique message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return md5('SHIPPERHQ_CREDENTIALS_INVALID');
    }

    /**
     * Check whether
     *
     * @return bool
     */
    public function isDisplayed()
    {
        if ($this->shipperDataHelper->getConfigValue('carriers/shipper/active')) {
            if ($this->shipperDataHelper->getConfigValue(self::SHIPPERHQ_INVALID_CREDENTIALS_SUPPLIED)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve message text
     *
     * @return string
     */
    public function getText()
    {
        $message = __(
            'Your ShipperHQ credentials saved in Magento are invalid.'
            .' You will no longer receive shipping rates until this is rectified.'
        ) . ' ';
        $url = $this->urlBuilder->getUrl('adminhtml/system_config/edit/section/carriers');
        $message .= __(
            'Click here to go to <a href="%1">Shipping Method Configuration</a> and enter correct credentials.',
            $url
        );
        return $message;
    }

    /**
     * Retrieve problem management url
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->urlBuilder->getUrl('adminhtml/system_config/edit/section/carriers');
    }

    /**
     * Retrieve message severity
     *
     * @return int
     */
    public function getSeverity()
    {
        return \Magento\Framework\Notification\MessageInterface::SEVERITY_CRITICAL;
    }
}
