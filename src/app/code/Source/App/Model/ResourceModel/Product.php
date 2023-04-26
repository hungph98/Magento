<?php

namespace Source\App\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Product extends AbstractDb
{

    private const TABLE_NAME = 'product';
    private const PRIMARY_KEY = 'product_id';
    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
