<?php

namespace Source\App\Controller\Frontend;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\DataObject;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $pagePactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pagePactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $textDisplay = new DataObject(array('text' => 'Magento'));
        $this->_eventManager->dispatch('source_app_display_text', ['mp_text' => $textDisplay]);
        echo $textDisplay->getText();
        exit();

        /*return $this->pagePactory->create();*/
    }
}
