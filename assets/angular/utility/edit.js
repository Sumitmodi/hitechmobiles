'use string';
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}


app.controller('utilityEditCtrl', ['$scope','$requestFactory', function ($scope, $request) {    
    $scope.countrys = {
        country:'',
        id:''
    };

    // add new device type
    $scope.addNewCounty = function(){
        $request.setSub('api/utility/country/'+ ($scope.editMode ? $scope.deviceId : 0));
        $request.setQuery({apikey: apikey, country: $scope.countrys});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            
            if (result.code == 200) {
                $.notify('Country type added successfully', 'success');
                window.location = base + 'admin/country';
            } else {
                $.notify('Unable to add', 'error');
            }
        });
    };

    if (deviceEditMode==true) {
        $scope.countrys.id = editId;

        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'country',
            model: {where: {id: editId}, select: ['country']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.countrys.country = result.result[0].country;

                if ($scope.countrys.country.length==0) {
                    var countryTitle = "COUNTRY"; 
                } else {
                    var countryTitle = $scope.countrys.country.toUpperCase();
                }
                document.title = 'COUNTRY : '+ countryTitle;
            }
        });
    };
}]);



