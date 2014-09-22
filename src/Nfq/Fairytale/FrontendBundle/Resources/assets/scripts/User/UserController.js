angular
    .module('Fairytale.User.Controller', [
        'Fairytale.User.Model'
    ])
    .controller('UserController', [
        '$scope', 'User', '$routeParams', function ($scope, User, $routeParams) {
            $scope.name = 'UserController';

            $scope.user = User($routeParams.id).get().$object;
        }
    ]);
