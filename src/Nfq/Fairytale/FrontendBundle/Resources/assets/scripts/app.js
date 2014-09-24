angular
    .module('fairytale', [
        'ngRoute',
        'Fairytale.Generic.PaginationFilter',
        'Fairytale.Book.RatingFilter',
        'Fairytale.User.ActiveUser',
        'Fairytale.User.Controller',
        'Fairytale.Category.Controller',
        'Fairytale.Sidebar.Controller',
        'Fairytale.Search.Controller',
        'Fairytale.Book.Controller',
        'restangular'
    ])
    .config(['$routeProvider', 'RestangularProvider',
        function ($routeProvider, RestangularProvider) {
        RestangularProvider.setBaseUrl('/api');

        $routeProvider
            .when('/user/:id', {
                templateUrl: '/partial/user',
                controller: 'UserController'
            })
            .when('/me', {
                redirectTo: '/user/' + activeUser.id
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
                redirectTo: '/category/popular'
            });
    }]);

