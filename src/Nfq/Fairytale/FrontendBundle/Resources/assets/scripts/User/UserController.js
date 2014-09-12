angular
    .module('Fairytale.User.Controller', [])
    .controller('UserController', ['$scope', '$http', '$window', function ($scope, $http, $window) {
        var id = $window.activeUser.id;
        $http.get('api/user/' + id).success(function (data) {
            $scope.user = data;
        });
    }]);
