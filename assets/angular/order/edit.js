'use string';
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}

// for serial key
var serialKeys = [];

app.controller('OrderEditCtrl', ['$scope', '$requestFactory', function ($scope, $request) {
    $scope.search_product = [];
    $scope.searchedProduct = [];
    $scope.search_customer = [];
    $scope.searchedCustomer = [];
    $scope.order = {
        product : [],
        customer: [],
        payment_type: '',
        shipping_type: '',
        shipping_description: '',
        quantity : '',
        note: {}
    };
    

    $scope.searchProduct = function (value) {
        $scope.query = value;
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'products',
            model: {where: {name: $scope.query},select: ['name', 'id']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults(),
                result_data = result.result;

            // debugger;
            if (result.result.length !== 0) {
                $scope.search_products = result.result;
                $scope.search_product = $scope.query;
                var res = [];
                for (var key in $scope.search_products) {
                    
                    var loc = $scope.search_products[key];
                    var substr = loc.name.substring(0, $scope.query.length);
                    if (substr.toLowerCase() === $scope.query.toLowerCase()) {
                        
                        res.push({id: loc.id, name: loc.name});
                    }
                }
                $scope.searchedProduct = res;
            }
            
        });
        
    };

    $scope.deleteProduct = function (index){
        $scope.order.product.splice(index, 1);
    };

    $scope.addProduct = function (acc){
        if ($scope.order.product.length>0) {
            $.notify('Product already added');
        }else {
            $scope.order.product.push(acc);
        }
        $scope.search_product = [];
    };

    $scope.searchCustomer = function (value) {
        $scope.query = value;
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'customers',
            model: {where: {first_name: $scope.query},select: ['first_name', 'sn']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults(),
                result_data = result.result;

            // debugger;
            if (result.result.length !== 0) {
                $scope.search_customers = result.result;
                $scope.search_customer = $scope.query;
                var res = [];
                for (var key in $scope.search_customers) {
                    
                    var loc = $scope.search_customers[key];
                    var substr = loc.first_name.substring(0, $scope.query.length);
                    if (substr.toLowerCase() === $scope.query.toLowerCase()) {
                        
                        res.push({id: loc.sn, name: loc.first_name});
                    }
                }
                $scope.searchedCustomer = res;
            }
            
        });
        
    };

    $scope.deleteCustomer = function (index){
        $scope.order.customer.splice(index, 1);
    };

    $scope.addCustomer = function (acc){
        // var customer = $scope.order.customer;
        if ( $scope.order.customer.length>0) {
            $.notify('Customer already added');
        }else {
             $scope.order.customer.push(acc);
        }
        $scope.search_customer = [];
    };

    if (editMode==false) {
        $request.setSub('api/products/rest');
        //fetch product categories
        $request.setQuery({
            apikey: apikey,
            table: 'payment_type'
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.order.payment_type = result.result;
            }
        });

        $request.setQuery({
            apikey: apikey,
            table: 'shipping_type'
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.order.shipping_type = result.result;
            }
        });
    };

    $scope.addNewOrder = function() {
        $request.setSub('api/order/edit/'+ ($scope.editMode ? $scope.orderId : 0));
        $request.setQuery({apikey: apikey, order: $scope.order});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            // result = $request.getResults();
            window.location = base + 'admin/order';
            
        });
    };

    $scope.addOrderNotes = function(){
        $request.setSub('api/order/note_edit/'+ ($scope.editMode ? $scope.orderId : 0));
        $request.setQuery({apikey: apikey, order: $scope.order});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            order_id = result.order_id;
            if (result.code == 200) {
                // <a href="'+base+'admin/jobs/comments/'+data.sn+'">'+data.sn+'</a>
                window.location = base + 'admin/order/orderDetail/'+order_id;
            };
            
        });
    };
    
    
}]); 
