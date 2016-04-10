app.controller('kendoCtrl', function ($scope, $requestFactory) {
    $scope.mainGridOptions = {};
    $scope.checked = [];
    var hitech = new Hitech({
        scope: $scope,
        grid: $scope.mainGridOptions,
        http: $requestFactory
    });
    document.title = 'ORDER';

    $scope.updateFilteredList = function (query) {
        var selecteditem = query;
        var kgrid = $("#mainGridOptions").data("kendoGrid");
        selecteditem = selecteditem.toUpperCase();
        var selectedArray = selecteditem.split(" ");
        if (selecteditem) {
            //kgrid.dataSource.filter({ field: "UserName", operator: "eq", value: selecteditem });
            var orfilter = { logic: "or", filters: [] };
            var andfilter = { logic: "and", filters: [] };
            $.each(selectedArray, function (i, v) {
                if (v.trim() == "") {
                }
                else {
                    $.each(selectedArray, function (i, v1) {
                        if (v1.trim() == "") {
                        }
                        else {
                            orfilter.filters.push({ field: "id", operator: "contains", value:v1},
                                                    { field: "product_id", operator: "contains", value:v1},
                                                    { field: "customer", operator: "contains", value:v1},
                                                    {field: "payment_type", operator: "contains", value:v1},
                                                    { field: "shipping_type", operator: "contains", value:v1},
                                                    { field: "quantity", operator: "contains", value:v1});
                            andfilter.filters.push(orfilter);
                            orfilter = { logic: "or", filters: [] };
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

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: base + 'api/order/order_list?apikey=' + apikey,
                dataType: "json",
                type: "GET"
            }
        },

        pageSize: 35,
        pageable: true,
        batch: true
    });

    $scope.addOrder = function(){
        window.location.href=base+'admin/order/edit/0';
    };

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        selectable: "multiple",
        pageable: true,
        toolbar:[
            {
                template: '<a href="javascript:;" ng-click="addOrder()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new order</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
            }

        ],
        columns: [{
            width: 35,
            template: "<input type='checkbox' class='checkbox' />"
        },{
            field: "id",
            title: "ID",
            template:function(data){
                return '<a href="'+base+'admin/order/orderDetail/'+data.id+'">'+data.id+'</a>';
            }
        },{
            field: "product_id",
            title: "Product Name"
        },{
            field: "customer",
            title: "Customer"
        },{
            field: "payment_type",
            title: "Payment Type"
        },{
            field: "shipping_type",
            title: "Shipping Type"
        },{
            field: "shipping_description",
            title: "Shipping Detail"
        },{
            field: "quantity",
            title: "Quantity"
        },{
            field: "date_created",
            title: "Date Created"
        }],
        dataBound: function () {
            hitech.gridDataBound('checkbox');
            // for showing total count of data in top of grid
            var grid = $("#mainGridOptions").data("kendoGrid"),
                topPagination = $('#topPageCount');

            if (topPagination.length==0) {
                var wrapper = $('<div class="k-pager-wrap k-grid-pager pagerTop" id="topPageCount"/>').insertBefore(grid.element.children(".k-grid-header"));
                grid.pagerTop = new kendo.ui.Pager(wrapper, $.extend({}, grid.options.pageable, { dataSource: grid.dataSource }));
                grid.element.height("").find(".pagerTop").css("border-width", "0 0 1px 0");
            };
        },
        
        change: function (e) {
            hitech.gridChangeEvent(this, e);
        }
    };

    $scope.deleteSelected = function () {
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " orders?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'orders',
                    action: 'delete',
                    model: {
                        where_in: {
                            id: ids
                        }
                    }
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code == 200) {
                        hitech.scope.checked = [];

                        hitech.omitSelected(ids);
                        $.notify("Selected order were deleted successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'

                        });

                        hitech.request({
                            sub: 'api/api/rest',
                            query: {
                                apikey: apikey,
                                table: 'order_notes',
                                action: 'delete',
                                model: {
                                    where_in: {
                                        order_id: ids
                                    }
                                }
                            },
                            method: 'POST',
                            complete: function (result) {
                                if (result.code == 200) {
                                    hitech.scope.checked = [];
                                    hitech.omitSelected(ids);
                                    $.notify("Selected order note were deleted successfully.", {
                                        className: 'success',
                                        autoHideDelay: '10000'

                                    });
                                } else {
                                    $.notify("Selected order note could not be deleted.", {
                                        className: 'error',
                                        autoHideDelay: '10000'
                                    });
                                }
                            }
                        });
                        

                    } else {
                        $.notify("Selected order could not be deleted.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            });
        } else {
            $.notify("Please select at least one order.", {
                className: 'info',
                autoHideDelay: '10000'
            });
        }
    };

    $scope.updateGridBySelection = function (selection) {
        var res = hitech.updateGridBySelection(selection);
        if (res === false) {
            return;
        }

        hitech.request({
            sub: 'api/api/rest',
            method: "POST",
            query: {
                apikey: apikey,
                method: 'selection',
                flag: selection.split(' ')[0].toLowerCase(),
                id: res,
                type: 'O'
            },

            complete: function (result) {
                $.notify(result.response, {
                    className: result.status == 200 ? 'success' : 'error',
                    autoHideDelay: '10000'
                });
            }
        });
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
                            identify: 'O',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',

                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Order have been reset successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                        location.reload();
                    } else {
                        $.notify("Order could not be reset.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            }
        });
    };
});