<?php

namespace Source\App\Model\Product;

use Magento\Framework\Exception\NoSuchEntityException;
use Source\App\Model\ResourceModel\Product\CollectionFactory;
use Source\App\Model\ProductFactory;
use Source\App\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;

    protected $productResource;
    private ProductFactory $productFactory;
    private RequestInterface $request;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        ProductResource $productResource,
        ProductFactory $productFactory,
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
    )
    {
        $this->collection = $collectionFactory->create();
        $this->productResource = $productResource;
        $this->productFactory = $productFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $image = [];
            $imageName = $model->getThumbnail();
            if ($imageName) {
                $image[0]['name'] = $imageName;
                $image[0]['url'] = $this->getMediaUrl().$imageName;
                $image[0]['type'] = 'image';
            }
        }

        $product = $this->getCurrentPost();
        if ($product->getId() !== null) {
            $product->setThumbnail($image);
        }
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

    /**
     * @throws NoSuchEntityException
     */
    public function getMediaUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'source_app/product/';
    }
}
