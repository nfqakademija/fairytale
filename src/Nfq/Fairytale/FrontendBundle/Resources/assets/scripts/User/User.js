angular
    .module('Fairytale.User.Model', [
        'restangular'
    ])
    .factory('User', ['Restangular', function (Restangular) {
        return function (id) {
            return Restangular.one('user', id);
        }
    }]);
