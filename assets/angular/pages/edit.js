'use string';
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle)
            return true;
    }
    return false;
}

// for serial key
var serialKeys = [];

app.controller('pagesEditCtrl', ['$scope', '$upload', '$requestFactory', function ($scope, $upload, $request) {
   
    $scope.pages = {
        id:'',
        name: '',
        description: '',
        slug_name: '',
        meta_title: '',
        meta_description: '',
        meta_keywords: ''
    };
    


    $scope.addPages = function () {
        $scope.pages.description = CKEDITOR.instances.description.getData();

        $request.setSub('api/page/edit/'+ ($scope.editMode ? $scope.pagesId : 0));
        $request.setQuery({apikey: apikey, pages: $scope.pages});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.code==200) {
                window.location = base + 'admin/pages';
            } else {
                alert('Unable to add page');
            }
            
        });
        
         
    };

    

    if (editMode) 
    {
        $scope.pages.id = editId;
        $scope.editMode = true;
        $scope.pagesId = editId;
        $request.setSub('api/products/rest');
        $request.setMethod('POST');
        $request.setQuery({
            apikey: apikey,
            table: 'pages',
            model: {
                where: {
                    id: editId
                },
                select: [
                    'id',
                    'name',
                    'description',
                    'slug_name',
                    'meta_title',
                    'meta_description',
                    'meta_keywords'
                ]
            }
            
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.pages = result.result[0];
                CKEDITOR.instances.description.setData($scope.pages.description);

                if ($scope.pages.name.length==0) {
                    var pagesTitle = "PAGE"; 
                } else {
                    var pagesTitle = $scope.pages.name.toUpperCase();
                }
                document.title = 'PAGE : '+ pagesTitle;
            }
        });
    }

    
    
}]);



