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
    document.title = 'JOBS';

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
                            orfilter.filters.push({ field: "sn", operator: "contains", value:v1},
                                                    { field: "make", operator: "contains", value:v1},
                                                    { field: "first_name", operator: "contains", value:v1},
                                                    { field: "model", operator: "contains", value:v1},
                                                    { field: "address", operator: "contains", value:v1},
                                                    { field: "opr", operator: "contains", value:v1});
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
                            identify: 'J',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',

                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Jobs have been reset successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                        location.reload();
                    } else {
                        $.notify("Jobs could not be reset.", {
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

                url: base + 'api/jobs/jobs_list?apikey=' + apikey,
                dataType: "json",
                type: "GET"
            }
        },

        pageSize: 35,
        pageable: true,
        batch: true
    });

    $scope.addJob = function(){
        window.location.href=base+'admin/jobs/edit/0';
    };

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        selectable: "multiple",
        pageable:true,
        allowCopy : true,
        filterable: true,
        resizable: true,
        toolbar:[
            {
                template: '<a href="javascript:;" ng-click="addJob()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new job</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="copy()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Copy</a>'  
            }

        ],
        columns: [{
            width: 35,
            template: "<input type='checkbox' class='checkbox' />"
        },
            {
                field: "sn",
                title: "Id",
                template:function(data){
                    return '<a href="'+base+'admin/jobs/comments/'+data.sn+'">'+data.sn+'</a>';
                }
            },{
                field: "id",
                title: "Id",
                hidden: true
            },{
                field: "make",
                title: "Submitted By",
            },{
                field: "name",
                title: "Name",
            },{
                field: "model",
                title: "Model",
            },{
                field: "repair_status",
                title: "Repair Status",
            },{
                field: "address",
                title: "Location Status",
            },{
                field: "payment_status",
                title: "Payment Status",
            },{
                field: "createdDate",
                title: "Date Created"
            },{
                field: "opr",
                title: "OPR",
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
            if (confirm("Are you sure you want to delete " + ids.length + " jobs?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'jobs',
                    action: 'delete',
                    model: {
                        where_in: {
                            customer_id: ids
                        }
                    }
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code == 200) {
                        hitech.scope.checked = [];

                        hitech.omitSelected(ids);
                        $.notify("Selected jobs were deleted successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'

                        });

                        hitech.request({
                            sub: 'api/api/rest',
                            query: {
                                apikey: apikey,
                                table: 'comments',
                                action: 'delete',
                                model: {
                                    where_in: {
                                        customer_id: ids
                                    }
                                }
                            },
                            method: 'POST',
                            complete: function (result) {
                                if (result.code == 200) {
                                    hitech.scope.checked = [];
                                    hitech.omitSelected(ids);
                                    $.notify("Selected comments were deleted successfully.", {
                                        className: 'success',
                                        autoHideDelay: '10000'

                                    });
                                } else {
                                    $.notify("Selected comments could not be deleted.", {
                                        className: 'error',
                                        autoHideDelay: '10000'
                                    });
                                }
                            }
                        });
                        

                    } else {
                        $.notify("Selected jobs could not be deleted.", {
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
                type: 'J'
            },

            complete: function (result) {
                $.notify(result.response, {
                    className: result.status == 200 ? 'success' : 'error',
                    autoHideDelay: '10000'
                });
            }
        });
    }

    $scope.copy = function(){
        var grid = $('#mainGridOptions').data("kendoGrid"),
            gridSelected = grid.select(),
            gidSelectFistRow = grid.dataItem(gridSelected[0]),
            selectedRowId = gidSelectFistRow.id;

        window.location.href = base + 'admin/jobs/copy/' + selectedRowId;
    };
});