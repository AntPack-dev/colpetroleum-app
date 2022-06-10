var spanish = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sSearch":         "Buscar:",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
    "sFirst":    "Primero",
    "sLast":     "Último",
    "sNext":     "Siguiente",
    "sPrevious": "Anterior"
},
"oAria": {
    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
},
"buttons": {
    "copy": "Copiar",
    "colvis": "Visibilidad"
}
}


$(document).ready(function(){
    $('#id_table_active').DataTable({
        responsive: true,
        paging: true,
        scrollY: 250,
        hover: true,
        lenguage: spanish,
        info: true,
        searching: false,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchActive.php",
            type: "POST"
        }
        
    }); 
});

$(document).ready(function(){
    $('#id_table_concept').DataTable({
        responsive: true,
        paging: true,
        scrollY: 250,
        hover: true,
        lenguage: spanish,
        info: true,
        searching: false,        
        processing: true,
        serverside: true
       
    });
});

$(document).ready(function() {
    $('#id_table_warehouse').DataTable({
        responsive: true,
        paging: true,
        scrollY: 200,
        hover: true, 
        searching: true,  
        info: true,    
        paging: true,  
        language: spanish,  
        ajax:{
            url: "../functions/Search/SearchWarehouse.php",
            type: "POST"
        }      
        
    });   
});

$(document).ready(function() {
    $('#table_id_permision').DataTable({
        responsive: true,
        paging: true,
        scrollY: 400,
        hover: true, 
        searching: false, 
        info: true,    
        paging: false,  
        language: spanish        
        
    });   
});

$(document).ready(function() {
    $('#id_table_users').DataTable({
        responsive: true,
        lengthChange: false,
        paging: true,
        scrollY: 200,
        hover: true,       
        language: spanish,      
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchUsers.php",
            type: "POST"
        }
        
    });   
});

$(document).ready(function() {
    var warehouse = $('#warehouse').val();
    $('#id_table_actives_warehouse').DataTable({
        paging: true,
        scrollY: 400,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchActivesWarehouse.php?warehouse=" + warehouse,
            type: "POST"
        }
    });
});

$(document).ready(function(){
    $('#id_table_warehouse_analisys').DataTable({
        paging: true,
        scrollY: 200,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchWarehouseAnalysis.php",
            type: "POST"
        }
    });

});

$(document).ready(function(){
    var warehouse = $('#warehouse').val();
    $('#datatable_modal_ea').DataTable({
        paging: true,
        scrollY: 400,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchActiveEA.php?warehouse=" + warehouse,
            type: "POST"
        }

    })
});

$(document).ready(function(){
    var warehouse = $('#warehouse').val();
    $('#datatable_modal_sa').DataTable({
        paging: true,
        scrollY: 400,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchActivesSA.php?warehouse=" + warehouse,
            type: "POST"
        }
    })

});


$(document).ready(function(){
    $('#id_table_actives_an').DataTable({
        paging: true,
        scrollY: 200,
        hover: true, 
        searching: true,       
        language: spanish,
        autoWidth: false,
        processing: true,
        serverside: true,
        responsive: true,
        info: true,

    });

});

$(document).ready(function(){
    $('#id_table_units_rsu').DataTable({
        paging: true,
        scrollY: 300,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchUnits.php",
            type: "POST"
        }
    })
});

$(document).ready(function(){
    $('#id_table_procedures').DataTable({
        paging: true,
        scrollY: 300,
        hover: true,
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchProcedures.php",
            type: "POST"
        }
    })
});

$(document).ready(function(){
    var warehouse = $('#warehouse').val();
    $('#id_table_units').DataTable({
        paging: true,
        scrollY: 300,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchTeams.php?warehouse=" + warehouse,
            type: "POST"
        }
    })

});

$(document).ready(function(){
    var teams = $('#teams').val();
    $('#id_table_register_man').DataTable({
        paging: true,
        scrollY: 300,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchReportMantTeams.php?teams=" + teams,
            type: "POST"
        }

    })

});

$(document).ready(function(){
    $('#id_table_fails').DataTable({
        paging: true,
        scrollY: 300,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchReportFails.php",
            type: "POST"
        }

    })

});

$(document).ready(function(){
    $('#id_table_notifications').DataTable({
        paging: true,
        scrollY: 500,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchNotification.php",
            type: "POST"
        }        

    })

});



$(document).ready(function(){
    $('#id_table_maint').DataTable({
        paging: true,
        scrollY: 300,
        hover: true,       
        language: spanish,
        autoWidth: false,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,  
        ajax:{
            url: "../functions/Search/SearchReportMant.php",
            type: "POST"
        }      

    })

});

$(document).ready(function(){
    $('#id_table_schedule').DataTable({
        paging: true,
        scrollY: 400,
        hover: true,
        language: spanish,
        autoWidth: true,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchSchedule.php",
            type: "POST"
        }
    })
});

$(document).ready(function(){
    $('#id_table_indicadors').DataTable({
        paging: true,
        scrollY: 400,
        hover: true,
        language: spanish,
        autoWidth: true,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchIndicators.php",
            type: "POST"
        }
    })
});

$(document).ready(function(){
    $('#id_table_consumables').DataTable({
        paging: true,
        scrollY: 400,
        hover: true,
        language: spanish,
        autoWidth: true,
        responsive: true,
        info: true,
        processing: true,
        serverside: true,
        ajax:{
            url: "../functions/Search/SearchReportConsumables.php",
            type: "POST"
        }
    })
});


$(document).ready(function(){
    load_data();

    function load_data(ware)
    {

        $('#id_table_analisys').DataTable({
            paging: true,
            scrollY: 300,
            hover: true,
            searching: true,
            language: spanish,
            autoWidth: false,
            responsive: true,
            info: true,
            processing: true,
            serverside: true,
            destroy: true,
            ajax:{
                url: "../functions/Search/SearchAnalisys.php",
                type: "POST",
                data: {ware:ware}
                
            }
        })
    }

    $(document).on('change', '#ware', function(){
        var warehouse = $(this).val();
        $('#id_table_analisys').DataTable().destroy();

        if(warehouse != '')
        {
            load_data(warehouse);
        }
        else
        {
            load_data();
        }
    })
});

$(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true,
      });
    });
 });

 $(function(){
     $("#id_mant").change( function(){
         if($(this).val() === "Preventivo"){
             $("#id_report_fails").prop("disabled", true);
         } else {
            $("#id_report_fails").prop("disabled", false);
         }
     })
 })

 $(function(){
    $("#id_type_report").change( function(){

        if($(this).val() === "890"){
            $("#id_alcance_report").prop("disabled", true);
            $("#id_unity_report").prop("disabled", true);
            $("#id_warehouse_report").prop("disabled", true);                                             
        } else {
            $("#id_warehouse_report").prop("disabled", true);
            $("#id_unity_report").prop("disabled", true);
            $("#id_alcance_report").prop("disabled", false);
        }
         

        switch($(this).val()){           

            case "635":
                
                $("#id_unity_report").prop("disabled", true);
                $("#id_warehouse_report").prop("disabled", true);              

                $("#id_alcance_report").change( function(){
                    if($(this).val() === "Especifico"){
                        $("#id_warehouse_report").prop("required", true);
                        $("#id_warehouse_report").prop("disabled", false);   
                        $("#id_unity_report").prop("disabled", true);                                             
                    } else {
                        $("#id_warehouse_report").prop("disabled", true);
                        $("#id_unity_report").prop("disabled", true);
                    }

                })

            break;

            case "734":

                
                $("#id_unity_report").prop("disabled", true);
                $("#id_warehouse_report").prop("disabled", true);  
                $("#id_alcance_report").change( function(){
                    if($(this).val() === "Especifico"){
                        $("#id_warehouse_report").prop("required", true);
                        $("#id_warehouse_report").prop("disabled", false);
                        $("#id_unity_report").prop("disabled", true);                         
                    } else {
                        $("#id_warehouse_report").prop("disabled", true);
                        $("#id_unity_report").prop("disabled", true);
                    }

                })

            break;

            case "528":

                
                $("#id_unity_report").prop("disabled", true);
                $("#id_warehouse_report").prop("disabled", true); 
                $("#id_alcance_report").change( function(){
                    if($(this).val() === "Especifico"){
                        $("#id_warehouse_report").prop("required", true);
                        $("#id_warehouse_report").prop("disabled", false); 
                        $("#id_unity_report").prop("disabled", true);                        
                    } else {
                        $("#id_warehouse_report").prop("disabled", true);
                        $("#id_unity_report").prop("disabled", true);
                    }

                })

            break;

            case "479":

                
                $("#id_unity_report").prop("disabled", true);
                $("#id_warehouse_report").prop("disabled", true); 
                $("#id_alcance_report").change( function(){
                    if($(this).val() === "Especifico"){
                        $("#id_unity_report").prop("required", true);
                        $("#id_unity_report").prop("disabled", false);    
                        $("#id_warehouse_report").prop("disabled", true);                      
                    } else {
                        $("#id_unity_report").prop("disabled", true);
                        $("#id_warehouse_report").prop("disabled", true); 
                    }

                })

            break;

            case "845":

                
                $("#id_unity_report").prop("disabled", true);
                $("#id_warehouse_report").prop("disabled", true);
                $("#id_alcance_report").change( function(){
                    if($(this).val() === "Especifico"){
                        $("#id_unity_report").prop("required", true);
                        $("#id_unity_report").prop("disabled", false);    
                        $("#id_warehouse_report").prop("disabled", true);                      
                    } else {
                        $("#id_unity_report").prop("disabled", true);
                        $("#id_warehouse_report").prop("disabled", true); 
                    }

                })

            break;

            case "564":

               
                $("#id_unity_report").prop("disabled", true);
                $("#id_warehouse_report").prop("disabled", true);  
                $("#id_alcance_report").change( function(){
                    if($(this).val() === "Especifico"){
                        $("#id_unity_report").prop("required", true);
                        $("#id_unity_report").prop("disabled", false);    
                        $("#id_warehouse_report").prop("disabled", true);                      
                    } else {
                        $("#id_unity_report").prop("disabled", true);
                        $("#id_warehouse_report").prop("disabled", true); 
                    }

                })

            break;
            

        }       
             
    })
})

$(document).ready(function(){
    $("#cbx_rsu").change(function(){
        
        $('#cbx_rsu option:selected').each(function(){
            id_rsu = $(this).val();
            $.post("../functions/Search/GetTeam.php", { id_rsu: id_rsu}, function(data){
                $("#cbx_team").html(data);
            })
        })

    })
})



 




