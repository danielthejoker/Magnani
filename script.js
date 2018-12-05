$(document).ready(function () {

    var aviso = "";

    $('#postes').click(function () {

        $('#div-avaliacoes').hide();
        $('#div-postes').show();

    });

    $('#avaliacoes').click(function () {

        $('#div-postes').hide();
        $('#div-avaliacoes').show();

    });

    $('#cadastrar-poste').click(function () {



        $.ajax({

            url: "codigo.php",
            method: "post",
            data: {
                etiqueta: $('#etiqueta').val().toUpperCase(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                material: $('#material').val(),
                acao: "cadastrar"

            },

            dataType: "json"

        }).done(function (tabela) {

            $('#erro').removeClass('alert-danger').removeClass('alert-warning').addClass('alert-success').show().text(tabela);
            $('#listar-postes').click();

        }).fail(function (erro) {

            console.log(erro.responseText);
            aviso = "Erro ao executar o comando no banco de dados.";
            $('#erro').removeClass('alert-success').removeClass('alert-warning').addClass('alert-danger').show().text(aviso);

        })

    });

    $('#listar-postes').click(function () {

        $('#titulo-tabela-postes').show();
        $('#div-tabela-postes').show();

        $.ajax({

            url: "codigo.php",
            method: "post",
            data: {
                acao: "listar"
            },

            dataType: "json",

        }).done(function (tabela) {

            $('#postes_tabela').find('tbody').empty();

            $.each(tabela, function (i, obj) {

                linha = $('<tr></tr>');

                $.each(obj, function (campo, valor) {
                    linha.append('<td>' + valor + '</td>');
                    $('#postes_tabela').find('tbody').append(linha);

                });

            });

        }).fail(function (tabela) {

            console.log(tabela);
            console.log("Fail em Listar Postes")

        });

    });

    $('#cadastrar-avaliacao').click(function () {

        $fisica = $('input[name=condicao-fisica]:checked').val();
        $cabeamento = $('input[name=condicao-cabeamento]:checked').val();
        $prumo = $('input[name=condicao-prumo]:checked').val();
        $nota = 3;

        if (!$fisica) {

           $nota--;

        } else if (!$cabeamento) {

            $nota--;

        } else if (!$prumo) {

            $nota--;

        }

        $.ajax({

            url: 'codigo.php',
            method: 'post',
            data: {
                acao: "cadastrar-avaliacao",
                etiqueta: $('#etiqueta_poste').val(),
                data: $('#data-avaliacao').val(),
                fisica: $fisica,
                cabeamento: $cabeamento,
                prumo: $prumo,
                nota: $nota
            }

        }).done(function (retorno) {

            $('#erro').removeClass('alert-danger').removeClass('alert-warning').addClass('alert-success').show().text(retorno);

        }).fail(function () {

            console.log("Fail no cadastro de avaliação");

        });

    });

});