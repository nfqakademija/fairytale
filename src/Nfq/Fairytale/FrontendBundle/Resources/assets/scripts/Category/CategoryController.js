angular
    .module('Fairytale.Category.Controller', [
        'Fairytale.Category.Model'
    ])
    .controller('CategoryController', ['$scope', '$routeParams', 'Category', function ($scope, $routeParams, Category) {
        $scope.name = 'CategoryController';
        $scope.category = Category($routeParams.id).get().$object;
    }]);