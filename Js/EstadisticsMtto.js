

$(document).ready(function(){


    $.ajax({
      url: "../functions/Search/EstadistCountMants.php",
      dataType: "json",
     
      success: function(conts) {  
     
        
        var chartdata = {
          labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          datasets : [
            {              
              label: 'No. Mantenimientos realizados',              
              backgroundColor: '#566573',
              borderColor: '#34495E',
              hoverBackgroundColor: '#808B96',
              hoverBorderColor: '#E74C3C',
              data: conts,
              
            }           
          ]
        };       
  
        var ctx = document.getElementById('ChartMants').getContext('2d');
  
        var barGraph = new Chart(ctx, {
          type: 'bar',
          data: chartdata
        });
      },
      error: function(conts) {
        console.log(conts);
      }
    });
    
  });

  $(document).ready(function(){
    $.ajax({
      url: "../functions/Search/CountHoursTeamsEstatic.php",
      dataType: "json",
      success: function(conth) {  
     
        
        var chartdata = {
          labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          datasets : [
            {              
              label: 'No. de horas paradas',              
              backgroundColor: '#566573',
              borderColor: '#34495E',
              hoverBackgroundColor: '#808B96',
              hoverBorderColor: '#E74C3C',
              data: conth,
              
            }           
          ]
        };       
  
        var ctx = document.getElementById('ChartHours').getContext('2d');
  
        var barGraph = new Chart(ctx, {
          type: 'bar',
          data: chartdata
        });
      },
      error: function(conth) {
        console.log(conth);
      }
    });
    
  });

  $(document).ready(function(){
    $.ajax({
      url: "../functions/Search/CountUnitsRsu.php",
      dataType: "json",
      success: function(data){
        var unidad = [];
        var total = [];
        
        
        for (var i in data){
            unidad.push(data[i].unidad);            
            total.push(data[i].total);            
        }       
        
        var chartdata = {
          labels: unidad,  
          datasets : [
            {              
              label: unidad,              
              backgroundColor : ['#EC7063', '#AF7AC5 ', '#5DADE2 ', '#48C9B0', '#58D68D', '#F4D03F', '#EB984E', '#76448A', '#F1C40F', '#C0392B', '#117A65 ', '#1F618D'],
              borderColor: 'rgba(234, 236, 238)',
              hoverBackgroundColor: ['#EC7063', '#AF7AC5 ', '#5DADE2 ', '#48C9B0', '#58D68D', '#F4D03F', '#EB984E', '#76448A', '#F1C40F', '#C0392B', '#117A65 ', '#1F618D'],
              hoverBorderColor: 'rgba(234, 236, 238)',
              data: total,
              
            }           
          ]
        };
  
        var ctx = document.getElementById('ChartUnits').getContext('2d');
  
        var barGraph = new Chart(ctx, {
          type: 'doughnut',
          data: chartdata
        });
      },
      error: function(data) {
        console.log(data);
      }
    });
  });

  $(document).ready(function(){
    $.ajax({
      url: "../functions/Search/CountMantRsu.php",
      dataType: "json",
      success: function(data){
        var unidad = [];
        var total = [];
        
        
        for (var i in data){
            unidad.push(data[i].unidad);            
            total.push(data[i].total);            
        }       
        
        var chartdata = {
          labels: unidad,  
          datasets : [
            {              
              label: unidad,              
              backgroundColor : ['#EC7063', '#AF7AC5 ', '#5DADE2 ', '#48C9B0', '#58D68D', '#F4D03F', '#EB984E', '#76448A', '#F1C40F', '#C0392B', '#117A65 ', '#1F618D'],
              borderColor: 'rgba(234, 236, 238)',
              hoverBackgroundColor: ['#EC7063', '#AF7AC5 ', '#5DADE2 ', '#48C9B0', '#58D68D', '#F4D03F', '#EB984E', '#76448A', '#F1C40F', '#C0392B', '#117A65 ', '#1F618D'],
              hoverBorderColor: 'rgba(234, 236, 238)',
              data: total,
              
            }           
          ]
        };
  
        var ctx = document.getElementById('ChartMantsRsu').getContext('2d');
  
        var barGraph = new Chart(ctx, {
          type: 'doughnut',
          data: chartdata
        });
      },
      error: function(data) {
        console.log(data);
      }
    });
  });

  $(document).ready(function(){
    load_data();

    function load_data(year){
      
      $.ajax({
        url: "../functions/Search/CountHoursTeams.php",
        dataType: "json",
        type: "POST",
        data: {year:year},
        success: function(conts){

          var chartdata = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets : [
              {
                label: 'No. de horas paradas',              
                backgroundColor: '#566573',
                borderColor: '#34495E',
                hoverBackgroundColor: '#808B96',
                hoverBorderColor: '#E74C3C',
                data: conts,
              }
            ]
          };

          var ctx = document.getElementById('ChartHoursEst').getContext('2d');
          var barGraph = new Chart(ctx, {
            type: 'bar',
            data: chartdata
          });

          $(document).on('change', '#year', function(){
            var year = $(this).val();  
            barGraph.destroy(); 
      
            if(year != '')
            {
              load_data(year);
            }
            else
            {
              load_data();
            }
          })
        },
        error: function(conts) {
          console.log(conts);
        }

      })
    }    

  });

  $(document).ready(function(){
    load_datas();

    function load_datas(year){

      $.ajax({
        url: "../functions/Search/EstadistCountMantsDate.php",
        dataType: "json",
        type: "POST",
        data: {year:year},
        success: function(conts){

          var chartdata = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets : [
              {              
                label: 'No. Mantenimientos realizados',              
                backgroundColor: '#566573',
                borderColor: '#34495E',
                hoverBackgroundColor: '#808B96',
                hoverBorderColor: '#E74C3C',
                data: conts,
                
              }           
            ]
          }; 

          var ctx = document.getElementById('ChartMantsEst').getContext('2d');
  
          var barGraph = new Chart(ctx, {
            type: 'bar',
            data: chartdata
          });

          $(document).on('change', '#year', function(){
            var year = $(this).val();  
            barGraph.destroy();

            if(year != '')
            {
              load_datas(year);
            }
            else
            {
              load_datas();
            }
          })

        },
        error: function(conts) {
          console.log(conts);
        }

      })
    }
  });
