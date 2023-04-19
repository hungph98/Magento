<?php

namespace Source\App\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Post extends AbstractDb
{
    private const TABLE_NAME = 'blog_post';
    private const PRIMARY_KEY = 'post_id';
    public function _construct()
    {
        $this->_init(self::TABLE_NAME,self::PRIMARY_KEY);
    }
}
