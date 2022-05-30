

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
        const chartMants = document.getElementById('ChartMants');
        if (chartMants) {
          var ctx = chartMants.getContext('2d');
          var barGraph = new Chart(ctx, {
            type: 'bar',
            data: chartdata
          });
        }
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

        const chartHours = document.getElementById('ChartHours');
        if (chartHours) {
          var ctx = chartHours.getContext('2d');

          var barGraph = new Chart(ctx, {
            type: 'bar',
            data: chartdata
          });
        }
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
        const chartUnits = document.getElementById('ChartUnits');
        if (chartUnits) {
          var ctx = chartUnits.getContext('2d');

          var barGraph = new Chart(ctx, {
            type: 'doughnut',
            data: chartdata
          });
        }
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

        const chartMantsRsu = document.getElementById('ChartMantsRsu');
        if (chartMantsRsu) {
          var ctx = chartMantsRsu.getContext('2d');

          var barGraph = new Chart(ctx, {
            type: 'doughnut',
            data: chartdata
          });
        }
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

          const chartHoursEst = document.getElementById('ChartHoursEst');
          if (chartHoursEst) {
            var ctx = chartHoursEst.getContext('2d');
            var barGraph = new Chart(ctx, {
              type: 'bar',
              data: chartdata
            });
          }
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

          const chartMantsEst = document.getElementById('ChartMantsEst');
          if (chartMantsEst) {
            var ctx = chartMantsEst.getContext('2d');

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
          }
        },
        error: function(conts) {
          console.log(conts);
        }

      })
    }
  });
