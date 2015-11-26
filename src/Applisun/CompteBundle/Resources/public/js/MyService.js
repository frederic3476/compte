myapp.factory('QUERY', function($http){
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
            promise = $http({
                url: api_url+"operation/by/type.json?month="+month+"&year="+year+"&compteId="+compteId,
                method: "GET"
            }).then(function(response){
                return response.data;                                
            },function(error){
                alert(JSON.stringify(error));
            }
                    );
            return promise;
        },
        
        getEvolutionByCompte: function(compteId, year){
            promise = $http({
                url: api_url+"evolution/list.json?year="+year+"&compteId="+compteId,
                method: "GET"
            }).then(function(response){
                return response.data; 
            },function(error){
                alert(JSON.stringify(error));
            }
                    );
            return promise;
        },
        
        getEvolutionByCompteAndDay: function(compteId, year){
            promise = $http({
                url: api_url+"evolution/by/day.json?year="+year+"&compteId="+compteId,
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


