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

app.controller('sliderEditCtrl', ['$scope', '$upload', '$requestFactory', function ($scope, $upload, $request) {
    $scope.slider= {
        photo : {},
        name: ''
    };    
    $scope.slider_photo = [];
    $scope.showImage = false;
    $scope.addSlider = function () {
      
        $request.setSub('api/slider/edit/'+ ($scope.editMode ? $scope.dealersId : 0));
        $request.setQuery({apikey: apikey, slider: $scope.slider});
        $request.setMethod('POST');
        $request.makeRequest().then(function () {
            var result = $request.getResults(); 
            
            if (result.sliderId>0) {
                $upload.upload({
                    url: base+'api/slider/upload',
                    method: 'POST',
                    fields: {
                        apikey: apikey,
                        'Content-Type': 'application/octet-stream',
                        sn: result.sliderId,                        
                    },
                    file: $scope.slider_photo.file

                }).then(function (res) {                    
                    //upload success

                    // window.location = base + 'admin/products'

                }, function () {

                }).then(function (res) {                   
                    //upload complete
                    $.notify('Slider added successfully','success');
                    window.location = base + 'admin/slider';

                });
            };
            
        });
    };

    if (editMode) 
    {
        $scope.showImage = true;
        $scope.slider.id = editId;
        $scope.editMode = true;
        $scope.dealersId = editId;
        //fetch pages categories
        $request.setSub('api/products/rest');
        $request.setMethod('POST');
        $request.setQuery({
            apikey: apikey,
            table: 'slider',
            model: {
                where: {
                    id: editId
                },
                select: [
                    'id',
                    'name',
                    'title',
                    'description'
                ]
            }
            
        });
        $request.makeRequest().then(function () {
            var result = $request.getResults();
            if (result.result.length > 0) {
                $scope.slider = result.result[0];
                $scope.slider.image = base +'uploads/slider/'+$scope.slider.id+'/'+$scope.slider.name +'-'+ $scope.slider.id+'_.jpg';
                
                if ($scope.slider.title.length==0) {
                    var sliderTitle = "SLIDER"; 
                } else {
                    var sliderTitle = $scope.slider.title.toUpperCase();
                }
                document.title = 'SLIDER : '+ sliderTitle;
            }
        });
    }

    $scope.fileSelected = function (files, $index, $event) {
        if(files.length>0){
            $scope.files = files;
            $scope.slider.name = $scope.files[0].name;
            if (files !== null && files[0] != undefined) {
                var file = files[0];
                var ext = file.name.substr(file.name.lastIndexOf('.') + 1);
                $scope.slider_photo.file = files[0];
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
                    img.onload = function () {
                       
                        $scope.slider_photo.attachment = {type: ext, image: img};
                        $scope.$apply();
                    };
                };
            }
        }
    };
}]);



