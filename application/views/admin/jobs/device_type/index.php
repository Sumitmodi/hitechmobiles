<script src="<?php echo base_url('assets/themes/default/js/notify.min.js'); ?>"></script>
<style type="text/css">
    .nav.spacing li {
        margin: 0 5px;
    }
</style>
<div class="container" ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3 class="pull-left">DEVICE TYPE LIST</h3>
        <h3 class="pull-right">
            <a href="javascript:;" ng-click="resetSelected()" class="btn btn-success">Reset</a>
        </h3>
    </div>
    <?php $this->load->view('admin/common/productsHeader'); ?>

    <div class="row">        
        <div class="col-md-12">
            <div>
                <div id="grid"></div>
                <!-- Name : <input type="text" name="name"> -->
                <kendo-grid options="mainGridOptions" id="mainGridOptions"></kendo-grid>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
        async="async"></script>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/jobs/deviceType/index.js'); ?>"></script>        
