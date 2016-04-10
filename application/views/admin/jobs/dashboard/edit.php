<script type="text/javascript" src="<?php echo base_url('assets/angular/jobs/edit.js');?>"></script>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var deviceEditMode = false;
    var jobEdit = false;
    var copy= false;
    var editId = <?php echo $this->uri->segment(4);?>;
    <?php }else{ ?>
    var editMode = false;
    var deviceEditMode = false;
    var jobEdit = false;
    var copy= false;
    <?php } ?>

    <?php if($this->uri->segment(5)>0){ ?>
        var jobEdit = true;
        var jobEditId = <?php echo $this->uri->segment(5);?>;
        var copy= false;
    <?php }else{ ?>
        var jobEditId = 0;
        var jobEdit = false;
        var copy= false;
    <?php } ?>

    <?php if($this->uri->segment(3) == "copy"){ ?>
        var copy= true;
        var editMode = true;
        var jobEdit = true;
        var editId = <?php echo $this->uri->segment(4);?>;
    <?php } else { ?>
        var copy= false;
     <?php } ?>
</script>
<div class="col-md-12" ng-controller="JobEditCtrl" ng-scope>
    <div class="jobsForm" ng-if='customer==null && new_customer == false && jobEdit == false'>
       <div class="row">
           <form action="" method="post" ng-submit="searchCustomer()">
               <div class="col-md-4">
                   <input type="text" ng-model="search.customer_name" class="form-control" placeholder="Enter customer name" ng-change="searchCustomer(search.customer_name)"/>
               </div>
                <button type="submit" ng-click="searchCustomer()" class="btn btn-success">Search</button>
                <ul ng-show="searchedCustomer.length">
                    <li ng-click="addCustomer(customer)"
                        ng-repeat="customer in searchedCustomer">
                        {{customer.name}}
                    </li>
                </ul>
           </form>
       </div>
       <div class="row">
           <p><small>or,</small></p>
           <p>
               <a href="javascript:;" ng-click="newCustomer()" class="btn btn-primary">New Customer</a>
           </p>
       </div>
    </div><div class="clear"></div>
    
    <div id="wrap" ng-if='(customer==null && new_customer == true) || (customer!==null && new_customer == false) || (jobEdit == true)'>
        <div class="cform">
            <form class="jobsForm" role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addNewCustomer()">
                <div class="row">
                    <h2 class="cdttl"> Customer Detail : </h2>
                    
                    <div class="form-group col-sm-6">
                        <label for="text">Custome Name *</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Name of the customer"
                               ng-model="search.customer.name" ng-value="search.customer.name">
                    </div>
                   
                    <div class="form-group col-sm-6">
                        <label for="text">Phone no</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Phone number of the customer"
                               ng-model="search.customer.phone" ng-value="search.customer.phone">
                    </div>
                   
                    <div class="form-group col-sm-6">
                        <label for="text">Address *</label>
                        <textarea type="text" class="form-control" id="text"
                               placeholder="Address of the customer"
                               ng-model="search.customer.address" ng-value="search.customer.address"></textarea>
                    </div>                   
                   
                    <div class="form-group col-sm-6">
                        <label for="text">Mobile</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Mobile no. of the customer"
                               ng-model="search.customer.mobile" ng-value="search.customer.mobile">
                    </div>
                    
                    <div class="row col-sm-12">
                        <div class="form-group col-sm-4">
                            <label for="name">City *</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="City of the customer"
                                   ng-model="search.customer.city" ng-value="search.customer.city">
                        </div>
                        
                        <div class="form-group col-sm-4">
                            <label for="name">Country *</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="City of the customer"
                                   ng-model="search.customer.country" ng-init="search.customer.country='New Zealand'" value="New Zealand">
                            
                        </div>
                        
                        <div class="form-group col-sm-4">
                            <label for="name">Post Code </label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Post code of the customer"
                                   ng-model="search.customer.postcode" ng-value="search.customer.postcode">
                        </div>
                    </div>
                </div>
                <div class="clear"></div><!-- detail end -->
              
                <div class="row">
                    <h2 class="cdttl"> Faculty Devices Detail : </h2>
                
                    <div class="form-group col-sm-12">
                        <label for="name">Device Type</label>
                        <input type="text" class="form-control" id="text" placeholder="Device type of the customer" ng-model="search.jobs.device_type" ng-value="search.jobs.device_type"/>
                        <!--  ng-selected="showSelectedVendors(item.name)" -->
                        <!--<select class="form-control" ng-model="search.jobs.device_type">
                            <option ng-repeat="item in search.jobs.device_type" value="{{item.device_type}}">{{item.device_type}}
                            </option>
                        </select>-->
                    </div>
                           
                    <div class="form-group col-sm-4">
                        <label for="name">Make *</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Make"
                               ng-model="search.jobs.make" ng-value="search.jobs.make">
                    </div>
                    
                    <div class="form-group col-sm-4">
                        <label for="name">Model *</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Model of the device"
                               ng-model="search.jobs.model" ng-value="search.jobs.model">
                    </div>
                    
                    <div class="form-group col-sm-4">
                        <label for="name">IMEI/Serial No *</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Serial no. of device"
                               ng-model="search.jobs.serial_no" ng-value="search.jobs.serial_no">
                    </div>
                    
                    <div class="form-group col-sm-4">
                        <label for="name">Colour </label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Colour of the device"
                               ng-model="search.jobs.colour" ng-value="search.jobs.colour">
                    </div>
                    
                    <div class="form-group col-sm-4">
                        <label for="name">Security/Phone Lock</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Security code of the device"
                               ng-model="search.jobs.security" ng-value="search.jobs.security">
                    </div>
                    
                    <div class="form-group col-sm-4">
                        <label for="name">Notify If Quote Exceeds</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Notify if quote exceeds"
                               ng-model="search.jobs.quote_exceeds" ng-value="search.jobs.quote_exceeds">
                    </div>
                </div>
                <div class="clear"></div><!-- faculty device end -->
              
                <div class="row">
                    <h2 class="cdttl">Fault Detail</h2>
                    <div class="form-group col-sm-12">
                        <label for="msg">Fault Detail*</label>
                        <textarea class="validate['required']" rows="8" cols="80" placeholder="Fault details of the device"
                               ng-model="search.jobs.fault_details" ng-value="search.jobs.fault_details"></textarea>
                    </div>
                </div><div class="clear"></div><!-- fault device end -->
              
                <div class="row">
                    <h2 class="cdttl">Accessories Included</h2>
                    <div class="form-group col-sm-12">
                        <label for="msg">Accessories</label>
                        <textarea type="text" class="validate['required']" rows="8" cols="80" placeholder="Accessories of the device"
                               ng-model="search.jobs.accessories" ng-value="search.jobs.accessories"></textarea>
                    </div>
                </div><div class="clear"></div><!-- accessoried end -->
              
              
                <div class="fdevice">
                    <h2 class="cdttl"> Warrenty Claim : </h2>
                    <div class="row">
                        <div class="col-sm-8">
                            <input type="radio" ng-model="search.jobs.warranty_claim" ng-value="1">Yes
                            <input type="radio" ng-model="search.jobs.warranty_claim" ng-value="0">No
                        </div>
                        <div class="col-sm-4">
                            Invoice No :
                        </div>
                        <div class="col-sm-8">
                            This only applies for the device that purchase from Hitech Mobiles with proof of purchase retained or for re-service device under same fault occur within 60days
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="text"
                               placeholder="Invoice no. of the customer"
                               ng-model="search.jobs.invoice_no" ng-value="search.jobs.invoice_no">
                        </div>
                        <div class="col-sm-8" ng-if='(customer==null && new_customer == true)'>
                            <div class="col-sm-6">
                                <label for="email">Email *</label>
                                <input id="email" name="email" type="text" class="validate['required','length[5,-1]','email']" size="30" ng-model="search.customer.email" ng-value="search.customer.email" />
                            </div>
                            <div class="col-sm-6">
                                <label for="email">Choose Password *</label>
                                <input id="email" name="email" type="password" class="validate['required','length[5,-1]','email']" size="30" ng-model="search.customer.password" ng-value="search.customer.password" />
                            </div>
                        </div>

                    </div>
                    <input type="checkbox" name="terms_and_condition" value="terms_and_condition">I have read all terms and conditions on Hitech Mobiles & More website and i agree to those terms
                    <div class="col-md-12" ng-if='search.customer_id.length == 0'>
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