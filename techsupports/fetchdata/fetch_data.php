<?php

include 'admin_function.php';

$fn = new dbconfig();
// $fn->fetch_cards_result();

$mode= $_POST['mode'];
switch ($mode) {
    case 'yearch':
      $records= $fn->fetch_cards_result();
        break;
    case 'overallgrph':
      $records= $fn->overallpie_res();
        break;
    case 'techbargrph':
      $records= $fn->bargrph_tech_res();   
        break;
    case 'dblinegrph':
      $records= $fn->linegraph();   

        break;
    case 'dashpie':
        $records= $fn->pie();
        // $records= $fn->sub();
        break;
    case 'area_grph':
       $records= $fn->area_grph();
        break;
    case 'str_grph':
        $records= $fn->str_grph();
        break;

     case 'dtb':
         $records['rptdata']= $fn->admin_data_table_res();

          break; 
    case 'newrpt_tbl':
         $records['newrptdata']= $fn->newreporthist();
        break;  
    case 'notif_support':
         $records['ntfsupdata']= $fn->notif_techsupp();
        break;
    case 'netpie':
        $records= $fn->netpie();
        break;
    case 'overallnet':
        $records= $fn->overallnet_res();
            break;
    case 'areanet_grph':
        $records= $fn->areanet_grph();
            break;
    case 'strnet_grph':
        $records= $fn->strnet_grph();
        break;
    case 'area_grph':
        $records= $fn->area_grph();
        break; 
    default:
        break;
}
echo json_encode($records);


?>