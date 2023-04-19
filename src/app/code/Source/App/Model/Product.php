<?php

namespace Source\App\Model;


use Magento\Framework\Model\AbstractModel;
use Source\App\Model\ResourceModel\Product as ProductResource;

class Product extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ProductResource::class);
    }
}
