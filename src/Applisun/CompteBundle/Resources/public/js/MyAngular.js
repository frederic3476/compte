var api_url = 'http://localhost/compte/web/api/';

var months = ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin",
  "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre"
];
       
date = new Date();
var year = date.getFullYear(); 

Date.prototype.getFullDateFr = function(){
    return this.getDay()+' '+months[this.getMonth()]+' '+this.getFullYear();
};

Array.prototype.pushArray = function(arr) {
    this.push.apply(this, arr);
};

Date.prototype.getDateFr = function(){
    return months[this.getMonth()]+' '+this.getFullYear();
}
    
var myapp = angular.module('MyApp', ['ngAnimate', 'ngRoute', 'ui.bootstrap', 'highcharts-ng']);    

myapp.run(function ( $rootScope, $window, QUERY ) {
    $rootScope.data = [];
    $rootScope.userId = $window.userId;
                
                QUERY.getAccountsByUser($rootScope.userId, year).then(function(result){
                    $rootScope.data = result;
                    angular.forEach($rootScope.data, function(dat){
                        compteId = dat.id;
                        QUERY.getOperationByCompte(compteId, year).then(function(res1){
                            dat.operations= res1;
                        });
                        QUERY.getEvolutionByCompte(compteId, year).then(function(res2){
                                dat.evolutions= res2;                                
                            });
                        QUERY.getOperationByType(0, year, compteId).then(function(res3){
                           dat.repartitions= res3; 
                           //$rootScope.$broadcast('dataSuccess');
                        });
                    });
                        });
                        
    $rootScope.getRepart = function (dataJson){
        repart = [];
        for (var i in dataJson){
            dArray = [dataJson[i].type, dataJson[i].somme];
            repart.pushArray(dArray);
        }
        
        return repart;
    }
    
    
    
});
    
myapp.controller('MyController', function ($scope, $routeParams, QUERY) {  
  $scope.ordre = 'createdat';
  $scope.tri = 'all';
  $scope.repart= [];
  year = $routeParams.year;
  
  $scope.filterOpe = function (actual, expected) {
        var isDebit = actual.is_debit;        
        if (expected === 'all') return true;
        if ((isDebit && expected == 0) || (!isDebit && expected == 1) )
            return true;                    
        return false;
    };
});

myapp.controller('GraphicController', function ($scope, $routeParams, $rootScope, QUERY) {
    $scope.compteId = $routeParams.compteId;
    $scope.items = {};
    $scope.dataG = [];
    $scope.items.evo = [];
    $scope.dat = months;
    $scope.interval = 'month';
    $scope.format = 'dd-MMMM-yyyy';
    
    $scope.initValue = function(){
        for (var i in $rootScope.data){
            if ($rootScope.data[i].id === parseInt($scope.compteId) ){            
                $scope.dataG = $rootScope.data[i].evolutions;
                firstDate = $rootScope.data[i].evolutions[0].created_at;
                lastDate = $rootScope.data[i].evolutions[$scope.dataG.length-1].created_at;
                $scope.debut = new Date(firstDate);
                $scope.fin = new Date(lastDate);
            }
        }
    };
    
    
    var a = [];

    a.push("1","2");
    
    a.push("3","4");
    
    alert(a);
    
    $scope.initValue();
    
    $scope.chartConfig = {
        options: {
            chart: {
                type: 'line'
            }
        },
        series: [{ name:'solde',
            data: $scope.items.evo
        }],
        xAxis: {
            categories: $scope.dat,          
            title: {
                enabled: true,
                text: '<i>Mois</i>',
                style: {
                    fontWeight: 'normal'
                }
            }
        },
        title: {text:"Solde"}
    };    
    
    $scope.updateInterval = function(){
        if ($scope.interval === 'day'){
            QUERY.getEvolutionByCompteAndDay($scope.compteId, year).then(function(res){
                $scope.dataG = res;
                $scope.debut = today = new Date();
                $scope.fin = new Date();
                $scope.debut.setDate(today.getDate()-30);
            });
        }
        else{
            $scope.initValue();
        }
    };    
  
  $scope.$watch('debut', function(value){ 
      $scope.finMinDate = new Date(value);
      val = new Date(value);
      $scope.finMinDate.setDate(val.getDate()+1);
      fin = new Date($scope.fin);
      if (val > $scope.fin){
          alert('la date de fin doit être supérieure à la date de début');
          $scope.fin = new Date(value);
          $scope.fin.setDate(val.getDate()+1);
      }
      
      //init value
      $scope.getValue(val, $scope.fin);
      
  });
  
  $scope.$watch('fin', function(value){ 
      val = new Date(value);
      $scope.getValue($scope.debut, val);
  });
  
  $scope.getValue = function(debut, fin){
      $scope.items.evo = [];
      $scope.dat = [];
      for (var j in $scope.dataG){
          d = new Date($scope.dataG[j].created_at);
          valueMonth = d.getMonth();
          if (d>=debut && d<=fin){
                $scope.items.evo.push($scope.dataG[j].solde);
                if ($scope.interval === 'month'){
                    $scope.dat.push(months[d.getMonth()]);
                }
                else{
                    $scope.dat.push(d.getDay()+' '+months[d.getMonth()]);
                }
          }
      }
      $scope.chartConfig.title.text = ($scope.interval == 'month'?'Solde de '+debut.getDateFr()+' à '+fin.getDateFr():'Solde du '+debut.getFullDateFr()+' au '+fin.getFullDateFr()); 
      $scope.chartConfig.xAxis.title.text = ($scope.interval == 'month'?'<i>Mois</i>':'<i>Jour</i>');
      $scope.chartConfig.series[0].data = $scope.items.evo;
      $scope.chartConfig.xAxis.categories = $scope.dat;
  };

  $scope.clear = function (){
    $scope.dedut = $scope.fin = null;
  };  
    
  $scope.debutMinDate = new Date(year,1,1);
  $scope.finMinDate = new Date();
  $scope.finMinDate.setDate($scope.debutMinDate .getDate() + 1);
  
  $scope.maxDate = new Date(year, 12, 31);

  $scope.openDebut = function($event) {
    $scope.debutStatus.opened = true;
  };
  
  $scope.openFin = function($event) {
    $scope.finStatus.opened = true;
  };

  $scope.debutStatus = $scope.finStatus = {
    opened: false
  };
    
});

myapp.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
  });
  
myapp.config(function ($routeProvider) {  
    $routeProvider.when("/home/?:year", {
        templateUrl: 'http://localhost/compte/web/bundles/applisuncompte/template/home.html',
        controller: 'MyController'
    })
            .when("/graphic/:compteId", {
        templateUrl: 'http://localhost/compte/web/bundles/applisuncompte/template/graphic.html',
        controller: 'GraphicController'
    })
    .otherwise({
        redirectTo:"/home/"+year
            });
 });
 
 myapp.directive("pierepart", function($http, $rootScope, $location, QUERY, $timeout){
     return {
        restrict: 'E',
        replace: false,
        template: '<highchart id="chart-repart-[[ number ]]" config="cConfig" class="span10"></highchart>',
        link: function(scope,element, attr){ 
                scope.number = attr.num;
                QUERY.getOperationByType(0, year, attr.compteid).then(function(res){                    
                    //alert(result);
                        result = scope.getRepart(res);
                        alert(result);
                        $timeout(function(){
                            scope.cConfig = {
                                    options: {
                                        chart: {
                                            type: 'pie'

                                        },
                                        plotOptions: {
                                            column: {
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                showInLegend: true
                                            }
                                        }
                                    },
                                    series: [{
                                        data: scope.getRepart(res)
                                    }]
                                }
                            }, 300);
                    //highchartController.setConfig(myConfig);
                   //scope.cConfig.series[0].data = result;
            });
    }
     };
 });


