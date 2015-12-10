myapp.factory('QUERY', function($http, $q){
    //add a promise to cancel http request if an another is launched
    var self = this;
    self.canceller = null;
    
    return {
        getAccountsByUser: function(userId, year){
        promise = $http({
                url: api_url+"compte/list.json?userId="+userId+'&year='+year,
                method: "GET"
            }).then(function(response){
                return response.data;                
            },function(error){
                //alert(JSON.stringify(error));
            }
                    );
            return promise;
        },
        
        getOperationByCompte: function(compteId, year){
            promise = $http({
                url: api_url+"operation/list.json?month=all&year="+year+"&compteId="+compteId,
                method: "GET"
            }).then(function(response){
                return response.data;                                
            },function(error){
                alert(JSON.stringify(error));
            }
                    );
            return promise;
        },
        
        getOperationByType: function(month, year, compteId){
            
            var deffered = $q.defer();
            
            $http({
                url: api_url+"operation/by/type.json?month="+month+"&year="+year+"&compteId="+compteId,
                method: "GET"
            }).success(function(response){
                deffered.resolve(response);                                
            }).error(function(error){
                deffered.reject(error);
            }
                    );
            return deffered.promise;
        },
        
        getEvolutionByCompte: function(compteId, year){
            promise = $http({
                url: api_url+"evolution/list.json?year="+year+"&compteId="+compteId,
                method: "GET",
                cache : true,
            }).then(function(response){
                return response.data; 
            },function(error){
                alert(JSON.stringify(error));
            }
                    );
            return promise;
        },
        
        getEvolutionByCompteAndDay: function(compteId, year){
            
            if (self.canceller){
                self.canceller.resolve("une autre requête a été lancée");
            }
            self.canceller = $q.defer();
            
            promise = $http({
                url: api_url+"evolution/by/day.json?year="+year+"&compteId="+compteId,
                method: "GET",
                timeout: self.canceller.promise
            }).then(function(response){
                return response.data; 
            },function(error){
                alert(JSON.stringify(error));
            }
                    );
            return promise;
        },
        
        getTypeOperation: function(){
            promise = $http({
                url: api_url+"type/operation.json",
                method: "GET"
            }).then(function(response){
                return response.data; 
            },function(error){
                alert(JSON.stringify(error));
            }
                    );
            return promise;
        }
    }
});

myapp.factory('OPERATION', function($resource){
    return $resource(api_url+'operation', {id:'@id'},{ 'update': {method: 'PUT'} });
});


