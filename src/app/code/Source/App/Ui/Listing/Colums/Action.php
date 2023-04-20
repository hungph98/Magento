<?php

namespace Source\App\Ui\Listing\Colums;

class   Action extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $urlBuilder;

    const URL_EDIT_POST_PATH = 'source_app/post/edit';
    const URL_DELETE_POST_PATH = 'source_app/post/delete';
    const URL_EDIT_PRODUCT_PATH = 'source_app/product/edit';
    const URL_DELETE_PRODUCT_PATH = 'source_app/product/delete';

    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['post_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_EDIT_POST_PATH,
                                [
                                    'post_id' => $item['post_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_DELETE_POST_PATH,
                                [
                                    'post_id' => $item['post_id']
                                ]
                            ),
                            'label' => __('Delete')
                        ],
                    ];
                }

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
