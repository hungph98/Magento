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
            $items = $this->collection->getItems();
            foreach ($items as $model) {
                $this->loadedData[$model->getId()] = $model->getData();
                if ($model->getImageField()) {
                    $m['thumbnail'][0]['name'] = $model->getImageField();
                    $m['thumbnail'][0]['url'] = $this->getMediaUrl($model->getImageField());
                    $fullData = $this->loadedData;
                    $this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
                }
            }
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

    /**
     * @throws NoSuchEntityException
     */
    public function getMediaUrl($path = ''): string
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'source_app/product/' . $path;
    }
}
