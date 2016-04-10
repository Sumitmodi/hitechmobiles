app.controller('PurchaseCtrl', ['$scope', '$requestFactory', function (scope, http) {
    scope.print_reports = [
        {id: 1, report: 'Purchase order'},
        {id: 2, report: 'Purchase order (no prices)'},
        {id: 3, report: 'odt purchase report'}
    ];

    scope.purchase_types = [
        {id: 1, type: 'RFQ (Request for Quote)'},
        {id: 2, type: 'P/O (Purchase Order)'},
        {id: 3, type: 'P/I (Purchase Invoice)'},
        {id: 4, type: 'Credit'},
        {id: 5, type: 'VOID'}
    ];

    scope.statuses = [
        {id: 1, status: 'To Be Ordered'},
        {id: 2, status: 'Ordered'},
        {id: 3, status: 'Dispatched'},
        {id: 4, status: 'Cleared'},
        {id: 5, status: 'Received Partial'},
        {id: 6, status: 'Received All'},
    ];

    scope.paid_status = [
        {id: 1, status: 'Paid'},
        {id: 0, status: 'UnPaid'}
    ];

    scope.user_type = ['customer', 'company'];

    scope.purchase = {
        tax_per: 15,
        shipping: {},
        items: []
    };

    scope.vendors = [];
    scope.allVendors = [];
    scope.query = '';
    scope.current = {};
    scope.selected_products = [];
    scope.total = scope.net_total = scope.discount = 0;
    scope.tax_per = 15;

    var hitech = new Hitech({
        scope: scope,
        http: http
    });

    hitech.request({
        sub: 'api/api/rest',
        method: 'POST',
        query: {
            apikey: apikey,
            table: 'vendors',
            model: {
                where: {
                    status: {method: 'strtoupper | y'},
                    adminSN: {method: 'getVar | _userSN'}
                },
                select: ['id', 'name']
            }
        },
        complete: function (result) {
            if (result.code == 200) {
                scope.allVendors = result.result;
            }
        }
    });

    hitech.request({
        sub: 'api/customers/list',
        method: 'GET',
        query: {
            apikey: apikey
        },
        complete: function (results) {
            if (results.length > 0) {
                scope.allCustomers = results;
            }
        }
    });

    hitech.request({
        sub: 'api/products/product',
        method: 'GET',
        query: {
            apikey: apikey
        },
        complete: function (results) {
            if (results.length > 0) {
                scope.allProducts = results;
            }
        }
    });

    scope.seachVendor = function (query) {
        scope.vendors = [];
        for (var i = 0, vendor; vendor = scope.allVendors[i]; i++) {
            if (vendor.name.toLowerCase().indexOf(query) > -1 || vendor.id.indexOf(query) > -1) {
                scope.vendors.push(vendor);
            }
        }
    };

    scope.selectVendor = function (vendor) {
        scope.vendors = [];
        scope.purchase.vendor = vendor.id;
        scope.selectedVendor = vendor.name;
        scope.query = '';
    };

    scope.changeShippingGroup = function (shipto) {
        scope.purchase.shipping.user_type = scope.user_type[shipto == true ? 1 : 0];
        scope.purchase.shipping = {};
    };

    scope.searchCustomer = function (query) {
        scope.customers = [];
        for (var i = 0, customer; customer = scope.allCustomers[i]; i++) {
            if (
                (customer.name != null && customer.name.toLowerCase().indexOf(query) > -1) ||
                customer.sn.indexOf(query) > -1 ||
                (customer.email != null && customer.email.toLowerCase().indexOf(query) > -1) ||
                (customer.city != null && customer.city.toLowerCase().indexOf(query) > -1) ||
                (customer.companyName != null && customer.companyName.toLowerCase().indexOf(query) > -1) ||
                (customer.address != null && customer.address.toLowerCase().indexOf(query) > -1) ||
                (customer.postcode != null && customer.postcode.toLowerCase().indexOf(query) > -1)
            ) {
                scope.customers.push(customer);
            }
        }
    };

    scope.selectCustomer = function (customer) {
        scope.customers = [];
        scope.purchase.shipping = customer;
        scope.purchase.shipping.user_type = scope.user_type[0];
        scope.cquery = '';
    };

    scope.searchProduct = function (type, value) {
        scope.searchedProducts = [];
        var index;
        switch (type.toLowerCase()) {
            case 'part':
                index = 'unique_code';
                break;
            case 'name':
                index = 'name';
                break;
            case 'vendor':
                index = 'vendor_name';
                break;
            default :
                index = 'name';
        }

        for (var i = 0, product; product = scope.allProducts[i]; i++) {
            if (product[index] == '' || product[index] == null) {
                continue;
            }
            if (product[index].toLowerCase().indexOf(value) > -1) {
                scope.searchedProducts.push(product);
            }
        }

        if (scope.searchedProducts.length > 0) {
            scope.showlist = type.toLowerCase();
        }
    };

    scope.selectProduct = function (product) {
        if (product == null) {
            return;
        }
        scope.showlist = '';
        scope.searchedProducts = [];

        scope.current.id = product.id;
        scope.current.part = product.unique_code;
        scope.current.vendor = product.vendor_name;
        scope.current.price = product.store_price || 0;
        scope.current.name = product.name;
        scope.current.qty = 1;
        scope.current.total = product.store_price || 0;
    };

    scope.pushProduct = function (current) {
        if (current.id == null) {
            $.notify("Please select a product.", 'error');
            return;
        }

        if (current.total == 0) {
            $.notify("Product quantity or price is zero.", 'error');
            return;
        }

        var product = {
            product_id: current.id,
            freight: parseFloat(current.freight) || 0,
            price: parseFloat(current.price),
            qty: parseInt(current.qty),
            total: parseFloat(current.total)
        };

        if (scope.purchase.items.length > 0) {
            for (var i = 0, item; item = scope.purchase.items[i]; i++) {
                if (item.product_id == current.id) {
                    $.notify("Product already selected.", 'error');
                    return;
                }
            }
        }
        ;

        scope.selected_products.push(current);
        scope.purchase.items.push(product);
        scope.current = {};

        scope.purchase.net_total = scope.net_total += parseFloat(current.total);
        scope.purchase.discount = scope.discount = (parseFloat(scope.purchase.discount_per || 0) * scope.net_total) / 100;
        scope.purchase.tax = (scope.tax_per * (scope.net_total - scope.discount)) / 100;
        scope.purchase.total = scope.purchase.tax + scope.net_total - scope.discount;
    };

    scope.updateTotal = function (current) {
        scope.current.qty = parseInt(current.qty);
        scope.current.price = parseFloat(current.price);
        scope.current.total = parseInt(current.qty) * parseFloat(current.price);
    };

    scope.popProduct = function (key) {
        scope.selected_products.splice(key, 1);
        scope.purchase.items.splice(key, 1);

        scope.purchase.total = scope.purchase.tax = scope.purchase.net_total = scope.net_total = 0;

        if (scope.purchase.items.length > 0) {
            for (var i = 0, item; item = scope.purchase.items[i]; i++) {
                scope.purchase.net_total = scope.net_total += item.total;
            }

            scope.purchase.discount = scope.discount = (parseFloat(scope.purchase.discount_per) * scope.net_total) / 100;
            scope.purchase.tax = (scope.tax_per * (scope.net_total - scope.discount)) / 100;
            scope.purchase.total = scope.purchase.tax + scope.net_total - scope.discount;
        } else {
            scope.purchase.discount = scope.discount = 0;
        }
    };

    scope.editProduct = function (key) {
        scope.current = scope.selected_products[key];
        scope.popProduct(key);
    }

    scope.calcDiscount = function (discount) {
        scope.purchase.discount_per = discount;
        scope.purchase.discount = scope.discount = (parseFloat(discount) * scope.net_total) / 100;
        scope.purchase.tax = (scope.tax_per * (scope.net_total - scope.discount)) / 100;
        scope.purchase.total = scope.purchase.tax + scope.net_total - scope.discount;
    };

    scope.addPurchase = function () {
        if (scope.purchase.items.length == 0) {
            $.notify("There aren't any products in the purchase list. Please select at least one product.", 'error');
            return;
        }

        scope.purchase.net_total = scope.net_total;
        hitech.request({
            sub: 'api/purchases/edit',
            method: 'POST',
            query: {
                apikey: apikey,
                purchase: scope.purchase
            },
            complete: function (result) {
                if (result.code == 200) {
                    scope.purchase.id = result.purchase;
                }

                $.notify(result.response, result.code == 200 ? 'success' : 'error');
            }
        });
    };

    if (typeof editMode !== typeof  undefined && editMode == true) {
        scope.purchase.id = editId;
        hitech.request({
            sub: 'api/api/rest',
            method: 'POST',
            query: {
                apikey: apikey,
                table: 'purchases',
                model: {
                    where: {
                        id: editId
                    }
                }
            },
            complete: function (result) {
                if (result.code == 200) {
                    scope.purchase = result.result;
                    //scope.$apply(scope.purchase);
                    console.log(scope.purchase);
                }
            }
        });
        hitech.request({
            sub: 'api/api/rest',
            method: 'POST',
            query: {
                apikey: apikey,
                table: 'purchase_shipping',
                model: {
                    where: {
                        purchase_id: editId
                    }
                }
            },
            complete: function (result) {
                if (result.code == 200) {
                    scope.purchase.shipping = result.result;
                    console.log(scope.purchase.shipping);
                }
            }
        });
    } else {
        scope.purchase.id = 0;
    }
}]);