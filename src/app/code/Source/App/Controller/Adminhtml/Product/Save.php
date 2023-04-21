<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;

class Save extends Action
{

    protected $dataPersistor;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(Context $context, DataPersistorInterface $dataPersistor)
    {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('product_id');

            $model = $this->_objectManager->create(\Source\App\Model\Product::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Product no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            if (isset($data['image']) && is_array($data['image'])) {
                if (!empty($data['image']['delete'])) {
                    $data['image'] = null;
                } else {
                    if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                        $data['image'] = $data['image'][0]['name'];
                    } else {
                        unset($data['image']);
                    }
                }
            }
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Product.'));
                $this->dataPersistor->clear('source_app_product');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['product_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Product.'));
            }

            $this->dataPersistor->set('source_app_product', $data);
            return $resultRedirect->setPath('*/*/edit', ['product_id' => $this->getRequest()->getParam('product_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
