angular
    .module('Fairytale.Generic.PaginationFilter', [])
    .filter('pagination', [function () {
        return function (input, start) {
            return input.slice(+start);
        };
    }]);
