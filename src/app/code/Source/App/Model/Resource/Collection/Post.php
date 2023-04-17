<?php

namespace Source\App\Model\Resource\Collection;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Post extends AbstractCollection
{

    public function _construct()
    {
        $this->_init(\Source\App\Model\Post::class, Post::class);
        parent::_construct();
    }

}
