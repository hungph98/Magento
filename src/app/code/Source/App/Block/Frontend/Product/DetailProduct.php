<?php

namespace Source\App\Block\Frontend\Product;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Url\Helper\Data;
use Source\App\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Template\Context;

class DetailProduct extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var Image
     */
    private $image;

    /**
     * @var AbstractProduct
     */
    private $abstractProduct;

    /**
     * @var Data
     */
    private $urlHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        CollectionFactory $productCollectionFactory,
        Image $image,
        AbstractProduct $abstractProduct,
        Data $urlHelper,
        array $data = []
    )
    {
        $this->productFactory = $productFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->image = $image;
        $this->abstractProduct = $abstractProduct;
        $this->urlHelper = $urlHelper;
        parent::__construct($context, $data);
    }

    public function showProductDetail()
    {
        $id = $this->getRequest()->getParam('id');
        $product = $this->productFactory->create();
        if ($id != null) {
            $product = $product->load($id);
        }
        return $product;
    }

    public function getImage($product): string
    {
        return $this->image->init($product, 'product_thumbnail_image')->getUrl();
    }

    public function getProductById($product_id)
    {
        return $this->productFactory->create()->load($product_id);
    }

    //Prepare for  button
    public function getProductCollection()
    {
        /** @var $collection Collection */
        $id = $this->getRequest()->getParam('id');
        $collection = $this->productCollectionFactory->create()->addAttributeToFilter('name', $id)->load();
        return $collection;
    }

    public function getAddToCartPostParams($product)
    {
        $url = $this->abstractProduct->getAddToCartUrl($product, ['_escape' => true]);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
