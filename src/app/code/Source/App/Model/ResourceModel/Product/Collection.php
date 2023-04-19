<?php

namespace Source\App\Model\ResourceModel\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Source\App\Model\ResourceModel\Product as ProductResource;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Product::class, ProductResource::class);
    }
}
