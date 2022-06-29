**Regina_AttributeAtCheckout**

The module is build for a project using:

- Magento 2.4.*
- Php 7.4

This module adds a new required dropdown field at checkout.

It includes these Use cases:
- Created sales_order and quote columns (customer_class)
- Show dropdown in checkout for guests
- Show dropdown in checkout for logged in users
- Don't show dropdown in checkout for customers who already have a customer_class assigned
- When placing an order - Save customer_class to the order when it is pre-selected
- When placing an order - Save customer_class to the Order when it is selected during checkout
- When placing an order - Save customer_class to the Customer when it is selected during checkout and it's Not guest
- Show the customer class in admin Order View
- Make the customer_class field visible to the order API
