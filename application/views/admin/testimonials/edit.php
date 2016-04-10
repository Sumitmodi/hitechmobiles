<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    var postUrl = '<?php echo base_url('api/pages/edit'); ?>';
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var editId = <?php echo $this->uri->segment(4);?>;
    <?php }else{ ?>
    var editMode = false;
    <?php } ?>
</script>

<div id="wrap" class="container" ng-controller="TestiCtrl" ng-scope="scope">
    <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addTestimonial()">
        <div class="table-responsive">
            <div class="form-group col-md-12">
                <label for="text" class="col-md-1">Customer</label>
                <div class="col-md-4">
                    <select ng-model="testi.cust_sn" class="form-control" ng-required="true">
                        <option ng-value="0" ng-selected="testi.cust_id==0 ? true : false">Select a customer</option>
                        <option ng-repeat="cust in customers" ng-value="{{cust.sn}}"  ng-selected="testi.cust_sn== cust.sn ? true : false">{{cust.name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label for="text" class="col-md-1">Feedback</label>
                <div class="col-md-4">
                    <textarea ng-model="testi.feedback" ng-value="testi.feedback" class="form-control" ng-required="true">
                        {{testi.feedback}}
                    </textarea>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label for="text" class="col-md-1">Date</label>
                <div class="col-md-4">
                    <input type="date" ng-model="testi.createdDate" ng-value="testi.createdDate" class="form-control" ng-required="true"/>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label for="text" class="col-md-1">Status</label>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label class="col-md-12">
                            Active <input type="radio" ng-model="testi.status" ng-value="1" name="status" ng-selected="testi.status==1"/>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-12">
                            De-Active <input type="radio" ng-model="testi.status" ng-value="0" name="status" ng-selected="testi.status==0"/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/angular/testimonials/edit.js');?>"></script>