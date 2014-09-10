angular
    .module('fairytale', [
        'ngRoute',
        'fairytale.controller.user',
        'fairytale.controller.categories'
    ])
    .controller('MainController', function ($scope) {

    })
    .config(function ($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                controller: 'MainController'
            })
            .when('/user', {
                templateUrl: '/partial/user-profile',
                controller: 'UserController'
            })
            .otherwise({
                redirectTo: '/'
            });

//        $locationProvider.html5Mode(true);
    });

