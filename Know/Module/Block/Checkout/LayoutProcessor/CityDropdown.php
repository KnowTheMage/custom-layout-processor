<?php
namespace Know\Module\Block\Checkout\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Stdlib\ArrayManager;

class CityDropdown implements LayoutProcessorInterface
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(ArrayManager $arrayManager)
    {
        $this->arrayManager = $arrayManager;
    }

    /**
     * @inheritDoc
     */
    public function process($jsLayout)
    {
        $steps = 'components/checkout/children/steps/children';
        $addressPaths = [
            'shippingForm' => $steps . '/shipping-step/children/shippingAddress/children/shipping-address-fieldset/children',
            'paymentList' => $steps . '/billing-step/children/payment/children/payments-list/children',
            'billingFormAfterMethods' => $steps . '/billing-step/children/payment/children/afterMethods/children/billing-address-form/children/form-fields/children',
        ];

        foreach ($addressPaths as $key => $path) {
            $this->updateCityField($key, $path, $jsLayout);
        }

        return $jsLayout;
    }

    /**
     * Convert the city field to dropdown
     *
     * @param string $key
     * @param string $addressPath
     * @param array $jsLayout
     * @return void
     */
    private function updateCityField($key, $addressPath, &$jsLayout)
    {
        if (empty($fields = $this->arrayManager->get($addressPath, $jsLayout))) {
            return;
        }

        if ($key == "paymentList") {
            foreach ($fields as $k => $item) {
                $this->updateCityField(
                    "",
                    $addressPath . '/' . $k . '/children/form-fields/children',
                    $jsLayout
                );
            }
        } else {
            $fields['city'] = $this->addDropdown($fields['city'] ?? []);
            $jsLayout = $this->arrayManager->replace($addressPath, $jsLayout, $fields);
        }
    }

    /**
     * Add City Dropdown
     *
     * @param array $city
     * @return array
     */
    private function addDropdown($city)
    {
        $city['component'] = 'Magento_Ui/js/form/element/select';
        $city['config']['elementTmpl'] = 'ui/form/element/select';
        $city['sortOrder'] = 80;
        $city['options'] = [
            ['label' => __('New york'), 'value' => __('New york'),],
            ['label' => __('Los Angeles'), 'value' => __('Los Angeles'),],
            ['label' => __('Chicago'), 'value' => __('Chicago'),],
            ['label' => __('Houston'), 'value' => __('Houston'),],
            ['label' => __('Phoenix'), 'value' => __('Phoenix'),],
            ['label' => __('Philadelphia'), 'value' => __('Philadelphia'),],
            ['label' => __('San Antonio'), 'value' => __('San Antonio'),],
            ['label' => __('San Diego'), 'value' => __('San Diego'),],
            ['label' => __('Dallas'), 'value' => __('Dallas'),],
            ['label' => __('Austin'), 'value' => __('Austin'),],
        ];
        return $city;
    }
}
