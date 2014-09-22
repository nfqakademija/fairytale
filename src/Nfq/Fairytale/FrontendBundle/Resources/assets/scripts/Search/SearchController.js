angular
    .module('Fairytale.Search.Controller', [
        'Fairytale.SearchResult.Model'
    ])
    .controller('SearchController', [
        '$scope', '$timeout', 'SearchResult', function ($scope, $timeout, SearchResult) {
            $scope.query = null;
            $scope.pause = 500;
            $scope.results = [];
            $scope.searchTimer = null;

            $scope.onTimerComplete = function () {
                $scope.searchTimer = null;
                $scope.results = !$scope.query ? [] : SearchResult().getList({q: $scope.query}).$object;
            };

            $scope.cancelTimer = function () {
                if ($scope.searchTimer) {
                    $timeout.cancel($scope.searchTimer);
                    $scope.searchTimer = null;
                }
            };

            $scope.search = function () {
                $scope.cancelTimer();
                $scope.searchTimer = $timeout($scope.onTimerComplete, $scope.pause);
            };

            $scope.onResultClick = function () {
                $scope.cancelTimer();
                $timeout(function () {
                    $scope.query = null;
                    $scope.results = [];
                });
            };
        }
    ]);
