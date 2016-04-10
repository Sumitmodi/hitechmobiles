<script src="http://cdn.kendostatic.com/2015.2.624/js/jszip.min.js"></script>
    <script src="http://cdn.kendostatic.com/2015.2.624/js/kendo.all.min.js"></script>
<div class="table-responsive" ng-scope ng-controller="kendoCtrl">
    <div class="col-md-12">
        <h3 class="pull-left">SLIDER LIST</h3>
        <h3 class="pull-right"><button type="button" class="top-right-button btn btn-success" ng-click="resetSlider()">Reset</button></h3>
    </div>    
    
    <table class="table table-bordered">

    <tr>

        <td>Search</td>

        <td>Items Per Page</td>

        <td>Search Selection</td>

    </tr>

    <tr>

        <td>

            <input type="text" class="form-control" ng-model="query" ng-change="updateFilteredList(query)" placeholder="Search this grid...">

        </td>

        <td>

            <select ng-model="config.itemsPerPage" class="form-control" ng-change="adjustPageSize(config.itemsPerPage)">

                <option value="100" ng-selected="true">100</option>

                <option value="500">500</option>

                <option value="1000">1000</option>

                <option value="2000">2000</option>

                <option value="5000">5000</option>

            </select>

        </td>

        <td>

            <select ng-model="selection" ng-change="updateGridBySelection(selection)" class="form-control">

                <option ng-selected="true">Set Selection </option>

                <option ng-value="omit">Omit Selected</option>

                <option ng-value="keep">Keep Selected</option>

            </select>

        </td>

    </tr>

</table>

    <div class="row">

        <div class="col-md-12">
            <div>
                <kendo-grid options="mainGridOptions" id="mainGridOptions">

                </kendo-grid>
                <!-- <div>{{fileContent}}</div> -->
            </div>

        </div>

    </div>

</div>
<script type="text/javascript">
    var apikey = "<?php echo $this->config->item('hitech_apikey'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/slider/index.js'); ?>"></script>


