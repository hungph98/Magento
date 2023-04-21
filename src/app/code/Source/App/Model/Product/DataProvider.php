<?php

namespace Source\App\Model\Product;

use Source\App\Model\ResourceModel\Product\CollectionFactory;
use Source\App\Model\ProductFactory;
use Source\App\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;

    protected $productResource;
    private ProductFactory $productFactory;
    private RequestInterface $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        ProductResource $productResource,
        ProductFactory $productFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
    )
    {
        $this->collection = $collectionFactory->create();
        $this->productResource = $productResource;
        $this->productFactory = $productFactory;
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $product = $this->getCurrentPost();
        $this->loadedData[$product->getId()] = $product->getData();

        return $this->loadedData;
    }

    private function getCurrentPost()
    {
        $productId = $this->getProductId();
        $product = $this->productFactory->create();
        if (!$productId) {
            return $product;
        }

        $this->productResource->load($product, $productId);

        return $product;
    }

    private function getProductId(): int
    {
        return (int)$this->request->getParam($this->getRequestFieldName());
    }
}
