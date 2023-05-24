<?php

namespace Wheelpros\CatalogExtended\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class AttributeList implements OptionSourceInterface
{
    /**
     * @var AttributeCollectionFactory
     */
    protected AttributeCollectionFactory $attributeCollectionFactory;

    /**
     * @param AttributeCollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        AttributeCollectionFactory $attributeCollectionFactory,
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * Get product attributes as options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection->addFieldToFilter('used_in_product_listing', 1);
        $attributeCollection->setOrder('frontend_label', 'ASC');

        $options = [];

        $manufacturerAttributeCode = 'manufacturer';
        $manufacturerAttributeLabel = 'Manufacturer';
        $manufacturerAttributeExists = false;

        foreach ($attributeCollection as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            if ($attributeCode === $manufacturerAttributeCode) {
                $manufacturerAttributeExists = true;
            }
            if ($attribute->getData('used_in_product_listing') == 1) {
                $options[] = [
                    'value' => $attributeCode,
                    'label' => $attribute->getFrontendLabel()
                ];
            }
        }

        // Add the manufacturer attribute if it doesn't already exist
        if (!$manufacturerAttributeExists) {
            $options[] = [
                'value' => $manufacturerAttributeCode,
                'label' => $manufacturerAttributeLabel
            ];
        }

        return $options;
    }
}
