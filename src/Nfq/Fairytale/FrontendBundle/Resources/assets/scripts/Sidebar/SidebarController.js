angular
    .module('Fairytale.Sidebar.Controller', [
        'Fairytale.User.Model',
        'Fairytale.Category.Model'
    ])
    .controller('SidebarController', [
        '$scope', 'User', 'Category', function ($scope, User, Category) {
            $scope.me = User(activeUser.id).get().$object;
            $scope.categories = Category().getList().$object;

            $scope.$on('$routeChangeSuccess', function (event, routeData) {
                if (routeData.$$route) {
                    switch (routeData.$$route.controller) {
                        case 'CategoryController':
                            $scope.activeCategoryId = routeData.params.id;
                            break;
                        default:
                    }
                }
            });
        }
    ]);
