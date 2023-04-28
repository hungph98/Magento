<?php

namespace Source\App\Model\Product\Type;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;

class Pool
{
    /**
     * Object Manager
     *
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Construct
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Gets product of particular type
     *
     * @param string $className
     * @param array $data
     * @return \Magento\Catalog\Model\Product\Type\AbstractType
     * @throws LocalizedException
     */
    public function get(string $className, array $data = []): \Magento\Catalog\Model\Product\Type\AbstractType
    {
        $product = $this->_objectManager->get($className, $data);

        if (!$product instanceof \Magento\Catalog\Model\Product\Type\AbstractType) {
            throw new LocalizedException(
                __('%1 doesn\'t extends \Magento\Catalog\Model\Product\Type\AbstractType', $className)
            );
        }
        return $product;
    }
}
