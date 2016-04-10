

<div class="table-responsive">
    <div class="floatright buttons">
        <a href="<?php echo base_url();?>admin/customers/edit/0" class="btn btn-success">Add</a>
        <a href="#" class="deleteBtn btn btn-danger" ng-disabled="disabled" ng-click="deleteSelected()">Delete</a>
    </div>
    <table class="table table-bordered">
        <tr>
            <td class="col-xs-1">Search</td>
            <td class="col-xs-11"><input type="text" class="form-control" ng-model="query" ng-change="updateFilteredList()"></td>
            <td class="col-xs-2"><a href="#" ng-model="resetBtn" ng-click="resetCustomers()" class="btn btn-danger form-control">Reset</a> </td>
        </tr>
        <tr>
            <td>Items Per Page</td>
            <td> <select ng-model="config.itemsPerPage" class="form-control">
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="2000">2000</option>
                    <option value="5000">5000</option>
                </select>
            </td>
            <td> </td>
        </tr>
        <tr>
            <td>Search Selection</td>
            <td>
                <select class="form-control" id="searchSelection" ng-model="searchSelection" ng-change="performToSelection(searchSelection)"
                        data-ng-options="searchSelection as searchSelection.name for searchSelection in searchSelection1">
                    
                </select>
            </td>
            <td> </td>
        </tr>
    </table>



    <!-- <table class="table table-bordered"  at-table at-paginated at-list="customers" at-config="config">
        <thead>
            <tr> 
                <th at-attribute="mailingList">ML</th> 
                <th at-attribute="feedbackEnabled">FB</th> 
                <th at-attribute="name">Name</th> 
                <th at-attribute="anAccount">AC</th> 
           </tr>
        </thead>
        <tbody>
            <tr ng-repeat="item in customers"
                selection-model
                selection-model-type="checkbox"
                selection-model-mode="multiple"
                selection-model-selected-items="selectedItems">
                <td><input type="checkbox" ng-model="item.sel" ng-change="push(item.sn)"></td>
                <td at-sortable at-attribute="sn" ><a target="_blank" href="<?php echo base_url();?>admin/customers/edit/{{item.sn}}">{{item.sn}}</a></td>
                 <td  at-sortable at-attribute="mailingList">
                    <span ng-if="item.mailingList === 'Y'"><span class="glyphicon glyphicon-ok"></span></span>
                    <span ng-if="item.mailingList === 'N'"><span class="glyphicon glyphicon-remove"></span></span>
                </td>
                 <td  at-sortable at-attribute="feedbackEnabled">
                    <span ng-if="item.feedbackEnabled === 'Y'"><span class="glyphicon glyphicon-ok"></span></span>
                    <span ng-if="item.feedbackEnabled === 'N'"><span class="glyphicon glyphicon-remove"></span></span>
                </td>
                <td  at-sortable at-attribute="name"  at-initial-sorting="asc">{{item.name}}</td>
                <td  at-sortable at-attribute="anAccount"> 
                    <span ng-if="item.anAccount === 'Y'"><span class="glyphicon glyphicon-ok"></span></span>
                    <span ng-if="item.anAccount === 'N'"><span class="glyphicon glyphicon-remove"></span></span>
                </td>
                <td  at-sortable at-attribute="companyName">{{item.companyName}}</td>
                <td  at-sortable at-attribute="email">{{item.email}}</td>
                <td  at-sortable at-attribute="phone">{{item.phone}}</td>
                <td  at-sortable at-attribute="mobile">{{item.mobile}}</td>
                <td  at-sortable at-attribute="city">{{item.city}}</td>
                <td  at-sortable at-attribute="address">{{item.address}}</td>
                <td  at-sortable at-attribute="postcode">{{item.postcode}}</td>
                
               
               
                <td  at-sortable at-attribute="createdDate">{{item.createdDate}}</td>
            </tr>
        </tbody>
    </table> -->
    <!-- <at-pagination at-list="customers" at-config="config"></at-pagination> -->
    <div class="row">
        <div class="col-md-12">
            <div ng-controller="kendoCtrl">
                <kendo-grid options="mainGridOptions">
                </kendo-grid>
            </div>
        </div>
    </div>

    <div class="hidden">
        <h3>Selected items</h3>
        <ul class=" ">
            <li ng-repeat="item in selectedItems">
                {{item.sn}}
            </li>
        </ul>
        <h3>UnSelected items</h3>
        <ul class="hidden">
            <li ng-repeat="item in unselectedItems1">{{item.sn}}</li>
        </ul>
    </div>
</div>

<script>

    // var app = angular.module('app', ['angular-table', 'selectionModel']);

    /*app.controller('customersController', function ($scope, $http, $filter) {
        var serviceUrl = '<?php echo base_url();?>api/customers/list?apikey=<?= $this->config->item('hitech_apikey') ?>';
        var keepomitUrl = '<?php echo base_url();?>api/keepomit/datapost';
        var keepomitDeleteUrl = '<?php echo base_url();?>api/keepomit/datadelete';
        var deleteUrl = '<?php echo base_url();?>api/customers/remove';

        $scope.searchSelection1 = [{id: "", name: "select"},{id: "omitselected", name: "omit selected"}, {id: "keepselected", name: "keep selected"}]

        $scope.config = {
            itemsPerPage: 100,
            fillLastPage: true,
            fillLastPage: "no"
        };
        $scope.customers = [];
        $scope.originalList = [];
        $scope.unselectedItems1 = [];

        //selected customers to delete
        $scope.selectedCustomers = [];
        $scope.disabled = false;

        //delete selected companies
        $scope.deleteSelected = function(){

            if($scope.selectedCustomers.length == 0)
            {
                return alert("Please select at least one customer.");
            }

            var ans = confirm('Are you sure you want to delete '+$scope.selectedCustomers.length+' customer(s)?');
            if(false == ans)
            {
                return;
            }

            $http.post(deleteUrl,{lists:$scope.selectedCustomers.join(),apikey:'<?= $this->config->item('hitech_apikey') ?>'}).then(function(data){

                alert($scope.selectedCustomers.length+' client(s) deleted.');
                $scope.selectedCustomers = [];
                $scope.loadCustomers();
            });
        };

        $scope.push = function(id){

            if($scope.selectedCustomers.length == 0){
                $scope.selectedCustomers.push(id);
                return true;
            }

            var found = false;
            for(var i = 0; i<$scope.selectedCustomers.length; i++)
            {
                if($scope.selectedCustomers[i] == id)
                {
                    $scope.selectedCustomers.splice(i,1);
                    found = true;
                    break;//not important;
                }
            }

            if(!found){
                $scope.selectedCustomers.push(id);
            }
        };
        //Get all customers
        $scope.loadCustomers = function () {
            $http.get(serviceUrl).success(function (data) {
                $scope.customers = data;
                $scope.unselectedItems1 = $scope.customers;
                $scope.originalList = $scope.customers;
            });
        };
        
        $scope.selectedItems = [];
        $scope.performToSelection = function (data) {
            // console.log(data.id);
            if (data.id == "keepselected") {
                var optionTexts = [];
                $($scope.selectedItems).each(function (key, val) {
                    var index = $scope.unselectedItems1.indexOf(val);
                    $scope.unselectedItems1.splice(index, 1);
                });
                $($scope.unselectedItems1).each(function (key, val) {
                    optionTexts.push(val.sn);
                });
                var postdata = {
                    sn: optionTexts.join(', '),
                    identify: 'C',
                    flag: 'O',
                    apikey: '<?= $this->config->item('hitech_apikey') ?>'
                };
                $async = $http.post(keepomitUrl, postdata).then(function (data) {
                    $scope.loadCustomers();
                });

            }
            if (data.id == "omitselected") {
                var optionTexts = [];
                $($scope.selectedItems).each(function (key, val) {
                    optionTexts.push(val.sn);
                });
                // console.log(optionTexts.join(', '));
                var postdata = {
                    sn: optionTexts.join(', '),
                    identify: 'C',
                    flag: 'O',
                    apikey: '<?= $this->config->item('hitech_apikey') ?>'
                };
                $async = $http.post(keepomitUrl, postdata).then(function (data) {
                    $scope.loadCustomers();
                });
            }
            $scope.searchSelection = $scope.searchSelection1[0];
            //window.setTimeout(, 5000);
        };

        $scope.resetCustomers = function () {
            var postdata = {
                identify: 'C',
                flag: 'O',
                apikey: '<?= $this->config->item('hitech_apikey') ?>'
            };
            $async = $http.post(keepomitDeleteUrl, postdata).then(function (data) {
                $scope.loadCustomers();
            });
        };
        //$scope.selectedItems = [];
        $scope.updateFilteredList = function () {
            $scope.customers = $filter("filter")($scope.originalList, $scope.query);
        };

        $scope.loadCustomers();
    });
    */
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";

    app.factory('$httpService',function($http){
        var base = '<?php echo base_url();?>';
        var sub = null;
        var query = null;
        var method = 'GET';
        var res = null;
        return {
            setSub : function(s){
                sub = s;
            },
            setQuery : function(q){
                query = q;
            },
            setMethod : function(m){
                method = m;
            },
            makeRequest : function(){
                switch(method){
                    case 'GET':
                        var append = '?';
                        for(var key in query){
                            append += key +'='+query[key];
                            append += '&';
                        }
                        return $http.get(base+sub+append).then(function(data){
                            if(typeof data.data != typeof undefined){
                                res = data.data;
                            }
                            else {
                                res = data;
                            }
                        });
                        break;
                    case 'POST':
                        return $http.post(base+sub,query).then(function(data){
                            if(typeof data.data != typeof undefined){
                                res = data.data;
                            }
                            else {
                                res = data;
                            }
                        });
                        break;
                }
            },
            setResults : function (data){
                res = data;
            },
            getResults : function(){
                return res;
            }
        };
    });

    app.controller('kendoCtrl',function($scope,$httpService) {
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: base + 'api/customers/list?apikey='+apikey,
                    dataType: "json"
                }
            },
            pageSize: 35,
            pageable: true
        });

        $scope.mainGridOptions = {
            dataSource: dataSource,
            sortable: true,
            pageable: true,
            selectable: "multiple",
            columns: [{
                field: "sn",
                title: "Id"
                },{
                field: "name",
                title: "Name"
                },{
                field: "companyName",
                title: "Company Name"
                },{
                field: "address",
                title: "Address"
                },{
                field: "city",
                title: "City"
                },{
                field: "postcode",
                title: "Postcode"
                },{
                field: "state",
                title: "State"
                },{
                field: "country",
                title: "Country"
                },{
                field: "phone",
                title: "Phone"
                },{
                field: "fax",
                title: "Fax"
                },{
                field: "mobile",
                title: "Mobile"
                },{
                field: "shippingInstruction",
                title: "ShippingInstruction"
                },{
                field: "website",
                title: "Website"
                },{                
                field: "status",
                title: "Status",
                template: function(data){
                        if (data.status=="Y") {
                            return "<span class='glyphicon glyphicon-ok'></span>";
                        } else {
                            return "<span class='glyphicon glyphicon-remove'></span>";
                        }  
                    }
                },{
                field: "createdDate",
                title: "Date Created"
            }]
        };
    });
</script>
