<div class="table-responsive container" ng-controller="testiCtrl">
    <?php $this->load->view('admin/common/productsHeader');?>
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
<script type="text/javascript" src="<?php echo base_url('assets/angular/testimonials/index.js');?>"></script>