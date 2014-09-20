angular
    .module('fairytale', [
        'ngRoute',
        'Fairytale.Generic.PaginationFilter',
        'Fairytale.User.ActiveUser',
        'Fairytale.User.Controller',
        'Fairytale.Category.Controller',
        'Fairytale.Sidebar.Controller',
        'Fairytale.Book.Controller',
        'restangular'
    ])
    .config(function ($routeProvider, RestangularProvider) {
        RestangularProvider.setBaseUrl('/api');

        $routeProvider
            .when('/', {
                controller: 'MainController'
            })
            .when('/user/:id', {
                templateUrl: '/partial/user',
                controller: 'UserController'
            })
            .when('/category/:id', {
                templateUrl: '/partial/category',
                controller: 'CategoryController'
            })
            .when('/book/:id', {
                templateUrl: '/partial/book',
                controller: 'BookController'
            })
            .otherwise({
                redirectTo: '/'
            });
    });

