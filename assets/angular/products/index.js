app.controller('kendoCtrl', function ($scope, $requestFactory) {
    $scope.mainGridOptions = {};
    $scope.checked = [];
    var hitech = new Hitech({
        scope: $scope,
        grid: $scope.mainGridOptions,
        http: $requestFactory
    });
    $scope.activeInactive = false;
    $scope.selectedRow = [];
    $scope.product = {};
    $scope.bulkPriceUpdate = {
        id: {},
        data: []
    };
    document.title = 'PRODUCTS';

    $scope.updateFilteredList = function (query) {
        var selecteditem = query;
        var kgrid = $("#mainGridOptions").data("kendoGrid");
        selecteditem = selecteditem.toUpperCase();
        var selectedArray = selecteditem.split(" ");
        if (selecteditem) {
            //kgrid.dataSource.filter({ field: "UserName", operator: "eq", value: selecteditem });
            var orfilter = {logic: "or", filters: []};
            var andfilter = {logic: "and", filters: []};
            $.each(selectedArray, function (i, v) {
                if (v.trim() == "") {
                }
                else {
                    $.each(selectedArray, function (i, v1) {
                        if (v1.trim() == "") {
                        }
                        else {
                            orfilter.filters.push({field: "name", operator: "contains", value: v1},
                                {field: "unique_code", operator: "contains", value: v1},
                                {field: "manufacturer_code", operator: "contains", value: v1},
                                {field: "short_description", operator: "contains", value: v1},
                                {field: "status", operator: "contains", value: v1},
                                {field: "is_accessory", operator: "contains", value: v1},
                                {field: "vendor_name", operator: "contains", value: v1});
                            andfilter.filters.push(orfilter);
                            orfilter = {logic: "or", filters: []};
                        }
                    });
                }
            });
            kgrid.dataSource.filter(andfilter);
        }
        else {
            kgrid.dataSource.filter({});
        }
    }
    $scope.filterGrid = function (field, value, operator) {
        hitech.applyFilter({
            field: field,
            value: value
        }, operator | 'eq');
    }
    $scope.adjustPageSize = function (size) {
        hitech.pageSize(size);
    };
    $scope.addProduct = function () {
        window.location.href = base + 'admin/products/edit/0';
    }
    $scope.importPoduct = function () {
        window.location.href = base + 'admin/products/import';
    };
    $scope.deleteSelected = function () {
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " products?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'products',
                    action: 'update',
                    model: {
                        where_in: {
                            id: ids
                        },
                        update: {
                            is_void: 1
                        }
                    }
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code == 200) {
                        hitech.scope.checked = [];
                        hitech.omitSelected(ids, true, 'id');
                        $.notify("Selected products were deleted successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                    } else {
                        $.notify("Selected products could not be deleted.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            });
        } else {
            $.notify("Please select at least one product.", {
                className: 'info',
                autoHideDelay: '10000'
            });
        }
    };
    $scope.resetSelected = function () {
        hitech.clearFilters();
        hitech.request({
            first: {
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'keep_omit',
                    model: {
                        where: {
                            identify: 'P',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Products have been reset successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                        hitech.request({
                            sub: 'api/api/rest',
                            method: "POST",
                            query: {
                                apikey: apikey,
                                table: 'products',
                                model: {
                                    where: {
                                        adminSN: {method: 'getVar|_userSN'}
                                    }
                                }
                            },
                            complete: function (res) {
                                if (res != false) {
                                    $scope.mainGridOptions.dataSource.data(res.result);
                                    hitech.request({
                                        first: {
                                            sub: 'api/api/rest',
                                            query: {
                                                apikey: apikey,
                                                table: 'save_search',
                                                model: {
                                                    where: {
                                                        table_name: 'products'
                                                    }
                                                },
                                                action: "delete"
                                            },
                                            method: 'POST',
                                            complete: function (result) {
                                                if (result.code === 200) {
                                                    $.notify("Products list has been reset successfully.", 'success');
                                                    hitech.request({
                                                        sub: 'api/products/product',
                                                        method: "GET",
                                                        query: {apikey: apikey},
                                                        complete: function (res) {
                                                            if (res != false) {
                                                                $scope.mainGridOptions.dataSource.data(res);
                                                                $.notify("Products list has been reloaded.", 'success');
                                                            } else {
                                                                $.notify("Products list could not be reloaded.", 'error');
                                                            }
                                                        }
                                                    });
                                                } else {
                                                    $.notify("Products list could not be reset.", 'error');
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    $.notify("Products could not be reloaded.", {
                                        className: 'error',
                                        autoHideDelay: '10000'
                                    });
                                }
                            }
                        });
                    } else {
                        $.notify("Products could not be reset.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            }
        });
    }
    $scope.updateGridBySelection = function (selection) {
        //var res = hitech.updateGridBySelection(selection);
        if (selection === false) {
            return;
        }
        if (selection == "Save Search") {
            var kgrid = $("#mainGridOptions").data("kendoGrid"),
                data = kgrid.dataItems();
            hitech.request({
                sub: 'api/customers/searchSave',
                query: {
                    apikey: apikey,
                    data: data,
                    name: 'products'
                },
                // query: {apikey: apikey, method: 'customers', action: 'delete', id: ids, multiple: 'multiple'},
                method: "POST",
                complete: function (result) {
                    $.notify('Successfully search save', 'success');
                }
            });
        } else {
            hitech.request({
                sub: 'api/api/rest',
                method: "POST",
                query: {
                    apikey: apikey,
                    method: 'selection',
                    flag: selection.split(' ')[0].toLowerCase(),
                    id: res,
                    type: 'P'
                },
                complete: function (result) {
                    $.notify(result.response, {
                        className: result.status == 200 ? 'success' : 'error',
                        autoHideDelay: '10000'
                    });
                }
            });
        }
    };
    $scope.updateGridByVendor = function (selection) {
    };
    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: base + 'api/products/product?apikey=' + apikey,
                dataType: "json"
            },
        },
        pageSize: 35,
        pageable: true,
        batch: true
    });
    $scope.updateSelected = function (data) {
        if (data == '') {
            return;
        }
        var grid = $('#mainGridOptions').data("kendoGrid");
        var gridSelected = grid.select();
        var selectedRowId = [];

        if (gridSelected != '') {
            for (var i = 0; i < gridSelected.length; i++) {
                var selectedItem = grid.dataItem(gridSelected[i]);
                $scope.selectedRow.push(selectedItem);
                selectedRowId.push(selectedItem.id);
            }
            ;

            if (data == "activeInactive") {
                $scope.for = 'status';
                $scope.activeInactive = true;
            }
            ;
            if (data == "isAccessory") {
                $scope.for = 'is_accessory';
                $scope.activeInactive = true;
            }
            ;
            if (data == "changePrices") {
                //var ids = JSON.stringify(selectedRowId);
                //var id = encodeURIComponent(ids);
                window.location.href = base + 'admin/updateSelected/prices?products=' + selectedRowId.join();
            }
            ;
            if (data == "makeCopy") {
                var id = selectedRowId[0];
                window.location.href = base + 'admin/products/copy/' + id;
            }
            ;
            if (data == "unvoidProduct") {
                $scope.for = 'is_void';
                $scope.activeInactive = true;
            }
            ;
        } else {
            $.notify('Please select at least one product.', 'error');
        }
    };

    $scope.updateProduct = function (data) {
        hitech.request({
            sub: 'api/products/updateSelected',
            query: {
                apikey: apikey,
                status: data,
                for: $scope.for,
                rows: $scope.selectedRow
            },
            method: 'POST',
            complete: function (result) {
                if (result.code == 200) {
                    $.notify("update selected successfully.", {
                        className: 'success',
                        autoHideDelay: '10000'
                    });
                    location.reload();
                } else {
                    $.notify("update selected unsuccess.", {
                        className: 'error',
                        autoHideDelay: '10000'
                    });
                }
            }
        });

    };

    $scope.updateProductPrices = function () {
        hitech.request({
            sub: 'api/products/updateSelectedPrice',
            query: {
                apikey: apikey,
                data: $scope.bulkPriceUpdate.data,
            },
            method: 'POST',
            complete: function (result) {
                if (result.code == 200) {
                    $.notify(result.response, {
                        className: 'success',
                        autoHideDelay: '10000'
                    });
                    // location.reload();
                } else {
                    $.notify("update selected unsuccess.", {
                        className: 'error',
                        autoHideDelay: '10000'
                    });
                }
            }
        });
    };

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        pageable: true,
        filterable: true,
        serverFiltering: true,
        selectable: "multiple",
        toolbar: [
            {
                template: '<a href="javascript:;" ng-click="addProduct()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new product</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="importPoduct()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Import Data</a>'
            },
            {
                template: '<select kendo-drop-down-list ng-model="updateSelect" ng-change="updateSelected(updateSelect)"><option value="">Update Selected</option><option value="activeInactive">Active/Inactive</option><option value="isAccessory">Accessory Enable</option><option value="changePrices">Change Prices</option><option value="makeCopy">Make Copy</option><option value="unvoidProduct">Unvoid Product</option></select>'
            },
            {
                template: '<a href="javascript:;" ng-click="updateSelected(\'changePrices\')" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Bulk Update Price</a>'
            }
        ],
        columns: [{
            width: 35,
            template: "<input type='checkbox' class='checkbox' />"
        }, {
            width: 50,
            field: 'id',
            title: 'id',
            template: function (data) {
                return '<a href="' + base + 'admin/products/edit/' + data.id + '" target="_blank">' + data.id + '</a>';
            }
        }, {
            width: 150,
            field: 'image_name',
            title: 'Images',
            template: function (data) {
                return '<img src="' + base + 'uploads/products/' + data.adminSN + '/' + data.id + '/' + (data.image_name || '') + '" width="80" align="center" alt="' + data.id + '">';
            }
        }, {
            width: 150,
            field: 'unique_code',
            title: 'Product code'
        }, {
            width: 100,
            field: 'status',
            title: 'Status',
            template: function (data) {
                return data.status == '1' ? 'Active' : 'Inactive';
            }
        }, {
            width: 100,
            field: 'cost_inc_gst',
            title: 'Cost Price'
        }, {
            width: 100,
            field: 'website_price',
            title: 'Website'
        }, {
            field: 'name',
            title: 'Product Name',
            width: 300
        }, {
            field: 'product_type',
            title: 'Product type',
            width: 100,
            template: function (row) {
                if (row.product_type == 1) {
                    return 'Inventory';
                }
                if (row.product_type == 2) {
                    return 'Virtual';
                }
            }
        }],
        edit: function (e) {
            $('label[for=sn]').parent().remove();
            e.container.find(".k-edit-field:first").hide();
            e.container.find(".k-edit-field:first+").hide();
            $('label[for=createdDate]').parent().remove();
            e.container.find(".k-edit-field:last").hide();
        },
        dataBound: function () {
            hitech.gridDataBound('checkbox');
            // for showing total count of data in top of grid
            var grid = $("#mainGridOptions").data("kendoGrid"),
                topPagination = $('#topPageCount');
            if (topPagination.length == 0) {
                var wrapper = $('<div class="k-pager-wrap k-grid-pager pagerTop" id="topPageCount"/>').insertBefore(grid.element.children(".k-grid-header"));
                grid.pagerTop = new kendo.ui.Pager(wrapper, $.extend({}, grid.options.pageable, {dataSource: grid.dataSource}));
                grid.element.height("").find(".pagerTop").css("border-width", "0 0 1px 0");
            }
            ;
        },
        change: function (e) {
            hitech.gridChangeEvent(this, e);
        }
    };


    $scope.bulkPriceUpdate = function (data) {
        $scope.bulkPriceUpdate.data = [];
        for (var i = 0; i < data.length; i++) {
            $scope.bulkPriceUpdate.data.push(data[i]);
        }
        ;
    }


});