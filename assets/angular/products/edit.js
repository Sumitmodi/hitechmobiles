'use string';
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}
// for serial key
var serialKeys = [];
app.controller('productEditCtrl', ['$scope', '$upload', '$requestFactory', function ($scope, $upload, $request) {
    $scope.cats = [];
    $scope.list1 = [];
    $scope.list2 = [];
    $scope.vendors = [];
    $scope.warehouse = [];
    $scope.rootCats = [];
    $scope.rootCatsWhileEdit= [];
    $scope.productLocations = [];
    $scope.accessory = [];
    $scope.searchedLocations = [];
    $scope.searchedAccessory = [];
    $scope.search_accesory = [];
    $scope.cat = {sel: [[], [], []]};
    $scope.pre_text = 'Select category';
    $scope.product = {
        categories: [[], [], []],
        compatibles: [],
        location: [],
        prices: {},
        product: {id:typeof editId == typeof  undefined ? 0 : editId},
        serial_keys: [],
        serialKeysTable: [],
        shipping: {},
        stock: {},
        seo: {},
        featured_product: '',
        availability: '',
        pricespy : {}
    };
    $scope.photos = [];
    $scope.uploadstart = false;
    $scope.files = [];
    $scope.product_photos = [];
    $scope.pre_selected= [];
    $scope.form = {};
    $scope.showSerialKey = false;
    $scope.default_shipping_accessory = '';

    //pricespy dealers
    $scope.pricespy_dealers = [];
    $scope.selectedDealers= [];
    $scope.toggleSelectedDealers = function(id){
        var idx = $scope.selectedDealers.indexOf(id);
        // is currently selected
        if (idx > -1) {
            $scope.selectedDealers.splice(idx, 1);
        }
        // is newly selected
        else {
            $scope.selectedDealers.push(id);
        }
    };
    //get pricespy dealers
    $scope.getPricespyDealers = function(){

        $request.setSub('api/products/rest');
        $request.setMethod('POST');
        $request.setQuery({
            apikey: apikey,
            table: 'dealer',
            model: {where: {is_pricespy: '1'}, select : ['first_name','last_name','company_name','id']}
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.pricespy_dealers = result.result;
            }
        });
    };
    $scope.getPricespyDealers();

    $scope.updateMarkup = function (product) {
        if (product.cost_inc_gst === null) {
            return null;
        }
        $scope.product.prices.markup = ((product.store_price - product.cost_inc_gst) / product.cost_inc_gst) * 100;
        $scope.product.prices.markup = parseFloat($scope.product.prices.markup).toFixed(2);
    };

    $scope.pushCategories = function (id){
        var categories = $scope.product.categories[0];
        var id_repeat = categories.indexOf(id);
        if (id_repeat == -1) {
            categories.push(id);
        } else {
            delete categories[id_repeat];
        }
        // 
        var a = $scope.getCategory(categories, 1);
    };
    $scope.pushCategories1 = function (id){
       var categories = $scope.product.categories[1];
        var id_repeat = categories.indexOf(id);
        if (id_repeat == -1) {
            categories.push(id);
        } else {
            delete categories[id_repeat];
        }
        // 
        var a = $scope.getCategory(categories, 2);
    
    };
    $scope.fileNameChanged  = function (id){
        if(id.length !== 0){
            $scope.product.prices.start_price = id;
            $scope.product.prices.reserve_price = id;
            $scope.product.prices.offer_price = id;
        }
    };
    $scope.showCatChecked = function (id){
        if($scope.rootCatsWhileEdit.length!==0){
            for (var i = 0; i < $scope.rootCatsWhileEdit.length; i++) {
                if(id==$scope.rootCatsWhileEdit[i].id){
                    return 'true';
                };
            };
            
        }
    };
    $scope.showSelectedWarehouse = function (id){
        if(id == $scope.product.product.warehouse){
            return 'true';
        }
    };
    $scope.showSelectedVendors = function (id){
        if(id == $scope.product.product.vendor_name){
            return 'true';
        }
    };
    $scope.showCheckedCompatibles = function (id){
        if ($scope.product.compatibles.length>0 && id.length>0) {
            for (var i = 0; i < $scope.product.compatibles.length; i++) {
                if ($scope.product.compatibles[i] == id) {
                    return 'true';
                };
            };
        };
    };
    $scope.showCheckedAccessory = function (id){
        if (id==true) {
            $request.setSub('api/products/rest');                
            $request.setMethod('POST');
            $request.setQuery({
                apikey: apikey,
                table: 'products_shipping_prices',
                model: {where: {action: 'accessory'}}
            });
            $request.makeRequest().then(function () {
                var result = $request.getResults();
                if (result.result.length > 0) {
                    $scope.default_shipping_accessory = 'falses';
                    $scope.product.shipping = result.result[0];
                }
            });
        };
        if (id==false) {
            $request.setSub('api/products/rest');                
            $request.setMethod('POST');
            $request.setQuery({
                apikey: apikey,
                table: 'products_shipping_prices',
                model: {where: {action: 'product'}}
            });
            $request.makeRequest().then(function () {
                var result = $request.getResults();
                if (result.result.length > 0) {
                    $scope.default_shipping_accessory = 'falses';
                    $scope.product.shipping = result.result[0];
                }
            });
        };
    };
   
    $scope.showSerailForm = function (){
        $scope.showSerialKey = true;
    };
    $scope.addProduct = function () {
        $scope.product.categories[2] = [];
        
        for (var i = 0; i < $scope.list2.length; i++) {
            $scope.product.categories[2].push({id: $scope.list2[i].id, name: $scope.list2[i].name});
        };
        $scope.product.product.short_description = CKEDITOR.instances.short_description.getData();
        
        if (updateSelected==true) {
            $scope.productId = 0;
            $scope.copyId = $scope.product.product.id;
            $scope.product.product.id = 0;
        }

        $scope.product.pricespy.pricespy_dealers = $scope.selectedDealers;

        $request.setSub('api/products/edit/'+ ($scope.editMode ? $scope.productId : 0));
        $request.setQuery({apikey: apikey, product: $scope.product});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            is_accessories = JSON.stringify(result.is_accessories);
            if(result.hasOwnProperty('product'))
            {
                if ($scope.product_photos.length > 0) {
                    if(updateSelected==true){
                        for (var i = 0, photo; photo = $scope.product_photos[i]; i++) {
                            $request.setSub('api/products/copy/'+ ($scope.editMode ? $scope.productId : 0));
                            $request.setQuery({apikey: apikey, product: photo, copyId: $scope.copyId, productId: result.product});
                            $request.setMethod('POST');
                            $request.makeRequest().then(function () {
                                console.log($request.getResults());
                            });                            
                        } 
                    } else {
                        for (var i = 0, photo; photo = $scope.product_photos[i]; i++) {
                            $.notify('Uploading image. Please wait...','info');
                            $upload.upload({
                                url: base+'api/products/upload',
                                method: 'POST',
                                fields: {
                                    apikey: apikey,
                                    'Content-Type': 'application/octet-stream',
                                    sn: photo.sn,
                                    description: photo.description,
                                    is_default: photo.is_default,
                                    is_auction: photo.is_auction,
                                    is_website: photo.is_website,
                                    product : result.product
                                },
                                file: photo.file
                            }).then(function (res) {
                                if(res.status == 200){
                                    $.notify('Image uploaded','success');
                                }else{
                                    $.notify('Image could not be uploaded','error');
                                }
                                if(i == $scope.product_photos.length){
                                    $.notify('Product has been updated.','success');
                                }
                            }, function () {
                            }).then(function (res) {
                            });
                        } 
                    }
                }else{
                    $.notify(result.response,result.code == 200 ? 'success' : 'error');
                }
            }
        });
        
         
    };
    $scope.resetProduct = function () {
        $scope.product = {};
    };
    $scope.getCategory = function (parent_id, index) {
        parent_id = parent_id.filter (function (value){
            return value!=null;
        });
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'category',
            model: {where: {status: 'Y'}, where_in: {parent_id: parent_id}, select: ['name', 'id', 'parent_id']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result.length !== 0) {
                $scope.rootCats[index] = result.result;
            } else {
                $request.setSub('api/products/rest');
                $request.setQuery({
                    apikey: apikey,
                    table: 'category',
                    model: {where: {status: 'Y'}, where_in: {id: parent_id}, select: ['name', 'id', 'parent_id']}
                });
                    $request.setMethod('POST');
                $request.makeRequest().then(function () {
                    result = $request.getResults();
                    if (result.result.length !== 0) {
                        $scope.rootCats[index] = result.result;
                    }
                });
            }
        });
    };
    $scope.warehouses = function () {
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({apikey: apikey, table: 'warehouse', model: {where: {status: 'Y'}, select: ['name', 'sn']}});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.warehouse = result.result;
            }
        });
    };
    $scope.deleteAccessories = function (index) {
        $scope.product.accessories.splice(index, 1);
    };
    $scope.deleteCategories = function(index) {
        $scope.list2.splice(index, 1);
    };
    $scope.deleteSerialKeys = function(index) {
        $scope.product.serial_keys.splice(index, 1);
    };
    $scope.deleteProductLocation = function(index) {
        $scope.product.location.splice(index, 1);
    };
    $scope.locations = function () {
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'products_locations',
            model: {where: {status: 'Y'}, select: ['code', 'id']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.product_locations = result.result;
            }
        });
    };
    $scope.search = function () {
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'products',
            model: {select: ['code', 'id']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.search_accesory = result.result;
            }
        });
    };
    $scope.vendors = function () {
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'vendors',
            model: {select: ['name', 'id']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.product.vendors = result.result;
            }
        });
    };
    $scope.compatibility = function () {
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({apikey: apikey, table: 'compatibles', model: {where: {status: 'Y', adminSN: 1}, select: ['name', 'id']}});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.compatibility = result.result;
            }
        });
    };
    $scope.addcompatibility = function(value){
        var dup = false;
        if (value.length>0) {
            for (var i = 0; i < $scope.product.compatibles.length; i++) {
                if (value == $scope.product.compatibles[i]) {
                    dup = true;                      
                    $scope.product.compatibles.splice(i, 1);
                }
            };
            if (dup==false) {
                $scope.product.compatibles.push(value);    
            }       
        };
    };
    $scope.searchLocation = function (location) {
        $scope.query = location;
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'products_locations',
            select: ['code', 'id', 'description']}
        );
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.search_location = result.result;
            }
            var res = [];
            for (var key in $scope.search_location) {
                
                var loc = $scope.search_location[key];
                var substr = loc.code.substring(0, $scope.query.length);
                if (substr.toLowerCase() === $scope.query.toLowerCase()) {                    
                    res.push({id: loc.id, code: loc.code, description: loc.description});
                }
            }
            if (res.length>0) {
                $scope.searchedLocations = res;
                $scope.searchLocationActive = true;    
            };
        });
    };
    
    $(document).on(
        "click",
        function( event ){
            $scope.$apply(
                function(){
                    if ($scope.searchLocationActive == true) {
                        $scope.searchedLocations = event;
                        $scope.searchLocationActive = false;  
                    };
                    
                }
            );
        }
    );
    $scope.searchedAccessory = [];
    $scope.product.accessories = [];
    $scope.addAccesory = function (acc){
        var duplicate_accessories = false;
        for (var i = 0; i < $scope.product.accessories.length; i++) {
            if ($scope.product.accessories[i].photo_id == acc.photo_id) {
                duplicate_accessories = true;
            }
        };
        if (duplicate_accessories == false) {
            $scope.product.accessories.push(acc);
            $scope.searchedAccessory = [];
        } else {
            $.notify('Duplicate data, already inserted');
        }
        
    };
    $scope.showCheckedAccessory(true);
    $scope.searchAccessory = function (value) {
        $scope.query = value;
        var result = [];
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'products',
            model: {where: {is_accessory: '1'},select: ['name', 'id', 'unique_code','photo_id']}
        });
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            result = $request.getResults();
            if (result.result) {
                $scope.search_accesory = result.result;
            }
            var res = [];
            for (var key in $scope.search_accesory) {
                
                var loc = $scope.search_accesory[key];
                if(loc.name == null){
                    continue;
                }
                var substr = loc.name.substring(0, $scope.query.length);
                if (substr.toLowerCase() === $scope.query.toLowerCase()) {
                    var image_name = loc.name;
                    var image_name = image_name.replace(' ','-');
                    res.push({id: loc.id, code: loc.unique_code, name: loc.name, photo_id: loc.photo_id, image_name:image_name});
                }
            }
            $scope.searchedAccessory = res;
        });
        
    };
    $scope.addLocation = function (location) {
         
        var total_location = $scope.product.location,
            duplicate_location = false;
        for (var i = 0; i < total_location.length ; i++) {
            if (total_location[i].id==location.id) {
                duplicate_location = true
            };
        };
        if (duplicate_location==false) {
            $scope.product.location.push(location);
            $scope.searchedLocations = [];
        } else {
            $.notify('Duplicate value entry');
        }
        
        
    };
    //watch changes in product categories selection
    $scope.$watch(
        function () {
            return $scope.product.categories;
        },
        function (newVal, oldVal) {
            if ($scope.product.categories[0].length > 0) {
                $scope.getCategory($scope.product.categories[0], 1);
            } else {
                if ($scope.product.categories[0].length === 0) {
                    $scope.product.categories[1] = [];
                    $scope.product.categories[2] = [];
                    $scope.rootCats[1] = [];
                    $scope.rootCats[2] = [];
                }
            }
            if ($scope.product.categories[1].length > 0) {
                $scope.getCategory($scope.product.categories[1], 2);                
            } else {
                if ($scope.product.categories[1].length === 0) {
                    $scope.product.categories[2] = [];
                    $scope.rootCats[2] = [];
                }
            }
        }, true);
    $scope.fileSelected = function (files, $index, $event) {
        $scope.files = files;
        if (files !== null && files[0] != undefined) {
            var file = files[0];
            var ext = file.name.substr(file.name.lastIndexOf('.') + 1);
            $scope.product_photos[$index].file = files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                var img = new Image();
                img.src = e.target.result;
                img.onload = function () {
                    $scope.product_photos[$index].attachment = {type: ext, image: img};
                    $scope.$apply();
                };
            };
        }
    };
    $scope.getProductPhotos = function () {
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'product_photos',
            model: {
                where: {
                    product_id: $scope.producId || 0
                },
                select: [
                    'file',
                    'description',
                    'is_default',
                    'is_auction',
                    'is_website',
                    'position'
                ],
                order_by: {
                    position: 'asc'
                }
            }
        });
        $request.makeRequest().then(function () {
        });
    };
    $scope.deletePhoto = function ($index,item) {
        $scope.product_photos.splice($index, 1);
        $request.setSub('api/api/rest')
        $request.setQuery({
            apikey: apikey,
            table: 'product_photos',
            model: {
                where: {
                    sn: item.sn
                }
            },
            action:'delete'
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if(result.code == 200){
                $.notify("Image delete successfully.",'success');
            }else{
                $.notify("Image could not be deleted.",'error');
            }
        });
    };
    $scope.addProductPhoto = function () {
        $scope.product_photos.push({
            product_id: $scope.productId || 0,
            name: '',
            description: '',
            is_default: 0,
            is_auction: 0,
            is_website: 0,
            sn : 0
        });
        return false;
    };
    $scope.addSerialKey = function(key){
        for (var i =0, k; k= $scope.product.serial_keys[i]; i++) {
            if (k===key) {
                $.notify('key already exits', 'error'); 
                return;
            }
        };
        $scope.product.serial_keys.push(key);
        $scope.form.serial_key = '';
    };

    $scope.getCategory([0], 0);
    $scope.warehouses();
    $scope.locations();
    $scope.compatibility();
    $scope.vendors();

    if (editMode) 
    {
        $scope.product.product.id = editId;
        $scope.showSerialKey = true;
        $scope.editMode = true;
        $scope.productId = editId;

        //fetch pricespy
        $request.setSub('api/products/rest');
        $request.setQuery({
            apikey: apikey,
            table: 'product_pricespy',
            model: {where: {product_id: editId}}
        });
        $request.makeRequest().then(function(){
            var result = $request.getResults();
            if(result.result.length > 0){
                $scope.product.pricespy = result.result[0];
                $request.setSub('api/products/rest');
                $request.setQuery({
                    apikey: apikey,
                    table: 'pricespy_dealers',
                    model: {where: {pricespy_id: result.result[0].sno},select:['dealer_id']}
                });
                $request.makeRequest().then(function(){
                    var result = $request.getResults();
                    if(result.result.length > 0){
                        var dealers = result.result;
                        for(var i = 0; i < dealers.length; i++){
                            $scope.selectedDealers.push(dealers[i].dealer_id);
                        }
                    }
                });
            }
        });

        $request.setSub('api/products/rest');
        $scope.cat_id = [];
        //fetch product categories
        $request.setQuery({
            apikey: apikey,
            table: 'product_categories',
            model: {where: {product_id: editId}, select: ['cat_id']}
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
            }
        });
        //fetch the product
        $request.setQuery({
            apikey: apikey,
            table: 'products',
            model: {
                where: {
                    id: editId
                },
                select: [
                    'id',
                    'unique_code',
                    'manufacturer_code',
                    'short_description',
                    'status',
                    'product_type',
                    'is_accessory',
                    'warehouse',
                    'name',
                    'invoice_desciption',
                    'warehouse',
                    'vendor_name',
                    'featured_product',
                    'availability',
                    'video'
                    // 'reserve_price',
                    // 'offer_price'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.product.product = result.result[0];
               
                if ($scope.product.product.name.length==0) {
                    var productTitle = "PRODUCT"; 
                } else {
                    var productTitle = $scope.product.product.name.toUpperCase();
                }
                document.title = 'PRODUCT : '+ productTitle;
            }
        });
        //fetch the seo
        $request.setQuery({
            apikey: apikey,
            table: 'seo',
            model: {
                where: {
                    table_id: editId
                },
                select: [
                    'seo_title',
                    'seo_keywords',
                    'seo_description'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {                
                $scope.product.seo = result.result[0];
            }
        });
        //fetch prices
        $request.setQuery({
            apikey: apikey,
            table: 'product_prices',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'store_price',
                    'use_last_purchse_order',
                    'cost_exc_gst',
                    'cost_inc_gst',
                    'markup',
                    'buy_now_price',
                    'buy_now',
                    'start_price',
                    'reserve_price',
                    'offer_price',
                    'offer_buy_now',
                    'website_price',
                    'special_price',
                    'delaer_price',
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.product.prices = result.result[0];
            }
        });
        //fetch stock
        $request.setQuery({
            apikey: apikey,
            table: 'product_stock',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'onshelf_stock',
                    'available_to_sell',
                    'safety_stock',
                    'available_to_stock'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                
                $scope.product.stock = result.result[0];
            }
        });
        //fetch serial keys
        $request.setQuery({
            apikey: apikey,
            table: 'product_serial_keys',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'serial_key'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                for (var i = 0, res; res = result.result[i]; i++) {
                    $scope.product.serial_keys.push(res.serial_key);
                }
            }
        });
        //fetch categoies
        $request.setQuery({
            apikey: apikey,
            table: 'product_categories',
            status: 'child',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'cat_id',
                    'name'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            $scope.parent_id = [];
            $scope.cat_id2 = [];
            if (result.result.length > 0) {
                var keys = [];
                for (var i = 0, res; res = result.result[i]; i++) {
                    $scope.cat_id.push(res.cat_id);
                    keys.push({name: res.name, id: res.cat_id});
                }
                
                $scope.list2 = keys;
                $request.setQuery({
                    apikey: apikey,
                    table: 'category',
                    model: {
                        where_in: {
                            id: $scope.cat_id
                        },
                        select: [
                            'id',
                            'parent_id',
                            'name'
                        ]
                    }
                });
                $request.makeRequest().then(function () {
                    var result = $request.getResults();
                   
                    if (result.result.length > 0) {
                        for (var i = 0, res; res = result.result[i]; i++) {
                            $scope.parent_id.push(res.parent_id);
                        }
                    }
                    $request.setQuery({
                        apikey: apikey,
                        table: 'category',
                        model: {
                            select: [
                                'id',
                                'parent_id',
                                'name'
                            ]
                        }
                    });
                    $request.makeRequest().then(function () {
                        var result = $request.getResults();
                        var keys = [];
                        var keys2 = [];
                        var cat_id2 = [];
                        var cat_id3 = [];
                        for (var i = 0, res; res = result.result[i]; i++) {
                            for (var j = 0, parent_id; parent_id = $scope.parent_id[j]; j++) {
                                if(res.id == parent_id){
                                    cat_id2.push(res.parent_id);
                                    if (keys.length==0) {
                                        keys.push({name: res.name, id: res.id});    
                                    } else {
                                        for (var k = 0, key; key = keys[k]; k++) {
                                            if (key.id !== res.id) {
                                                keys.push({name: res.name, id: res.id});   
                                            } else {
                                                // do nothing
                                            }
                                        }
                                    }
                                    
                                    
                                }
                            }
                            
                        }
                        $scope.rootCats[1] = keys;
                        for (var i = 0, res; res = result.result[i]; i++) {
                            for (var j = 0, cat_id; cat_id = cat_id2[j]; j++) {
                                if(res.id == cat_id){
                                    // cat_id3.push(res.parent_id);
                                    if (keys2.length==0) {
                                        keys2.push({name: res.name, id: res.id});
                                    } else {
                                        for (var k = 0, key; key = keys2[k]; k++) {
                                            if (key.id !== res.id) {
                                                keys2.push({name: res.name, id: res.id});
                                            } else {
                                                // do nothing
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        $scope.rootCatsWhileEdit = keys2;
                    });
                });
            }
        });
        //fetch accessories
        $request.setQuery({
            apikey: apikey,
            table: 'products_accessories',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'id',
                    'name',
                    'product_id',
                    'photo_id'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                var keys = [];
                for (var i = 0, res; res = result.result[i]; i++) {
                    var image_name = res.name,
                        image_name = image_name.replace(' ','-'),
                        index = i+1;
                    keys.push({name: res.name, id: res.id, photo_id: res.photo_id, image_name:image_name, index: index});
                }
                
                $scope.product.accessories = keys;
            }
        });
        //fetch shipping
        $request.setQuery({
            apikey: apikey,
            table: 'product_shipping',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'shipping_required',
                    'standard_shipping_price',
                    'secondary_shipping_price',
                    'intl_standard_shipping_price',
                    'intl_seconday_shipping_price'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
            }
        });
        $request.setQuery({
            apikey: apikey,
            table: 'product_compatibles',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'device_id'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                var value = [];
                for (var i = 0; i < result.result.length; i++) {
                    value.push(result.result[i]['device_id']);
                };
                
                $scope.product.compatibles = value;
            }
        });
        //fetch product locations
        $request.setQuery({
            apikey: apikey,
            table: 'product_location',
            model: {
                where: {
                    product_id: editId
                },
                select: [
                    'location_id',
                    'is_default'
                ]
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                var keys = [];
                for (var i = 0, res; res = result.result[i]; i++) {
                    var location_id = res.location_id;
                        
                    $request.setQuery({
                        apikey: apikey,
                        table: 'products_locations',
                        model: {
                            where: {
                                id: location_id
                            },
                            select: [
                                'id',
                                'code',
                                'description'
                            ]
                        }
                    });
                    $request.makeRequest().then(function () {
                        var result = $request.getResults();
                        if (result.result.length > 0) {
                            for (var i = 0, res; res = result.result[i]; i++) {
                                var location_id = res.location_id,
                                    index = i+1;
                                keys.push({code: res.code, id:res.id, description: res.description});
                            }
                        }
                    });
                }
                $scope.product.location = keys;
            }
        });
        $request.setSub('api/products/rest');$request.setQuery({
            apikey: apikey,
            table: 'product_photos',
            model: {
                where: {
                    product_id: editId
                }
            }
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if(result.hasOwnProperty('result')){
                if (result.result.length > 0) {
                    $scope.admin = result.user;
                    $scope.product_photos = result.result;
                    
                }
            }
        });
        $scope.showEnableBuyNow = function (id){
            
        };
    }
}]);
app.directive('dropdownMultiselect', function () {
    return {
        restrict: 'E',
        scope: {
            model: '=',
            options: '=',
            pre_selected: '=preSelected',
            pre_text: '=preText',
            on_change: '=onChange'
        },
        template: "<div class='btn-group' data-ng-class='{open: open}'>" +
        "<button class='btn btn-small dropdown-toggle' data-ng-click='open=!open;openDropdown()'>{{pre_text}} <span class='caret'></span></button>" +
        "<ul class='dropdown-menu multi-dropdown' aria-labelledby='dropdownMenu'>" +
        "<li data-ng-repeat='option in options' data-ng-class='isChecked(option.id)' data-ng-click='setSelectedItem()'>{{option.name}}</li>" +
        "</ul>" +
        "</div>",
        controller: function ($scope) {
            $scope.openDropdown = function () {
                $scope.selected_items = [];
                for (var i = 0; i < $scope.pre_selected.length; i++) {
                    $scope.selected_items.push($scope.pre_selected[i].id);
                }
            };
            $scope.setSelectedItem = function () {
                var id = this.option.id;
                var found = false;
                for (var i = 0; i < $scope.model.length; i++) {
                    var m = $scope.model[i];
                    if (m === id) {
                        $scope.model.splice(i, 1);
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    $scope.model.push(id);
                }
                return false;
            };
            $scope.isChecked = function (id) {
                for (var i = 0; i < $scope.model.length; i++) {
                    var m = $scope.model[i];
                    if (m === id) {
                        return 'selected';
                        break;
                    }
                }
                return 'unselected';
            };
        }
    }
});
app.directive('draggable', function() {
  return {
    restrict:'A',
    link: function(scope, element, attrs) {
      element.draggable({
        revert:true
      });
    }
  };
});
app.directive('droppable', function($compile) {
  return {
    restrict: 'A',
    link: function(scope,element,attrs){
      element.droppable({
        drop:function(event,ui) {
          var dragIndex = angular.element(ui.draggable).data('index'),
              reject = angular.element(ui.draggable).data('reject'),
              dragEl = angular.element(ui.draggable).parent(),
              dropEl = angular.element(this);
          
          if (dragEl.hasClass('list1') && !dropEl.hasClass('list1') && reject !== true) {
            var drag_id = scope.rootCats[2][dragIndex].id,
                duplicate_category = false;
            for (var i = 0; i < scope.list2.length; i++) {
                if (drag_id==scope.list2[i].id) {
                    duplicate_category = true;
                }
            };
            if (duplicate_category == false) {
                scope.list2.push(scope.rootCats[2][dragIndex]);
                scope.rootCats[2].splice(dragIndex, 1);
            } else {
                $.notify('Duplicate Data Enter');
                return;
            }
            
          } else if (dragEl.hasClass('list2') && !dropEl.hasClass('list2') && reject !== true) {
            scope.rootCats[2].push(scope.list2[dragIndex]);
            scope.list2.splice(dragIndex, 1);
          }
          scope.$apply();
        }
      });
    }
  };
}); 
