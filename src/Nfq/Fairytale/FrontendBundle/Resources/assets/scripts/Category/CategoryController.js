angular
    .module('Fairytale.Category.Controller', [])
    .controller('CategoryController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        var categoryId = $routeParams.categoryId;
        $http.get('api/category/' + categoryId).success(function (data) {
            $scope.category = data;
        });
    }]);
