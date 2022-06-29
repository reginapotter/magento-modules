define([
    'jquery',
    'uiComponent',
    'Magento_Ui/js/form/element/abstract',
    'Magento_Ui/js/lib/validation/validator',
    'Magento_Ui/js/form/element/select'
], function ($, Component, Abstract, validator, Select) {
    'use strict';

    let options =  window.checkoutConfig.customer_class;

    return Select.extend({
        defaults: {
            template: 'Regina_AttributeAtCheckout/form/element/customer-class',
            customer_class: checkoutConfig.quoteData.customer_class
        },

        initConfig: function () {
            this._super();
            return this;
        },

        initObservable: function () {
            this._super()
                .observe(['customer_class']);
            return this;
        },

        validate: function () {
            let customerClassSelector = $('#customer_class'),
                customer_class = customerClassSelector.val(),
                error = $('#error-message'),
                result  = validator(this.validation, customer_class, this.validationParams),
                message = result.message,
                isValid = result.passed;

            this.error(message);
            this.bubble('error', message);

            if (!isValid) {
                this.source.set('params.invalid', true);
                error.html(message);
                customerClassSelector.focus();
                error.show();
            } else {
                error.hide();
            }

            return {
                valid: isValid,
                target: this
            };
        },

        getOptions: function () {
            options.shift();
            return options;
        },

        afterRender: function () {
            $('#customer_class').on('change', function() {
                var customer_class = $(this).find(":selected").val(),
                    error = $('#error-message');
                if (customer_class === '') {
                    error.html('This is a required field.');
                    $('#customer_class').focus();
                    error.show();
                } else {
                    error.hide();
                }
            });
        }
    });
});
