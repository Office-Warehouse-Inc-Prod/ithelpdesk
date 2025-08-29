<?php 
require_once('db.php');
// extracting POST Variables
extract($_POST);
    $error = [];
    $check = $concat->query("SELECT * FROM item_masterfile_refine WHERE item_masterfile_refine.ALU = '{$Alu}'");
    if($check->num_rows < 1){
        $error['field_name'] = 'Alu';
        $error['msg']=" INVALID ALU.";
    }
    echo json_encode($error);