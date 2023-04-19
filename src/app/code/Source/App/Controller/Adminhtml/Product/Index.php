<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $pagePactory = false;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pagePactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->pagePactory->create();
        $result->getConfig()->getTitle()->prepend(__('Products'));
        return $result;
    }
}
