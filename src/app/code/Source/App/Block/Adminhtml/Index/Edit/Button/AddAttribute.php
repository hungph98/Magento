<?php

namespace Source\App\Block\Adminhtml\Index\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddAttribute implements ButtonProviderInterface
{

    public function getButtonData()
    {
        return [
            'label' => __('Add Attribute'),
            'class' => 'action-secondary',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }
}
