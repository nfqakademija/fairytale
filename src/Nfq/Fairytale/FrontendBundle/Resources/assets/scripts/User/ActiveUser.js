angular
    .module('Fairytale.User.ActiveUser', [
        'restangular'
    ])
    .factory('ActiveUser', ['Restangular', function (Restangular) {
        return Restangular.one('user', activeUser.id);
    }]);
