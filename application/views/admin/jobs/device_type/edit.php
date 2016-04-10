<script type="text/javascript" src="<?php echo base_url('assets/angular/jobs/edit.js');?>"></script>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    
    <?php if($this->uri->segment(4)>0){ ?>
    var deviceEditMode = true;
    var jobEdit = false;
    var editId = <?php echo $this->uri->segment(4);?>;
    var editMode = false;
    var copy = false;
    <?php }else{ ?>
    var deviceEditMode = false;
    var jobEdit = false;
    var editMode = false;
    var copy = false;
    <?php } ?>
</script>
<div class="col-md-12" ng-controller="JobEditCtrl" ng-scope>
    <div class="cform">
        <form class="jobsForm" role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addNewDeviceType()">
            <div class="row">                
                <div class="form-group col-sm-4">
                    <label for="text">Device Name *</label>
                    <input type="text" class="form-control" id="text" ng-model="device.device_type" ng-value='device.device_type'
                           placeholder="Enter device name">
                </div>
                   
                <div class="col-md-12">
                    <div class="col-md-4">
                        <ul class="nav navbar-nav spacing pull-left">
                            <li role="presentation">
                                <button class="btn btn-success" type="submit"><span
                                        class="glyphicons glyphicons-plus"></span> Save
                                </button>
                            </li>
                            <li role="presentation">
                                <button type="reset" class="btn btn-danger" ng-click="resetProduct()"><span
                                        class="glyphicons glyphicons-remove-2"></span> Reset
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><div class="clear"></div><!-- warranty end -->
            
      </form>
    </div><div class="clear"></div>
</div><div class="clear"></div><!-- wrapper end -->