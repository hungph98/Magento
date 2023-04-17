<?php

namespace Source\App\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Post extends AbstractDb
{
    const TABLE_NAME = 'Blog_post';
    const PRIMARY_KEY = 'post_id';
    public function _construct()
    {
        $this->_init(self::TABLE_NAME,self::PRIMARY_KEY);
    }

}
