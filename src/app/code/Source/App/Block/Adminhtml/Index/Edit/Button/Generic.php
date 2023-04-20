<?php

namespace Source\App\Block\Adminhtml\Index\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Search\Controller\RegistryConstants;

use Magento\Cms\Api\PageRepositoryInterface;

class Generic
{
    protected $urlBuilder;
    protected $registry;

    public function __construct(Context $context, \Magento\Framework\Registry $registry)
    {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    public function getId()
    {
        $contact = $this->registry->registry('product');
        return $contact ? $contact->getId : null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

}
