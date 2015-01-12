var app = angular.module('avStore', ['ngRoute', 'timer']);

app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'product-list.html',
                controller: 'StoreController'
            }).
            when('/product/:SKU', {
                templateUrl: 'product-detail.html',
                controller: 'ProductDetailCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

app.controller('StoreController', ['$scope', '$http',
    function ($scope, $http) {
        $scope.products = [];
        $http.get('index.php', {
               params: {p: 2
               }}).success(function(data) {
            $scope.products = data;
            $scope.EndTime = data[0]['expires']*1000;

        });
        $scope.startTimer = function (){
            $scope.$broadcast('timer-start');
            $scope.timerRunning = true;
        };

        $scope.stopTimer = function (){
            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
        };

        $scope.$on('timer-stopped', function (event, data){
            $http.get('index.php', {
                params: {p: 2}})
                .success(function(data) {
                $scope.products = data;
                $scope.EndTime = data[0]['expires'] *1000;
                });
        });

        //$scope.EndTime =  1421113600000;
     }
]);

app.controller('ProductDetailCtrl', ['$scope', '$routeParams', '$http',
    function($scope, $routeParams, $http) {
        $scope.SKU = $routeParams.SKU;

            $http.get('index.php', {params:{i :$routeParams.SKU}}).success(function(data) {
                $scope.products = data;
                $scope.EndTime = data[0]['expires'] *1000;
            });




    }]);












