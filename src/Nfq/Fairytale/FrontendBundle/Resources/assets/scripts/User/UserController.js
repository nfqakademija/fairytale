angular
    .module('Fairytale.User.Controller', [
        'Fairytale.User.ActiveUser',
        'Fairytale.User.Model'
    ])
    .controller('UserController', ['$scope', 'User', 'ActiveUser', function ($scope, User, ActiveUser) {
        $scope.user = ActiveUser.get().$object;
    }]);
