app.controller('kendoCtrl', function ($scope, $http, $requestFactory) {
    $scope.apikey = apikey;
    $scope.checkColumns = false;
    document.title = 'CUSTOMERS';

    $scope.checkboxModel = {
        sn: {data: 'true', field: 'sn'},
        name: {data: 'true', field: 'name'},
        mailingList: {data: 'true', field: 'mailingList'},
        companyName: {data: 'true', field: 'companyName'},
        email: {data: 'true', field: 'email'},
        phone: {data: 'true', field: 'phone'},
        mobile: {data: 'true', field: 'mobile'},
        userName: {data: 'true', field: 'userName'},
        city: {data: 'true', field: 'city'},
        orders: {data: 'true', field: 'orders'},
        email_attachment: {data: 'true', field: 'email_attachment'}
    };

    $scope.columnsChecked = function (data) {
        if (data.data == 'false') {
            debugger;
            return 'false';
        } else {
            return 'true';
        }
    };

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: base + 'api/customers/list?apikey=' + apikey,
                dataType: "json"
            }
        },
        pageSize: 100,
        pageable: true
    });
    $scope.checked = [];
    $scope.mainGridOptions = {};
    var hitech = new Hitech({
        scope: $scope,
        grid: $scope.mainGridOptions,
        http: $requestFactory
    });
    $scope.addCustomer = function () {
        window.location.href = base + 'admin/customers/edit/0';
    };
    $scope.columnChange = function (data) {
        var grid = $("#mainGridOptions").data("kendoGrid");
        if (data.data == false) {
            grid.hideColumn(data.field);
        }
        if (data.data == true) {
            grid.showColumn(data.field);
        }
    };
    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        pageable: true,
        serverFiltering: true,
        selectable: "multiple",
        allowCopy: true,
        filterable: true,
        resizable: true,

        toolbar: [
            {
                template: '<a href="javascript:;" ng-click="addCustomer()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new customer</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetCustomers()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="newJob()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Create New Repair Request/Job</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="newInvoice()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Create New Invoice</a>'
            },
            "excel",
            {
                template: '<a href="javascript:;" ng-click="exportSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Export Selected Data To Excel</a>'
            }
        ],
        columns: [
            {
                width: 35,
                template: "<input type='checkbox' class='checkbox' ng-model=\"entity.isChecked\" ng-click=\"onSelection()\"/>"
            }, {
                field: "sn",
                title: "Id",
                width: 70,
                template: function (item) {
                    return '<a href="' + base + 'admin/customers/edit/' + item.sn + '" target="_blank">' + item.sn + '</a>';
                }
            }, {
                field: "mailingList",
                title: "ML",
                width: 50,
                template: function (data) {
                    if (data.mailingList == "Y") {
                        return "<span class='glyphicon glyphicon-ok'></span>";
                    } else {
                        return "<span class='glyphicon glyphicon-remove'></span>";
                    }
                }
            }, {
                field: "name",
                title: "Name",
                width: 120
            }, {
                field: "companyName",
                title: "Company Name",
                width: 120
            }, {
                field: "email",
                title: "Email",
                width: 200
            }, {
                field: "email_attachment",
                title: "Secondary emails",
                width: 200
            }, {
                field: "phone",
                title: "Phone"
            }, {
                field: "mobile",
                title: "Mobile"
            }, {
                field: "userName",
                title: "User Name",
                width: 120
            }, {
                field: "city",
                title: "City"
            }, {
                field: "orders",
                title: "Orders"
            }
        ],
        dataBound: function (data) {
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

            hitech.request({
                sub: 'api/customers/showOrHide_list',
                method: "GET",
                query: {apikey: apikey},
                complete: function (res) {
                    var grid = $("#mainGridOptions").data("kendoGrid"),
                        gridColumns = grid.columns;

                    for (var i = 0; i < res.length; i++) {
                        for (var j = 0; j < gridColumns.length; j++) {
                            if (res[i].columns == grid.columns[j].field) {
                                grid.hideColumn(grid.columns[j]);
                                var checbox = $scope.checkboxModel.hasOwnProperty(grid.columns[j].field);
                                if (checbox == true) {
                                    var fields = grid.columns[j].field;
                                    $scope.checkboxModel[fields].data = 'false';
                                    console.log($scope.checkboxModel[fields]);
                                }
                                ;
                            }
                            ;
                        }
                        ;
                    }
                    ;
                    $scope.checkColumns = true;
                }
            });


        },
        change: function (e) {
            hitech.gridChangeEvent(this, e);
        }
    };

    // if ($("#mainGridOptions").data("kendoGrid")) {
    //     debugger;
    // if (dataSource) {
    //     debugger;
    // };
    // };
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
                                {field: "sn", operator: "contains", value: v1},
                                {field: "address", operator: "contains", value: v1},
                                {field: "companyName", operator: "contains", value: v1},
                                {field: "city", operator: "contains", value: v1},
                                {field: "postcode", operator: "contains", value: v1},
                                {field: "state", operator: "contains", value: v1},
                                {field: "country", operator: "contains", value: v1},
                                {field: "phone", operator: "contains", value: v1},
                                {field: "fax", operator: "contains", value: v1},
                                {field: "email", operator: "contains", value: v1},
                                {field: "mobile", operator: "contains", value: v1},
                                {field: "shippingInstruction", operator: "contains", value: v1},
                                {field: "email_attachment", operator: "contains", value: v1},
                                {field: "website", operator: "contains", value: v1});
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
    $scope.resetCustomers = function () {
        hitech.clearFilters();
        $scope.query = null
        hitech.request({
            first: {
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'keep_omit',
                    model: {
                        where: {
                            identify: 'C',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Customers list has been reset successfully.", 'success');
                        hitech.request({
                            sub: 'api/customers/list',
                            method: "GET",
                            query: {apikey: apikey},
                            complete: function (res) {
                                if (res != false) {
                                    $scope.mainGridOptions.dataSource.data(res);
                                    // $.notify("Customers list has been reset reloaded.", 'success');
                                    hitech.request({
                                        first: {
                                            sub: 'api/api/rest',
                                            query: {
                                                apikey: apikey,
                                                table: 'save_search',
                                                model: {
                                                    where: {
                                                        table_name: 'customers'
                                                    }
                                                },
                                                action: "delete"
                                            },
                                            method: 'POST',
                                            complete: function (result) {
                                                if (result.code === 200) {
                                                    $.notify("Customers list has been reset successfully.", 'success');
                                                    hitech.request({
                                                        sub: 'api/customers/list',
                                                        method: "GET",
                                                        query: {apikey: apikey},
                                                        complete: function (res) {
                                                            if (res != false) {
                                                                $scope.mainGridOptions.dataSource.data(res);
                                                                $.notify("Customers list has been reset reloaded.", 'success');
                                                            } else {
                                                                $.notify("Customers list could not be reloaded.", 'error');
                                                            }
                                                        }
                                                    });
                                                } else {
                                                    $.notify("Customers list could not be reset.", 'error');
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    $.notify("Customers list could not be reloaded.", 'error');
                                }
                            }
                        });
                    } else {
                        $.notify("Customers list could not be reset.", 'error');
                    }
                }
            }
        });

    }
    $scope.newJob = function () {
        var grid = $("#mainGridOptions").data("kendoGrid"),
            selectedRow = grid.select();

        if (selectedRow.length > 0) {
            var data = grid.dataItem(selectedRow);
            window.location.href = base + 'admin/jobs/edit/0/' + data.sn;
        } else {
            window.location.href = base + 'admin/jobs/edit/0';
        }
    }

    $scope.editColumns = function (columnChange) {
        hitech.request({
            sub: 'api/customers/columnChange',
            query: {
                apikey: apikey,
                data: columnChange,
                name: 'customers'
            },
            method: "POST",
            complete: function (result) {
                if (result == "Success") {
                    $.notify('Column change successfully', 'success');
                } else {
                    $.notify('Unable to change column', 'error');
                }
            }
        });
    }

    $scope.adjustPageSize = function (size) {
        hitech.pageSize(size);
    }
    $scope.updateGridBySelection = function (selection) {
        if (selection == "Save Search") {
            var kgrid = $("#mainGridOptions").data("kendoGrid"),
                data = kgrid.dataItems()
            hitech.request({
                sub: 'api/customers/searchSave',
                query: {
                    apikey: apikey,
                    data: data,
                    name: 'customers'
                },
                // query: {apikey: apikey, method: 'customers', action: 'delete', id: ids, multiple: 'multiple'},
                method: "POST",
                complete: function (result) {
                    $.notify('Successfully search save', 'success');
                }
            });
        } else {
            var res = hitech.updateGridBySelection(selection);
            if (res === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    method: 'selection',
                    flag: selection.split(' ')[0].toLowerCase(),
                    id: res,
                    type: 'C'
                },
                method: "POST",
                complete: function (result) {
                    $.notify(result.response, result.status == 200 ? 'success' : 'error');
                }
            });
        }

    }
    $scope.deleteSelected = function () {
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " customers?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {apikey: apikey, method: 'customers', action: 'delete', id: ids, multiple: 'multiple'},
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
            $.notify("Please select at least one customer.", 'info');
        }
    }
    $scope.exportSelected = function (e) {
        // e.preventDefault();
        var grid = $("#mainGridOptions").getKendoGrid();
        var rows = [{
            cells: [
                {value: "sn"},
                {value: "mailingList"},
                {value: "name"},
                {value: "companyName"},
                {value: "email"},
                {value: "phone"},
                {value: "mobile"},
                {value: "userName"},
                {value: "city"},
                {value: "orders"}
            ]
        }];

        var trs = $("#mainGridOptions").find('tr');
        for (var i = 0; i < trs.length; i++) {
            if ($(trs[i]).find(":checkbox").is(":checked")) {
                var dataItem = grid.dataItem(trs[i]);
                rows.push({
                    cells: [
                        {value: dataItem.sn},
                        {value: dataItem.mailingList},
                        {value: dataItem.name},
                        {value: dataItem.companyName},
                        {value: dataItem.email},
                        {value: dataItem.phone},
                        {value: dataItem.mobile},
                        {value: dataItem.userName},
                        {value: dataItem.city},
                        {value: dataItem.orders}
                    ]
                })
            }
        }
        excelExport(rows);
    };
    function excelExport(rows) {
        var workbook = new kendo.ooxml.Workbook({
            sheets: [
                {
                    columns: [
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true},
                        {autoWidth: true}
                    ],
                    title: "Orders",
                    rows: rows
                }
            ]
        });
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "Customers.xlsx"});
    };

});