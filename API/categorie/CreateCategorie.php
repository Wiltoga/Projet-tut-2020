<?php
require_once '../app/Database.php';

header('Content-Type: application/json');

$postObj = json_decode(utf8_encode(file_get_contents('php://input')));

$id_entered=[];
$indexId=0;

$returnedValues = new stdClass;
    $returnedValues->values = [];
    $returnedValues->success=false;
    $returnedValues->errors=[];


foreach ($postObj->values as $values) {
    $strReq = "INSERT INTO `categories` (";
    $data = "(";
    
    $strReq .= " `no_cat` ";
    $data .= "'$values->no_cat'";
    array_push($id_entered, $values->no_cat);

    $strReq .= " ,`categorie` ";
    $data .= ",'$values->categorie'"; 

    $strReq .= ") VALUES $data )";

    $createReq = $db->prepare($strReq);
    $statement = $createReq->execute();
    $error = $createReq->errorInfo();
    
    if ( $error[0] == '00000' ) {
        $nbRows = $createReq->rowCount();
        if ($nbRows != 0) {
            $resultStr = "SELECT `no_cat`,`categorie` FROM `categories` WHERE ";
            $resultStr .= "`no_cat` = '$id_entered[$indexId]'";
            $indexId++;

            $result=$db->query($resultStr);
            $row=$result->fetch(PDO::FETCH_OBJ);

            $obj = new stdClass();
            $obj->no_cat = $row->no_cat;
            $obj->categorie = $row->categorie;

            array_push($returnedValues->values, $obj);
            $returnedValues->success=true;
        } else {
            $obj = new stdClass();
            $obj->error_desc = "0 row affected";
            $returnedValues->errors[] = $obj;
        }
    }   
    else {
        $obj = new stdClass();
        $obj->error_code = $error[0]; //enregistrement code d'erreur
        $obj->error_desc = $error[2]; //enregistrement message d'erreur renvoyé
        array_push($returnedValues->errors, $obj);
    }
}

echo json_encode($returnedValues);