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

app.controller('dealersEditCtrl', ['$scope', '$requestFactory', function ($scope, $request) {
   
    $scope.pages = {      
        first_name:'',
        last_name:'',
        company_name:'',
        email:'',
        phone:'',
        website:'',
        gst_no:'',
        address:'',
        city:'',
        post_code:'',
        country:'',
        interest:'',
        est_month_handset_expenditure:'',
        password:''
    };

    $scope.dealers= {};
    

    $scope.addDealer = function () {
        $request.setSub('api/dealers/edit/'+ ($scope.editMode ? $scope.dealersId : 0));
        $request.setQuery({apikey: apikey, dealers: $scope.dealers});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.code==200) {
                window.location = base + 'admin/dealers';
            } else {
                alert('Unable to add page');
            }
            
        });
    };

    if (editMode) 
    {
        $scope.dealers.id = editId;
        $scope.editMode = true;
        $scope.dealersId = editId;
        //fetch pages categories
        $request.setSub('api/products/rest');
        $request.setMethod('POST');
        $request.setQuery({
            apikey: apikey,
            table: 'dealer',
            model: {
                where: {
                    id: editId
                },
                select: [
                    'first_name',
                    'last_name',
                    'company_name',
                    'email',
                    'phone',
                    'website',
                    'gst_no',
                    'address',
                    'city',
                    'post_code',
                    'country',
                    'interest',
                    'est_month_handset_expenditure',
                    'password',
                ]
            }
            
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.dealers = result.result[0];
                
                if ($scope.dealers.first_name.length==0) {
                    var dealerTitle = "DEALER"; 
                } else {
                    var dealerTitle = $scope.dealers.first_name.toUpperCase();
                }
                document.title = 'DEALER : '+ dealerTitle;
            }
        });
    }

    
    
}]);



