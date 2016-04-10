<div class="container" ng-scope ng-app="products" ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3>BULK PRICE UPDATE</h3>
    </div>
    <span ng-model="bulkPriceUpdate.id" ng-value="bulkPriceUpdate.id" ng-checked="bulkPriceUpdate(bulkPriceUpdate.id)"
          ng-init='bulkPriceUpdate.id=<?= json_encode($product_data); ?>'></span>

    <div class="col-md-12">
        <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="updateProductPrices()">
            <table class="table table-bordered faint-border">
                <tr>
                    <th class="faint-color">Store Price</th>
                    <th class="faint-color">Cost (inc gst)</th>
                    <th class="faint-color">Special price</th>
                    <th class="faint-color">Website Price</th>
                    <th class="faint-color">Dealer price</th>
                    <th class="faint-color">Buy now price</th>
                    <th class="faint-color">Start price</th>
                    <th class="faint-color">Reserve price</th>
                    <th class="faint-color">Offer price</th>
                </tr>
                <tbody data-ng-repeat="item in bulkPriceUpdate.data track by $index">
                <tr>
                    <th colspan="9" class="center-text faint-bkg faint-color">{{item.name}}</th>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" ng-model="item.store_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.cost_inc_gst"></td>
                    <td><input type="text" class="form-control" ng-model="item.special_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.website_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.delaer_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.buy_now_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.start_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.reserve_price"></td>
                    <td><input type="text" class="form-control" ng-model="item.offer_price"></td>
                </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/products/index.js'); ?>"></script>