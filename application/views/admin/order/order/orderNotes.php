<script type="text/javascript" src="<?php echo base_url('assets/angular/order/edit.js');?>"></script>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var editId = <?php echo $this->uri->segment(4);?>;
    <?php }else{ ?>
    var editMode = false;
    <?php } ?>
</script>
<div class="col-md-12" ng-controller="OrderEditCtrl" ng-scope>
	<div id="wrap">
		<div class="cform">
		    <form class="jobsComments" role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addOrderNotes()">
				<div class="row">
					<input type="hidden" name="id" ng-model="order.note.order_id" ng-value="order.note.order_id" ng-init='order.note.order_id=<?=$order_id;?>'>
				    <div class="form-group col-sm-12">
				    	<label for="text">Note</label>
				        <input type="text" name="note" ng-model="order.note.note">
				    </div>
				    <div class="form-group col-sm-12">
				    	<label for="text">Notify</label>
				        <input type="text" name="notify" ng-model="order.note.notify">
				    </div>

				    <div class="form-group col-sm-12">
				    	<label for="text">Status</label>
				        <input type="radio" ng-model="order.note.status" ng-value="1">Yes
				        <input type="radio" ng-model="order.note.status" ng-value="0">No
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