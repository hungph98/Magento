<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;
class Add extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Add Product'));
        return $resultPage;
    }
}
