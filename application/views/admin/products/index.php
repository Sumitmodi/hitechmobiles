<script src="<?php echo base_url('assets/themes/default/js/notify.min.js'); ?>"></script>
<style type="text/css">
    .nav.spacing li {
        margin: 0 5px;
    }
    td[role=gridcell],th.k-header,a.k-link{
        text-align: center;
    }
</style>
<div class="container" ng-scope ng-app="products" ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3 class="pull-left">PRODUCTS LIST</h3>
        <h3 class="pull-right">
            <a href="javascript:;" ng-click="resetSelected()" class="btn btn-success">Reset</a>
        </h3>
    </div>
    <?php //$this->load->view('admin/common/productsHeader');?>
    <div class="clearfix"></div>
    <table class="table table-bordered">
        <tr>
            <td>Search</td>
            <td>Items Per Page</td>
            <td>Search Selection</td>
        </tr>
        <tr>
            <td>
                <input type="text" class="form-control" ng-model="query" ng-change="updateFilteredList(query)"
                       placeholder="Search this grid...">
            </td>
            <td>
                <select ng-model="config.itemsPerPage" class="form-control"
                        ng-change="adjustPageSize(config.itemsPerPage)">
                    <option value="100" ng-selected="true">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="2000">2000</option>
                    <option value="5000">5000</option>
                </select>
            </td>
            <td>
                <select ng-model="selection" ng-change="updateGridBySelection(selection)" class="form-control">
                    <option ng-selected="true">Set Selection</option>
                    <option ng-value="omit">Omit Selected</option>
                    <option ng-value="keep">Keep Selected</option>
                    <option ng-value="save_search">Save Search</option>
                </select>
            </td>
        </tr>
    </table>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <td>
                        <select class="form-control" ng-change="filterGrid('status',status)" ng-model="status">
                            <option ng-selected="true">Filter by status</option>
                            <option ng-value="1">Active</option>
                            <option ng-value="0">Inactive</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" ng-change="filterGrid('is_featured',featured)" ng-model="featured">
                            <option ng-selected="true">Filter by featured</option>
                            <option ng-value="1">Featured</option>
                            <option ng-value="0">Not featured</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" ng-change="filterGrid('availability',availability)" ng-model="availability">
                            <option ng-selected="true">Filter by availability</option>
                            <option ng-value="1">available</option>
                            <option ng-value="0">unavailable</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" ng-change="filterGridByStatus(productStatus)" ng-model="productStatus">
                            <option ng-selected="true" value="0">All products</option>
                            <option ng-value="1">Void products</option>
                            <option ng-value="2">Active products</option>
                            <option ng-value="3">Virtual products</option>
                        </select>
                    </td>
                    <!--
                    <td>
                        <select class="form-control" ng-change="filterGrid('vendor',vendor)" ng-model="vendor">
                            <option ng-selected="true">Filter by vendors</option>
                            <option value="1">available</option>
                            <option value="0">unavailable</option>
                        </select>
                    </td>-->
                    <td>
                        <input type="text" class="form-control" ng-change="filterGrid('unique_code',ucode,'Contains')" ng-model="ucode" placeholder="Search by product code"/>
                    </td>
                    <td>
                        <input type="text" class="form-control" ng-change="filterGrid('manufacturer_code',mcode,'Contains')" ng-model="mcode" placeholder="Search by manufacturer code"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="updateProduct(product.status)" ng-if="activeInactive==true">
            <!--<label for="text" class="col-md-1">Status</label>-->
            <div class="col-sm-2">
                <label class="col-md-5">Active <input type="radio" ng-model="product.status" ng-value="1" name="status" ng-selected="product.status==1"/></label>
                <label class="col-md-5">Inactive <input type="radio" ng-model="product.status" ng-value="2" name="status" ng-selected="product.status==0"/></label>
            </div>
            <div class="form-group col-md-12">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div>
                <kendo-grid options="mainGridOptions" id="mainGridOptions"></kendo-grid>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/products/index.js');?>"></script>