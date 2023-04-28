<?php

namespace Source\App\Ui\Listing\Columns;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class Price extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const NAME = 'column.price';

    /**
     * @var CurrencyInterface
     */
    protected $localeCurrency;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var PriceCurrencyInterface
     */
    private mixed $priceCurrency;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CurrencyInterface $localeCurrency
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     * @param PriceCurrencyInterface|null $priceCurrency
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CurrencyInterface $localeCurrency,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = [],
        ?PriceCurrencyInterface $priceCurrency = null
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->localeCurrency = $localeCurrency;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency ?? ObjectManager::getInstance()->get(PriceCurrencyInterface::class);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $store = $this->storeManager->getStore(
                $this->context->getFilterParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            );

            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $this->priceCurrency->format(
                        sprintf("%F", $item[$fieldName]),
                        false,
                        PriceCurrencyInterface::DEFAULT_PRECISION,
                        $store
                    );
                }
            }
        }

        return $dataSource;
    }
}
