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
        case 'dashpie2':
            $records= $fn->pie2();
            // $records= $fn->sub();
            break;
    case 'area_grph':
       $records= $fn->area_grph();
        break;
    case 'polled_store':
        $records= $fn->polled_store();
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
    case 'usermtc_dtable':
         $records['usermtc_data']= $fn->usermtc_table();
        break;    
    case 're_assigned_supp':
       $records['re_assigned_supp'] =$fn->reassign_itsup();
       break; 
    case 'changepass':
       $records['re_assigned_supp'] =$fn->reassign_itsup();
        break;
    case 'notif_support':
         $records['ntfsupdata']= $fn->notif_techsupp();
        break;
    case 'admin_get_reports':
         $records['adminrptdata']= $fn->admin_get_reports();
         break; 
    case 'genrep_overallpie':
             $records= $fn->genrep_statpie();
        break;
    case 'genrep_catpie':
        $records= $fn->gencatpie();
        break;
    case 'genrep_strgrph':
        $records= $fn->genstr_grph();
        break;
    default:
        break;

}
echo json_encode($records);

 
?>