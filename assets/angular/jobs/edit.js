'use string';
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}


app.controller('JobEditCtrl', ['$scope','$requestFactory', function ($scope, $request) {
    $scope.search = {      
        customer_name:[],
        customer_id: '',
        customer: {},
        jobs: {},
        comments: {}
    };

    $scope.customer = null;
    $scope.new_customer = false;
    $scope.customer_data = [];
    $scope.searchedCustomer = {};
    $scope.jobEdit = false;
    $scope.device = {
        device_type:'',
        id:''
    };
    // deviceEditMode = '';
    $scope.addCustomer = function (acc){
        $scope.searchedCustomer = [];
        $scope.new_customer = false;
        $scope.customer = true;
        $scope.search.customer_id = acc.id;
    };

    $scope.searchCustomer = function(query){
        var value = query || $scope.search.customer_name;
        $scope.query = value;
       
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'customers',
            model: {where: {name: value}}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.search_customer = result.result;
            }

            for (var key in $scope.search_customer) {
                var loc = $scope.search_customer[key];
                if (
                    (loc.name != null && (loc.name.toLowerCase() == $scope.query.toLowerCase())) ||
                    (loc.email == $scope.query.toLowerCase()) ||
                    (loc.phone == $scope.query)
                ) {
                    $scope.search.customer = result.result[0];
                    $scope.search.customer_id = loc.sn;
                    $scope.customer_data.push({
                        id: loc.id, 
                        name: loc.name,
                        phone_no: loc.phone_no,
                        address: loc.address,
                        mobile: loc.mobile,
                        city: loc.city,
                        country: loc.country,
                        post_code: loc.post_code,
                        email: loc.email,
                        password: loc.password
                    });
                   
                }
            }
            var temp = $scope.customer_data;
            $scope.searchedCustomer = temp;

            $request.setQuery({
                apikey: apikey,
                table: 'jobs',
                model: {where: {customer_id: $scope.search.customer_id}}
            });
            $request.setMethod('POST');
            $request.makeRequest().then(function () {
                result = $request.getResults();
                if (result.result) {
                    $scope.search_customers = result.result;
                    $scope.search_customer = $scope.query;
                }
                for (var key in $scope.search_customers) {
                    var loc = $scope.search_customers[key];
                                      
                    $scope.search.jobs = result.result[0];
                    $scope.search.customer_id = loc.id;
                    $scope.customer_data.push({
                        device_type: loc.device_type,
                        make: loc.make,
                        model: loc.model,
                        serial_no: loc.serial_no,
                        colour: loc.colour,
                        security: loc.security,
                        quote_exceeds: loc.quote_exceeds,
                        fault_details: loc.fault_details,
                        accessories: loc.accessories,
                        warranty_claim: loc.warranty_claim,
                        invoice_no: loc.invoice_no
                    });
                }
            });
        });
    };

    $scope.newCustomer = function(acc){
        $scope.customer = null;
        $scope.new_customer = true;
    };

    $scope.addNewCustomer = function(){
        if (copy==true) {
            $scope.search.jobs.id = 0
        };
        $request.setSub('api/jobs/edit/'+ ($scope.editMode ? $scope.productId : 0));
        $request.setQuery({apikey: apikey, customer: $scope.search});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            window.location = base + 'admin/jobs/dashboard/index';
            
        });
    };

    $scope.addJobComments = function(){
        $request.setSub('api/jobs/comments_edit/'+ ($scope.editMode ? $scope.commentId : 0));
        $request.setQuery({apikey: apikey, comments: $scope.search.comments});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            customer_id = result.customer_id;
            if (result.code == 200) {
                $.notify('Comment added successfully','success');
                window.location = base + 'admin/jobs/comments/'+customer_id;
            }else {
                $.notify('Unable to add comment','error');
            }
        });
    };

    // add new device type
    $scope.addNewDeviceType = function(){
        $request.setSub('api/jobs/device_type/'+ ($scope.editMode ? $scope.deviceId : 0));
        $request.setQuery({apikey: apikey, device: $scope.device});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            
            if (result.code == 200) {
                $.notify('Device type added successfully', 'success');
                window.location = base + 'admin/jobs/deviceType';
            } else {
                $.notify('Unable to add', 'error');
            }
        });
    };

    if (deviceEditMode == true) {
        $scope.device.id = editId;

        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'device_type',
            model: {where: {id: editId}, select: ['device_type']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.device.device_type = result.result[0].device_type;
                
                if ($scope.device.device_type.length==0) {
                    var deviceTypeTitle = "DEVICE TYPE"; 
                } else {
                    var deviceTypeTitle = $scope.device.device_type.toUpperCase();
                }
                document.title = 'DEVICE TYPE : '+ deviceTypeTitle;
            }
        });
    };

    if (copy == true) {
        $scope.jobEdit = true;
        var jobResult = [];

        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'jobs',
            model: {where: {id: editId}}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            jobResult = $request.getResults();
            if (jobResult.result) {
                $scope.search_jobs = jobResult.result;
                $scope.search.jobs = jobResult.result[0];
                customer_id = jobResult.result[0].customer_id;
            }

            var result = [];
            $request.setSub('api/products/rest');
            $request.setQuery({
                apikey: apikey,
                table: 'customers',
                model: {where: {sn: customer_id}}
            });
            $request.setMethod('POST');
            $request.makeRequest().then(function () {
                result = $request.getResults();
                if (result.result) {
                    $scope.search_customer = result.result;
                }
                for (var key in $scope.search_customer) {
                    var loc = $scope.search_customer[key];
                    var substr = loc.first_name;             
                    $scope.search.customer = result.result[0];
                }
            });
        });
    }

    if (jobEdit == true && copy== false) {
        $scope.jobEdit = true;
        
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'customers',
            model: {where: {sn: jobEditId}}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.search_customer = result.result;
            }
            for (var key in $scope.search_customer) {
                var loc = $scope.search_customer[key];
                var substr = loc.first_name;
                    $scope.search.customer = result.result[0];
            }
        });
    };
}]);



