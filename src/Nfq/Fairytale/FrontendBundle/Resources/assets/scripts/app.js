angular
    .module('fairytale', [
        'ngRoute',
        'Fairytale.User.ActiveUser',
        'Fairytale.User.Controller',
        'Fairytale.Category.Controller'
    ])
    .config(function ($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                controller: 'MainController'
            })
            .when('/user', {
                templateUrl: '/partial/user-profile',
                controller: 'UserController'
            })
            .when('/category/:categoryId', {
                templateUrl: '/partial/book-list',
                controller: 'CategoryController'
            })
            .otherwise({
                redirectTo: '/'
            });
    });

