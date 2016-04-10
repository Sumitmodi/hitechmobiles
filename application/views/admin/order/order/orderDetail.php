<script src="<?php echo base_url('assets/themes/default/js/notify.min.js'); ?>"></script>
<style type="text/css">
    .nav.spacing li {
        margin: 0 5px;
    }
</style>
<div class="container" ng-controller="kendoCtrl">

    <?php //$this->load->view('admin/common/productsHeader'); ?>

    <div class="row">        
        <div class="col-md-12">
            <div>
                <div id="grid"></div>
                <div class="container-fluid" ng-init="userInit('<?php echo $id;?>')"></div>
                <kendo-grid options="mainGridOptions" id="mainGridOptions"></kendo-grid>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";

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
        document.title = 'ORDER NOTE';

        $scope.userInit = function(uid, role) {
            $scope.comments_id = uid;
            
            var dataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        url: base + 'api/order/order_nodes_list?apikey=' + apikey + '&& id=' + $scope.comments_id,
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
                        template: '<a href="javascript:;" ng-click="addNotes()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new order note</a>'
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
                    field: "order_id",
                    title: "Order Id",
                },{
                    field: "note",
                    title: "Note",
                },{
                    field: "notify",
                    title: "Notify",
                },{
                    field: "status",
                    title: "Status",
                },{
                    field: "date_created",
                    title: "Date Created"
                }],
                dataBound: function () {
                    hitech.gridDataBound('checkbox');
                },
                
                change: function (e) {
                    hitech.gridChangeEvent(this, e);
                }
            };
        };

        $scope.addNotes = function(){
            window.location.href=base+'admin/order/orderDetail/0/'+ $scope.comments_id;
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

    


</script>
<script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
        async="async"></script>
