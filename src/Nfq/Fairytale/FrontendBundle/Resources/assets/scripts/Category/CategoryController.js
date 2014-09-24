angular
    .module('Fairytale.Category.Controller', [
        'Fairytale.Category.Model'
    ])
    .controller('CategoryController', [
        '$scope', '$routeParams', 'Category', function ($scope, $routeParams, Category) {
            $scope.name = 'CategoryController';

            // pagination
            $scope.curPage = 0;
            $scope.pageSize = 9;

            $scope.category = Category($routeParams.id).get().$object;

            $scope.numberOfPages = function () {
                return $scope.category.books ? Math.ceil($scope.category.books.length / $scope.pageSize) : 0;
            };

            $scope.onNextClick = function () {
                if ($scope.curPage < $scope.numberOfPages() - 1) {
                    $scope.curPage++;
                }
            };

            $scope.onPrevClick = function () {
                if (0 < $scope.curPage) {
                    $scope.curPage--;
                }
            };
        }
    ]);
