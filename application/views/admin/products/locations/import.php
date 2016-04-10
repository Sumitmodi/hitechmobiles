<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="<?php echo base_url(); ?>assets/js/c1xlsx.js"></script>
<script src="<?php echo base_url(); ?>assets/js/wijmo.grid.min.js"></script>
 
<script src="<?php echo base_url(); ?>assets/js/wijmo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/css/wijmo.min.css"></script>
<script src="<?php echo base_url(); ?>assets/js/ExcelConverter.js"></script>

<div class="table-responsive container" ng-controller="kendoCtrl">
    <form role="form" method="POST" action="" enctype="multipart/form-data">
        <div class="table-responsive">
            <input type="file" class="form-control" id="importFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/> 
            <button class="btn btn-default" ng-click="importExcel()">Import</button>
        </div>
    </form>
</div>

<script>
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
    app.controller('kendoCtrl', function ($scope, $requestFactory) { 
        var hitech = new Hitech({
            scope:$scope,
            http:$requestFactory
        });

        $scope.importExcel = function () {
            // if ($scope.mainGridOptions) {
                var reader = new FileReader(),
                    fileData;

                reader.onload = function(e){
                    var a=wijmo.grid.ExcelConverter.import(
                        reader.result, 
                        { includeColumnHeader: true }
                    );
                    
                    if (wijmo.importData) {
                        hitech.request({
                            sub: 'api/products/import_location',
                            method: "POST",
                            query: {
                                apikey: apikey,
                                method: 'selection',                               
                                importLocation: wijmo.importData
                            },

                            complete: function (result) { 
                                $.notify(result.response, result.code == 200 ? 'Import successfully' : 'error');
                            }
                        });
                    };
                };
                if ($('#importFile')[0].files[0]) {
                    reader.readAsArrayBuffer($('#importFile')[0].files[0]);
                }
            // }
        }

    });
</script