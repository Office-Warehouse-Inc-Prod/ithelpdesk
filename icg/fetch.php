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
        case 'search_desc':
            $output= $fn->search_desc();
            break;
        case 'search_tkt':
            $output= $fn->search_tkt();
            break;
        case 'pditems':
            $output['rptpd']=$fn->pditems();
        break;
        default:
            $output['rptdata']=$fn->getstrreports();
        break;
        case 'get_tos':
            $output= $fn->get_tos();
            break;
        case 'pv_res':
            $output= $fn->pv_res();
            break;
        case 'rars_count':
            $output= $fn->rars_count();
            break;
}
echo json_encode($output);

?>