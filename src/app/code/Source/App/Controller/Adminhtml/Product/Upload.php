<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Source\App\Model\ImageUploader;
use Magento\Backend\App\Action;

class Upload extends Action
{
    protected ImageUploader $imageUploader;

    public function __construct(Context $context, ImageUploader $imageUploader)
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Source_App::upload');
    }

    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('thumbnail');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
