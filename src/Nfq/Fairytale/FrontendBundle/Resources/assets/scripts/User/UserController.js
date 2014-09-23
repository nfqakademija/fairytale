angular
    .module('Fairytale.User.Controller', [
        'Fairytale.User.Model',
        'Fairytale.UserFavCategory.Model',
        'Fairytale.Generic.FirstFilter'
    ])
    .controller('UserController', [
        '$scope', 'User', 'UserFavCategory', '$routeParams', function ($scope, User, UserFavCategory, $routeParams) {
            $scope.name = 'UserController';

            $scope.user = User($routeParams.id).get().$object;

            $scope.categories = UserFavCategory($routeParams.id).getList().$object;
        }
    ]);
