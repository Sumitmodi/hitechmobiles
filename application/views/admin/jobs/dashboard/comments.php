<script type="text/javascript" src="<?php echo base_url('assets/angular/jobs/edit.js');?>"></script>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var jobEdit= false;
    var deviceEditMode = false;
    var copy = false;
    var editId = <?php echo $this->uri->segment(4);?>;
    document.title = 'COMMENT';
    <?php }else{ ?>
    var editMode = false;
    var jobEdit = false;
    var deviceEditMode = false;
    var copy = false;
    <?php } ?>
</script>
<div class="col-md-12" ng-controller="JobEditCtrl" ng-scope>
	<div id="wrap">
		<div class="cform">
		    <form class="jobsComments" role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addJobComments()">
				<div class="row">
					<input type="hidden" name="id" ng-model="search.comments.id" ng-value="search.comments.id" ng-init='search.comments.id=<?=$customer_id;?>'>
				    <div class="form-group col-sm-12">
				    	<label for="text">Repair Status</label>
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'waiting_to_assing'">Waiting to Assign
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'under_inspection'">Under Inspection
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'quotation_sent'">Quotation Sent
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'unserviceable'">Unserviceable
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'code_ordered'">Part/Code Ordered
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'repaired'">Repaired
				        <input type="radio" name="repair_status" ng-model="search.comments.repair_status" ng-value="'job_cancelled'">Job Cancelled
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Location Status</label>
				        <input type="radio" name="location_status" ng-model="search.comments.location_status" ng-value="'with_customer'">With Customer/Dealer
				        <input type="radio" name="location_status" ng-model="search.comments.location_status" ng-value="'service_center'">Service Center
				        <input type="radio" name="location_status" ng-model="search.comments.location_status" ng-value="'sent_to_dealer'">Has been sent to Dealer
				        <input type="radio" name="location_status" ng-model="search.comments.location_status" ng-value="'sent_to_customer'">Has been sent to Customer
				        <input type="radio" name="location_status" ng-model="search.comments.location_status" ng-value="'picked_up'">Picked Up
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Payment Status</label>
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'awaiting_inspection_fee'">Awaiting Inspection Fee
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'warranty_claim'">Warranty Claim
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'inspection_paid'">Inspection Paid
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'awaiting_payment'">Awaiting Payment
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'partial_payment'">Partial Payment
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'full_payment_received'">Full Payment Received
				        <input type="radio" name="payment_status" ng-model="search.comments.payment_status" ng-value="'others'">Others
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Comments</label>
				        <textarea class="validate['required']" rows="8" cols="80" placeholder="Fault details of the customer"
		                               ng-model="search.comments.comments" ng-value="search.comments.comments"></textarea>
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Notify Dealer</label>
				        <input type="radio" ng-model="search.comments.notify_dealer" ng-value="1">Yes
				        <input type="radio" ng-model="search.comments.notify_dealer" ng-value="0">No
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Notify Customer</label>
				        <input type="radio" ng-model="search.comments.notify_customer" ng-value="1">Yes
				        <input type="radio" ng-model="search.comments.notify_customer" ng-value="0">No
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Add To Report</label>
				        <input type="radio" ng-model="search.comments.reports" ng-value="1">Yes
				        <input type="radio" ng-model="search.comments.reports" ng-value="0">No
				    </div>
				    <div class="col-md-12">
		                <div class="col-md-4">
		                    <ul class="nav navbar-nav spacing pull-left">
		                        <li role="presentation">
		                            <button class="btn btn-success" type="submit"><span
		                                    class="glyphicons glyphicons-plus"></span> Submit
		                            </button>
		                        </li>
		                        <li role="presentation">
		                            <button class="btn btn-danger" ng-click="jobDelete()"><span
		                                    class="glyphicons glyphicons-remove-2"></span> Delete
		                            </button>
		                        </li>
		                        <li role="presentation">
		                            <button class="btn btn-danger" ng-click="jobRepair()"><span
		                                    class="glyphicons glyphicons-remove-2"></span> Repair
		                            </button>
		                        </li>
		                    </ul>
		                </div>
		            </div>
				</div>
			</form>
		</div>
	</div>
</div>