app.controller('kendoCtrl', function ($scope, $requestFactory) {
    $scope.mainGridOptions = {};
    $scope.checked = [];
    var hitech = new Hitech({
        scope:$scope,
        grid:$scope.mainGridOptions,
        http:$requestFactory
    })
    document.title = 'DEALERS';

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
                                                    { field: "first_name", operator: "contains", value:v1},
                                                    { field: "last_name", operator: "contains", value:v1},
                                                    { field: "company_name", operator: "contains", value:v1},
                                                    { field: "email", operator: "contains", value:v1},
                                                    { field: "phone", operator: "contains", value:v1},
                                                    { field: "website", operator: "contains", value:v1},
                                                    { field: "gst_no", operator: "contains", value:v1},
                                                    { field: "address", operator: "contains", value:v1},
                                                    { field: "city", operator: "contains", value:v1},
                                                    { field: "post_code", operator: "contains", value:v1},
                                                    { field: "country", operator: "contains", value:v1},
                                                    { field: "interest", operator: "contains", value:v1},
                                                    { field: "est_month_handset_expenditure", operator: "contains", value:v1});
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

    $scope.addDealer = function () {
        window.location = base + 'admin/dealers/edit/0';
    }

    $scope.adjustPageSize = function(size){
        hitech.pageSize(size);
    }

    $scope.deleteSelected = function(){
        var ids = hitech.prepareDelete();
        if (ids.length > 0) {
            if (confirm("Are you sure you want to delete " + ids.length + " dealer?") === false) {
                return;
            }

            hitech.request({
                sub: 'api/api/rest',
                query: {
                    apikey: apikey,
                    table: 'dealer',
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
                        $.notify("Selected dealer were deleted successfully.", {
                            className:'success',
                            autoHideDelay:'10000'
                        });
                        location.reload();
                    }else{
                        $.notify("Selected dealer could not be deleted.", {
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
                            identify: 'DL',
                            userSN: {method: 'getVar|_userSN'}
                        }
                    },
                    action: "delete"
                },
                method: 'POST',
                complete: function (result) {
                    if (result.code === 200) {
                        $.notify("Dealers have been reset successfully.", {
                            className:'success',
                            autoHideDelay:'10000'
                        });
                        hitech.request({
                            sub: 'api/api/rest',
                            method: "POST",
                            query: {
                                apikey: apikey,
                                table:'dealer',
                                model: {
                                    where: {
                                        adminSN: {method: 'getVar|_userSN'}
                                    }
                                }
                            },
                            complete: function (res) {
                                if (res != false) {
                                    $scope.mainGridOptions.dataSource.data(res.result);
                                    $.notify("Dealers have been reloaded.", {
                                        className:'success',
                                        autoHideDelay:'10000'
                                    });
                                } else {
                                    $.notify("Dealers could not be reloaded.", {
                                        className:'error',
                                        autoHideDelay:'10000'
                                    });
                                }
                            }
                        });
                    } else {
                        $.notify("Dealers could not be reset.", {
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
                type: 'DL'
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

    // var interest = function (container, options){
    //     $("<input type='checkbox' name='vehicle' value='Bike'> I have a bike<br>
    //         <input type='checkbox' name='vehicle' value='Car' checked> I have a car<br>")
    //         .appendTo(container)
    //         .kendoCheckBoxList({
    //             autoBind: false
    //             /*dataSource: {
    //                 data: [{name: "Select Status", value: ""}, {name: "Yes", value: "Y"}, {name: "No", value: "N"}]
    //             }*/
    //         });
    // };

    var countryDropDown = function (container, options){
        $('<input data-text-field="name" data-value-field="value" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                autoBind: false,
                dataSource: {
                    data: [
                        {name: "Select Status", value: ""}, 
                        {name: "Andorra", value:"Andorra"},
                        {name: "United Arab Emirates", value:"United Arab Emirates"},
                        {name: "Afghanistan", value:"Afghanistan"},
                        {name: "Antigua and Barbuda", value:"Antigua and Barbuda"},
                        {name: "Anguilla", value:"Anguilla"},
                        {name: "Albania", value:"Albania"},
                        {name: "Armenia", value:"Armenia"},
                        {name: "Angola", value:"Angola"},
                        {name: "Antarctica", value:"Antarctica"},
                        {name: "Argentina", value:"Argentina"},
                        {name: "American Samoa", value:"American Samoa"},
                        {name: "Austria", value:"Austria"},
                        {name: "Australia", value:"Australia"},
                        {name: "Aruba", value:"Aruba"},
                        {name: "Azerbaijan", value:"Azerbaijan"},
                        {name: "Bosnia and Herzegovina", value:"Bosnia and Herzegovina"},
                        {name: "Barbados", value:"Barbados"},
                        {name: "Bangladesh", value:"Bangladesh"},
                        {name: "Belgium", value:"Belgium"},
                        {name: "Burkina Faso", value:"Burkina Faso"},
                        {name: "Bulgaria", value:"Bulgaria"},
                        {name: "Bahrain", value:"Bahrain"},
                        {name: "Burundi", value:"Burundi"},
                        {name: "Benin", value:"Benin"},
                        {name: "Saint Barthelemy", value:"Saint Barthelemy"},
                        {name: "Bermuda", value:"Bermuda"},
                        {name: "Brunei", value:"Brunei"},
                        {name: "Bolivia", value:"Bolivia"},
                        {name: "Brazil", value:"Brazil"},
                        {name: "Bahamas, The", value:"Bahamas, The"},
                        {name: "Bhutan", value:"Bhutan"},
                        {name: "Bouvet Island", value:"Bouvet Island"},
                        {name: "Botswana", value:"Botswana"},
                        {name: "Belarus", value:"Belarus"},
                        {name: "Belize", value: "Belize"},
                        {name: "Canada", value: "Canada"},
                        {name: "Cocos (Keeling) Islands", value: "Cocos (Keeling) Islands"},
                        {name: "Congo, Democratic Republic of the", value: "Congo, Democratic Republic of the"},
                        {name: "Central African Republic", value: "Central African Republic"},
                        {name: "Congo, Republic of the", value: "Congo, Republic of the"},
                        {name: "Switzerland", value: "Switzerland"},
                        {name: "Cote d'Ivoire", value: "Cote d'Ivoire"},
                        {name: "Cook Islands", value: "Cook Islands"},
                        {name: "Chile", value: "Chile"},
                        {name: "Cameroon", value: "Cameroon"},
                        {name: "China", value: "China"},
                        {name: "Colombia", value: "Colombia"},
                        {name: "Costa Rica", value: "Costa Rica"},
                        {name: "Cuba", value: "Cuba"},
                        {name: "Cape Verde", value: "Cape Verde"},
                        {name: "Curacao", value: "Curacao"},
                        {name: "Christmas Island", value: "Christmas Island"},
                        {name: "Cyprus", value: "Cyprus"},
                        {name: "Czech Republic", value: "Czech Republic"},
                        {name: "Germany", value: "Germany"},
                        {name: "Djibouti", value: "Djibouti"},
                        {name: "Denmark", value: "Denmark"},
                        {name: "Dominica", value: "Dominica"},
                        {name: "Dominican Republic", value: "Dominican Republic"},
                        {name: "Algeria", value: "Algeria"},
                        {name: "Ecuador", value: "Ecuador"},
                        {name: "Estonia", value: "Estonia"},
                        {name: "Egypt", value: "Egypt"},
                        {name: "Western Sahara", value: "Western Sahara"},
                        {name: "Eritrea", value: "Eritrea"},
                        {name: "Spain", value: "Spain"},
                        {name: "Ethiopia", value: "Ethiopia"},
                        {name: "Finland", value: "Finland"},
                        {name: "Fiji", value: "Fiji"},
                        {name: "Falkland Islands (Islas Malvinas)", value: "Falkland Islands (Islas Malvinas)"},
                        {name: "Micronesia, Federated States of", value: "Micronesia, Federated States of"},
                        {name: "Faroe Islands", value: "Faroe Islands"},
                        {name: "France", value: "France"},
                        {name: "France, Metropolitan", value: "France, Metropolitan"},
                        {name: "Gabon", value: "Gabon"},
                        {name: "United Kingdom", value: "United Kingdom"},
                        {name: "Grenada", value: "Grenada"},
                        {name: "Georgia", value: "Georgia"},
                        {name: "French Guiana", value: "French Guiana"},
                        {name: "Guernsey", value: "Guernsey"},
                        {name: "Ghana", value: "Ghana"},
                        {name: "Gibraltar", value: "Gibraltar"},
                        {name: "Greenland", value: "Greenland"},
                        {name: "Gambia, The", value: "Gambia, The"},
                        {name: "Guinea", value: "Guinea"},
                        {name: "Guadeloupe", value: "Guadeloupe"},
                        {name: "Equatorial Guinea", value: "Equatorial Guinea"},
                        {name: "Greece", value: "Greece"},
                        {name: "South Georgia and the Islands", value: "South Georgia and the Islands"},
                        {name: "Guatemala", value: "Guatemala"},
                        {name: "Guam", value: "Guam"},
                        {name: "Guinea-Bissau", value: "Guinea-Bissau"},
                        {name: "Guyana", value: "Guyana"},
                        {name: "Hong Kong", value: "Hong Kong"},
                        {name: "Heard Island and McDonald Islands", value: "Heard Island and McDonald Islands"},
                        {name: "Honduras", value: "Honduras"},
                        {name: "Croatia", value: "Croatia"},
                        {name: "Haiti", value: "Haiti"},
                        {name: "Hungary", value: "Hungary"},
                        {name: "Indonesia", value: "Indonesia"},
                        {name: "Ireland", value: "Ireland"},
                        {name: "Israel", value: "Israel"},
                        {name: "Isle of Man", value: "Isle of Man"},
                        {name: "India", value: "India"},
                        {name: "British Indian Ocean Territory", value: "British Indian Ocean Territory"},
                        {name: "Iraq", value: "Iraq"},
                        {name: "Iran", value: "Iran"},
                        {name: "Iceland", value: "Iceland"},
                        {name: "Italy", value: "Italy"},
                        {name: "Jersey", value: "Jersey"},
                        {name: "Jamaica", value: "Jamaica"},
                        {name: "Jordan", value: "Jordan"},
                        {name: "Japan", value: "Japan"},
                        {name: "Kenya", value: "Kenya"},
                        {name: "Kyrgyzstan", value: "Kyrgyzstan"},
                        {name: "Cambodia", value: "Cambodia"},
                        {name: "Kiribati", value: "Kiribati"},
                        {name: "Comoros", value: "Comoros"},
                        {name: "Saint Kitts and Nevis", value: "Saint Kitts and Nevis"},
                        {name: "Korea, North", value: "Korea, North"},
                        {name: "Korea, South", value: "Korea, South"},
                        {name: "Kuwait", value: "Kuwait"},
                        {name: "Cayman Islands", value: "Cayman Islands"},
                        {name: "Kazakhstan", value: "Kazakhstan"},
                        {name: "Laos", value: "Laos"},
                        {name: "Lebanon", value: "Lebanon"},
                        {name: "Saint Lucia", value: "Saint Lucia"},
                        {name: "Liechtenstein", value: "Liechtenstein"},
                        {name: "Sri Lanka", value: "Sri Lanka"},
                        {name: "Liberia", value: "Liberia"},
                        {name: "Lesotho", value: "Lesotho"},
                        {name: "Lithuania", value: "Lithuania"},
                        {name: "Luxembourg", value: "Luxembourg"},
                        {name: "Latvia", value: "Latvia"},
                        {name: "Libya", value: "Libya"},
                        {name: "Morocco", value: "Morocco"},
                        {name: "Monaco", value: "Monaco"},
                        {name: "Moldova", value: "Moldova"},
                        {name: "Montenegro", value: "Montenegro"},
                        {name: "Saint Martin", value: "Saint Martin"},
                        {name: "Madagascar", value: "Madagascar"},
                        {name: "Marshall Islands", value: "Marshall Islands"},
                        {name: "Macedonia", value: "Macedonia"},
                        {name: "Mali", value: "Mali"},
                        {name: "Burma", value: "Burma"},
                        {name: "Mongolia", value: "Mongolia"},
                        {name: "Macau", value: "Macau"},
                        {name: "Northern Mariana Islands", value: "Northern Mariana Islands"},
                        {name: "Martinique", value: "Martinique"},
                        {name: "Mauritania", value: "Mauritania"},
                        {name: "Montserrat", value: "Montserrat"},
                        {name: "Malta", value: "Malta"},
                        {name: "Mauritius", value: "Mauritius"},
                        {name: "Maldives", value: "Maldives"},
                        {name: "Malawi", value: "Malawi"},
                        {name: "Mexico", value: "Mexico"},
                        {name: "Malaysia", value: "Malaysia"},
                        {name: "Mozambique", value: "Mozambique"},
                        {name: "Namibia", value: "Namibia"},
                        {name: "New Caledonia", value: "New Caledonia"},
                        {name: "Niger", value: "Niger"},
                        {name: "Norfolk Island", value: "Norfolk Island"},
                        {name: "Nigeria", value: "Nigeria"},
                        {name: "Nicaragua", value: "Nicaragua"},
                        {name: "Netherlands", value: "Netherlands"},
                        {name: "Norway", value: "Norway"},
                        {name: "Nepal", value: "Nepal"},
                        {name: "Nauru", value: "Nauru"},
                        {name: "Niue", value: "Niue"},
                        {name: "New Zealand", value: "New Zealand"},
                        {name: "Oman", value: "Oman"},
                        {name: "Panama", value: "Panama"},
                        {name: "Peru", value: "Peru"},
                        {name: "French Polynesia", value: "French Polynesia"},
                        {name: "Papua New Guinea", value: "Papua New Guinea"},
                        {name: "Philippines", value: "Philippines"},
                        {name: "Pakistan", value: "Pakistan"},
                        {name: "Poland", value: "Poland"},
                        {name: "Saint Pierre and Miquelon", value: "Saint Pierre and Miquelon"},
                        {name: "Pitcairn Islands", value: "Pitcairn Islands"},
                        {name: "Puerto Rico", value: "Puerto Rico"},
                        {name: "Gaza Strip", value: "Gaza Strip"},
                        {name: "West Bank", value: "West Bank"},
                        {name: "Portugal", value: "Portugal"},
                        {name: "Palau", value: "Palau"},
                        {name: "Paraguay", value: "Paraguay"},
                        {name: "Qatar", value: "Qatar"},
                        {name: "Reunion", value: "Reunion"},
                        {name: "Romania", value: "Romania"},
                        {name: "Serbia", value: "Serbia"},
                        {name: "Russia", value: "Russia"},
                        {name: "Rwanda", value: "Rwanda"},
                        {name: "Saudi Arabia", value: "Saudi Arabia"},
                        {name: "Solomon Islands", value: "Solomon Islands"},
                        {name: "Seychelles", value: "Seychelles"},
                        {name: "Sudan", value: "Sudan"},
                        {name: "Sweden", value: "Sweden"},
                        {name: "Singapore", value: "Singapore"},
                        {name: "Saint Helena, Ascension, and Tristan da Cunha", value: "Saint Helena, Ascension, and Tristan da Cunha"},
                        {name: "Slovenia", value: "Slovenia"},
                        {name: "Svalbard", value: "Svalbard"},
                        {name: "Slovakia", value: "Slovakia"},
                        {name: "Sierra Leone", value: "Sierra Leone"},
                        {name: "San Marino", value: "San Marino"},
                        {name: "Senegal", value: "Senegal"},
                        {name: "Somalia", value: "Somalia"},
                        {name: "Suriname", value: "Suriname"},
                        {name: "South Sudan", value: "South Sudan"},
                        {name: "Sao Tome and Principe", value: "Sao Tome and Principe"},
                        {name: "El Salvador", value: "El Salvador"},
                        {name: "Sint Maarten", value: "Sint Maarten"},
                        {name: "Syria", value: "Syria"},
                        {name: "Swaziland", value: "Swaziland"},
                        {name: "Turks and Caicos Islands", value: "Turks and Caicos Islands"},
                        {name: "Chad", value: "Chad"},
                        {name: "French Southern and Antarctic Lands", value: "French Southern and Antarctic Lands"},
                        {name: "Togo", value: "Togo"},
                        {name: "Thailand", value: "Thailand"},
                        {name: "Tajikistan", value: "Tajikistan"},
                        {name: "Tokelau", value: "Tokelau"},
                        {name: "Timor-Leste", value: "Timor-Leste"},
                        {name: "Turkmenistan", value: "Turkmenistan"},
                        {name: "Tunisia", value: "Tunisia"},
                        {name: "Tonga", value: "Tonga"},
                        {name: "Turkey", value: "Turkey"},
                        {name: "Trinidad and Tobago", value: "Trinidad and Tobago"},
                        {name: "Tuvalu", value: "Tuvalu"},
                        {name: "Taiwan", value: "Taiwan"},
                        {name: "Tanzania", value: "Tanzania"},
                        {name: "Ukraine", value: "Ukraine"},
                        {name: "Uganda", value: "Uganda"},
                        {name: "United States Minor Outlying Islands", value: "United States Minor Outlying Islands"},
                        {name: "United States", value: "United States"},
                        {name: "Uruguay", value: "Uruguay"},
                        {name: "Uzbekistan", value: "Uzbekistan"},
                        {name: "Holy See (Vatican City)", value: "Holy See (Vatican City)"},
                        {name: "Saint Vincent and the Grenadines", value: "Saint Vincent and the Grenadines"},
                        {name: "Venezuela", value: "Venezuela"},
                        {name: "British Virgin Islands", value: "British Virgin Islands"},
                        {name: "Virgin Islands", value: "Virgin Islands"},
                        {name: "Vietnam", value: "Vietnam"},
                        {name: "Vanuatu", value: "Vanuatu"},
                        {name: "Wallis and Futuna", value: "Wallis and Futuna"},
                        {name: "Samoa", value: "Samoa"},
                        {name: "Kosovo", value: "Kosovo"},
                        {name: "Yemen", value: "Yemen"},
                        {name: "Mayotte", value: "Mayotte"},
                        {name: "South Africa", value: "South Africa"},
                        {name: "Zambia", value: "Zambia"},
                        {name: "Zimbabwe", value: "Zimbabwe"}
                    ]
                }
            });
    }

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {

                url: base + 'api/dealers/dealers_list?apikey=' + apikey,
                dataType: "json"
            },
            create: {
                // url: base + 'api/page/pages',
                url: base + 'api/dealers/dealers_insert',
                type: "POST",
                dataType: "json"
            },
            update: {
                url: base + 'api/dealers/dealers_insert',
                type: "POST",
                dataType: "json"
            },
            destroy: {
                url: base + 'api/dealers/delete',
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

        pageSize: 20,
        batch: true,
        schema: {
            model: {
                id: "id",
                fields: {
                    name: {validation: {required: true}, editable: true},
                    description: {validation: {required: true}, editable: true},
                    status: {type: "string", editable: true}
                }
            }
        },

        pageable: true
    });

    $scope.mainGridOptions = {
        dataSource: dataSource,
        sortable: true,
        pageable: true,
        selectable: "multiple",                
        toolbar:[
            {
                template: '<a href="javascript:;" ng-click="addDealer()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-add"></span>Add new dealer</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="deleteSelected()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-delete"></span>Delete Selected</a>'
            },
            {
                template: '<a href="javascript:;" ng-click="resetSelected()" class="k-button k-button-icontext k-grid-add"><span class="k-icon k-reset"></span>Reset</a>'
            }
        ],
        columns: [{
            width: 35,
            template: "<input type='checkbox' class='checkbox' />"
        },
            {
                field: "id",
                title: "Id",
                width: 35,
                template:function(data){
                    return '<a href="'+base+'admin/dealers/edit/'+data.id+'">'+data.id+'</a>';
                }
            }, {
                field: "first_name",
                title: "First Name",
                width: 80
            }, {
                field: "last_name",
                title: "Last Name"
            },{
                field: "company_name",
                title: "Company Name",
            },{
                field: "email",
                title: "Email",
            },{
                field: "phone",
                title: "Phone",
            },{
                field: "website",
                title: "Website",
            },{
                field: "gst_no",
                title: "Gst No.",
            },{
                field: "address",
                title: "Address",
            },{
                field: "city",
                title: "City",
            },{
                field: "post_code",
                title: "Post Code",
            },{
                field: "country",
                title: "Country",
                template: function (data){
                    return "<span>"+data.country+"</span>";
                },
                editor: countryDropDown
            },{
                field: "interest",
                title: "Interest",
                // editor: interest
            },{
                field: "est_month_handset_expenditure",
                title: "Est. Month Handset Expenditure",
            },{
                field: "password",
                title: "Password",
                hidden: true
            },{
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

            $('label[for=createdDate]').parent().remove();
            e.container.find(".k-edit-field:last").hide();

            /*$('label[for=createdDate]').parent().remove();
            e.container.find(".k-edit-field:first++++++++++++++").hide();*/
        },
        change:function(e){
            hitech.gridChangeEvent(this,e);
        }
    };
});