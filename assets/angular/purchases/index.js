app.controller('PurchaseCtrl', ['$scope', '$requestFactory', function (scope, http) {
    scope.mainGridOptions = {};
    scope.checked = [];

    scope.print_reports = ['Purchase order', 'Purchase order (no prices)', 'odt purchase report'];

    scope.purchase_types = ['RFQ (Request for Quote)', 'P/O (Purchase Order)', 'P/I (Purchase Invoice)', 'Credit', 'VOID'];

    scope.statuses = ['To Be Ordered', 'Ordered', 'Dispatched', 'Cleared', 'Received Partial', 'Received All'];

    scope.paid_status = ['UnPaid', 'Paid'];

    var hitech = new Hitech({
        scope: scope,
        grid: scope.mainGridOptions,
        http: http
    });

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: base + 'api/purchases/list?apikey=' + apikey,
                dataType: "json"
            },
        },
        pageSize: 35,
        pageable: true,
        batch: true
    });

    scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        pageable: true,
        filterable: true,
        serverFiltering: true,
        selectable: "multiple",
        resizable: true,
        toolbar: [
            {
                template: '<a href="javascript:;" ng-click="addPurchase()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>New purchase</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
            }
        ],
        columns: [{
            width: 10,
            template: "<input type='checkbox' class='checkbox' />"
        }, {
            width: 15,
            title: "#",
            field: 'id',
            template: function (row) {
                return '<a href="' + (base + "admin/purchases/edit/" + row.id) + '" target="_blank">' + row.id + '</a>';
            }
        }, {
            width: 35,
            title: "Vendor",
            field: 'vendor_name'
        }, {
            width: 35,
            title: "Purchase type",
            field: 'type',
            template: function (row) {
                return row.type == 0 ? '-' : scope.purchase_types[parseInt(row.type) + 1];
            }
        }, {
            width: 35,
            title: "Order date",
            field: 'order_date',
            template: function (row) {
                var d = new Date(row.order_date);
                if (isNaN(d.getTime())) {
                    return 'Not Set';
                }
                return (d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDay());
            }
        }, {
            width: 35,
            title: "Due date",
            field: 'due_date',
            template: function (row) {
                var d = new Date(row.due_date);
                if (isNaN(d.getTime())) {
                    return 'Not Set';
                }
                return (d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDay());
            }
        }, {
            width: 35,
            title: "Current status",
            field: 'status',
            template: function (row) {
                return row.status == 0 ? '-' : scope.statuses[parseInt(row.status) + 1];
            }
        }, {
            width: 45,
            title: "Payment status",
            field: 'paid_status',
            template: function (row) {
                return scope.paid_status[parseInt(row.paid_status)];
            }
        }, {
            width: 35,
            title: "Assigned to",
            field: 'assigned_to',
            template: function (row) {
                return row.assigned_to || 'No one';
            }
        }, {
            width: 35,
            title: "Total items",
            field: 'items',
            template: function (row) {
                return '<a href="' + (base + "admin/purchases/items/" + row.id) + '" target="_blank" title="view items">' + row.items + '</a>';
            }
        }, {
            width: 35,
            title: "Net total",
            field: 'net_total',
            template: function (row) {
                return hitech.number_format(row.net_total, 2);
            }
        }, {
            width: 35,
            title: "Discount",
            field: 'discount',
            template: function (row) {
                return hitech.number_format(row.discount, 2);
            }
        }, {
            width: 35,
            title: "Tax",
            field: 'tax',
            template: function (row) {
                return hitech.number_format(row.tax, 2);
            }
        }, {
            width: 35,
            title: "Total",
            field: 'total',
            template: function (row) {
                return hitech.number_format(row.total, 2);
            }
        }],
        change: function (e) {
            hitech.gridChangeEvent(this, e);
        },
        dataBound: function () {
            hitech.gridDataBound('checkbox');
        }
    };

    scope.addPurchase = function () {
        window.location.href = base + 'admin/purchases/edit/0';
    }

    scope.updateFilteredList = function (query) {
        var selecteditem = query;
        var kgrid = $("#mainGridOptions").data("kendoGrid");
        selecteditem = selecteditem.toUpperCase();
        var selectedArray = selecteditem.split(" ");
        if (selecteditem) {
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
                            orfilter.filters.push({field: "vendor_name", operator: "contains", value: v1},
                                {field: "type", operator: "contains", value: v1},
                                {field: "status", operator: "contains", value: v1},
                                {field: "assigned_to", operator: "contains", value: v1});
                            andfilter.filters.push(orfilter);
                            //orfilter = {logic: "or", filters: []};
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

    scope.adjustPageSize = function (size) {
        hitech.pageSize(size);
    };

    scope.resetSelected = function () {
        hitech.clearFilters();
        hitech.request({
            first: {
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'keep_omit',
                    model: {
                        where: {
                            identify: 'PUR',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Purchases have been reset successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                        hitech.request({
                            sub: 'api/purchases/list',
                            method: "GET",
                            query: {apikey: apikey},
                            complete: function (res) {
                                if (res != false) {
                                    scope.mainGridOptions.dataSource.data(res);
                                    $.notify("Purchases list has been reloaded.", 'success');
                                } else {
                                    $.notify("Purchases could not be reloaded.", {
                                        className: 'error',
                                        autoHideDelay: '10000'
                                    });
                                }
                            }
                        });
                    } else {
                        $.notify("Purchases could not be reset.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            }
        });
    }

    scope.updateGridBySelection = function (selection) {
        if (selection === false || selection == "Save Search") {
            return;
        }

        var res = hitech.updateGridBySelection(selection);

        if (res === false) {
            $.notify('Please select at least one item from the table.', {
                className: 'info',
                autoHideDelay: '3000'
            });
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
                type: 'PUR'
            },
            complete: function (result) {
                $.notify(result.response, {
                    className: result.status == 200 ? 'success' : 'error',
                    autoHideDelay: '10000'
                });
            }
        });
    };

    scope.deleteSelected = function () {
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " items?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {apikey: apikey, method: 'purchases', action: 'delete', id: ids, multiple: 'multiple'},
                method: 'POST',
                complete: function (result) {
                    $.notify(result.response, result.status == 200 ? 'success' : 'error');
                    if (result.status == 200) {
                        hitech.scope.checked = [];
                        hitech.omitSelected(ids, true, 'sn');
                    }
                }
            });
        } else {
            $.notify("Please select at least one item.", 'info');
        }
    }
}]);
