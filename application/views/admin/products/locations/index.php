<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="<?php echo base_url(); ?>assets/js/c1xlsx.js"></script>
<script src="<?php echo base_url(); ?>assets/js/wijmo.grid.min.js"></script>
<script src="//cdn.jsdelivr.net/alasql/0.1/alasql.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/wijmo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/css/wijmo.min.css"></script>
<script src="<?php echo base_url(); ?>assets/js/ExcelConverter.js"></script>

<div class="table-responsive container" ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3 class="pull-left">PRODUCTIONS LOCATIONS</h3>
        <h3 class="pull-right">
            <a href="javascript:;" ng-click="resetSelected()" class="btn btn-success">Reset</a>
        </h3>
    </div>

    <?php $this->load->view('admin/common/productsHeader'); ?>

    <div class="row">

        <div class="col-md-12">

            <div id="example">

                <div>

                    <div id="grid">

                        <kendo-grid options="mainGridOptions" id="mainGridOptions"></kendo-grid>

                    </div>
                    <div class="row">
                        <wj-flex-grid style="max-height:400px"
                                      control="ctx.flexGrid"
                                      items-source="ctx.data">
                        </wj-flex-grid>
                    </div>


                </div>

            </div>

        </div>

    </div>

</div>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/products/locations/index.js'); ?>"></script>




