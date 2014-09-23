angular
    .module('Fairytale.Generic.FirstFilter', [])
    .filter('first', [
        function () {
            return function (input) {
                return _.first(input);
            };
        }
    ]);
