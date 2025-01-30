$(document).ready(function() {
    $('#tipo_local').change(function() {
        var tipo = $(this).val();
        $('#campos_adicionais').show();

        if (tipo == 'evento') {
            $('#campos_evento').show();
        } else {
            $('#campos_evento').hide();
        }
    });

    $('input[name="cep"]').mask('00000-000');
    $('input[name="cnpj"]').mask('00.000.000/0000-00');
});
