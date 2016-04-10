app.controller('kendoCtrl', function ($scope, $requestFactory) {

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

    $scope.mainGridOptions = {};

    $scope.checked = [];

    var hitech = new Hitech({

        scope: $scope,

        grid: $scope.mainGridOptions,

        http: $requestFactory

    });

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
                                                    { field: "parent_id", operator: "contains", value:v1},
                                                    { field: "name", operator: "contains", value:v1});
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

                            identify: 'PC',

                            userSN: {method: 'getVar|_userSN'}

                        }

                    },

                    action: "delete"

                },

                method: 'POST',

                complete: function (result) {

                    if (result.code === 200) {

                        $.notify("Category list has been reset successfully.", 'success');

                        hitech.request({

                            sub: 'api/customers/category',

                            method: "GET",                                

                            query: {

                                apikey: apikey, 

                                for: 'dropdown',

                                table: 'category',

                                model: {

                                    where: {

                                        adminSN: {method: 'getVar|_userSN'}

                                    }

                                }

                            },

                            complete: function (res) {

                                if (res != false) {

                                    $scope.mainGridOptions.dataSource.data(res);

                                    $.notify("Category list has been reset reloaded.", 'success');

                                } else {

                                    $.notify("Category list could not be reloaded.", 'error');

                                }

                            }

                        });

                    } else {

                        $.notify("Category list could not be reset.", 'error');

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

            $.notify("Please select at least one category.", 'info');

            return;

        }



        hitech.request({

            sub: 'api/api/rest',

            query: {

                apikey: apikey,

                method: 'selection',

                flag: selection.split(' ')[0].toLowerCase(),

                id: res,

                type: 'PC'

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

            if (confirm("Are you sure you want to delete " + ids.length + " categories?") === false) {

                return;

            }



            hitech.request({

                sub: 'api/api/rest',

                query: {apikey: apikey, method: 'categories', action: 'delete', id: ids, multiple: 'multiple'},

                method: 'POST',

                complete: function (result) {

                    $.notify(result.response, result.status == 200 ? 'success' : 'error');

                    if (result.status == 200) {

                        hitech.scope.checked = [];

                        hitech.omitSelected(ids);

                    }

                }

            });

        } else {

            $.notify("Please select at least one category.", 'info');

        }

    }



    var parentDropDown = function (container, options) {

        $('<input data-text-field="name" data-value-field="value" data-bind="value:' + options.field + '"/>')

            .appendTo(container)

            .kendoDropDownList({

                autoBind: false,

                serverFiltering: true,

                dataSource: dataSource1

            });

    };



    // for parent

    var dataSource1 = new kendo.data.DataSource({

        transport: {

            read: {

                url: base + 'api/customers/category?for=&&apikey=' + apikey,

                dataType: "json"



            }

        }

    });

    // end parent



    var dataSource = new kendo.data.DataSource({

        transport: {

            read: {

                url: base + 'api/customers/category?for=dropdown&&apikey=' + apikey,

                dataType: "json"

            },

            update: {

                url: base + 'api/customers/category',

                type: "POST",

                dataType: "json"

            },

            create: {

                url: base + 'api/customers/category',

                type: "POST",

                dataType: "json"

            },

            destroy: {

                url: base + 'api/customers/deleteCategories',

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

            }

        },

        pageSize: 35,

        batch: true,

        schema: {

            model: {

                id: "id",

                fields: {

                    name: {validation: {required: true}, editable: true},

                    status: {type: "string", editable: true},

                    parent_id: {type: "string", editable: true}

                }

            }

        },



        pageable: true

    });



    $scope.mainGridOptions = {

        dataSource: dataSource,

        sortable: true,

        pageable: true,

        toolbar: ["create",

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

            template: "<input type='checkbox' class='checkbox' ng-model=\"entity.isChecked\" ng-click=\"onSelection()\"/>"

        }, {

            field: "id",

            title: "Id"

        }, {

            field: "parent_id",

            title: "Parent Name",

            editor: parentDropDown,

            hidden: true

        }, {

            field: "name",

            title: "Name"

        }, 
        // {

        // field: "status",

        // title: "Status",

        // template: function (data) {

        //         if (data.status == "Y") {

        //             return "<span class='glyphicon glyphicon-ok'></span>";

        //         } else {

        //             return "<span class='glyphicon glyphicon-remove'></span>";

        //         }

        //     },

        //     editor: statusDropDown

        // }, 
        {

            field: "created_date",

            title: "Date Created",

        },{

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



            $('label[for=created_date]').parent().remove();

            e.container.find(".k-edit-field:first++++++++++++++").hide();

        },

        change: function (e) {

            hitech.gridChangeEvent(this, e);

        }

    };



    $scope.detailGridOptions = function (dataItem) {

        var dataSource = new kendo.data.DataSource({

            transport: {

                read: {

                    url: base + 'api/customers/category_detail?id=' + dataItem.id + '&&apikey=' + apikey,

                    dataType: "json"

                },

                update: {

                    url: base + 'api/customers/category' + '?edit=detail',

                    type: "POST",

                    dataType: "json"

                },

                destroy: {

                    url: base + 'api/customers/deleteCategories' + '?edit=detail',

                    type: "POST",

                    dataType: "json"

                },

                parameterMap: function (options, operation) {

                    if (operation !== "read") {

                        return {models: (options), apikey: apikey};

                    }

                }

            },

            pageSize: 35,

            pageable: true,

            schema: {

                model: {

                    id: "id",

                    fields: {

                        name: {validation: {required: true}, editable: true},

                        status: {type: "string", editable: true},

                        parent_id: {type: "string", editable: true}

                    }

                }

            }

        });

        return {

            dataSource: dataSource,

            scrollable: false,

            sortable: true,

            editable: "popup",

            pageable: true,

            selectable: "multiple",

            columns: [

                {field: "id", title: "Id"},

                {field: "parent_id", title: "Parent Id", hidden: true},

                {field: "name", title: "Name"},

                {

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

                    field: "created_date",

                    title: "Date Created"

                },

                {command: ["edit", "destroy"], title: "&nbsp;", width: "250px"}

            ],

            edit: function (e) {

                $('label[for=id]').parent().remove();

                e.container.find(".k-edit-field:first").hide();

                $('label[for=parent_id]').parent().remove();

                e.container.find(".k-edit-field:first+").hide();



                $('label[for=created_date]').parent().remove();

                e.container.find(".k-edit-field:last").hide();

            }

        };

    };





    

    $scope.detailSecondGridOptions = function (dataItem) {

        var dataSource = new kendo.data.DataSource({

            transport: {

                read: {

                    url: base + 'api/customers/category_detail?id=' + dataItem.id + '&&apikey=' + apikey,

                    dataType: "json"

                },

                update: {

                    url: base + 'api/customers/category' + '?edit=detail',

                    type: "POST",

                    dataType: "json"

                },

                destroy: {

                    url: base + 'api/customers/deleteCategories' + '?edit=detail',

                    type: "POST",

                    dataType: "json"

                },

                parameterMap: function (options, operation) {

                    if (operation !== "read") {

                        return {models: (options), apikey: apikey};

                    }

                }

            },

            pageSize: 35,

            pageable: true,

            schema: {

                model: {

                    id: "id",

                    fields: {

                        name: {validation: {required: true}, editable: true},

                        status: {type: "string", editable: true},

                        parent_id: {type: "string", editable: true}

                    }

                }

            }

        });

        return {

            dataSource: dataSource,

            scrollable: false,

            sortable: true,

            editable: "popup",

            pageable: true,

            selectable: "multiple",

            columns: [

                {field: "id", title: "Id"},

                {field: "parent_id", title: "Parent Id", hidden: true},

                {field: "name", title: "Name"},

                {

                    field: "status", title: "Status", template: function (data) {

                    if (data.status == "Y") {

                        return "<span class='glyphicon glyphicon-ok'></span>";

                    } else {

                        return "<span class='glyphicon glyphicon-remove'></span>";

                    }

                },

                editor: statusDropDown

                }, {

                    field: "created_date",

                    title: "Date Created"

                },

                {command: ["edit", "destroy"], title: "&nbsp;", width: "250px"}

            ],

            edit: function (e) {

                $('label[for=id]').parent().remove();

                e.container.find(".k-edit-field:first").hide();

                $('label[for=parent_id]').parent().remove();

                e.container.find(".k-edit-field:first+").hide();



                $('label[for=created_date]').parent().remove();

                e.container.find(".k-edit-field:last").hide();

            }

        };

    };



    // delete

    $scope.deleteCategories = function (dataItem) {

        var grid = $("#mainGridOptions").data('kendo-grid'),

            selected_grid = grid.select(),

            ids = [];



        for (var i = 0; i < selected_grid.length; i++) {

            data = grid.dataItem(selected_grid[i]);

            ids.push(data.id);

        }

        ;

        var keepomitUrl = base + 'api/customers/deleteCategoriesMulti';

        var postdata = {

            id: ids,

            apikey: apikey

        };

        $async = $http.post(keepomitUrl, postdata).then(function (data) {

            // debugger;

        });

    };

});