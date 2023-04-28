<?php

namespace Source\App\Api\Data;

use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface;
use Magento\Catalog\Api\Data\ProductCustomOptionInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Catalog\Api\Data\ProductTierPriceInterface;

interface ProductInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const SKU = 'sku';

    const NAME = 'name';

    const PRICE = 'price';

    const WEIGHT = 'weight';

    const STATUS = 'status';

    const VISIBILITY = 'visibility';

    const ATTRIBUTE_SET_ID = 'attribute_set_id';

    const TYPE_ID = 'type_id';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const MEDIA_GALLERY = 'media_gallery';

    const TIER_PRICE = 'tier_price';

    const ATTRIBUTES = [
        self::SKU,
        self::NAME,
        self::PRICE,
        self::WEIGHT,
        self::STATUS,
        self::VISIBILITY,
        self::ATTRIBUTE_SET_ID,
        self::TYPE_ID,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::MEDIA_GALLERY,
        self::TIER_PRICE,
    ];
    /**#@-*/

    /**
     * Frontend id
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set product id
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static;

    /**
     * Frontend sku
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set product sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): static;

    /**
     * Frontend name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static;

    /**
     * Frontend attribute set id
     *
     * @return int|null
     */
    public function getAttributeSetId(): ?int;

    /**
     * Set product attribute set id
     *
     * @param int $attributeSetId
     * @return $this
     */
    public function setAttributeSetId(int $attributeSetId): static;

    /**
     * Frontend price
     *
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * Set product price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): static;

    /**
     * Frontend status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set product status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): static;

    /**
     * Frontend visibility
     *
     * @return int|null
     */
    public function getVisibility(): ?int;

    /**
     * Set product visibility
     *
     * @param int $visibility
     * @return $this
     */
    public function setVisibility(int $visibility): static;

    /**
     * Frontend type id
     *
     * @return string|null
     */
    public function getTypeId(): ?string;

    /**
     * Set product type id
     *
     * @param string $typeId
     * @return $this
     */
    public function setTypeId(string $typeId): static;

    /**
     * Frontend created date
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set product created date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): static;

    /**
     * Frontend updated date
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set product updated date
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): static;

    /**
     * Frontend weight
     *
     * @return float|null
     */
    public function getWeight(): ?float;

    /**
     * Set product weight
     *
     * @param float $weight
     * @return $this
     */
    public function setWeight(float $weight): static;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magento\Catalog\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes): static;

    /**
     * Get product links info
     *
     * @return ProductLinkInterface[]|null
     */
    public function getProductLinks(): ?array;

    /**
     * Set product links info
     *
     * @param ProductLinkInterface[] $links
     * @return $this
     */
    public function setProductLinks(array $links = null): static;

    /**
     * Get list of product options
     *
     * @return ProductCustomOptionInterface[]|null
     */
    public function getOptions(): ?array;

    /**
     * Set list of product options
     *
     * @param ProductCustomOptionInterface[] $options
     * @return $this
     */
    public function setOptions(array $options = null): static;

    /**
     * Get media gallery entries
     *
     * @return ProductAttributeMediaGalleryEntryInterface[]|null
     */
    public function getMediaGalleryEntries(): ?array;

    /**
     * Set media gallery entries
     *
     * @param ProductAttributeMediaGalleryEntryInterface[] $mediaGalleryEntries
     * @return $this
     */
    public function setMediaGalleryEntries(array $mediaGalleryEntries = null): static;

    /**
     * Gets list of product tier prices
     *
     * @return ProductTierPriceInterface[]|null
     */
    public function getTierPrices(): ?array;

    /**
     * Sets list of product tier prices
     *
     * @param ProductTierPriceInterface[] $tierPrices
     * @return $this
     */
    public function setTierPrices(array $tierPrices = null): static;
}
