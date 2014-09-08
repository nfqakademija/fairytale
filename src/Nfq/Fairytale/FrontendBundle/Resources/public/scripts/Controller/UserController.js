angular
    .module('fairytale.controller.user', [])
    .controller('userController', function ($scope, $http) {
        $http.get('api/user/2').success(function (data) {
            $scope.user = data;
        });
    });
