<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Model\ResourceModel\Address;

use Magento\Directory\Model\AllowedCountries;
use Magento\Sales\Block\Adminhtml\Order\Create\Form\Address as AddressBlock;
use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class StoreAddressCollection extends Collection
{
    /**
     * @var AddressBlock
     */
    private $addressBlock;

    /**
     * @var AllowedCountries
     */
    private $allowedCountryReader;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param \Magento\Eav\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot
     * @param AddressBlock $addressBlock
     * @param AllowedCountries $allowedCountryReader
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot,
        AddressBlock $addressBlock,
        AllowedCountries $allowedCountryReader,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        $this->addressBlock = $addressBlock;
        $this->allowedCountryReader = $allowedCountryReader;

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $entitySnapshot,
            $connection
        );
    }

    /**
     * Set allowed country filter for customer's addresses
     *
     * @param \Magento\Customer\Model\Customer|array $customer
     * @return $this|Object
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setCustomerFilter($customer)
    {
        parent::setCustomerFilter($customer);

        $storeId = $this->addressBlock->getStoreId() ?? null;
        if ($storeId) {
            $allowedCountries = $this->allowedCountryReader->getAllowedCountries(
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
            $this->addAttributeToFilter('country_id', ['in' => $allowedCountries]);
        }
        return $this;
    }
}
