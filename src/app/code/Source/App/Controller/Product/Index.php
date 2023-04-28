<?php

namespace Source\App\Controller\Product;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
class Index extends Action implements HttpGetActionInterface
{
    private $pageFactory;
    private $session;

    public function __construct(Context $context, PageFactory $pageFactory, Session $session)
    {
        $this->pageFactory = $pageFactory;
        $this->session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}
