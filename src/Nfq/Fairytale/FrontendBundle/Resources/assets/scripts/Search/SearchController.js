angular
    .module('Fairytale.Search.Controller', [
        'Fairytale.SearchResult.Model'
    ])
    .controller('SearchController', ['$scope', 'SearchResult', function ($scope, SearchResult) {
        $scope.query = null;

        $scope.search = function() {
            if ($scope.results && $scope.results.$object) {
                $scope.results.$object.reject();
            }
            $scope.results =  !$scope.query ? [] : SearchResult().getList({q: $scope.query}).$object;
        };
    }]);
