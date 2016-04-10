app.controller('kendoCtrl', function ($scope, $http, $requestFactory) {

    $scope.apikey = apikey;
    document.title = 'SLIDER';
    var dataSource = new kendo.data.DataSource({

        transport: {

            read: {

                url: base + 'api/slider/list?apikey=' + apikey,

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

    $scope.addSlider = function(){
        window.location.href = base + 'admin/slider/edit/0';
    };

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        pageable: true,
        serverFiltering: true,
        selectable: "multiple",
        allowCopy: true,
        filterable: true,
        
        toolbar:[

            {

                template: '<a href="javascript:;" ng-click="addSlider()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new slider</a>'

            },

            {

                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'

            },

            {

                template: '<a href="javascript:;" ng-click="resetSlider()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'

            }

        ],       

        columns: [

            {

                width: 35,

                template: "<input type='checkbox' class='checkbox' ng-model=\"entity.isChecked\" ng-click=\"onSelection()\"/>"

            }, {

                field: "id",

                title: "Id",

                width: 70,

                template: function (item) {

                    return '<a href="' + base + 'admin/slider/edit/' + item.id + '" target="_blank">' + item.id + '</a>';

                }

            }, {

                field: "name",

                title: "Name",

                width: 50
            }, {

                field: "title",

                title: "Title",

                width: 120

            }, {

                field: "description",

                title: "Description",

                width: 120

            } 
        ],

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
                                                    { field: "name", operator: "contains", value:v1},
                                                    { field: "title", operator: "contains", value:v1},
                                                    { field: "description", operator: "contains", value:v1});
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

    $scope.resetSlider = function () {
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

                            identify: 'SLI',

                            userSN: {method: 'getVar|_userSN'}

                        }

                    },

                    action: "delete"

                },

                method: 'POST',

                complete: function (result) {

                    if (result.code === 200) {
                        $.notify("Slider list has been reset successfully.", 'success');
                        hitech.request({
                            sub: 'api/api/rest',
                            method: "POST",
                            query: {
                                apikey: apikey,
                                table: 'slider',
                                model: {
                                    where: {
                                        adminSN: {method: 'getVar|_userSN'}
                                    }
                                }
                            },
                            complete: function (res) {
                                if (res != false) {
                                    $scope.mainGridOptions.dataSource.data(res.result);
                                    $.notify("Slider have been reloaded.", {
                                        className: 'success',
                                        autoHideDelay: '10000'
                                    });
                                } else {
                                    $.notify("Slider could not be reloaded.", {
                                        className: 'error',
                                        autoHideDelay: '10000'
                                    });
                                }
                            }
                        });

                    } else {

                        $.notify("Slider list could not be reset.", 'error');

                    }

                }

            }

        });
        


    }
    
    $scope.adjustPageSize = function (size) {

        hitech.pageSize(size);

    }

    $scope.updateGridBySelection = function (selection) {
        
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

                    type: 'SLI'

                },

                method: "POST",

                complete: function (result) {

                    $.notify(result.response, result.status == 200 ? 'success' : 'error');

                }

            });
      

    }

    $scope.deleteSelected = function () {
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " slider?") === false) {
                return;
            }

            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'slider',
                    action: 'delete',
                    model:{
                        where_in:{
                            id:ids
                        }
                    }
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code == 200) {
                        hitech.scope.checked = [];
                        hitech.omitSelected(ids);
                        $.notify("Selected slider were deleted successfully.", {
                            className:'success',
                            autoHideDelay:'10000'
                        });
                        location.reload();
                    }else{
                        $.notify("Selected slider could not be deleted.", {
                            className:'error',
                            autoHideDelay:'10000'
                        });
                    }
                }
            });
        }else{
            $.notify("Please select at least one dealer.", {
                className:'info',
                autoHideDelay:'10000'
            });
        }
    }
});