angular
    .module('Fairytale.SearchResult.Model', [
        'restangular'
    ])
    .factory('SearchResult', ['Restangular', function (Restangular) {
        return function () {
            return Restangular.one('book').all('search');
        }
    }]);
