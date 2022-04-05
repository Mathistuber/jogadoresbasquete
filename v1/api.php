<?php

require_once '../model/Operacao.php';

function isTheseParametersAvailable($params){
$avalilable = true;
$missingparams = "";

foreach($params as $param){
    if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
        $avalilable = false;
        $missingparams = $missingparams.", ".$param;
    }
}

if(!$avalilable){
    $response = array();
    $response['error'] = true;
    $response['message'] = 'Parameters '.substr($missingparams, 1,strlen($missingparams)).' missing';

    echo json_encode($response);

    die();
}
}

$response = array();

if(isset($_GET['apicall'])){
    switch($_GET['apicall']){

        case 'createJogador':
           isTheseParametersAvailable(array('campo_2','campo_3','campo_4'));

            $db = new Operacao();

            $result = $db->createJogador(
                $_POST['campo_2'],
                $_POST['campo_3'],
                $_POST['campo_4'],
               
            );

            if($result){
                $response['error'] = false;
                $response['message']= 'Dados inseridos com sucesso.';
                $response['dadoscreate'] = $db->getJogador();
            }else{
                $response['error'] = true;
                $response['message']= 'Dados nao foram inseridos.';
            }

        break;
        case 'getJogador':
            $db= new Operacao();
            $response['error'] = false;
            $response['message']= 'Dados Listados com sucesso.';
            $response['dadoslista']=$db->getJogador();

        break;
        case 'updateJogador':
            isTheseParametersAvailable(array('campo_1','campo_2','campo_3','campo_4'));

            $db = new Operacao();
            $result = $db->updateJogador(
                $_POST['campo_1'],
                $_POST['campo_2'],
                $_POST['campo_3'],
                $_POST['campo_4']
            );

            if($result){
                $response['error'] = false;
                $response['message'] = "Dados alterados com sucesso.";
                $response['dadosalterar'] = $db->getJogador();
            }else{
                $response['error'] = true;
                $response['message'] = "Dados não alterados.";
            }
        break;
        case 'deleteJogador':
            if(isset($_GET['campo_1'])){
                $db = new Operacao();
                if($db->deleteJogador($_GET['campo_1'])){
                    $response['error'] = false;
                    $response['message'] = "Dado excluido com sucesso";
                    $response['deleteFrutas'] = $db->getJogador();

                }else{
                    $response['error'] = true;
                    $response['message'] = "Algo deu errado";
                }
            }else{
                $response['error'] = true;
                $response['message'] = "Dados não apagados.";
            }
        break;

    }
}else{
    $response['error'] = true;
    $response['message'] = "Chamada de Api com defeito";
}

echo json_encode($response);
