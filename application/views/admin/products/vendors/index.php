<script src="<?php echo base_url('assets/themes/default/js/notify.min.js');?>"></script>
<style type="text/css">
    .nav.spacing li{
        margin:0 5px;
    }
</style>
<div class="container" ng-controller="vendorCtrl" ng-scope>

    <div class="row">
        <div class="col-md-12 pull-right">
            <ul class="nav navbar-nav spacing pull-right">
                <li role="presentation">
                    <button class="btn btn-success" ng-click="activateAddModal()"><span class="glyphicons glyphicons-plus"></span> Add</button>
                </li>
                <li role="presentation">
                    <button ng-click="deleteVendors()" class="btn btn-danger"><span class="glyphicons glyphicons-remove-2"></span> Delete</button>
                </li>
            </ul>
        </div>
    </div>

    <div class="row" ng-if="showAddModal">
        <style type="text/css">
            .layer{
                background-color: rgba(0, 0, 0, 0.7);
                height: 100%;
                left: 0;
                position: absolute;
                top: 0;
                width: 100%;
                z-index: 9999;
            }
            .layer .content-section{
                background-color: rgba(0, 0, 0, 0.43);
                display: block;
                margin: 100px auto 0;
                padding: 40px;
                width: 50%;
            }
            .content-section label{
                color:#FFF;
            }
        </style>
            <div class="layer">
                <div class="content-section">
                    <form method="post">
                        <div class="form-group col-md-6">
                            <label form="name">Name:</label>
                            <input type="text" ng-model="vendor.name" class="form-control"/>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6">
                            <label form="name">Active Status:</label>
                            <select ng-model="vendor.status" class="form-control">
                                <option ng-value="Y" value="Y">Yes</option>
                                <option ng-value="N" value="N">No</option>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <ul class="nav navbar-nav spacing pull-left">
                                <li role="presentation">
                                    <button class="btn btn-success" type="submit" ng-click="addVendor(vendor)"><span class="glyphicons glyphicons-plus"></span> Save</button>
                                </li>
                                <li role="presentation">
                                    <button href="" class="btn btn-danger" ng-click="cancelAdd()"><span class="glyphicons glyphicons-remove-2"></span> Cancel</button>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-6">
            <h2 ng-if="vendors == null">Vendors have not been added.</h2>
            <table ng-if="vendors" class="table table-stripped">
                <thead>
                <tr>
                    <th></th>
                    <th>Sno</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="item in vendors" selection-model
                    selection-model-type="checkbox"
                    selection-model-mode="multiple"
                    selection-model-selected-items="vendors">
                    <th><input type="checkbox" ng-click="push(item)"/></th>
                    <th>{{item.id}}</th>
                    <th>{{item.name}}</th>
                    <th>{{item.status}}</th>
                    <th><a href="javascript:;" ng-click="editVendor(item,$index);">Edit</a> </th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
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
                var ret = null
                switch(method){
                    case 'GET':
                        var append = '?';
                        for(var key in query){
                            append += key +'='+query[key];
                            append += '&';
                        }
                        ret =  $http.get(base+sub+append).then(function(data){
                            if(typeof data.data != typeof undefined){
                                res = data.data;
                            }
                            else {
                                res = data;
                            }
                        });
                        break;
                    case 'POST':
                        ret =  $http.post(base+sub,query).then(function(data){
                            if(typeof data.data != typeof undefined){
                                res = data.data;
                            }
                            else {
                                res = data;
                            }
                        });
                        break;
                }
                return ret;
            },
            setResults : function (data){
                res = data;
            },
            getResults : function(){
                return res;
            }
        };
    });

    app.controller('vendorCtrl',function($scope,$httpService) {
        var apikey = '<?php echo $this->config->item('hitech_apikey');?>';
        $scope.showAddModal = false;
        $scope.vendors = null;
        $scope.selectedVendors = [];

        $scope.getVendors = function(){
            $httpService.setSub('api/customers/vendors');
            $httpService.setQuery({apikey: apikey});
            $httpService.setMethod('GET');
            $httpService.makeRequest().then(function () {
                $scope.vendors = $httpService.getResults();
                $scope.showAddModal = false;
            });
        };

        $scope.activateAddModal = function () {
            $scope.vendor = {id:0,name:'',action:'add',status:'Y'};
            $scope.showAddModal = true;
        };

        $scope.addVendor = function (category) {
            var query = {
                id:$scope.vendor.id,
                name:$scope.vendor.name,
                status:$scope.vendor.status,
                action:$scope.vendor.action !== undefined ? $scope.vendor.action : 'update',
                apikey:apikey
            };
            $httpService.setSub('api/customers/vendor');
            $httpService.setQuery(query);
            $httpService.setMethod('POST');
            $httpService.makeRequest().then(function(){
                $scope.getVendors();
                data = $httpService.getResults();
                $.notify(data.response,data.status.toLowerCase());
            })
        };

        $scope.cancelAdd = function () {
            $scope.vendor = {};
            $scope.showAddModal = false;
        };

        $scope.editVendor = function(item,$index){
            item.action = 'update';
            $scope.vendor = item;
            $scope.showAddModal = true;
        };

        $scope.push = function(item){
            $scope.selectedVendors.push(item.id);
        };

        $scope.deleteVendors = function(){
            if($scope.selectedVendors.length == 0){
                return alert("No vendors selected.");
            }
            $httpService.setSub('api/customers/deleteVendors');
            $httpService.setMethod("POST");
            $httpService.setQuery({
                apikey:apikey,
                snos:$scope.selectedVendors.join()
            });
            $httpService.makeRequest().then(function(){
                data = $httpService.getResults();
                $.notify(data.response,data.status.toLowerCase());
                $scope.getVendors();
            });
        };
        $scope.getVendors();
    });
</script>