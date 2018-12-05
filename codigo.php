<?php
session_start();
 $stringConexao = isset($_SESSION['conexao'])?$_SESSION['conexao']:false;
if ($_POST) {

    $acao = $_POST['acao'];

    switch ($acao) {

        case "cadastrar":

            $sql = array(
                "etiqueta" => $_POST['etiqueta'],
                "latitude" => $_POST['latitude'],
                "longitude" => $_POST['longitude'],
                "material" => $_POST['material']
            );

            $stringConexao = "host=localhost port=5432 dbname=MagnaniInspections user=postgres password=123456";
            $db = pg_connect($stringConexao);

            if (!$db) {

                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {

                $insert = "INSERT INTO poste (etiqueta, latitude, longitude, material_id)
	                    VALUES ('{$sql['etiqueta']}', {$sql['latitude']}, {$sql['longitude']}, '{$sql['material']}');";

                $result = pg_query($db, $insert);

                if (!$result) {

                    $erro = pg_last_error($db);
                    echo json_encode($erro);
                    break;

                } else {
                    $_SESSION['conexao'] = $stringConexao;
                    $erro = "Poste de etiqueta {$sql['etiqueta']} cadastrado com sucesso!";
                    echo json_encode($erro);
                    break;

                }

            }

        case "listar":

            $stringConexao = $_SESSION['conexao'];
            $db = pg_connect($stringConexao);

            if (!$db) {

                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {

                $sql = "SELECT poste.id, etiqueta, latitude, longitude, material.material from poste join material on material.id=poste.material_id order by poste.id desc";
                $select = pg_query($db, $sql);
                $tabela = pg_fetch_all($select);
                if (!$select) {

                    $erro = pg_last_error($db);
                    echo json_encode($erro);
                    break;

                } else {
                    echo json_encode($tabela);
                    break;

                }


            }

        case "listar_nao_avaliados":

            $stringConexao = $_SESSION['conexao'];
            $db = pg_connect($stringConexao);

            if (!$db) {
                $inicial = $_POST['inicial'];
                $final = $_POST['final'];
                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {
                $inicial = $_POST['inicial'];
                $final = $_POST['final'];
                $sql = "SELECT postes_etiqueta, TO_CHAR(avaliacao.data, 'DD/MM/YYYY') as data, poste.latitude, poste.longitude, material.material
                        FROM avaliacao join poste on poste.etiqueta=avaliacao.postes_etiqueta join material on material.id = poste.material_id
                        WHERE avaliacao.data NOT BETWEEN '{$inicial}' and '$final';";
                $select = pg_query($db, $sql);
                $tabela = pg_fetch_all($select);
                $rows = pg_num_rows($select);
                if (!$select) {

                    $erro = pg_last_error($db);
                    $retorno = array(
                        'erro' => $erro,
                        'falha' => true

                    );
                    echo json_encode($retorno);
                    break;

                } else {

                    $erro = "Listagem Concluida!";
                    $retorno = array(
                        'tabela' => $tabela,
                        'erro' => $erro,
                        'falha' => false,
                        'linhas' => $rows
                    );
                    echo json_encode($retorno);
                    break;

                }

            }
        case "listar_avaliados":

            $stringConexao = $_SESSION['conexao'];
            $db = pg_connect($stringConexao);

            if (!$db) {
                $inicial = $_POST['inicial'];
                $final = $_POST['final'];
                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {
                $inicial = $_POST['inicial'];
                $final = $_POST['final'];
                $sql = "SELECT avaliacao.id, TO_CHAR(avaliacao.data, 'DD/MM/YYYY') as data, case when condicao_adequada = true then 'Adequado' else 'Inadequado' END AS Condicao_Adequada, case when prumo_adequada = true then 'Adequado' else 'Inadequado' END AS Prumo_Adequado, case when cabeamento_adequada = true then 'Adequado' else 'Inadequado' END AS Cabeamento_Adequado, nota, postes_etiqueta 
    	                FROM avaliacao JOIN poste ON avaliacao.postes_etiqueta = poste.etiqueta where avaliacao.data between '{$inicial}' and '{$final}';";
                $select = pg_query($db, $sql);
                $tabela = pg_fetch_all($select);
                $rows = pg_num_rows($select);

                if (!$select) {

                    $erro = pg_last_error($db);
                    $retorno = array(
                        'erro' => $erro,
                        'falha' => true

                    );
                    echo json_encode($retorno);
                    break;

                } else {

                    $erro = "Listagem Concluida!";
                    $retorno = array(
                        'tabela' => $tabela,
                        'erro' => $erro,
                        'falha' => false,
                        'linhas' => $rows
                    );
                    echo json_encode($retorno);
                    break;

                }
            }
        case "exibir_notas":

            $stringConexao = $_SESSION['conexao'];
            $db = pg_connect($stringConexao);

            if (!$db) {
                $inicial = $_POST['inicial'];
                $final = $_POST['final'];
                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {
                $inicial = $_POST['inicial'];
                $final = $_POST['final'];
                $sql = "SELECT SUM(avaliacao.nota) as nota_geral, COUNT(avaliacao.nota)*3 as nota_maxima FROM avaliacao where avaliacao.data between '{$inicial}' and '{$final}';";
                $select = pg_query($db, $sql);
                $tabela = pg_fetch_all($select);
                $rows = pg_num_rows($select);
                if (!$select) {

                    $erro = pg_last_error($db);
                    $retorno = array(
                        'erro' => $erro,
                        'falha' => true

                    );
                    echo json_encode($retorno);
                    break;

                } else {

                    $erro = "Listagem Concluida!";
                    $retorno = array(
                        'tabela' => $tabela,
                        'erro' => $erro,
                        'falha' => false,
                        'linhas' => $rows
                    );
                    echo json_encode($retorno);
                    break;

                }
            }

        case "cadastrar-avaliacao":

            $stringConexao = $_SESSION['conexao'];
            $db = pg_connect($stringConexao);

            if (!$db) {

                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {

                $sql = array(
                    "etiqueta" => $_POST['etiqueta'],
                    "data" => $_POST['data'],
                    "fisica" => $_POST['fisica'],
                    "cabeamento" => $_POST['cabeamento'],
                    "prumo" => $_POST['prumo'],
                    "nota" => $_POST['nota']
                );
                $insert = "INSERT INTO avaliacao(data, condicao_adequada, prumo_adequada, cabeamento_adequada, postes_etiqueta, nota)
	                        VALUES ('{$sql['data']}', '{$sql['fisica']}', '{$sql['prumo']}', '{$sql['cabeamento']}', '{$sql['etiqueta']}',{$sql['nota']})";
                $result = pg_query($db, $insert);
                $tabela = pg_fetch_all($result);

                if (!$result) {

                    $erro = pg_last_error($db);
                    echo json_encode($erro);
                    break;

                } else {

                    $erro = "Avaliação de cadastrada com sucesso";
                    echo json_encode($erro);
                    break;

                }

            }

    }

}

