<script src="<?php echo base_url('assets/kendo/js/jszip.min.js'); ?>"></script>
<style type="text/css">
    .customize-view {
        width: 100%;
        border: 1px solid #eee;
    }

    .customize-view li {
        padding: 15px;
        font-size: 14px;
        color: #777;
    }

    .customize-view li label {
        font-weight: normal;
    }

    .customize-view li:first-child {
        padding-left: 0;
    }
</style>
<div class="col-md-12" ng-scope ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3 class="pull-left">CUSTOMERS LIST</h3>
        <h3 class="pull-right">
            <a href="javascript:;" ng-click="resetCustomers()" class="btn btn-success">Reset</a>
        </h3>
    </div>
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
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <form method="POST" ng-submit="editColumns(checkboxModel);" class="form" ng-if="checkColumns == true">
                <div class="">
                    <ul class="nav navbar-nav customize-view">
                        <li>
                            <label>
                                <span>SN</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.sn.data"
                                       ng-change="columnChange(checkboxModel.sn)"
                                       ng-checked="checkboxModel.sn.data == 'true'" class="checkbox-colum-show-hide"
                                       readonly>
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Name</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.name.data"
                                       ng-change="columnChange(checkboxModel.name)"
                                       ng-checked="checkboxModel.name.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Mailing List</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.mailingList.data"
                                       ng-change="columnChange(checkboxModel.mailingList)"
                                       ng-checked="checkboxModel.mailingList.data == 'true'"
                                       class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Company Name</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.companyName.data"
                                       ng-change="columnChange(checkboxModel.companyName)"
                                       ng-checked="checkboxModel.companyName.data == 'true'"
                                       class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Email</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.email.data"
                                       ng-change="columnChange(checkboxModel.email)"
                                       ng-checked="checkboxModel.email.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Phone</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.phone.data"
                                       ng-change="columnChange(checkboxModel.phone)"
                                       ng-checked="checkboxModel.phone.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Mobile</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.mobile.data"
                                       ng-change="columnChange(checkboxModel.mobile)"
                                       ng-checked="checkboxModel.mobile.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Username</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.userName.data"
                                       ng-change="columnChange(checkboxModel.userName)"
                                       ng-checked="checkboxModel.userName.data=='true'"
                                       class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>City</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.city.data"
                                       ng-change="columnChange(checkboxModel.city)"
                                       ng-checked="checkboxModel.city.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Orders</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.orders.data"
                                       ng-change="columnChange(checkboxModel.orders)"
                                       ng-checked="checkboxModel.orders.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li>
                            <label>
                                <span>Secondary emails</span>&nbsp;
                                <input type="checkbox" ng-model="checkboxModel.email_attachment.data"
                                       ng-change="columnChange(checkboxModel.email_attachment)"
                                       ng-checked="checkboxModel.email_attachment.data=='true'" class="checkbox-colum-show-hide">
                            </label>
                        </li>
                        <li class="pull-right">
                            <input type="submit" class="btn btn-success" value="Save"/>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <kendo-grid options="mainGridOptions" id="mainGridOptions"></kendo-grid>
        </div>
    </div>
</div>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/customer/index.js'); ?>"></script>
