angular
    .module('Fairytale.Book.Model', [
        'restangular'
    ])
    .factory('Book', ['Restangular', function (Restangular) {
        return function (id) {
            return Restangular.one('book', id);
        }
    }]);
