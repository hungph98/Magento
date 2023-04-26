<?php

namespace Source\App\Ui\Listing\Colums;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;

class   Action extends Column
{
    protected $urlBuilder;

    const URL_EDIT_PRODUCT_PATH = 'source_app/product/edit';
    const URL_DELETE_PRODUCT_PATH = 'source_app/product/delete';

    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['product_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_EDIT_PRODUCT_PATH,
                                [
                                    'product_id' => $item['product_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_DELETE_PRODUCT_PATH,
                                [
                                    'product_id' => $item['product_id']
                                ]
                            ),
                            'label' => __('Delete')
                        ],
                    ];
                }
            }
        }
        return $dataSource;
    }
}
