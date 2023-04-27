<?php

include('function.php');

$fn = new dbconfig();
$mode = $_POST['operation'];
;

switch ($mode) {
        case 'Add':
            $output['insertdata'] =$fn->inserdata();
        break;
        case 'changepass':
            $output['changepass'] =$fn->user_change_password();
            break;
        case 'remarks':
            $output['msgs'] =$fn->getmsgs();
        break;
        case 'Addcomment':
            $output['cmmnt'] =$fn->insertcomm();
        break;
        default:
            $output['rptdata']=$fn->getstrreports();
        break;
}
echo json_encode($output);

?>