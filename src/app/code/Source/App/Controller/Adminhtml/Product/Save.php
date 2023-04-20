<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends Action
{
    protected $categoryRepository;
    protected $dataPersistor;
    protected $attributeFactory;

    public function __construct(Context $context, DataPersistorInterface $dataPersistor, AttributeFactory $attributeFactory, CategoryRepositoryInterface $categoryRepository )
    {
        $this->dataPersistor = $dataPersistor;
        $this->attributeFactory = $attributeFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('id');

            $model = $this->_objectManager->create(Product::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Product no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            // $attr = $this->attributeFactory->create()->loadByCode('catalog_product', 'brand');
            $attr = $this->attributeFactory->create()->loadByCode('product', 'product');
            if ($attr->usesSource()) {
                $productName = $attr->getSource()->getOptionText($data['product']);
                $model->setData('name', $productName);
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Product.'));
                $this->dataPersistor->clear('source_app_product');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Product.'));
            }

            $this->dataPersistor->set('source_app_product', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
