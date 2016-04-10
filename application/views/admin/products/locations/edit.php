<script src="<?php echo base_url('assets/themes/default/js/notify.min.js');?>"></script>
<style type="text/css">
    .nav.spacing li{
        margin:0 5px;
    }
    .whitebkg{
        background-color: #FFF;
    }
</style>
<div class="container" ng-controller="locationCtrl" ng-scope>
    <form>
         <div class="row">
             <div class="col-md-5">
                 <fieldset>
                     <legend>General</legend>
                     <div class="form-group col-md-4">
                         <label>Part number (code)</label>
                         <input class="form-control" ng-model="location.code"/>
                         <span class="hint">Part number is a unique identifier for this product and is used for invoicing and reporting purposes</span>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-md-4">
                         <label>Manufacturer code (MPN/ISBN)</label>
                         <input class="form-control" ng-model="location.manufacturer"/>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-md-4">
                         <textarea class="form-control" placeholder="Text here"></textarea>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-md-4">
                         <textarea class="form-control" placeholder="Text here"></textarea>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-md-4">
                         <label>Description</label>
                         <input class="form-control" ng-model="location.description"/>
                         <span class="hint">This description is shown by default in the memo field of invoice line items.</span>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-md-4">
                         <label>Vendor category</label>
                         <select ng-model="location.category">
                             <option ng-value="1">Test</option>
                             <option ng-value="2">Test</option>
                             <option ng-value="3">Test</option>
                         </select>
                     </div>
                     <div class="clearfix"></div>
                 </fieldset>
                 <div class="clearfix"></div>
                 <fieldset>
                     <legend>Price/Cost</legend>
                     <div class="form-group col-md-4">
                         <label>Main price</label>
                         <input class="form-control" ng-model="location.main_price"/>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-md-4">
                         <input type="checkbox" ng-model="location.use_cost"/>
                         <label>Use cost from last last received purchase order.</label>
                     </div>
                     <div class="clearfix"></div>
                     <div class="col-md-12 form-group">
                         <div class="col-md-3">
                             <div class="form-group col-md-12">
                                 <label class="col-md-12">Cost (ex.GST)</label>
                                 <input type="checkbox" ng-model="location.ex_cost"/>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group col-md-12">
                                 <label class="col-md-12">Cost (inc.GST)</label>
                                 <input type="checkbox" ng-model="location.inc_cost"/>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group col-md-12">
                                 <label class="col-md-12">Markup</label>
                                 <input type="checkbox" ng-model="location.markup"/>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group col-md-12">
                                 <button ng-click="updatePriceFromMarkup(location)">Update price from markup</button>
                             </div>
                         </div>
                     </div>
                 </fieldset>
                 <div class="clearfix"></div>
                 <fieldset>
                     <legend>Stock control</legend>
                     <div class="form-group col-md-6">
                         <label>Safety stock level</label>
                         <input class="form-control" ng-model="location.stock_level"/>
                     </div>
                 </fieldset>

             </div>
             <div class="col-md-5">
                 <fieldset>
                     <legend>Warehouse/Stock locations</legend>
                     <div class="col-md-12">
                         <div class="col-md-8">
                             <div class="form-group col-md-7">
                                 <label>Warehouse</label>
                                 <select ng-model="location.warehouse">
                                     <option ng-value="1">One</option>
                                     <option ng-value="2">One</option>
                                     <option ng-value="3">One</option>
                                 </select>
                             </div>
                             <div class="clearfix"></div>
                             <hr/>
                             <div class="col-md-12">
                                 <p>
                                     <span class="glyphicon glyphicon-" ng-click="addAnotherLocation()"></span> Add another location
                                 </p>
                                 <p>
                                     <input type="checkbox" ng-model="location.location"/>
                                     &nbsp;
                                     <input type="text" ng-model="location.startsearc" placeholder="Search"/>
                                     &nbsp;
                                     <span class="glyphicon glyphicon-" ng-click="removeLocation()"></span>
                                 </p>
                             </div>
                         </div>
                         <div class="col-md-4"></div>
                     </div>
                 </fieldset>
                 <div class="clearfix"></div>
                 <div class="form-group col-md-12">
                     <textarea class="form-control" ng-model="location.snos" placeholder="Add multiple serial numbers"></textarea>
                 </div>
                 <fieldset class="whitebkg">
                     <legend>Compatibility</legend>
                     <div class="col-md-12">
                         <div class="col-md-3">Compatible with</div>
                         <div class="col-md-9">
                             <div class="col-md-4">
                                 <input type="checkbox" ng-model="location.vendor"/> Vodafone
                             </div>
                             <div class="col-md-4">
                                 <input type="checkbox" ng-model="location.vendor"/> BSNL
                             </div>
                             <div class="col-md-4">
                                 <input type="checkbox" ng-model="location.vendor"/> Airtel
                             </div>
                             <div class="col-md-4">
                                 <input type="checkbox" ng-model="location.vendor"/> Skiny
                             </div>
                         </div>
                     </div>
                 </fieldset>
                 <div class="clearfix"></div>
                 <fieldset class="whitebkg">
                     <legend>Vendor</legend>
                     <div class="form-group col-md-4">
                         <label>Vendor</label>
                         <input type="text" class="form-control" placeholder="Search vendor"/>
                     </div>
                 </fieldset>
                 <div class="clearfix"></div>
                 <fieldset class="whitebkg">
                     <legend>Shipping</legend>
                     <div class="col-md-12 form-group">
                         <div class="col-md-6">Shipping required?</div>
                         <div class="col-md-6">
                             <input type="checkbox" ng-model="location.shipping_required" ng-value="1"/>Yes
                             &nbsp;
                             <input type="checkbox" ng-model="location.shipping_required" ng-value="0"/>No
                         </div>
                     </div>
                     <div class="col-md-12 form-group">
                         <div class="col-md-6">Standard Shipping</div>
                         <div class="col-md-6">
                             <input class="form-control" type="text" ng-model="location.standard_shipping" ng-value="10"/
                         </div>
                     </div>
                     <div class="col-md-12 form-group">
                         <div class="col-md-6">Secondary Shipping</div>
                         <div class="col-md-6">
                             <input class="form-control" type="text" ng-model="location.secondary_shipping" ng-value="10"/
                         </div>
                     </div>
                     <div class="col-md-12 form-group">
                         <div class="col-md-6">International Standard Shipping</div>
                         <div class="col-md-6">
                             <input class="form-control" type="text" ng-model="location.international_standard_shipping" ng-value="10"/
                         </div>
                     </div>
                     <div class="col-md-12 form-group">
                         <div class="col-md-6">International secondary Shipping</div>
                         <div class="col-md-6">
                             <input class="form-control" type="text" ng-model="location.international_secondary_shipping" ng-value="10"/
                         </div>
                     </div>
                 </fieldset>
             </div>
             <div class="col-md-2">
                 <fieldset>
                     <legend>Attributes</legend>
                     <div class="form-group col-md-12">
                         <label class="col-md-12">ID</label>
                         <input type="text" class="form-contro" ng-model="location.id" placeholder="ID"/>
                         <input type="checkbox" ng-model="location.unknown"/> This will remove the product from sale from all marketplaces (auction site or website).
                     </div>
                 </fieldset>

                 <fieldset>
                     <legend>Stock</legend>
                     <div class="form-group col-md-12">
                         <label class="col-md-12">Warehouse/onshelf stock</label>
                         <input type="text" class="form-contro" ng-model="location.stock"/>
                     </div>
                     <div class="form-group col-md-12">
                         <label class="col-md-12">Available to sell</label>
                         <input type="text" class="form-contro" ng-model="location.available_qty"/>
                     </div>
                 </fieldset>

                 <fieldset>
                     <legend>Product TM report</legend>
                     <div class="form-group col-md-12">
                         <p>Total auctions: 0</p>
                         <p>Total sales: 0</p>
                         <p>Sell through: 0.00%</p>
                     </div>
                     <div class="clearfix"></div>
                     <hr/>
                     <div class="form-group col-md-12">
                         <p>Promo/Listing fees: 0</p>
                         <p>Success fees: 0</p>
                         <p>Paynow fees: 0</p>
                         <p>Total fees: 0</p>
                     </div>
                     <div class="clearfix"></div>
                     <hr/>
                     <div class="form-group col-md-12">
                         <p>Total sales: 0</p>
                         <p>Total sales-fees: 0</p>
                         <p>Avg. sale: 0</p>
                         <p>Avg. sale-fees: 0</p>
                         <p>Fees as percentage: 0.00%</p>
                     </div>
                     <p>Report is for the last 60 days. Details report <a href="javascript:;">click here</a></p>
                 </fieldset>
                 <div class="clearfix"></div>
                 <fieldset>
                     <legend>Product type</legend>
                     <div class="form-group col-md-12">
                         <p>
                            <input type="checkbox" ng-model="location.type" ng-value="Inventory"/>
                         &nbsp; Inventory
                         </p>
                         <p>
                             <input type="checkbox" ng-model="location.type" ng-value="Non-inventory"/>
                             &nbsp; Non-inventory
                         </p>
                         <p>
                             <input type="checkbox" ng-model="location.type" ng-value="Assembly"/>
                             &nbsp; Assembly (advanced)
                         </p>
                         <p>
                             <input type="checkbox" ng-model="location.type" ng-value="Virtual"/>
                             &nbsp; Virtual (advanced)
                         </p>
                         <hr/>
                         <p>
                             <input type="checkbox" ng-model="location.allowasaccessory"/> Allow as accessory
                         </p>
                     </div>
                 </fieldset>
             </div>
         </div>
        <div class="row">
            <div class="form-group col-md-5">
                <ul class="nav navbar-nav spacing pull-left">
                    <li role="presentation">
                        <button class="btn btn-success" ng-click="saveLocation()"><span class="glyphicons glyphicons-plus"></span> Save</button>
                    </li>
                    <li role="presentation">
                        <button href="" class="btn btn-danger" ng-click="cancelAdd()"><span class="glyphicons glyphicons-remove-2"></span> Cancel</button>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>