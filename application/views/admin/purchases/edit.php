<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    var postUrl = '<?php echo base_url('api/purchases/edit'); ?>';
    <?php if ($this->uri->segment(4) > 0) { ?>
    var updateSelected = false;
    var editMode = true;
    var editId = <?php echo $this->uri->segment(4); ?>;
    <?php } else { ?>
    var editMode = false;
    var updateSelected = false;
    <?php } ?>
</script>
<div class="container" id="container" ng-controller="PurchaseCtrl" ng-scope="scope">
    <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addPurchase()">
        <div class="table-responsive">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="active">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#status" aria-controls="status" role="tab" data-toggle="tab">Status history</a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">Attachments</a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes/task/evets</a>
                    </li>
                    <li class="top-right-button">
                        <input type="submit" class="btn btn-success" value="Save" ng-disabled="disabled"/>
                        <input type="reset" class="btn btn-danger" value="Reset" ng-disabled="disabled"/>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general">
                        <div class="col-md-12">
                            <h3 class="faint-color">PURCHASE ORDER</h3>
                        </div>
                        <div class="col-md-12 faint-bkg">
                            <div class="col-md-9">
                                <div class="col-md-6">
                                    <label class="faint-color">Name of this purchase: <input type="text" ng-model="purchase.name" ng-value="purchase.name" placeholder="A title for this purchase" class="form-control"/></label>
                                </div>
                                <div class="clearfix"></div>
                                <fieldset class="fieldset bordered">
                                    <legend class="faint-color">Vendor</legend>
                                    <label class="col-md-2 faint-color">Search by name or id</label>

                                    <div class="col-md-5">
                                        <input class="form-control" ng-model="query" ng-value="query" ng-change="seachVendor(query)"
                                               placeholder="Search a vendor by either id or name"/>
                                        <ul class="search nav nav-inverse"  ng-show="vendors.length">
                                            <li data-ng-repeat="v in vendors">
                                                <a href="javascript:void(0)" ng-click="selectVendor(v)" class="faint-color">{{v.name}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-5" ng-show="selectedVendor != null">
                                        <label class="faint-color">Currently selected vendor: {{selectedVendor}}</label>
                                    </div>
                                </fieldset>
                                <fieldset class="fieldset bordered">
                                    <legend class="faint-color">Ship to</legend>
                                    <label class="col-md-3 faint-color">
                                        <input type="checkbox" ng-model="shipto" ng-value="1" ng-click="changeShippingGroup(shipto)"/>
                                        Ship to company address
                                    </label>
                                    <label class="col-md-5 faint-color absolutepos">
                                        <b>OR,</b> Ship directly to the customer
                                        <input class="form-control" ng-model="cquery" ng-change="searchCustomer(cquery)"
                                               placeholder="Search a customer by id,name,email,etc.." ng-disabled="shipto == 1"/>
                                        <ul class="search nav nav-inverse" ng-show="customers.length">
                                            <li data-ng-repeat="c in customers">
                                                <a href="javascript:void(0)" ng-click="selectCustomer(c)" class="faint-color">{{c.name || c.companyName}}</a>
                                            </li>
                                        </ul>
                                    </label>

                                    <div class="col-md-12">
                                        <label class="faint-color">Name:</label>

                                        <div class="col-md-5">
                                            <input type="text" ng-model="purchase.shipping.name" ng-value="purchase.shipping.name" class="form-control"
                                                   placeholder="Name of reciever"/>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="faint-color">Company:</label>

                                        <div class="col-md-5">
                                            <input type="text" ng-model="purchase.shipping.company" class="form-control"
                                                   placeholder="Name of company"/>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="faint-color">Address:</label>

                                        <div class="col-md-5">
                                            <input type="text" ng-model="purchase.shipping.address" class="form-control"
                                                   placeholder="Shipping address"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12"><label></label></div>
                                    <div class="col-md-row">
                                        <label class="col-md-1 faint-color">City:</label>

                                        <div class="col-md-5">
                                            <input type="text" ng-model="purchase.shipping.city" class="form-control"
                                                   placeholder="Shipping city"/>
                                        </div>
                                        <label class="col-md-1 faint-color">Postcode:</label>

                                        <div class="col-md-2">
                                            <input type="text" ng-model="purchase.shipping.postcode"
                                                   class="form-control"
                                                   placeholder="Postal code"/>
                                        </div>
                                        <label class="col-md-1 faint-color">Region:</label>

                                        <div class="col-md-2">
                                            <input type="text" ng-model="purchase.shipping.region" class="form-control"
                                                   placeholder="Shipping region"/>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="faint-color">Country:</label>

                                        <div class="col-md-5">
                                            <input type="text" ng-model="purchase.shipping.country" class="form-control"
                                                   ng-value="purchase.shipping.country" placeholder="Shipping country"/>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="faint-color">Shipping instruction
                                            <small>(will appear on shipping labels,etc)</small>
                                            :</label>

                                        <div class="col-md-5">
                                            <input type="text" ng-model="purchase.shipping.instruction" class="form-control"
                                                   ng-value="purchase.shipping.instruction"
                                                   placeholder="Shipping instrcution"/>
                                        </div>
                                    </div>

                                    <div class="col-md-12"><label></label></div>
                                    <div class="col-md-row">
                                        <label class="col-md-1 faint-color">Phone:</label>

                                        <div class="col-md-3">
                                            <input type="text" ng-model="purchase.shipping.phone" class="form-control"
                                                   placeholder="Phone"/>
                                        </div>
                                        <label class="col-md-1 faint-color">Mobile:</label>

                                        <div class="col-md-3">
                                            <input type="text" ng-model="purchase.shipping.mobile" class="form-control"
                                                   placeholder="Mobile"/>
                                        </div>
                                        <label class="col-md-1 faint-color">Email:</label>

                                        <div class="col-md-3">
                                            <input type="text" ng-model="purchase.shipping.email" class="form-control"
                                                   placeholder="Shipping email"/>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="fieldset bordered">
                                    <legend class="faint-color">Printing</legend>
                                    <div class="col-md-12">
                                        <label class="faint-color">Default print report</label>

                                        <div class="">
                                            <select ng-model="purchase.print_report" class="form-control faint-color">
                                                <option ng-value="0" ng-selected="purchase.print_report == null">Select
                                                    report type
                                                </option>
                                                <option ng-repeat="r in print_reports" ng-value="{{r.id}}"
                                                        ng-selected="r.id == purchase.print_report">{{r.report}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="fieldset bordered">
                                    <legend class="faint-color">Attributes</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="faint-color">ID</label>
                                            <input type="text" ng-model="purchase.id" placeholder="ID"
                                                   class="form-control" ng-disabled="true"/>
                                            <label class="faint-color">Type</label>

                                            <select ng-model="purchase.type" class="form-control faint-color">
                                                <option ng-value="0" ng-selected="purchase.type == null">Select purchase
                                                    type
                                                </option>
                                                <option ng-repeat="r in purchase_types" ng-value="{{r.id}}"
                                                        ng-selected="r.id == purchase.type">{{r.type}}
                                                </option>
                                            </select>

                                            <label class="faint-color">P/I invoice#</label>
                                            <input type="text" ng-model="purchase.invoice_no"
                                                   placeholder="Invoice number"
                                                   class="form-control"/>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="faint-color">Order date</label>
                                            <input kendo-date-picker type="text" ng-model="purchase.order_date" placeholder="Order"
                                                   class="form-control"/>

                                            <label class="faint-color">Due date</label>
                                            <input kendo-date-picker type="text" ng-model="purchase.due_date" placeholder="Due"
                                                   class="form-control"/>

                                            <label class="faint-color">Received date</label>
                                            <input kendo-date-picker type="text" ng-model="purchase.receive_date" placeholder="Received"
                                                   class="form-control"/>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="faint-color">Note</label>
                                            <input type="text" ng-model="purchase.note" placeholder="Purchase note"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </fieldset>


                                <fieldset class="fieldset bordered">
                                    <legend class="faint-color">Status</legend>
                                    <div class="col-md-12">
                                        <label class="faint-color">Current status</label>
                                        <select ng-model="purchase.status" class="form-control faint-color">
                                            <option ng-value="0" ng-selected="purchase.status == null">Current purchase
                                                status
                                            </option>
                                            <option ng-repeat="r in statuses" ng-value="{{r.id}}"
                                                    ng-selected="r.id == purchase.status">{{r.status}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <label class="faint-color">Paid status</label>
                                            <select ng-model="purchase.paid_status" class="form-control faint-color">
                                                <option ng-value="0" ng-selected="purchase.status == null">Payment
                                                    status
                                                </option>
                                                <option ng-repeat="r in paid_status" ng-value="{{r.id}}"
                                                        ng-selected="r.id == purchase.paid_status">{{r.status}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="faint-color">Status date</label>
                                            <input kendo-date-picker type="text" ng-model="purchase.status_date" placeholder="Status date"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="faint-color">Assigned to</label>
                                        <input type="text" ng-model="purchase.assigned_to" placeholder="Assigned to"
                                               class="form-control"/>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="clearfix"></div>
                            <table class="table table-borderless table-responsive">
                                <thead>
                                <tr>
                                    <th class="dark-bkg">Reference</th>
                                    <th class="dark-bkg">UPC</th>
                                    <th class="dark-bkg" style="width:220px;">Part number</th>
                                    <th class="dark-bkg">Vendor name</th>
                                    <th class="dark-bkg" style="width: 300px;">Description</th>
                                    <th class="dark-bkg">Freight</th>
                                    <th class="dark-bkg">Price</th>
                                    <th class="dark-bkg">Qty.</th>
                                    <th class="dark-bkg">Rec.</th>
                                    <th class="dark-bkg">Cost*</th>
                                    <th class="dark-bkg">price x qty</th>
                                    <th class="dark-bkg" style="width: 50px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th></th>
                                    <th>
                                        <input class="form-control" ng-model="current.upc" ng-change="searchByUPC(current.upc)" ng-disabled="true"/>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.part" ng-change="searchProduct('part',current.part)"/>
                                        <ul class="search nav nav-inverse absolutepos" ng-show="showlist == 'part'">
                                            <li data-ng-repeat="p in searchedProducts">
                                                <a href="javascript:;" class="faint-color" ng-click="selectProduct(p)">{{p.name || p.id}}</a>
                                            </li>
                                        </ul>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.vendor" ng-change="searchProduct('vendor',current.vendor)"/>
                                        <ul class="search nav nav-inverse absolutepos" ng-show="showlist == 'vendor'">
                                            <li data-ng-repeat="p in searchedProducts">
                                                <a href="javascript:;" class="faint-color" ng-click="selectProduct(p)">{{p.name || p.id}}</a>
                                            </li>
                                        </ul>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.name" ng-change="searchProduct('name',current.name)"/>
                                        <ul class="search nav nav-inverse absolutepos" ng-show="showlist == 'name'">
                                            <li data-ng-repeat="p in searchedProducts">
                                                <a href="javascript:;" class="faint-color" ng-click="selectProduct(p)">{{p.name || p.id}}</a>
                                            </li>
                                        </ul>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.freight"/>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.price" ng-change="updateTotal(current)"/>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.qty" ng-change="updateTotal(current)"/>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.rec" ng-disabled="true"/>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.cost" ng-disabled="true"/>
                                    </th>
                                    <th>
                                        <input class="form-control" ng-model="current.total""/>
                                    </th>
                                    <th class="valign">
                                        <a href="javascript:;" ng-click="pushProduct(current)">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </a>
                                    </th>
                                </tr>
                                <tr ng-repeat="(k,p) in selected_products">
                                    <th class="faint-color text-left"></th>
                                    <th class="faint-color text-left"></th>
                                    <th class="faint-color text-right" style="width:220px;">{{ p.part }}</th>
                                    <th class="faint-color text-right">{{p.vendor}}</th>
                                    <th class="faint-color text-right" style="width: 300px;">{{ p.name }}</th>
                                    <th class="faint-color text-right">{{ p.freight  | number_format}}</th>
                                    <th class="faint-color text-right">{{ p.price  | number_format}}</th>
                                    <th class="faint-color text-right">{{ p.qty }}</th>
                                    <th class="faint-color text-left"></th>
                                    <th class="faint-color text-left"></th>
                                    <th class="faint-color text-right">{{ p.total | number_format }}</th>
                                    <th class="faint-color text-center">
                                        <a href="javascript:;" ng-click="popProduct(k)">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </a>&nbsp;
                                        <a href="javascript:;" ng-click="editProduct(k)">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th class="valign faint-color text-right">Net total</th>
                                    <th class="valign faint-color">{{net_total | number_format}}</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th class="valign faint-color">Discount (%)</th>
                                    <td class="valign faint-color">
                                        <input type="text" class="form-control" placeholder="Discount %" ng-model="scope.purchase.discount_per" ng-change="calcDiscount(scope.purchase.discount_per)"/>
                                    </td>
                                    <th class="valign faint-color">{{discount | number_format}}</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th class="valign faint-color text-right">Tax (15%)</th>
                                    <th class="valign faint-color">{{purchase.tax | number_format}}</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th class="valign faint-color text-right">Gross total</th>
                                    <th class="valign faint-color">{{purchase.total | number_format}}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="row">
                <table class="table">
                    <tr>
                        <td>
                            <button class="btn btn-success" type="submit"><span
                                    class="glyphicons glyphicons-plus"></span> Save
                            </button>
                        </td>
                        <td>
                            <button type="reset" class="btn btn-danger">
                                <span class="glyphicons glyphicons-remove-2"></span> Reset
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div><!-- container -->
<hr>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    var app = angular.module('app', ['kendo.directives','hitech.filters']);
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/purchases/edit.js'); ?>"></script>
