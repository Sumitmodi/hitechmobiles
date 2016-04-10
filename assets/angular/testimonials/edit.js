'use strict'
app.controller('TestiCtrl', ['$scope', '$requestFactory', function ($scope, $request) {
    $scope.testi = {
        sn: 0,
        cust_sn: 0,
        feedback: '',
        status: 1,
        createdDate: '',
    };

    $scope.customers = [];

    $scope.addTestimonial = function () {
        $request.setSub('api/testimonials/testi');
        $request.setMethod('POST');
        $request.setQuery({
            apikey: apikey,
            testi: $scope.testi
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.code == 200) {
                if (typeof editId !== typeof undefined) {
                    $.notify(result.response, {
                        className: result.response,
                        autoHideDelay: '10000'
                    });
                } else {
                    $.notify(result.response, {
                        className: result.response,
                        autoHideDelay: '3000'
                    });
                }
            }
        });
    };

    $request.setSub('api/api/rest');
    $request.setMethod('POST');
    $request.setQuery({
        apikey: apikey,
        table: 'customers',
        model: {select: ['sn', 'name'], where: {"name != ": ''}, order_by: {name: 'asc'}}
    });
    $request.makeRequest().then(function () {
        var result = $request.getResults();
        if (result.code == 200) {
            $scope.customers = result.result;
        }
    });

    if (editMode) {
        $scope.testi.sn = editId;
        $request.setSub('api/api/rest');
        $request.setMethod('POST');
        $request.setQuery({
            apikey: apikey,
            table: 'testimonials',
            model: {where: {sn: editId}}
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.code == 200) {
                $scope.testi = result.result[0] != undefined ? result.result : result.result[0];
                console.log($scope.testi);
            }
        });
    }
}]);
