<?php
require_once '../app/Database.php';

header('Content-Type: application/json');

$postObj = json_decode(utf8_encode(file_get_contents('php://input')));

$id_entered = [];
$indexId = 0;

$presence = new stdClass();
$presence->code_ec = false;
$presence->libelle_ec = false;
$presence->nature = false;
$presence->HCM = false;
$presence->HEI = false;
$presence->HTD = false;
$presence->HTP = false;
$presence->HTPL = false;
$presence->HPRJ = false;
$presence->NbEpr = false;
$presence->CNU = false;
$presence->no_cat = false;
$presence->code_ec_pere = false;
$presence->code_ue = false;

$returnedValues = new stdClass;
$returnedValues->values = [];
$returnedValues->success = false;
$returnedValues->errors = [];


foreach ($postObj->values as $values) {
    $strReq = "INSERT INTO `ec` (";
    $data = "(";

    $strReq .= " `code_ec` ";
    $data .= "'$values->code_ec'";
    array_push($id_entered, $values->code_ec);

    $strReq .= " ,`libelle_ec` ";
    $data .= ",'$values->libelle_ec'";

    if (isset($values->nature)) {
        $strReq .= " ,`nature` ";
        $data .= ",'$values->nature'";
    }

    if (isset($values->HCM)) {
        $strReq .= " ,`HCM` ";
        $data .= ",'$values->HCM'";
    }

    if (isset($values->HEI)) {
        $strReq .= " ,`HEI` ";
        $data .= ",'$values->HEI'";
    }
    if (isset($values->HTD)) {
        $strReq .= " ,`HTD` ";
        $data .= ",'$values->HTD'";
    }
    if (isset($values->HTP)) {
        $strReq .= " ,`HTP` ";
        $data .= ",'$values->HTP'";
    }
    if (isset($values->HTPL)) {
        $strReq .= " ,`HTPL` ";
        $data .= ",'$values->HTPL'";
    }
    if (isset($values->HPRJ)) {
        $strReq .= " ,`HPRJ` ";
        $data .= ",'$values->HPRJ'";
    }
    if (isset($values->NbEpr)) {
        $strReq .= " ,`NbEpr` ";
        $data .= ",'$values->NbEpr'";
    }
    if (isset($values->CNU)) {
        $strReq .= " ,`CNU` ";
        $data .= ",'$values->CNU'";
    }
    if (isset($values->no_cat)) {
        $strReq .= " ,`no_cat` ";
        $data .= ",'$values->no_cat'";
    }
    if (isset($values->code_ec_pere)) {
        $strReq .= " ,`code_ec_pere` ";
        $data .= ",'$values->code_ec_pere'";
    }
    if (isset($values->code_ue)) {
        $strReq .= " ,`code_ue` ";
        $data .= ",'$values->code_ue'";
    }

    $strReq .= ") VALUES $data )";

    $requete = $db->prepare($strReq);

    if ($requete->execute()) {
        $resultStr = "SELECT `code_ec`, `libelle_ec`, `nature`, `HCM`, `HEI`, `HTD`, `HTP`, `HTPL`, `HPRJ`
        , `NbEpr`, `CNU`, `no_cat`, `code_ec_pere`, `code_ue` FROM `ec` WHERE ";
        $resultStr .= "`code_ec` = '$id_entered[$indexId]'";
        $indexId++;

        $result = $db->query($resultStr);
        $row = $result->fetch(PDO::FETCH_OBJ);

        $obj = new stdClass();
        $obj->code_ec = $row->code_ec;
        $obj->libelle_ec = $row->libelle_ec;
        $obj->nature = $row->nature;
        $obj->HCM = $row->HCM;
        $obj->HEI = $row->HEI;
        $obj->HTD = $row->HTD;
        $obj->HTP = $row->HTP;
        $obj->HTPL = $row->HTPL;
        $obj->HPRJ = $row->HPRJ;
        $obj->NbEpr = $row->NbEpr;
        $obj->CNU = $row->CNU;
        $obj->no_cat = $row->no_cat;
        $obj->code_ec_pere = $row->code_ec_pere;
        $obj->code_ue = $row->code_ue;

        array_push($returnedValues->values, $obj);
        $returnedValues->success = true;
    } else {
        $error = $requete->errorInfo();

        $obj = new stdClass();
        $obj->error_code = $error[0]; //enregistrement code d'erreur
        $obj->error_desc = $error[2]; //enregistrement message d'erreru renvoyé
        array_push($returnedValues->errors, $obj);
    }
}

echo json_encode($returnedValues);