angular
    .module('Fairytale.Book.RatingFilter', [])
    .filter('rating', [
        function () {
            return function (book, precision) {
                if (!book.ratings) {
                    return '?';
                }
                var sum, coef;

                precision = precision || 0;

                coef = Math.pow(10, precision);

                sum = _.reduce(book.ratings, function (sum, rating) {
                    return sum + rating.value;
                }, 0).valueOf();

                return Math.round(sum / book.ratings.length * coef) / coef;
            };
        }
    ]);
