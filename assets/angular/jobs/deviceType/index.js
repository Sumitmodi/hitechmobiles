app.controller('kendoCtrl', function ($scope, $requestFactory) {
    $scope.mainGridOptions = {};
    // $scope.search_customer = [];
    // $scope.searchedCustomer = [];
    $scope.checked = [];
    var hitech = new Hitech({
        scope: $scope,
        grid: $scope.mainGridOptions,
        http: $requestFactory
    });
    document.title = 'DEVICE TYPE';
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
                                                    { field: "device_type", operator: "contains", value:v1});
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
                            identify: 'DT',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',

                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Device have been reset successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                        location.reload();
                    } else {
                        $.notify("Device could not be reset.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            }
        });
    };

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {

                url: base + 'api/jobs/device_type_list?apikey=' + apikey,
                dataType: "json",
                type: "GET"
            }
        },

        pageSize: 35,
        pageable: true,
        batch: true
    });

    $scope.addDeviceType = function(){
        window.location.href=base+'admin/jobs/deviceType/0/1';
    };

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        selectable: "multiple",
        pageable:true,
        toolbar:[
            {
                template: '<a href="javascript:;" ng-click="addDeviceType()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new device type</a>'
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
        },
            {
                field: "id",
                title: "Id",
                template:function(data){
                    return '<a href="'+base+'admin/jobs/deviceType/'+data.id+'/1">'+data.id+'</a>';
                }
            },{
                field: "device_type",
                title: "Device Type",
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
            if (confirm("Are you sure you want to delete " + ids.length + " device type?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'device_type',
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
                        $.notify("Selected device deleted successfully.", {
                            className: 'sucess',
                            autoHideDelay: '10000'
                        });
                        location.reload();
                    } else {
                        $.notify("Selected device could not be deleted.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            });
        } else {
            $.notify("Please select at least one job.", {
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
                type: 'DT'
            },

            complete: function (result) {
                $.notify(result.response, {
                    className: result.status == 200 ? 'success' : 'error',
                    autoHideDelay: '10000'
                });
            }
        });
    }
});