var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Regina_AttributeAtCheckout/js/model/payload-extender-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'Regina_AttributeAtCheckout/js/view/shipping': true
            }
        }
    }
};
