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

$('#team_activity_type').change(function () {
    if (this.value == 1) {
        $('#div_team_activity_hours_worked').show();
        $('#team_activity_hours_worked').attr('required', 'required');
    } else {
        $('#div_team_activity_hours_worked').hide();
        $('#team_activity_hours_worked').removeAttr('required');
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
        if (event.type_row == 'frecuencia' && event.next_date) {

            const dateParts = event.next_date.split("-");
            const jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0, 2));

            eventResults.push({
                title: event.maintenance_carried,
                start: jsDate,
                allDay: false,
                backgroundColor: '#0073b7', //Blue
                borderColor: '#0073b7' //Blue
            });
        } else {
            if (event.date) {
                const dateParts = event.date.split("-");
                const jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0, 2));
                eventResults.push({
                    title: event.comment ? event.comment : 'Horas Trabajadas',
                    start: jsDate,
                    allDay: false,
                    backgroundColor: '#b7002b', //Blue
                    borderColor: '#b7002b' //Blue
                });
            }
        }
    });
    var calendar = new Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        //Random default events
        events: eventResults,
        editable: true,
        eventClick: function (info) {
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
        if (event.type_row == 'frecuencia') {
            if (event.next_date) {
                const dateParts = event.next_date.split("-");
                const jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0, 2));

                eventResults.push({
                    title: `${event.type_teams_units} / ${event.name_teams_units} ${event.maintenance_carried}`,
                    start: jsDate,
                    allDay: false,
                    backgroundColor: '#0073b7', //Blue
                    borderColor: '#0073b7', //Blue,
                    extraDataAttrs: event
                });
            }
        } else {
            const dateParts = event.date.split("-");
            const jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0, 2));
            eventResults.push({
                title: (`${event.type_teams_units} / ${event.name_teams_units} ` + (event.comment ? event.comment : 'Horas Trabajadas')),
                start: jsDate,
                allDay: false,
                backgroundColor: '#b7002b', //Blue
                borderColor: '#b7002b', //Blue,
                extraDataAttrs: event
            });
        }
    });
    var calendar = new Calendar(generalCalendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        //Random default events
        events: eventResults,
        editable: true,
        eventClick: function (info) {
            if (info.event.extendedProps.extraDataAttrs.type_row == 'frecuencia') {
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

                $('#container-modelo').show();
                $('#container-serie').show();
                $('#container-referencia').show();
                $('#container-capacidad').show();
                $('#container-marca').show();
                $('#container-placa').show();
                $('#container-caracteristicas').show();
                $('#container-mantenimiento').show();
                $('#container-frecuencia').show();

                $('#container-fecha-activity').hide();
                $('#container-hours-activity').hide();
                $('#container-comment-activity').hide();
                $('#modal-event-general-calendar').modal('show');
            } else {
                $('#nombre-especifico').text(info.event.extendedProps.extraDataAttrs.name_teams_units);
                $('#tipo-equipo').text(info.event.extendedProps.extraDataAttrs.type_teams_units);

                $('#fecha-activity').text(info.event.extendedProps.extraDataAttrs.date);
                $('#hours-activity').text(info.event.extendedProps.extraDataAttrs.hours_worked);
                $('#comment-activity').text(info.event.extendedProps.extraDataAttrs.comment);

                $('#container-fecha-activity').show();
                $('#container-hours-activity').show();
                $('#container-comment-activity').show();

                $('#container-modelo').hide();
                $('#container-serie').hide();
                $('#container-referencia').hide();
                $('#container-capacidad').hide();
                $('#container-marca').hide();
                $('#container-placa').hide();
                $('#container-caracteristicas').hide();
                $('#container-mantenimiento').hide();
                $('#container-frecuencia').hide();
                $('#modal-event-general-calendar').modal('show');
            }

        }
    });
    calendar.render();
}


function eliminarRequisicion(id) {
    swal.fire({
        title: `¿Estás seguro que deseas eliminar este registro?`,
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cerrar',
        showLoaderOnConfirm: true,
        preConfirm: (arg) => {
            return fetch(`../functions/Delete/DeleteRequisition.php?id=${id}`, {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
            }).then(response => {
                if (response.status == 401) {
                    location.reload();
                }
                if (!response.ok) {
                    response.json().then(result => {
                        swal.fire({
                            title: result.message,
                            type: 'error',
                        });
                    });
                    return false;
                }
                return response.json();
            }).catch(error => {
                console.error(error);
                swal.fire({
                    title: error,
                    type: 'error'
                });
                return false;
            });
        },
        allowOutsideClick: () => !swal.isLoading()
    }).then((result) => {
        if (result.value) {
            swal.fire({
                title: result.value.message,
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

function deleteItemUnitRsu(id) {
    swal.fire({
        title: `¿Estás seguro que deseas eliminar este registro?`,
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cerrar',
        showLoaderOnConfirm: true,
        preConfirm: (arg) => {
            return fetch(`../functions/Delete/DeleteUnitRsu.php?id=${id}`, {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
            }).then(response => {
                if (response.status == 401) {
                    location.reload();
                }
                if (!response.ok) {
                    response.json().then(result => {
                        swal.fire({
                            title: result.message,
                            type: 'error',
                        });
                    });
                    return false;
                }
                return response.json();
            }).catch(error => {
                console.error(error);
                swal.fire({
                    title: error,
                    type: 'error'
                });
                return false;
            });
        },
        allowOutsideClick: () => !swal.isLoading()
    }).then((result) => {
        if (result.value) {
            swal.fire({
                title: result.value.message,
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

function deleteItemTeamUnitRsu(id) {
    swal.fire({
        title: `¿Estás seguro que deseas eliminar este registro?`,
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cerrar',
        showLoaderOnConfirm: true,
        preConfirm: (arg) => {
            return fetch(`../functions/Delete/DeleteTeamUnitRsu.php?id=${id}`, {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
            }).then(response => {
                if (response.status == 401) {
                    location.reload();
                }
                if (!response.ok) {
                    response.json().then(result => {
                        swal.fire({
                            title: result.message,
                            type: 'error',
                        });
                    });
                    return false;
                }
                return response.json();
            }).catch(error => {
                console.error(error);
                swal.fire({
                    title: error,
                    type: 'error'
                });
                return false;
            });
        },
        allowOutsideClick: () => !swal.isLoading()
    }).then((result) => {
        if (result.value) {
            swal.fire({
                title: result.value.message,
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }).then(() => {
                window.location.reload();
            });
        }
    });
}
