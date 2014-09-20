angular
    .module('Fairytale.Book.Controller', [
        'Fairytale.Book.Model'
    ])
    .controller('BookController', [
        '$scope', '$routeParams', 'Book', function ($scope, $routeParams, Book) {
            $scope.name = 'BookController';

            $scope.book = Book($routeParams.id).get().$object;
        }
    ]);
