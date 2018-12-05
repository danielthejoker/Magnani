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
    $('#pesquisar-avaliados').click(function () {
        $('#tabela-notas').hide();
        $('#nota_postes').hide();
        $('#tabela-nao-avaliacoes').hide();
        $('#tabela-postes-nao-avaliados').hide();
        if ($('#data_inicial').val()!="" && $('#data_final')!= "") {
            $.ajax({

                url: "codigo.php",
                method: "post",
                data: {
                    acao: "listar_avaliados",
                    inicial: $('#data_inicial').val(),
                    final: $('#data_final').val()
                },

                dataType: "json",

            }).done(function (tabela) {
                if (tabela.linhas > 0) {
                    $('#tabela-avaliacoes').show();
                    $('#avaliacoes-postes').show();
                }
                if (tabela.falha == false && tabela.linhas > 0) {

                    $('#tabela-avaliacoes').find('tbody').empty();

                    $.each(tabela.tabela, function (i, obj) {

                        linha = $('<tr></tr>');

                        $.each(obj, function (campo, valor) {
                            linha.append('<td>' + valor + '</td>');
                            $('#tabela-avaliacoes').find('tbody').append(linha);

                        });

                    });
                    $('#erro').removeClass('alert-danger').addClass('alert-success').show().text('Listagem concluida!');
                } else {
                    if (tabela.linhas == 0) {
                        $('#erro').show().text('0 resultados para este periodo!');
                    }
                    ;
                    console.log(tabela.erro);
                }
            });
        }else {
            $('#erro').show().text('Os campos de data nao podem estar vazios!');
        }
    });

    $('#listar-postes-nao-avaliados').click(function () {
        $('#tabela-notas').hide();
        $('#tabela-avaliacoes').hide();
        $('#nota_postes').hide();
        $('#avaliacoes-postes').hide();
        if ($('#data_inicial').val()!="" && $('#data_final')!= "") {
            $.ajax({

                url: "codigo.php",
                method: "post",
                data: {
                    acao: "listar_nao_avaliados",
                    inicial: $('#data_inicial').val(),
                    final: $('#data_final').val()
                },

                dataType: "json",

            }).done(function (tabela) {
                if (tabela.linhas > 0) {
                    $('#tabela-nao-avaliacoes').show();
                    $('#tabela-postes-nao-avaliados').show();
                }
                if (tabela.falha == false && tabela.linhas > 0) {

                    $('#tabela-postes-nao-avaliados').find('tbody').empty();

                    $.each(tabela.tabela, function (i, obj) {

                        linha = $('<tr></tr>');

                        $.each(obj, function (campo, valor) {
                            linha.append('<td>' + valor + '</td>');
                            $('#tabela-postes-nao-avaliados').find('tbody').append(linha);

                        });

                    });
                    $('#erro').removeClass('alert-danger').addClass('alert-success').show().text('Listagem concluida!');
                } else {
                    if (tabela.linhas == 0) {
                        $('#erro').show().text('0 resultados para este periodo!');
                    }
                    console.log(tabela.erro)
                }

            });
        }else{
                $('#erro').show().text('Os campos de data nao podem estar vazios!');
            }
    });

    $('#relatorio-notas').click(function () {
        $('#tabela-avaliacoes').hide();
        $('#avaliacoes-postes').hide();
        $('#tabela-nao-avaliacoes').hide();
        $('#tabela-postes-nao-avaliados').hide();
        if ($('#data_inicial').val()!="" && $('#data_final')!= "") {
            $.ajax({

                url: "codigo.php",
                method: "post",
                data: {
                    acao: "exibir_notas",
                    inicial: $('#data_inicial').val(),
                    final: $('#data_final').val()
                },

                dataType: "json",

            }).done(function (tabela) {
                if (tabela.linhas > 0) {
                    $('#nota_postes').show();
                    $('#tabela-notas').show();
                }
                if (tabela.falha == false && tabela.linhas > 0) {

                    $('#nota_postes').find('tbody').empty();

                    $.each(tabela.tabela, function (i, obj) {

                        linha = $('<tr></tr>');

                        $.each(obj, function (campo, valor) {
                            linha.append('<td>' + valor + '</td>');
                            $('#nota_postes').find('tbody').append(linha);

                        });

                    });
                    $('#erro').removeClass('alert-danger').addClass('alert-success').show().text('Listagem concluida!');
                } else {
                    if (tabela.linhas == 0) {
                        $('#erro').show().text('0 resultados para este periodo!');
                    }
                    ;
                    console.log(tabela.erro);
                }
            });
        }else {
            $('#erro').show().text('Os campos de data nao podem estar vazios!');
        }
    });

    $('#cadastrar-avaliacao').click(function () {

        $fisica = $('input[name=condicao-fisica]:checked').val();
        $cabeamento = $('input[name=condicao-cabeamento]:checked').val();
        $prumo = $('input[name=condicao-prumo]:checked').val();
        var nota=3;
        if($fisica=='false'){nota--}
        if($cabeamento=='false'){nota--}
        if($prumo=='false'){nota--}
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
                nota: nota
            }

        }).done(function (retorno) {

            $('#erro').removeClass('alert-danger').removeClass('alert-warning').addClass('alert-success').show().text(retorno.toString());

        }).fail(function () {

            console.log("Fail no cadastro de avaliação");

        });

    });

});