angular
    .module('fairytale', [
        'ngRoute',
        'fairytale.controller.user',
        'fairytale.controller.categories',
        'fairytale.controller.category'
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

//        $locationProvider.html5Mode(true);
    });

