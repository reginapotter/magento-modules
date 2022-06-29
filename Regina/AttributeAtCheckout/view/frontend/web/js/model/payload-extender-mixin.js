/**
 * Mixin for Magento_Checkout/js/model/shipping-save-processor/payload-extender
 * - set customer_class value to the  payload.addressInformation.extension_attributes
 */

define([
    'jquery',
    'mage/utils/wrapper'
], function (
    $,
    wrapper
) {
    'use strict';

    return function (processor) {
        return wrapper.wrap(processor, function (proceed, payload) {
            payload = proceed(payload);

            let customerClass = $('#customer_class').val();

            if (customerClass) {
                let extendWithCustomerClass = {
                    'customer_class': customerClass
                };

                payload.addressInformation.extension_attributes = _.extend(
                    payload.addressInformation.extension_attributes,
                    extendWithCustomerClass
                );
            }

            return payload;
        });
    };
});
