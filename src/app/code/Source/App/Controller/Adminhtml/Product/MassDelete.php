<?php

namespace Source\App\Controller\Adminhtml\Product;

use Exception;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Source\App\Model\ResourceModel\Product\CollectionFactory;
use Source\App\Model\Product;

class MassDelete extends Action
{
    protected $filter;
    protected $collectionFactory;
    protected $productModel;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory, Product $productModel)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productModel = $productModel;
        parent::__construct($context);
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $data = $this->collectionFactory->create();

        foreach ($data as $value) {
            $templateId[] = $value['product_id'];
        }
        $parameterData = $this->getRequest()->getParams('product_id');
        $selectedId = $this->getRequest()->getParams('product_id');
        if (array_key_exists("selected", $parameterData)) {
            $selectedId = $parameterData['selected'];
        }
        if (array_key_exists("excluded", $parameterData)) {
            if ($parameterData['excluded'] == 'false') {
                $selectedId = $templateId;
            } else {
                $selectedId = array_diff($templateId, $parameterData['excluded']);
            }
        }
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('product_id', ['in' => $selectedId]);
        $delete = 0;
        $model = [];
        foreach ($collection as $item) {
            $this->deleteById($item->getJobId());
            $delete++;
        }
        $this->messageManager->addSuccess(__('A total of %1 Records have been deleted.', $delete));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @throws Exception
     */
    public function deleteById($id): void
    {
        $item = $this->productModel->load($id);
        $item->delete();
        return;
    }
}
