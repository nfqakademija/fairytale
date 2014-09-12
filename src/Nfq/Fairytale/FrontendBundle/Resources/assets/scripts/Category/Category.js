angular
    .module('Fairytale.Category.Model', [
        'restangular'
    ])
    .factory('Category', ['Restangular', function (Restangular) {
        return function (id) {
            return Restangular.one('category', id);
        }
    }]);
