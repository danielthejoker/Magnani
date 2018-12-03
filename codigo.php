<?php

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

            $stringConexao = "host=localhost port=5432 dbname=magnaniinspections user=poli password=poli";
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

                    $erro = "Poste de etiqueta {$sql['etiqueta']} cadastrado com sucesso!";
                    echo json_encode($erro);
                    break;

                }

            }

        case "listar":

            $stringConexao = "host=localhost port=5432 dbname=magnaniinspections user=poli password=poli";
            $db = pg_connect($stringConexao);

            if (!$db) {

                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {

                $sql = "SELECT poste.id, etiqueta, latitude, longitude, material.material FROM poste JOIN material ON material.id = poste.material_id ORDER BY poste.id DESC";
                $select = pg_query($db, $sql);
                $tabela = pg_fetch_all($select);

                echo json_encode($tabela);
                break;

            }

        case "cadastrar-avaliacao":

            $stringConexao = "host=localhost port=5432 dbname=magnaniinspections user=poli password=poli";
            $db = pg_connect($stringConexao);

            if (!$db) {

                $erro = "Sem conexão com o banco de dados!";
                break;

            } else {

                $sql = array(
                    "id" => $_POST['id_avaliacao'],
                    "etiqueta" => $_POST['etiqueta'],
                    "data" => $_POST['data'],
                    "fisica" => $_POST['fisica'],
                    "cabeamento" => $_POST['cabeamento'],
                    "prumo" => $_POST['prumo'],
                    "nota" => $_POST['nota']
                );

                $insert = "INSERT INTO avaliacao(id, data, condicao_adequada, prumo_adequada, cabeamento_adequada, nota, postes_etiqueta)
	                        VALUES ({$sql['id']}, {$sql['data']}, {$sql['fisica']}, {$sql['cabeamento']}, {$sql['prumo']}, {$sql['nota']}, {$sql['etiqueta']})";
                $result = pg_query($db, $insert);

                if (!$result) {

                    $erro = pg_last_error($db);
                    echo json_encode($erro);
                    break;

                } else {

                    $erro = "Avaliação de ID {$sql['id']} cadastrada com sucesso";
                    echo json_encode($erro);
                    break;

                }

            }

    }

}

