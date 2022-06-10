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
