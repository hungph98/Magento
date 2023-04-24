<?php

namespace Source\App\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Source\App\Model\ProductFactory;
use Source\App\Model\ImageUploader;

class Save extends Action
{

    protected $dataPersistor;
    protected $productFactory;
    protected $cacheManager;
    protected $imageUploaderModel;

    public function __construct(Context $context, DataPersistorInterface $dataPersistor, ProductFactory $productFactory, ImageUploader $imageUploaderModel)
    {
        $this->productFactory = $productFactory;
        $this->imageUploaderModel = $imageUploaderModel;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * @throws LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('product_id');

            $model = $this->_objectManager->create(\Source\App\Model\Product::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Product no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            if ($model->getId()) {
                $pageData = $this->productFactory->create();
                $pageData->load($model->getId());
                if (isset($data['thumbnail'][0]['name'])) {
                    $imageName1 = $pageData->getThumbnail();
                    $imageName2 = $data['thumbnail'][0]['name'];
                    if ($imageName1 != $imageName2) {
                        $imageUrl = $data['thumbnail'][0]['url'];
                        $imageName = $data['thumbnail'][0]['name'];
                        $data['thumbnail'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
                    } else {
                        $data['thumbnail'] = $data['thumbnail'][0]['name'];
                    }
                } else {
                    $data['thumbnail'] = '';
                }
            } else {
                if (isset($data['thumbnail'][0]['name'])) {
                    $imageUrl = $data['thumbnail'][0]['url'];
                    $imageName = $data['thumbnail'][0]['name'];
                    $data['thumbnail'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
                }
            }

            $model->setData($data);

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
