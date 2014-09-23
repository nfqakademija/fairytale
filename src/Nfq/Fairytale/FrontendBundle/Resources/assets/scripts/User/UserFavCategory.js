angular
    .module('Fairytale.UserFavCategory.Model', [
        'restangular'
    ])
    .factory('UserFavCategory', ['Restangular', function (Restangular) {
        return function (id) {
            return Restangular.one('user', id).all('categories');
        }
    }]);
