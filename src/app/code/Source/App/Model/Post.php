<?php

namespace Source\App\Model;

use Magento\Framework\Model\AbstractModel;
use Source\App\Model\ResourceModel\Post as PostResource;

class Post extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(PostResource::class);
    }
}
