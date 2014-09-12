angular
    .module('Fairytale.Sidebar.Controller', [
        'Fairytale.User.Model',
        'Fairytale.Category.Model'
    ])
    .controller('SidebarController', ['$scope', 'User', 'Category', function ($scope, User, Category) {
        $scope.me = User(activeUser.id).get().$object;
        $scope.categories = Category().getList().$object;
    }]);
