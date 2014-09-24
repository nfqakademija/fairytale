angular
    .module('Fairytale.Book.RatingFilter', [])
    .filter('rating', [
        function () {
            return function (book, precision) {
                precision = precision || 0;
                return Math.round(_.reduce(book.ratings, function (sum, rating) {
                    return sum + rating.value;
                }, 0).valueOf() / book.ratings.length * Math.pow(10, precision)) / Math.pow(10, precision);
            };
        }
    ]);
