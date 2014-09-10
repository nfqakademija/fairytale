angular
    .module('fairytale.controller.categories', [])
    .controller('CategoriesController', ['$scope', '$http', function ($scope, $http) {
        $http.get('api/category').success(function (data) {
            $scope.categories = data;
        });
    }]);
