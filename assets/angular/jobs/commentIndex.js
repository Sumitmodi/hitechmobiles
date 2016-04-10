app.controller('kendoCtrl', function ($scope, $requestFactory) {
    $scope.mainGridOptions = {};
    // $scope.comments_id = "";
    // $scope.searchedCustomer = [];
    $scope.checked = [];
    var hitech = new Hitech({
        scope: $scope,
        grid: $scope.mainGridOptions,
        http: $requestFactory
    });
    document.title = 'COMMENTS LIST';

    $scope.userInit = function(uid, role) {
        $scope.comments_id = uid;
        
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: base + 'api/jobs/comments_list?apikey=' + apikey + '&& id=' + $scope.comments_id,
                    dataType: "json",
                    type: "GET"
                }
               
            },
            pageSize: 35,
            pageable: true,
            batch: true
        });
        $scope.mainGridOptions = {
            dataSource: dataSource,
            sortable: true,
            selectable: "multiple",

            toolbar:[
                {
                    template: '<a href="javascript:;" ng-click="addComment()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new comments</a>'
                }
                // ,
                // {
                //     template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
                // },
                // {
                //     template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
                // }

            ],
            columns: [{
                width: 35,
                template: "<input type='checkbox' class='checkbox' />"
            },{
                field: "id",
                title: "Id",
            },{
                field: "customer_id",
                title: "Customer Id",
            },{
                field: "repair_status",
                title: "Repair Status",
            },{
                field: "location_status",
                title: "Location Status",
            },{
                field: "payment_status",
                title: "Payment Status",
            },{
                field: "comments",
                title: "Comments"
            },{
                field: "notify_dealer",
                title: "Notify Dealer"
            },{
                field: "notify_customer",
                title: "Notify Customer"
            },{
                field: "reports",
                title: "Add To Report",
            }],
            dataBound: function () {
                hitech.gridDataBound('checkbox');
            },
            
            change: function (e) {
                hitech.gridChangeEvent(this, e);
            }
        };
    };

    $scope.addComment = function(){
        window.location.href=base+'admin/jobs/comments/0/'+ $scope.comments_id;
    };

    

    

    // $scope.searchedCustomer = function (value) {
    //     console.log(value);
    //     $scope.query = value;
    //     var result = [];
    //     $requestFactory.setSub('api/products/rest');
    //     $requestFactory.setQuery({
    //         apikey: apikey,
    //         table: 'jobs',
    //         model: {where: {name: value},select: ['name', 'id']}
    //     });
    //     $requestFactory.setMethod('POST');
    //     $requestFactory.makeRequest().then(function () {
    //         result = $requestFactory.getResults();
    //         if (result.result) {
    //             console.log('fine');
    //             $scope.search_customer = result.result;
    //         }
    //         var res = [];
    //         for (var key in $scope.search_customer) {
                
    //             var loc = $scope.search_customer[key];
    //             var substr = loc.name.substring(0, $scope.query.length);
    //             if (substr.toLowerCase() === $scope.query.toLowerCase()) {
    //                 res.push({id: loc.id, name: loc.name});
    //             }
    //         }
    //         $scope.searchedCustomer = res;
    //     });
        
    // };

});