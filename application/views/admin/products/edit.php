<style type="text/css">
    .container {
        width: 100%;
    }
    .box {
        margin-top: 15px;
        background: yellow;
        padding: 10px;
        border: solid 1px #ccc;
        border-radius: 5px
    }
    .l-box {
        border: solid 1px #ccc;
        border-radius: 5px;
        padding: 10px;
    }
    .col-md-6 {
        padding: 0;
    }
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        border-radius: 5px;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
    }
    legend.scheduler-border {
        width: inherit; /* Or auto */
        padding: 0 10px; /* To give a bit of padding on the left and right */
        border-bottom: none;
    }
    hr {
        border-top-style: dashed;
        color: #aaa;
    }
</style>
<style type="text/css">
    .nav.spacing li {
        margin: 0 5px;
    }
    .search-res {
        background-color: #fff;
        list-style: outside none none;
        margin: 0 auto;
        padding: 0;
        width: 100%;
        border: none;
    }
    .search-res li {
        padding: 10px;
        background-color: #00113a;
        border-bottom: 1px solid #fff;
        cursor: pointer;
        color: #FFF;
        opacity: 0.8;
    }
    .search-res li:hover {
        opacity: 1.0;
    }
    .search-res li a {
        color: #FFF;
    }
    .multi-dropdown li {
        cursor: pointer;
        padding: 5px;
    }
    .multi-dropdown li:hover {
        background-color: #eee;
    }
    .multi-dropdown li:hover a {
        background-color: #eee !important;
    }
    .multi-dropdown li.selected a:link, .multi-dropdown li.selected a:visited {
        background-color: none;
    }
    .multi-dropdown li.selected {
        background-color: #ddd;
        /*color:#FFF;*/
        border-bottom: 1px solid #fff;
    }
    .multi-dropdown li.unselected {
        background-color: none;
        color: #000;
    }
    .btn-group button {
        min-width: 150px;
    }
    .upload-button {
        cursor: pointer;
    }
    li.ui-widget-content {
        width: 100%;
        height: 30px;
        list-style-type: none;
        background-color: #eee;
        border-bottom: 1px solid white;
        padding-left: 10px;
        padding-top: 5px;
    }
    ul.list2 {
        padding: 0;
        margin: 10px 0 auto;
    }
</style>
<!-- Main Content -->
<script src="<?php echo base_url(); ?>assets/dd.js"></script>
<script src="<?php echo base_url(); ?>assets/themes/default/js/angular-file-upload-shim.js"></script>
<script src="<?php echo base_url(); ?>assets/themes/default/js/angular-file-upload.js"></script>
<script src="<?php echo base_url(); ?>assets/themes/default/js/notify.min.js"></script>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    var postUrl = '<?php echo base_url('api/products/edit'); ?>';
<?php if ($this->uri->segment(4) > 0) 
{ ?>
        var updateSelected = false;
        var editMode = true;
        var editId = <?php echo $this->uri->segment(4); ?>;        
<?php } else
{ ?>
        var editMode = false;
        var updateSelected = false;
<?php } ?>
<?php if ($this->uri->segment(3) == "copy") { ?>
    var updateSelected = true;
<?php }; ?>
</script>
<div class="container" id="container" ng-controller="productEditCtrl" ng-scope="scope" data-ng-init="init()">
    <h3 ng-if="product.product.name">
        Editing currently: {{product.product.name}}
    </h3>
    <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addProduct()">
        <div class="table-responsive">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="active">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
                    </li>
                    <li role="presentation">
                        <a href="#photos" aria-controls="photos" role="tab" data-toggle="tab">Photos / SEO</a>
                    </li>
                    <li role="presentation">
                        <a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Accessories</a>
                    </li>
                    
                    <li role="presentation">
                        <a href="#priceSpy" aria-controls="priceSpy" role="tab" data-toggle="tab">Pricespy</a>
                    </li>
                    <li class="top-right-button">
                        <input type="submit" class="btn btn-success" value="Save" ng-disabled="disabled"/>
                        <input type="reset" class="btn btn-danger" value="Reset" ng-disabled="disabled"/>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general">
                        <div class="col-md-10">
                            <div class="col-md-6">
                                <div class="">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">General</legend>
                                        <div class="form-group">
                                            <label for="text">Part no code</label>
                                            <input type="text" class="form-control" id="text"
                                                   placeholder="Enter product code"
                                                   ng-model="product.product.unique_code">
                                            <span class="help-inline">Part no a unique identifier</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="text">Product name</label>
                                            <input type="text" class="form-control" id="text"
                                                   placeholder="Name of the product"
                                                   ng-model="product.product.name" ng-value="product.product.name">
                                        </div>
                                        <div class="form-group">
                                            <label for="text">Manufacturer code</label>
                                            <input type="text" class="form-control" id="text"
                                                   placeholder="Enter manufacturer code"
                                                   ng-model="product.product.manufacturer_code">
                                        </div>
                                        <div class="form-group">
                                            <label for="text" class="col-md-12">Website Category</label>
                                            <span data-ng-repeat="item in rootCats[0]">
                                                <label> <input type="checkbox" name="product.categories[0][]"
                                                               value="{{item.id}}" ng-click="pushCategories(item.id)"
                                                               ng-checked="showCatChecked(item.id)"> {{item.name}} &nbsp;
                                                </label>
                                            </span>
                                            <div class="row">
                                                <select name="product.categories[1][]" size="8" class="col-sm-5"
                                                        multiple="multiple" ng-model="product.categories[1]"
                                                        ng-change="pushCategories1(item.id)" style="margin-left:15px">
                                                    <option value="{{item.id}}" data-ng-repeat="item in rootCats[1]">
                                                        {{item.name}} &nbsp;</option>
                                                </select>
                                                <ul class='list1 nav' droppable
                                                    style="width:200px; float:right; margin-right:40px;">
                                                    <li class="ui-widget-content" data-ng-repeat="item in rootCats[2]"
                                                        data-index="{{$index}}" data-reject="{{item.reject}}" draggable>
                                                        {{item.name}}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 no-padding">
                                                <ul class='list2' droppable style="min-height:170px;width:100%;"
                                                    name="product.main_category[]" class="ui-widget-content">
                                                    <li class="ui-widget-content" data-ng-repeat="item in list2"
                                                        data-index="{{$index}}" data-reject="{{item.reject}}"
                                                        name="product.main_category"
                                                        ng-model="product.product_categories" draggable>{{item.name}}
                                                        <button type="button" class="btn btn-default btn-sm pull-right"
                                                                ng-click="deleteCategories($index)">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="text">Videos</label>
                                            
                                            <input type="text" step="any" class="form-control" id="text"
                                                           placeholder="Add video"
                                                           ng-model="product.product.video">
                                        </div>
                                        <div class="form-group">
                                            <label for="text">Description</label>
                                            <textarea class="ckeditor" name="short_description" ng-model="product.product.short_description"></textarea>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="" style="margin-left: 10px;">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Price / Cost</legend>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="text">Main price(GST)</label>
                                                <input class="form-control" id="text" placeholder="Enter store price"
                                                       ng-change="updateMarkup(product.prices)"
                                                       ng-blur="updateMarkup(product.prices)"
                                                       ng-model="product.prices.store_price" type="text" step="any">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text">Cost(inc GST)</label>
                                                    <input type="text" step="any" class="form-control" id="text"
                                                           placeholder="Cost including GST"
                                                           ng-model="product.prices.cost_inc_gst">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label><input type="checkbox" ng-value="1"
                                                              ng-model="product.prices.use_last_purchse_order" ng-checked="true">Use cost
                                                    from last received purchase order</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text">Website Price</label>
                                                    <input class="form-control" id="text"
                                                           placeholder="Website price"
                                                           ng-model="product.prices.website_price" type="text"
                                                           step="any">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text">Special Price</label>
                                                    <input type="text" step="any" class="form-control" id="text"
                                                           placeholder="Special price"
                                                           ng-model="product.prices.special_price">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text">Dealer Price</label>
                                                    <input type="text" step="any" class="form-control" id="text"
                                                           placeholder="Delaer price"
                                                           ng-model="product.prices.delaer_price"
                                                           ng-change="updateStorePrice(product.prices)"
                                                           ng-blur="updateStorePrice(product.prices.delaer_price)"/>
                                                </div>
                                            </div>
                                        </div
                                    </fieldset>
                                </div>
                                <div class="" style="margin-left: 10px;">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Warehouse / Stock Locations</legend>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="text">Warehouse</label>
                                                <select class="form-control" id="text"
                                                        ng-model="product.product.warehouse">
                                                    <option ng-repeat="w in warehouse" ng-value="w.sn"
                                                            ng-selected="showSelectedWarehouse(w.sn)">{{w.name}}
                                                    </option>
                                                </select>
                                                <span class="help-inline">Detailed warehouse help <a
                                                        href="">here</a></span>
                                            </div>
                                            <hr>
                                            <p>Product locations:</p>
                                            <p><a href="javascript:;" title="Add another location"
                                                  ng-click="addLocation()"><span
                                                        class="glyphicons glyphicons-circle-plus"></span></a></p>
                                            <span class="help-inline">Detailed location help
                                                <a href="javascript:;">here</a>
                                            </span>
                                            <div class="radio">
                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                   ng-model="location_code"
                                                                   ng-change="searchLocation(location_code)"
                                                                   placeholder="Search a location by code"/>
                                                            <ul class="navbar navbar-inverse search-res"
                                                                ng-show="searchedLocations.length">
                                                                <li ng-click="addLocation(location)"
                                                                    ng-repeat="location in searchedLocations">
                                                                    {{location.code}}
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="table table-stripped" ng-show="product.location.length">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Location code</th>
                                                            <th>Description</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="proLocation in product.location">
                                                            <td style="vertical-align:top;text-align: center"><input
                                                                    type="radio" ng-model="product.location.default"
                                                                    ng-value="proLocation.index"/></td>
                                                            <td style="vertical-align:middle">
                                                                {{proLocation.code}}
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                {{proLocation.description}}
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-default btn-sm pull-right" ng-click="deleteProductLocation($index)">
                                                                    <span class="glyphicon glyphicon-remove"></span> 
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="border-left:1px solid">
                                            <div class="form-group">
                                                <label for="text">Buy Now</label><label>
                                                    <input type="text" step="any" class="form-control" id="text"
                                                           placeholder="Buy now price"
                                                           ng-model="product.prices.buy_now_price"
                                                           ng-Change="fileNameChanged(product.prices.buy_now_price)">
                                                    <input type="checkbox" ng-value="1"
                                                           ng-model="product.prices.buy_now" ng-checked="true">Enable
                                                    Buy Now</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="text">Start</label><label>
                                                    <input type="text" class="form-control" id="text"
                                                           placeholder="Start price"
                                                           ng-model="product.prices.start_price" step="any">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Reserve</label><label>
                                                            <input type="text" class="form-control" id="text"
                                                                   placeholder="Reserve price" step="any"
                                                                   ng-model="product.prices.reserve_price">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="text">Offer</label><label>
                                                                    <input type="text" step="any" class="form-control" id="text"
                                                                           placeholder="Offer price"
                                                                           ng-model="product.prices.offer_price">
                                                                    <input type="checkbox" ng-value="1"
                                                                           ng-model="product.prices.offer_buy_now" ng-checked="true">Enable Offer
                                                                </label>
                                                            </div>
                                                    </div>
                                                    </fieldset>
                                            </div>
                                            <div class="" style="margin-left:10px">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border">Compatibility</legend>
                                                    <div class="col-md-5">
                                                        <p>Compatible with</p>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label ng-repeat="(k,c) in compatibility" class="col-md-4">
                                                                    <input type="checkbox" ng-model="compatibles[k]"
                                                                           ng-change="addcompatibility(c.id)"
                                                                           ng-value="{{c.id}}"
                                                                           ng-checked="showCheckedCompatibles(c.id)">{{c.name}}</label>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="" style="margin-left:10px">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border">Vendors</legend>
                                                    <select class="form-control"
                                                            ng-model="product.vendors">
                                                        <option ng-repeat="item in product.vendors" value="{{item.name}}"
                                                                ng-selected="showSelectedVendors(item.name)">{{item.name}}
                                                        </option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="" style="margin-left:10px">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border">Shipping details</legend>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-7" for="email">Shipping required:</label>
                                                        <!-- product.shipping -->
                                                        <div class="col-sm-5">
                                                            <input type="radio" ng-model="product.shipping.shipping_required"
                                                                   ng-value="1" ng-checked="true"><span>Yes</span>
                                                            <input type="radio" ng-model="product.shipping.shipping_required"
                                                                   ng-value="0"><span>No</span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-7" for="">Standard Shipping</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" step="any" class="form-control" id="text"
                                                                   placeholder="Standard shipping price"
                                                                   ng-model="product.shipping.standard_shipping_price">
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-7" for="">Secondary Shipping</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" step="any" class="form-control" id="text"
                                                                   placeholder="Secondary shipping price"
                                                                   ng-model="product.shipping.secondary_shipping_price">
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-7" for="">International Standard
                                                            Shipping</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" step="any" class="form-control" id="text"
                                                                   placeholder="International standard shipping price"
                                                                   ng-model="product.shipping.intl_standard_shipping_price">
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-7" for="">International Secondary
                                                            Shipping</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" step="any" class="form-control" id="text"
                                                                   placeholder="International secondary shipping price"
                                                                   ng-model="product.shipping.intl_seconday_shipping_price">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Attributes</legend>
                                            <div class="form-group">
                                                <label for="text">id</label>
                                                <input type="number" class="form-control" id="text" placeholder="Product id"
                                                       value="{{product.product.id}}" ng-disabled="true"/>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" ng-value="1" ng-model="product.product.status" ng-checked="1">Inactive</label>
                                                </div>
                                                <span class="help-inline">This will remove this product from all market places(auction site or website)</span>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" ng-value="1" ng-model="product.product.publish_website" ng-checked="1">Publish on website</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Stock</legend>
                                            <div class="form-group">
                                                <label for="text">Warehouse / on shelf stock</label>
                                                <input type="text" class="form-control" id="text" placeholder="onshelf stock" ng-model="product.stock.onshelf_stock" ng-readonly="true">
                                            </div>
                                            <div class="form-group">
                                                <label for="text">Available (to sell)</label>
                                                <input type="text" class="form-control" id="text" placeholder="Available to sell" ng-model="product.stock.available_to_sell" ng-readonly="true">
                                            </div>
                                            <!--<div class="form-group">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" ng-value="1" ng-model="product.stock.available_to_stock">Inactive</label>
                                                </div>
                                                <span class="help-inline">This will remove this product from all market places(auction site or website)</span>
                                            </div>-->
                                            <div class="form-group">
                                                <label for="text">Safety stock control</label>
                                                <input type="text" class="form-control" id="text"
                                                       placeholder="Safety stock control"
                                                       ng-model="product.stock.safety_stock">
                                                <span class="help-inline"><p>When your safety level is reached. Hitech treats
                                                        the item as if it is out of stock.</p
                                                        ><!--<p>Detailed help <a href="">here</a> </p>--></span>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Product Type</legend>
                                            <div class="radio">
                                                <label><input type="radio" ng-model="product.product.product_type" ng-value="1">Inventory</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" ng-model="product.product.product_type" ng-value="2">Virtual
                                                    (Advanced)</label>
                                            </div>
                                            <hr>
                                            <div class="checkbox">
                                                <label><input type="checkbox" ng-model="product.product.is_accessory"
                                                              ng-value="1" ng-checked="product.product.is_accessory == 1"
                                                              ng-change="showCheckedAccessory(product.product.is_accessory)">Allow
                                                    as Accessory</label>
                                                <label class="control-label col-sm-12" for="email">Featured Product</label>
                                                <!-- product.shipping -->
                                                <div class="col-sm-12">
                                                    <input type="radio" ng-model="product.product.featured_product"
                                                           ng-value="1"><span>Yes</span>
                                                    <input type="radio" ng-model="product.product.featured_product"
                                                           ng-value="0"><span>No</span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="photos">
                                <table class="table  table-bordered attachments">
                                    <tr>
                                        <td colspan="2">
                                            <b>Photos</b>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-success" ng-click="addProductPhoto()">Add</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Preview</td>
                                        <td> File</td>
                                        <td> Description</td>
                                        <td> Default</td>
                                        <td> Auction</td>
                                        <td> Website</td>
                                        <td> Remove</td>
                                    </tr>
                                    <tr ng-repeat="item in product_photos">
                                        <td>
                                            <span ng-if="item.attachment != null">
                                                <img width="50" ng-src="{{item.attachment.image.src}}"/>
                                            </span>
                                            <span ng-if="item.name.length > 0">
                                                <img width="50"
                                                     ng-src="<?php echo base_url('uploads/products/'); ?>/{{admin}}/{{item.product_id}}/{{item.name}}"/>
                                            </span>
                                        </td>
                                        <td>
                                            <div ng-file-select="fileSelected(files,$index,$event)" ng-model="files"
                                                 class="upload-button" ng-multiple="false" ng-accept="',*.jpg,*.png'"
                                                 ng-model-rejected="rejFiles" tabindex="0">Upload image
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" ng-model="item.description"/>
                                        </td>
                                        <td>
                                            <label><input type="radio" ng-model="item.is_default" ng-value="1"
                                                          name="is_default"/>&nbsp;
                                                default</label>
                                        </td>
                                        <td>
                                            <label><input type="checkbox" ng-model="item.is_auction" ng-value="1"/>&nbsp;
                                                auction</label>
                                        </td>
                                        <td>
                                            <label><input type="checkbox" ng-model="item.is_website" ng-value="1"/>&nbsp;
                                                website</label>
                                        </td>
                                        <td>
                                            <a href="javascript:;" ng-click="deletePhoto($index, item)"><span
                                                    class="glyphicon glyphicon-remove"></span></a>
                                        </td>
                                    </tr>
                                </table>
                                <div class="container" style="padding:20px">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label>Meta Title</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" ng-model="product.seo.seo_title" name="meta_title"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="container" style="padding:20px">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label>Meta Keywords</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" ng-model="product.seo.seo_keywords" name="meta_keywords"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="container" style="padding:20px">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label>Meta Descriptions</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <textarea name="meta_descriptions" class="form-control"
                                                      ng-model="product.seo.seo_description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="accessories">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control"
                                                   ng-model="search_accessory"
                                                   ng-change="searchAccessory(search_accessory)"
                                                   placeholder="Search a accessory"/>
                                            <ul class="navbar navbar-inverse search-res"
                                                ng-show="searchedAccessory.length">
                                                <li ng-click="addAccesory(accesorys)"
                                                    ng-repeat="accesorys in searchedAccessory">
                                                    {{accesorys.name}}
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table ng-show" id="accessories_table">
                                    <?php $a = 0; ?>
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Accesory Name</th>
                                            <th>Product Code</th>
                                            <th>Product Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="a in product.accessories">
                                            <td>
                                                {{a.index}}
                                            </td>
                                            <td style="vertical-align:middle">
                                                {{ a.name}}
                                                <!-- <input type="button" id="delete" value="delete" onclick="deleteAccessories(this)"> -->
                                            </td>
                                            <td>
                                                {{a.code}}
                                            </td>
                                            <td> <!-- $this->_userSN -->
                                <?php $this->_loggedIn = $this->session->userdata('logged_hitech_admin_id'); ?>
                                                <img
                                                    src="<?php echo base_url() . 'uploads/products/' . $this->_loggedIn . '/{{a.photo_id}}/{{a.image_name}}-{{a.photo_id}}.jpg'; ?>"
                                                    width=90>
                                            </td>
                                            <td>
                                                <button ng-click="deleteAccessories($index)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="priceSpy">
                                <div class="row faint-bkg">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="col-md-12">
                                            <h3 class="faint-color">Pricespy info</h3>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="col-md-12">
                                                <label class="faint-color">Price Spy URL</label>
                                                <div class="col-md-6">
                                                    <input class="form-control" placeholder="Pricespy url" ng-model="product.pricespy.url" type="text"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="faint-color">Lowest rate</label>
                                                <div class="col-md-6">
                                                    <input class="form-control" placeholder="Pricespy lowest rate" ng-model="product.pricespy.lowest_rate" type="text"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="faint-color">Decrease by</label>
                                                <div class="col-md-6">
                                                    <input class="form-control" placeholder="Price decrease by" ng-model="product.pricespy.decrease_by" type="text"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="faint-color">Dealers</label>
                                                <div class="col-md-6">
                                                    <label class="col-md-4 faint-color" ng-repeat="d in pricespy_dealers">
                                                        <input type="checkbox" ng-value="{{d.id}}" ng-checked="selectedDealers.indexOf(d.id) > -1" ng-click="toggleSelectedDealers(d.id)"/>
                                                        {{d.first_name}} {{d.last_name}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="faint-color">Lowest rate dealer</label>
                                                <div class="col-md-6">
                                                    <select ng-model="product.pricespy.lowest_rate_dealer" class="form-control">
                                                        <option ng-value="0" ng-selected="product.pricespy.lowest_rate_dealer==null">Select a dealer</option>
                                                        <option  data-ng-repeat="d in pricespy_dealers" ng-value="{{d.id}}" ng-selected="product.pricespy.lowest_rate_dealer == d.id">{{d.first_name}} {{d.last_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                </form>
            </div><!-- container -->
            <hr>
            <script type="text/javascript">
                var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
                var app = angular.module('app', ['ngFileUpload']);
            </script>
            <script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
            async="async"></script>
            <script type="text/javascript" src="<?php echo base_url('assets/angular/products/edit.js'); ?>"></script>
