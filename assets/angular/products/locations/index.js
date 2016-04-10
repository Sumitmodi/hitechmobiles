app.controller('kendoCtrl', function ($scope, $requestFactory) {



    $scope.mainGridOptions = {};
    $scope.getImportData = [];
    $scope.checked = [];

    var hitech = new Hitech({

        scope:$scope,

        grid:$scope.mainGridOptions,

        http:$requestFactory

    })



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
                            orfilter.filters.push({ field: "code", operator: "contains", value:v1},
                                                    { field: "description", operator: "contains", value:v1},
                                                    { field: "warehouseSN", operator: "contains", value:v1});
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



    $scope.adjustPageSize = function(size){

        hitech.pageSize(size);

    }



    $scope.deleteSelected = function(){

        var ids = hitech.prepareDelete();

        if (ids.length > 0) {

            if (confirm("Are you sure you want to delete " + ids.length + " locations?") === false) {

                return;

            }



            hitech.request({

                sub: 'api/api/rest',

                query: {

                    apikey: apikey,

                    table: 'products_locations',

                    action: 'delete',

                    model:{

                        where_in:{

                            sn:ids

                        }

                    }

                },

                method: 'POST',

                complete: function (result) {

                    if (result.code == 200) {

                        hitech.scope.checked = [];

                        hitech.omitSelected(ids);

                        $.notify("Selected locations were deleted successfully.", {

                            className:'success',

                            autoHideDelay:'10000'

                        });

                    }else{

                        $.notify("Selected locations could not be deleted.", {

                            className:'error',

                            autoHideDelay:'10000'

                        });

                    }

                }

            });

        }else{

            $.notify("Please select at least one location.", {

                className:'info',

                autoHideDelay:'10000'

            });

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

                            identify: 'PL',

                            userSN: {method: 'getVar|_userSN'}

                        }

                    },

                    action: "delete"

                },

                method: 'POST',

                complete: function (result) {

                    if (result.code === 200) {

                        $.notify("Products locations have been reset successfully.", {

                            className:'success',

                            autoHideDelay:'10000'

                        });

                        hitech.request({

                            sub: 'api/api/rest',

                            method: "POST",

                            query: {

                                apikey: apikey,

                                table:'products_locations',

                                model: {

                                    where: {

                                        adminSN: {method: 'getVar|_userSN'}

                                    }

                                }

                            },

                            complete: function (res) {

                                if (res != false) {

                                    $scope.mainGridOptions.dataSource.data(res.result);

                                    $.notify("Products locations have been reloaded.", {

                                        className:'success',

                                        autoHideDelay:'10000'

                                    });

                                } else {

                                    $.notify("Products locations could not be reloaded.", {

                                        className:'error',

                                        autoHideDelay:'10000'

                                    });

                                }

                            }

                        });

                    } else {

                        $.notify("Products locations could not be reset.", {

                            className:'error',

                            autoHideDelay:'10000'

                        });

                    }

                }

            }

        });

    }



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

                type: 'PL'

            },

            complete: function (result) {

                $.notify(result.response, result.status == 200 ? 'success' : 'error');

            }

        });

    }



    var statusDropDown = function (container, options) {

        $('<input data-text-field="name" data-value-field="value" data-bind="value:' + options.field + '"/>')

            .appendTo(container)

            .kendoDropDownList({

                autoBind: false,

                dataSource: {

                    data: [{name: "Select Status", value: ""}, {name: "Yes", value: "Y"}, {name: "No", value: "N"}]

                }

            });

    };



    // for warehouse

    var dataSource1 = new kendo.data.DataSource({

        transport: {

            read: {

                url: base + 'api/customers/warehouse?apikey=' + apikey,

                dataType: "json"



            }

        }

    });



    var wareHouseDropDown = function (container, options) {

        $('<input data-text-field="name" data-value-field="sn" data-bind="value:' + options.field + '"/>')

            .appendTo(container)

            .kendoDropDownList({

                autoBind: false,

                serverFiltering: true,

                dataSource: dataSource1

            });

    };



    var dataSource = new kendo.data.DataSource({

        transport: {

            read: {



                url: base + 'api/prodlocations/list?apikey=' + apikey,

                dataType: "json"

            },

            update: {

                url: base + 'api/prodlocations/product?action=update',

                type: "POST",

                dataType: "json"

            },

            destroy: {

                url: base + 'api/prodlocations/deleteProduct',

                type: "POST",

                dataType: "json"

            },

            create: {

                url: base + 'api/prodlocations/product?action=create',

                type: "POST",

                dataType: "json"

            },

            parameterMap: function (options, operation) {

                if (operation !== "read") {

                    if (options.models.length > 0) {

                        return {models: (options.models), apikey: apikey};

                    } else {

                        return {models: (options), apikey: apikey};

                    }

                }

            },

            change:function(e){

                console.log(e);

            }

        },



        pageSize: 20,

        batch: true,

        schema: {

            model: {

                id: "id",

                fields: {

                    code: {validation: {required: true}, editable: true},

                    status: {type: "string", editable: true},

                    description: {type: "string", editable: true},

                    warehouse: {editable: true}

                }

            }

        },



        pageable: true

    });



    $scope.mainGridOptions = {

        dataSource: dataSource,

        sortable: true,
        pageable: true,

        toolbar: [

            "create",

            {

                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'

            },

            {

                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'

            },
            {

                template: '<a href="javascript:;" ng-click="importPoductLocation()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Import Data</a>'

            }

        ],

        editable: "popup",

        selectable: "multiple",

        columns: [{

            width: 35,

            template: "<input type='checkbox' class='checkbox' />"

        },

            {

                field: "id",

                title: "Id",

                width: 50

            }, {

                field: "code",

                title: "Code",

                width: 80

            }, {

                field: "description",

                title: "Description"

            }, {

                field: "warehouseSN",

                title: "Warehouse",

                editor: wareHouseDropDown

            }, {

                field: "status",

                title: "Status",

                template: function (data) {

                    if (data.status == "Y") {

                        return "<span class='glyphicon glyphicon-ok'></span>";

                    } else {

                        return "<span class='glyphicon glyphicon-remove'></span>";

                    }

                },

                editor: statusDropDown



            }, {

                field: "createdDate",

                title: "Date Created",

                id: 'createdDate'

            } ,{

                command: ["edit", "destroy"], title: "&nbsp;", width: "250px"

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

        edit: function (e) {

            $('label[for=id]').parent().remove();

            e.container.find(".k-edit-field:first").hide();

            e.container.find(".k-edit-field:first+").hide();



            $('label[for=modifiedDate]').parent().remove();

            e.container.find(".k-edit-field:last").hide();



            $('label[for=createdDate]').parent().remove();

            e.container.find(".k-edit-field:first++++++++++++++++").hide();

        },

        change:function(e){

            hitech.gridChangeEvent(this,e);

        }

    };

    $scope.importPoductLocation = function (){
         window.location.href = base + 'admin/products_locations/import';
    };

    // $scope.importExcel = function () {
    //     if ($scope.mainGridOptions) {
    //         var reader = new FileReader(),
    //             fileData;

    //         reader.onload = function(e){
    //             var a=wijmo.grid.ExcelConverter.import(
    //                 reader.result, 
    //                 $scope.mainGridOptions,
    //                 { includeColumnHeader: true }
    //             );
    //             if (wijmo.importData) { 
    //                 hitech.request({
    //                     sub: 'api/products/import_location',
    //                     method: "POST",
    //                     query: {
    //                         apikey: apikey,
    //                         method: 'selection',                               
    //                         importLocation: wijmo.importData
    //                     },

    //                     complete: function (result) {                                
    //                         $.notify(result.response, result.status == 200 ? 'success' : 'error');
    //                         location.reload();
    //                     }

    //                 });
    //             };
    //         };
    //         if ($('#importFile')[0].files[0]) {
    //             reader.readAsArrayBuffer($('#importFile')[0].files[0]);
    //         }
    //     }
    // }


});