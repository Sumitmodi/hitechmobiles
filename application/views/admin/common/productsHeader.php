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