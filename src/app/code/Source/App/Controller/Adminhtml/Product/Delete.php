<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Source\App\Model\Product;

class Delete extends Action
{
    protected Product $productModel;

    public function __construct(Action\Context $context, Product $productModel) {
        parent::__construct($context);
        $this->productModel = $productModel;
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Source_App::product_delete');
    }

    public function execute(): ResultInterface
    {
        $id = $this->getRequest()->getParam('product_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->productModel;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Record deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Record does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
