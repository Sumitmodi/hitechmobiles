app.controller('kendoCtrl',function($scope,$requestFactory) {

    var statusDropDown = function (container, options) {

        $('<input data-text-field="name" data-value-field="value" data-bind="value:' + options.field + '"/>')

            .appendTo(container)

            .kendoDropDownList({

                autoBind: false,

                dataSource: {

                    data: [{name: "Select Status", value:""}, {name: "Yes", value: "Y"}, {name: "No", value: "N"}]

                }

            });

    };
    document.title = 'SHIPPING TYPE';


    $scope.mainGridOptions = {};

    $scope.checked = [];

    var hitech = new Hitech({

        scope: $scope,

        grid: $scope.mainGridOptions,

        http: $requestFactory

    })



    $scope.updateFilteredList = function (query) {

        hitech.applyFilter({

            field: 'name',

            value: query

        })



    }



    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: base + 'api/order/shipping_type?apikey='+apikey,
                dataType: "json"
            },
            create: {
                url: base + 'api/order/shipping_type',
                type: "POST",
                dataType: "json"
            },
            update: {

                url:  base + 'api/order/shipping_type',

                type: "POST",

                dataType: "json"

            },

            destroy: {

                url: base + 'api/order/delete_shipping_type',

                type: "POST",

                dataType: "json"

            },
            parameterMap: function(options, operation) {
                if (operation !== "read") {
                    if (options.models.length > 0) {
                         return {models:(options.models),apikey:apikey};
                    } else {
                        return {models:(options),apikey:apikey}; 
                    }
                }
            }
        },

        pageSize: 20,
        batch: true,
        schema: {

            model: {

                id: "id",

                fields: {

                    type: { validation: { required: true }, editable:true }

                }

            }

        },

       

        pageable: true

    });



    $scope.mainGridOptions = {

        dataSource: dataSource,

        sortable: true,

        toolbar: [

            "create",

            {

                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'

            },

            {

                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'

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

            },{

            field: "type",

            title: "Shipping Type",

            },{

            field: "description",

            title: "Shipping Detail",
            editor: "<textarea rows='8' cols='22' name='description'></textarea>"

            },{

            field: "date_created",

            title: "Date Created",
            },{
            command: ["edit", "destroy"], title: "&nbsp;", width: "250px" 
        }],

        dataBound: function () {

          $(".checkbox").bind("change", function (e) {

            $(e.target).closest("tr").toggleClass("k-state-selected");

          });

        }, 

        edit: function(e) {
            $('label[for=id]').parent().remove();
            e.container.find(".k-edit-field:first").hide();
            e.container.find(".k-edit-field:first+").hide();

            $('label[for=date_created]').parent().remove();
            e.container.find(".k-edit-field:last").hide();

        }          

    };



    



    $scope.adjustPageSize = function (size) {

        hitech.pageSize(size);

    }





    $scope.deleteSelected = function () {

        var ids = hitech.prepareDelete();

        if (ids.length > 0) {

            if (confirm("Are you sure you want to delete " + ids.length + " vendors?") === false) {

                return;

            }



            hitech.request({

                sub: 'api/api/rest',

                query: {

                    apikey: apikey,

                    table: 'vendors',

                    action: 'delete',

                    model: {

                        where_in: {

                            sn: ids

                        }

                    }

                },

                method: 'POST',

                complete: function (result) {

                    if (result.code == 200) {

                        hitech.scope.checked = [];

                        hitech.omitSelected(ids);

                        $.notify("Selected vendors were deleted successfully.", {

                            className: 'success',

                            autoHideDelay: '10000'

                        });

                    } else {

                        $.notify("Selected vendors could not be deleted.", {

                            className: 'error',

                            autoHideDelay: '10000'

                        });

                    }

                }

            });

        } else {

            $.notify("Please select at least one vendors.", {

                className: 'info',

                autoHideDelay: '10000'

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

                            identify: 'V',

                            userSN: {method: 'getVar|_userSN'}

                        }

                    },

                    action: "delete"

                },

                method: 'POST',

                complete: function (result) {

                    if (result.code === 200) {

                        $.notify("Vendors have been reset successfully.", {

                            className: 'success',

                            autoHideDelay: '10000'

                        });

                        hitech.request({

                            sub: 'api/api/rest',

                            method: "POST",

                            query: {

                                apikey: apikey,

                                table: 'vendors',

                                model: {

                                    where: {

                                        adminSN: {method: 'getVar|_userSN'}

                                    }

                                }

                            },

                            complete: function (res) {

                                if (res != false) {

                                    $scope.mainGridOptions.dataSource.data(res.result);

                                    $.notify("Vendors have been reloaded.", {

                                        className: 'success',

                                        autoHideDelay: '10000'

                                    });

                                } else {

                                    $.notify("Vendors could not be reloaded.", {

                                        className: 'error',

                                        autoHideDelay: '10000'

                                    });

                                }

                            }

                        });

                    } else {

                        $.notify("Vendors could not be reset.", {

                            className: 'error',

                            autoHideDelay: '10000'

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

                type: 'V'

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