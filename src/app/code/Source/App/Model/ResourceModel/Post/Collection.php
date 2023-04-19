<?php

namespace Source\App\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Source\App\Model\ResourceModel\Post as PostResource;

class Collection extends AbstractCollection
{

    public function _construct()
    {
        $this->_init(\Source\App\Model\Post::class, PostResource::class);
        parent::_construct();
    }
}
