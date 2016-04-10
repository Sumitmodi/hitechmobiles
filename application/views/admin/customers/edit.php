<script src="<?= $this->config->item('theme_admin_url') ?>js/angular-file-upload-shim.js"></script>
<script src="<?= $this->config->item('theme_admin_url') ?>js/angular-file-upload.js"></script>
<script src="<?= $this->config->item('theme_admin_url') ?>js/selection-model.js"></script>

<script src="<?php echo base_url(); ?>assets/js/lightbox.js"></script>
<script src="<?php echo base_url(); ?>assets/css/lightbox.css"></script>
<script src="<?php echo base_url(); ?>assets/js/bpop.js"></script>
<script src="<?php echo base_url(); ?>assets/css/bpop.css"></script>
<style type="text/css">
    .nav li{
        padding: 5px;
    }
</style>
<div class="container"   ng-controller="customersController">
 
    <div class="table-responsive">
        <?php
        if (!empty($status))
        {
            ?>
            <div class="alert alert-success">
                <?= $status ?>
            </div>
        <?php } ?>
        <div role="tabpanel">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="myTab">
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">General</a></li>
                <li role="presentation" class="disabled "><a href="#" aria-controls="purchasehistory" role="tab" data-toggle="tab">Purchase History</a></li>
                <li role="presentation" class="disabled"><a href="#" aria-controls="notes" role="tab" data-toggle="tab">Notes/Task/Events</a></li>
                <li class="top-right-button"><input type="submit" class="btn btn-success" value="Save" ng-disabled="disabled"/></li>
                <li class="top-right-button"><input type="submit" class="btn btn-success" value="Create New Invoice" ng-disabled="disabled"/></li>
                <li class="top-right-button"><input type="button" class="btn btn-success" value="Create New Repair Request/Job" ng-disabled="disabled" ng-click="newJob()"/></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <h2>Customer: <span>{{customer.name}}</span></h2>

                    <form method="POST"  ng-submit="editCustomer(customer);" class="form">
                        <div rel="lightbox" id="imgbox">
                    
                        </div>
                        <div class="clear floatRight" style="width:100%">
                            <P ng-show="uploadstart">File upload: {{uploadedfiles}} files(s) uploaded of {{totalfiles}}. Errors: {{uploadErrors}}. Success: {{uploadSuccess}}</p>
                            
                        </div>
                        <input type="hidden" ng-model="customer.sn"  />
                        <input type="hidden" ng-model="action" value="General" />
                        <div class="floatleft col-sm-6">
                            <table class="table  table-bordered">
                                <tr>
                                    <th colspan="2">
                                        General Details
                                    </th>
                                </tr>
                                <tr>
                                    <td>Customer ID</td>
                                    <td><input type="text" class="form-control" ng-model="customer.sn" ng-readonly="true"/></td>
                                </tr>
                                <tr>
                                    <td>Customer Name</td>
                                    <td><input type="text" class="form-control" ng-model="customer.name" /></td>
                                </tr>
                                <tr>
                                    <td>Company Name</td>
                                    <td><input type="type" class="form-control" ng-model="customer.companyName" /></td>
                                </tr>                                
                            </table>
                            <br/><br/>
                            <table class="table  table-bordered">
                                <tr>
                                    <td colspan="2">
                                        <b>Billing / Main Details</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><textarea ng-model="customer.address" class="form-control" rows="3"></textarea></td>
                                </tr>

                                <tr>
                                    <td>City</td>
                                    <td><input type="type" class="form-control" ng-model="customer.city" /></td>
                                </tr>
                                <tr>
                                    <td>Postcode</td>
                                    <td><input type="type" class="form-control" ng-model="customer.postcode" /></td>
                                </tr>
                                <tr>
                                    <td>Region/State</td>
                                    <td><input type="type" class="form-control" ng-model="customer.state" /></td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>                                        
                                        <input type="type" class="form-control" ng-model="customer.country" ng-init="customer.country='New Zealand'" value="New Zealand"/>
                                     
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><input type="type" class="form-control" ng-model="customer.email" /></td>
                                </tr>
                                <tr>
                                    <td>Website</td>
                                    <td><input type="type" class="form-control" ng-model="customer.website" /></td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td><input type="type" class="form-control" ng-model="customer.phone" /></td>
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td><input type="type" class="form-control" ng-model="customer.fax" /></td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td><input type="type" class="form-control" ng-model="customer.mobile" /></td>
                                </tr>
                            </table>
                            <br/><br/>
                            
                        </div>
                        <div class="floatRight col-sm-6">
                            <table class="table  table-bordered">
                                <tr>
                                    <td colspan="2">
                                        <b>Attributes</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Subscribe to mailing list?</td>
                                    <td><input type="checkbox" class="form-control" value="Y" ng-model="customer.mailingList" ng-checked="true"/></td>
                                </tr>
                                <tr>
                                    <td>Feedback Enabled?</td>
                                    <td><input type="checkbox" class="form-control" value="Y" ng-model="customer.feedbackEnabled" ng-checked="true"/></td>
                                </tr>
                                <tr>
                                    <td>Customer need Account?</td>
                                    <td><input type="checkbox" class="form-control" value="Y" ng-model="customer.anAccount" /></td>
                                </tr>
                                <tr>
                                    <td>Use Shipping Address?</td>
                                    <td><input type="checkbox" class="form-control" value="Y" ng-model="customer.useShippingAddress" /></td>
                                </tr>
                            </table>
                            <br/><br/> 
                            <table class="table  table-bordered">
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <input type="radio"  value="Y"  ng-model="customer.status" ng-checked="true"/> Enable
                                        <input type="radio"  value="N" ng-model="customer.status"  /> Disable
                                    </td>
                                </tr>
                                <tr>
                                    <td>User Name</td>
                                    <td><input type="type" class="form-control" ng-model="customer.userName" ng-readonly="true"/></td>
                                </tr>
                            </table>
                            </br></br>
                            <table class="table  table-bordered">
                                <tr>
                                    <td>
                                        <b>Comments</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td><textarea ng-model="customer.comments" class="form-control" rows="3"></textarea></td>
                                </tr>
                            </table>
                        </div>
                        <div class="floatRight col-sm-6" ng-if="showEmailAndAttachment==true">
                            <table class="table  table-bordered">
                                <tr>
                                    <td colspan="2">
                                        <b>More Email Address</b>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-success" ng-click="addCustomerEmails()">Add Emails</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td> Remove </td>
                                    <td> Email Address    </td>
                                    <td> Email Type    </td>
                                </tr>
                                <tr ng-repeat="item in customeremails">
                                    <td>
                                        <a href="javascript:;" ng-click="deleteEmail($index,item)"><span class="glyphicon glyphicon-remove"></span></a>
                                        <!--<input type="hidden" ng-model="item.sn"  />-->
                                    </td>
                                    <td><input type="email" class="form-control" ng-model="item.emailAddress" required /></td>
                                    <td><input type="text" class="form-control" ng-model="item.emailType" /></td>
                                </tr> 
                            </table>
                            <br/><br/> 
                            <table class="table  table-bordered attachments">
                                <tr>
                                    <td colspan="2">
                                        <b>Attachments</b>
                                    </td>
                                    <td colspan="3">
                                        <a href="javascript:void(0)" class="btn btn-success" ng-click="addCustomerAttachment()">Add Attachments</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td> Remove </td>
                                    <td> File Name  </td>
                                    <td> File    </td>
                                    <td> Description    </td>
                                    <td> Action    </td>
                                </tr>
                                <tr ng-repeat="item in customers_attachments">
                                    <td>
										<form method="post" ng-submit="deleteAttachment(item)">
											<input type="hidden" ng-model="item.remove"  />
											<input type="hidden" ng-model="item.sn"  />
											<input type="hidden" ng-model="item.customerSN" />
											<a ng-click="deleteAttachment(item,$index)"><span class="glyphicon glyphicon-remove"></span></a>
										</form>
                                    </td>
                                    <td>
                                        <span ng-if="item.filename !=null">{{item.filename}}</span>
                                        <span ng-if="item.file !=null">{{item.file}}</span>
                                    </td>
                                    <td> 
                                        <div ng-file-select="fileSelected(files,$index,$event)" ng-model="files" class="upload-button" ng-click="getImageID(item.sn)" 
                                             ng-multiple="false" ng-accept="'*.*'" 
                                             tabindex="0">Attach a File</div>

                                    </td>
                                    <td>
                                        <input type="text" class="form-control" ng-model="item.description" ng-change="updateDescription(item.description, $index)" ng-blur="submitDescription(item.description,item.sn)"/>
                                    </td>
                                    <td>
                                        <ul class="nav navbar-nav">
                                            <li>
                                                <input type="button" ng-model="previewAttachmentFile" ng-click="previewAttachmentFile(item)" value="Preview" class="btn btn-success"> <!-- {{item.file}}-->
                                            </li>
                                            <li>
                                                <input type="button" ng-model="downloadAttachmentFile" ng-click="downloadAttachmentFile(item)" value="Download" class="btn btn-info">
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
							<table class="table table-stripped" ng-show="uploadstart">
								<thead>
									<tr>
										<th colspan="3">Tracking file upload</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Filename</td>
										<td>Status</td>
										<td>Message</td>
									</tr>
									<tr ng-repeat="file in fileUpload">
										<td>{{file.name}}</td>
										<td>{{file.status}}</td>
										<td>{{file.message}}</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td>Summary:</td>
										<td>
											Uploaded: {{uploadedfiles}} of {{totalfiles}} file(s)											
										</td>
										<td>
											Error: {{uploadErrors}}
											<br/>
											Success: {{uploadSuccess}}
										</td>
									</tr>
								</tfoot>
							</table>
                        </div> 

                        <div class="floatRight col-sm-6">
                            <table class="table  table-bordered">
                                <tr>
                                    <td>
                                        <b>Shipping Instruction</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <!-- <textarea ng-model="customer.shipping_instruction"
                                                      name="shipping_instruction" class="ckeditor"
                                                      placeholder="Enter shipping instruction"></textarea>    -->
                                        <textarea ng-model="customer.shipping_instruction" class="form-control" rows="3"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                                           
                        <div class="clear submitBox">
                            <P ng-show="uploadstart">File upload: {{uploadedfiles}} files(s) uploaded of {{totalfiles}}. Errors: {{uploadErrors}}. Success: {{uploadSuccess}}</p>
                            <input type="submit" class="btn btn-success" value="Save!" ng-disabled="disabled"/>
                        </div>
                    </form>       

                    <input type="hidden" id="imageID" value="1" />
                </div>
                <div role="tabpanel" class="tab-pane" id="purchasehistory">

                </div>
                <div role="tabpanel" class="tab-pane" id="attachments">

                </div>
                <div role="tabpanel" class="tab-pane" id="notes">...</div>
                
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
var app = angular.module('app', ['ngFileUpload']);

app.controller('customersController', ['$scope', '$http', '$timeout', '$compile', '$upload', function ($scope, $http, $timeout, $compile, $upload) {

   var serviceUrl = '<?php echo base_url(); ?>api/customers/list/sn/<?= $sn ?>?apikey=<?= $this->config->item('hitech_apikey') ?>';

   var serviceDataPost = '<?php echo base_url(); ?>api/customers/data';

   var serviceEmailUrl = '<?php echo base_url(); ?>api/customers/email/sn/<?= $sn ?>?apikey=<?= $this->config->item('hitech_apikey') ?>';

   var serviceAttachmentUrl = '<?php echo base_url(); ?>api/customers/attachment/sn/<?= $sn ?>?apikey=<?= $this->config->item('hitech_apikey') ?>';

    var attachmentDeleteUrl = '<?php echo base_url(); ?>api/customers/attachmentDelete';

    var attachmentDescUpdate = '<?php echo base_url(); ?>api/customers/attachmentDescUpdate';

    var serviceEmailDelete = '<?php echo base_url(); ?>api/customers/deleteEmail';

   $customerSN = <?= $sn ?>;

   $scope.attachments = [];
   $scope.uploadstart = false;
   $scope.disabled = false;
   $scope.showEmailAndAttachment = false;
   $scope.customers_attachment = [];


    $scope.deleteEmail = function($index,item){
        if(item.deleteme)
        {
            $scope.customeremails.splice($index,1);
            return false;
        }
        var query = {
            sn:item.sn,
            apikey:'<?= $this->config->item('hitech_apikey') ?>'
        };
        $http.post(serviceEmailDelete,query).then(function(){
            $scope.customeremails.splice($index,1);
            return false;
        });
    }

    $scope.newJob = function() {
        window.location.href=base+'admin/jobs/edit/0/'+ $customerSN;
    }

    $scope.deleteAttachment = function(item,$index){
		var query = {
            sn:item.sn,
            apikey:'<?= $this->config->item('hitech_apikey') ?>'
        };

        $http.post(attachmentDeleteUrl,query).then(function(data){
           $scope.customers_attachments.splice($index,1);
        });

	};

    $scope.submitDescription = function(description,sno){
        var query = {
            sn:sno,
            desc: description,
            apikey:'<?= $this->config->item('hitech_apikey') ?>'
        };

        $http.post(attachmentDescUpdate,query).then(function(data){
            console.log(data);
        });
    };

    $scope.updateDescription = function (data, $index) {
        if($scope.attachments[$index] == undefined){
            return;
        }

        if ($scope.attachments[$index].hasOwnProperty('file')) {
            $scope.attachments[$index].description = data;
        }
    };

    $scope.getCustomersEmail = function () {
        $http.get(serviceEmailUrl).success(function (data) {
            $scope.customeremails = data;
        });
    };

    $scope.files = [];

    $scope.fileSelected = function (files, $index, $event) {
       
        $scope.files = files;
        if (files !== null && files[0] != undefined) {
            var file = files[0];
            var ext = file.name.substr(file.name.lastIndexOf('.') + 1);

 
            if (typeof $scope.attachments[$index] !== typeof undefined)
            {
                $scope.attachments[$index].file = files[0];
                $scope.attachments[$index].description = $scope.customers_attachments[$index].description;
            } else {
                $scope.attachments.push({file: files[0], description: ''});
            }

            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                if (ext.toLowerCase() == 'jpg' || ext.toLowerCase() == 'JPG' || ext.toLowerCase() == 'PNG' || ext.toLowerCase() == 'png' || ext.toLowerCase() == 'jpeg' || ext.toLowerCase() == 'JPEG') {
                    var img = new Image();
                    img.src = e.target.result;
                    img.onload = function () {
                        $scope.customers_attachments[$index].attachment = {type: ext, image: img};
                        delete $scope.customers_attachments[$index].file;
                        delete $scope.customers_attachments[$index].link;
                        $scope.$apply();
                    };
                }else{                    
                    $scope.customers_attachments[$index].link = ext;
                    $scope.customers_attachments[$index].file = e.target.result;
                    $scope.customers_attachments[$index].filename = file.name;
                    $scope.$apply();
                }
            };
        }
    };

    $scope.editCustomer = function () {
        if ($customerSN > 0) {
            var datapost = {
                customer: $scope.customer, 
				customersemail: $scope.customeremails,
                action: 'General',
                apikey: '<?= $this->config->item('hitech_apikey') ?>',
                mode: 'edit'
            };
        } else {
            var datapost = {
                customer: $scope.customer,
                action: 'General',
                apikey: '<?= $this->config->item('hitech_apikey') ?>',
                mode: 'new'
            };
        }
		
		$scope.disabled = true;
        // $scope.customer.shipping_instruction = CKEDITOR.instances.shipping_instruction.getData();
        // console.log($scope.attachments);return;
        if($scope.attachments.length > 0){
			var uploaded = 0;
			$scope.uploadstart = true;
			$scope.totalfiles = $scope.attachments.length;
			$scope.uploadedfiles = 0;
			$scope.uploadErrors = 0;
			$scope.uploadSuccess = 0;
			$scope.fileUpload = [];
            angular.forEach($scope.attachments,function(value,key){
                var file = value.file;
                
                $upload
                    .upload({
                        url: serviceDataPost,
                        method: 'POST',
                        fields: {
                            apikey: '<?= $this->config->item('hitech_apikey') ?>',
                            'Content-Type': file.type === null || file.type === '' ? 'application/octet-stream' : file.type,
                            filename: file.name,
                            sn: $("#imageID").val(),
                            customerSN: $scope.customer.sn,
                            action: 'attachment',
							description:value.description
                        },
                        file: file
                    }).then(function(data){
						if(data.data[0].toLowerCase() == 'success'){
							++$scope.uploadSuccess;
							$scope.fileUpload.push({name:file.name,status:'ok',message:data.data[0].toLowerCase()});
						}else{
							++$scope.uploadErrors;
							$scope.fileUpload.push({name:file.name,status:'Failure',message:data.data[0].toLowerCase()});
						}
                        $.notify(file.name+': '+data.data[0],data.data[0].toLowerCase());
                    },function(data){
						//error code here
					}).then(function(data){
						//the request is complete
						++uploaded;
						$scope.uploadedfiles = uploaded;
                        if(uploaded == $scope.attachments.length){
                            $http.post(serviceDataPost, datapost).then(function (data) {
								$scope.disabled = false;
                                $.notify(data.data[0],data.data[0].toLowerCase());
                                if ($customerSN > 0) {
                                    $scope.attachments = [];
                                    $scope.getCustomersEmail();
                                    $scope.getCustomersAttachment();
                                } else {
                                    location.href = '<?php echo base_url(); ?>admin/customers';
                                }
                            });
                        }
					});
            });

        }else{
            datapost.customersattachment = $scope.customers_attachments;
            $http.post(serviceDataPost, datapost).then(function (data) {                
                if (data.data.message == 'duplicate_email') {
                    var exist_customer_id = data.data.data.id;
                    // $.notify('Duplicate Email','error');
                    if (window.confirm('Duplicate Email.Please click to see!')) 
                    {
                        // window.location.href = base +'admin/customers/edit/'+ exist_customer_id;
                        window.open(
                            base +'admin/customers/edit/'+ exist_customer_id,
                            '_blank' 
                        );
                    };
                    return;
                };

				$scope.disabled = false;
                $.notify(data.data[0],data.data[0].toLowerCase());
                if ($customerSN > 0) {
                    $scope.attachments = [];
                    $scope.getCustomersEmail();
                    $scope.getCustomersAttachment();
                } else {

                    if (data.data == 'duplicate_email') {
                        // $.notify('This email is already added. Use diffrent email','error');
                    }else{
                        //location.href = '<?php echo base_url(); ?>admin/customers';    
                    }
                }
            });
        }
    };

    $scope.getCustomersAttachment = function () {
        $http.get(serviceAttachmentUrl).success(function (data) {
            $scope.customers_attachments = data;
            $(data).each(function (key, val) {
				var ext = val.file.substr(val.file.lastIndexOf('.') + 1);
                $scope.customers_attachments[key].link = ext.toLowerCase();
            });
        });
    };

    $scope.addCustomerEmails = function () {
        $scope.customeremails.push({sn: 0, emailType: '', emailAddress: '',deleteme:true});
        return false;
    };
	
	$scope.popEmail = function(item,$index){
		$scope.customeremails.splice($index,1);
	};
	
    $scope.addCustomerAttachment = function () {
        $scope.customers_attachments.push({sn: 0, customerSN: '<?= $sn ?>', file: '', description: ''});
        return false;
    };

    $scope.getImageID = function (id) {
        $("#imageID").val(id);
    };

    $scope.downloadAttachmentFile = function (item){
        var imgBase = base + 'uploads/customers/' + item.file;
        var a = $("<a>").attr("href", imgBase).attr("download", item.file).appendTo("body");
        a[0].click();
        a.remove();
    };

    $scope.previewAttachmentFile = function (item){
        if("item.attachment != null && item.attachment.type != 'pdf'"){
            // <img width="50" ng-src="{{item.attachment.image.src}}" />
        }

        if("item.link === 'jpg' || item.link === 'png' || item.link === 'PNG' || item.link === 'JPG'"){
            var imgbox=document.getElementById("imgbox");           
                img = document.createElement("img");

            img.src = base + 'uploads/customers/' + item.file;
            img.width = 600;
            img.height = 600;
            img.alt = "image";
            img.id = "paimg";

            imgbox.appendChild(img);
            $('#paimg').bPopup();
        }

        if("item.link === 'pdf'"){
            // <a href="<?= $this->config->item('customers_url') ?>{{item.file}}" ng-if="item.filename==null">click here</a>
            // <span ng-if="item.filename !=null">{{item.filename}}</span>
        }
    };

    if ($customerSN > 0) {
        $scope.showEmailAndAttachment = true;
        $scope.getCustomers = function () {
            $http.get(serviceUrl).success(function (data) {
                $scope.customer = data[0];
                if (data[0].feedbackEnabled === "Y") {
                    $scope.customer.feedbackEnabled = true;
                }
                if (data[0].mailingList == "Y") {
                    $scope.customer.mailingList = true;
                }
                if (data[0].anAccount == "Y") {
                    $scope.customer.anAccount = true;
                }
                if (data[0].useShippingAddress == "Y") {
                    $scope.customer.useShippingAddress = true;
                }
                
                if ($scope.customer.name.length==0) {
                    var customerTitle = "Customer";
                } else {
                    var customerTitle = $scope.customer.name.toUpperCase();
                }
                document.title = 'CUSTOMER : '+ customerTitle;
            });
            $scope.getCustomersEmail();
            $scope.getCustomersAttachment();
        };
        $scope.getCustomers();
    } else {
        $(".editModule").hide();
    }

}]);

<?php
if ($sn > 0)
{
    ?>
    $("a[aria-controls='purchasehistory']").attr('href', '#purchasehistory');
    $("a[aria-controls='attachments']").attr('href', '#attachments');
    $("a[aria-controls='notes']").attr('href', '#notes');
    $("#myTab li").removeClass('disabled');
<?php } ?>

$('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

</script>
<script type="text/javascript" src="<?= $this->config->item('theme_admin_url') ?>js/imageupload.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
        async="async"></script>