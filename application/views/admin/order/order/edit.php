<script type="text/javascript" src="<?php echo base_url('assets/angular/jobs/edit.js');?>"></script>
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
            <form class="jobsForm" role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addNewOrder()">
                <div class="row">
                    <h2 class="cdttl"> Order : </h2>
                    
                    <div class="form-group col-sm-12">
                        <label for="text">Search Product</label>
                        <table class="table">
                            <tr>
                                <td>
                                    <input type="text" class="form-control"
                                       ng-model="search_product"
                                       ng-change="searchProduct(search_product)"
                                       placeholder="Search a product"/>                                    
                                    <ul 
                                        ng-show="searchedProduct.length">
                                        <li ng-click="addProduct(product)"
                                            ng-repeat="product in searchedProduct">
                                            {{product.name}}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table> 
                        <table class="table ng-show" id="product_table">
                        <?php $a = 0; ?>
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="a in order.product">
                                    <td>
                                        {{a.id}}
                                    </td> 
                                    <td style="vertical-align:middle">
                                        {{ a.name }}  <!-- <input type="button" id="delete" value="delete" onclick="deleteAccessories(this)"> -->
                                        
                                    </td>                                    
                                    <td><button ng-click="deleteProduct($id)">Delete</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="text">Search Customer</label>
                        <table class="table">
                            <tr>
                                <td>
                                    <input type="text" class="form-control"
                                       ng-model="search_customer"
                                       ng-change="searchCustomer(search_customer)"
                                       placeholder="Search a customer"/>                                    
                                    <ul 
                                        ng-show="searchedCustomer.length">
                                        <li ng-click="addCustomer(customer)"
                                            ng-repeat="customer in searchedCustomer">
                                            {{customer.name}}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table> 
                        <table class="table ng-show" id="product_table">
                        <?php $a = 0; ?>
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Customer Name</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="a in order.customer">
                                    <td>
                                        {{a.id}}
                                    </td> 
                                    <td style="vertical-align:middle">
                                        {{ a.name }}  <!-- <input type="button" id="delete" value="delete" onclick="deleteAccessories(this)"> -->
                                        
                                    </td>                                    
                                    <td><button ng-click="deleteCustomer($id)">Delete</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="text">Payment Type</label>
                        <select ng-model="order.payment_type">
                            <option ng-repeat="item in order.payment_type">{{item.type}}</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="text">Shipping Type</label>
                        <select ng-model="order.shipping_type">
                            <option ng-repeat="item in order.shipping_type">{{item.type}}</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="text">Shipping Description</label>
                        <textarea ng-model="order.shipping_description"
                           class="ckeditor" ng-value="order.shipping_description" 
                            placeholder="Enter shipping description"></textarea>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="text">Quantity</label>
                        <input type="text" class="form-control" id="text"
                            placeholder="Enter product quantity"
                            ng-model="order.quantity">
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
                </div>
                <div class="clear"></div><!-- faculty device end -->
          </form>
        </div><div class="clear"></div>
    </div><div class="clear"></div><!-- wrapper end -->

<script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
        async="async"></script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/order/edit.js'); ?>"></script>