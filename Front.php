<?php

namespace Wheelpros\CatalogExtended\Plugin\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front as Subject;

class Front
{

    /**
     * @var Yesno
     */
    protected Yesno $yesNo;
    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @param Yesno $yesNo
     * @param Registry $registry
     */
    public function __construct(
        Yesno    $yesNo,
        Registry $registry
    ) {
        $this->yesNo = $yesNo;
        $this->registry = $registry;
    }

    /**
     * @param Subject $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundGetFormHtml(
        Subject  $subject,
        \Closure $proceed
    ) {

        $yesNoSource = $this->yesNo->toOptionArray();
        $form = $subject->getForm();
        $fieldset = $form->getElement('front_fieldset');
        $fieldset->addField(
            'is_brand_attribute',
            'select',
            [
                'name' => 'is_brand_attribute',
                'label' => __('Is Brand Attribute'),
                'title' => __('Is Brand Attribute'),
                'values' => $yesNoSource,
                'value' => $this->_getIsAttributeDefaultValue()
            ]
        );
        return $proceed->__invoke();
    }

    /**
     * Get the default value for the "Is Brand Attribute" field
     *
     * @return string
     */
    protected function _getIsAttributeDefaultValue(): string
    {
        $attribute = $this->registry->registry('entity_attribute');
        if ($attribute && $attribute->getId()) {
            return $attribute->getIsBrandAttribute();
        }
        return 'no'; // Default value when creating a new attribute
    }
}
