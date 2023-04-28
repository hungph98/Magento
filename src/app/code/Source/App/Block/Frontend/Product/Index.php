<?php

namespace Source\App\Block\Frontend\Product;

use Magento\Framework\View\Element\Template\Context;
use Source\App\Model\ResourceModel\Product\CollectionFactory;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context, CollectionFactory $collectionFactory, array $data = [])
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    public function listProductCollection()
    {
        $valueConfig = \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->getValue(
                'product/listproduct/listproduct_enable',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            );
        return $valueConfig == 1 ? $this->collectionFactory->create()->addFieldToFilter('status', 1) : false;
    }
}
