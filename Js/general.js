$('.summernote').summernote();

$('#frequency_type').change(function () {
    if (this.value == 1) {
        $('#div_frequency_value_hours').show();
        $('#div_frequency_value_date').hide();
    } else {
        $('#div_frequency_value_hours').hide();
        $('#div_frequency_value_date').show();
    }
});

$('#frequency_type_edit').change(function () {
    if (this.value == 1) {
        $('#div_frequency_value_hours_edit').show();
        $('#div_frequency_value_date_edit').hide();
    } else {
        $('#div_frequency_value_hours_edit').hide();
        $('#div_frequency_value_date_edit').show();
    }
});

$('#form_inspection_of_mant_teams').on('submit', function (e) {
    e.preventDefault();
    const frequencyType = $('#frequency_type').val();
    if (frequencyType == 1 && !$('#frequency_value_hours').val()) {
        swal.fire({
            title: 'Ingrese un valor para la frecuencia en horas',
            type: 'error',
        });
        return;
    }
    if (frequencyType == 2 && !$('#frequency_value_date').val()) {
        swal.fire({
            title: 'Ingrese un valor para la frecuencia en fechas',
            type: 'error',
        });
        return;
    }
    document.querySelector('#form_inspection_of_mant_teams').submit();
});

var calendarEl = document.getElementById('calendar');
if (calendarEl) {
    var Calendar = FullCalendar.Calendar;

    const eventResults = [];
    maintenanceEvents.forEach(event => {
        const dateParts = event.next_date.split("-");
        const jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2));

        eventResults.push({
            title          : event.maintenance_carried,
            start          : jsDate,
            allDay         : false,
            backgroundColor: '#0073b7', //Blue
            borderColor    : '#0073b7' //Blue
        });
    });
    var calendar = new Calendar(calendarEl, {
        headerToolbar: {
            left  : 'prev,next today',
            center: 'title',
            right : 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        //Random default events
        events: eventResults,
        editable  : true,
        eventClick: function(info) {
            alert(info.event.title);
        }
    });

    // calendar.render();

    function showCalendar() {
        $('#modal-calendar').modal('show');
    }

    $('#modal-calendar').on('shown.bs.modal', function () {
        calendar.render();
    });
}

var generalCalendarEl = document.getElementById('general-calendar');
if (generalCalendarEl) {
    var Calendar = FullCalendar.Calendar;
    const eventResults = [];
    maintenanceEvents.forEach(event => {
        if (event.next_date) {
            const dateParts = event.next_date.split("-");
            const jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2));

            eventResults.push({
                title          : `${event.type_teams_units} / ${event.plate_teams_units} ${event.maintenance_carried}`,
                start          : jsDate,
                allDay         : false,
                backgroundColor: '#0073b7', //Blue
                borderColor    : '#0073b7', //Blue,
                extraDataAttrs: event
            });
        }
    });
    var calendar = new Calendar(generalCalendarEl, {
        headerToolbar: {
            left  : 'prev,next today',
            center: 'title',
            right : 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        //Random default events
        events: eventResults,
        editable  : true,
        eventClick: function(info) {
            $('#nombre-especifico').text(info.event.extendedProps.extraDataAttrs.name_teams_units);
            $('#tipo-equipo').text(info.event.extendedProps.extraDataAttrs.type_teams_units);
            $('#modelo').text(info.event.extendedProps.extraDataAttrs.model_teams_units);
            $('#serie').text(info.event.extendedProps.extraDataAttrs.serie_teams_units);
            $('#referencia').text(info.event.extendedProps.extraDataAttrs.reference);
            $('#capacidad').text(info.event.extendedProps.extraDataAttrs.capacity_teams_units);
            $('#marca').text(info.event.extendedProps.extraDataAttrs.mark_teams_units);
            $('#placa').text(info.event.extendedProps.extraDataAttrs.plate_teams_units);
            $('#caracteristicas').text(info.event.extendedProps.extraDataAttrs.description_teams_units);
            $('#mantenimiento').text(info.event.extendedProps.extraDataAttrs.maintenance_carried);
            $('#frecuencia').text(info.event.extendedProps.extraDataAttrs.frequency_inspection_teams);
            $('#modal-event-general-calendar').modal('show');
        }
    });
    calendar.render();
}
