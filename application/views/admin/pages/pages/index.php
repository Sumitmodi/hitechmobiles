
<div class="table-responsive container" ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3 class="pull-left">PAGES LIST</h3>
        <h3 class="pull-right">
            <a href="javascript:;" ng-click="resetSelected()" class="btn btn-success">Reset</a>
        </h3>
    </div>
    <?php $this->load->view('admin/common/productsHeader'); ?>
    <!-- <div class="row">
        <div class="col-md-12 pull-right">
            <ul class="nav navbar-nav spacing pull-right">
                <li role="presentation">
                    <button class="btn btn-success" ng-click="addPages()"><span class="glyphicons glyphicons-plus"></span> Add</button></a>
                </li>
                <li role="presentation">
                    <button ng-click="deleteSelected()" class="btn btn-danger"><span class="glyphicons glyphicons-remove-2"></span> Delete</button>
                </li>
                <li role="presentation">
                    <button ng-click="resetSelected()" class="btn btn-danger" ng-model="resetBtn"><span class="glyphicons glyphicons-remove-2"></span> Reset</button>
                </li>
            </ul>
        </div>
    </div> -->

    <div class="row">
        <div class="col-md-12">
            <div id="example">
                <div>
                    <div id="grid">
                        <kendo-grid options="mainGridOptions" id="mainGridOptions"></kendo-grid>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/pages/index.js'); ?>"></script>
