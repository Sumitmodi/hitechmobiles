'use strict'
app.controller('testiCtrl', ['$scope', '$requestFactory', function ($scope, $requestFactory) {
    $scope.mainGridOptions = {};
    $scope.checked = [];

    var hitech = new Hitech({
        scope: $scope,
        grid: $scope.mainGridOptions,
        http: $requestFactory
    })

    $scope.addTestimonial = function () {
        window.location = base + 'admin/testimonials/edit/0';
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
                type: 'T'
            },
            complete: function (result) {
                $.notify(result.response, {
                    className: result.status == 200 ? 'success' : 'error',
                    autoHideDelay: '10000'
                });
            }
        });
    };

    $scope.deleteSelected = function () {
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " testimonials?") === false) {
                return;
            }
            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'testimonials',
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
                        $.notify("Selected testimonials were deleted successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                    } else {
                        $.notify("Selected testimonials could not be deleted.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            });
        } else {
            $.notify("Please select at least one testimonial.", {
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
                            identify: 'T',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Testimonials list have been reset successfully.", {
                            className: 'success',
                            autoHideDelay: '10000'
                        });
                        hitech.request({
                            sub: 'api/api/rest',
                            method: "POST",
                            query: {
                                apikey: apikey,
                                table: 'testimonials',
                                model: {
                                    where: {
                                        adminSN: {method: 'getVar|_userSN'}
                                    }
                                }
                            },
                            complete: function (res) {
                                if (res != false) {
                                    $scope.mainGridOptions.dataSource.data(res.result);
                                    $.notify("Testimonials list have been reloaded.", {
                                        className: 'success',
                                        autoHideDelay: '10000'
                                    });
                                } else {
                                    $.notify("Testimonials list could not be reloaded.", {
                                        className: 'error',
                                        autoHideDelay: '10000'
                                    });
                                }
                            }
                        });
                    } else {
                        $.notify("Testimonials list could not be reset.", {
                            className: 'error',
                            autoHideDelay: '10000'
                        });
                    }
                }
            }
        });
    };

    $scope.updateFilteredList = function (query) {
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
                            orfilter.filters.push({field: "customer", operator: "contains", value: v1},
                                {field: "feedback", operator: "contains", value: v1});
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
    };

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: base + 'api/testimonials/list?apikey=' + apikey,
                dataType: "json"
            }
        },
        pageSize: 20,
        pageable: true
    });

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        pageable: true,
        toolbar : [
            {
                template: '<a href="javascript:;" ng-click="addTestimonial()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add a testimonial</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-delete"><span class="k-icon k-delete"></span>Delete selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-reset"><span class="k-icon k-reset"></span>Reset</a>'
            }
        ],
        editable: false,
        selectable: "multiple",
        columns: [
            {
                width: 35,
                template: "<input type='checkbox' class='checkbox' />"
            },
            {
                field: "sn",
                title: "SN",
                width: 35,
                template: function (data) {
                    return '<a href="' + base + 'admin/testimonials/edit/' + data.sn + '">' + data.sn + '</a>';
                }
            }, {
                field: "customer",
                title: "Customer"
            }, {
                field: "feedback",
                title: "Feedback"
            }, {
                field: "status",
                title: "Status",
                template: function (data) {
                    if (data.status == "1") {
                        return "<span class='glyphicon glyphicon-ok'></span>";
                    } else {
                        return "<span class='glyphicon glyphicon-remove'></span>";
                    }
                }

            }, {
                field: "created_date",
                title: "Date created",
                id: 'createdDate'
            }],
        dataBound: function () {
            hitech.gridDataBound('checkbox');
            var grid = $("#mainGridOptions").data("kendoGrid"), topPagination = $('#topPageCount');
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
}]);
