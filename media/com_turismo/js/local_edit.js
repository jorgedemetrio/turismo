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


    $('input[name="cep"]').on('blur', function() {
        var cep = $(this).val();
        if (cep) {
            $.ajax({
                url: 'index.php?option=com_turismo&task=local.checkCep&cep=' + cep,
                method: 'GET',
                success: function(response) {
                    if (response) {
                        var data = JSON.parse(response);
                        $('input[name="uf"]').val(data.uf).prop('readonly', true);
                        $('input[name="cidade"]').val(data.cidade).prop('readonly', true);
                        $('input[name="endereco"]').val(data.endereco).prop('readonly', true);
                        $('input[name="bairro"]').val(data.bairro).prop('readonly', true);
                    }
                }
            });
        }
    });

    $('input[name="cep"]').on('change', function() {
        $('input[name="uf"], input[name="cidade"], input[name="endereco"], input[name="bairro"]').prop('readonly', false);
    });


    $('input[name="cep"]').mask('00000-000');
    $('input[name="cnpj"]').mask('00.000.000/0000-00');
});
