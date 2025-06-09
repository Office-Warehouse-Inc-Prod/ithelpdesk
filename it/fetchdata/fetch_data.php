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
    case 'admin_get_reports_bycat':
         $records['adminrptdata_bycat']= $fn->admin_get_reports_bycat();
         break; 
    case 'genrep_overallpie':
             $records= $fn->genrep_statpie();
        break;
    case 'genrep_bycat_pie':
             $records= $fn->genrep_bycat_pie();
        break;
    case 'genrep_catpie':
        $records= $fn->gencatpie();
        break;
    case 'genrep_bycat_catpie':
        $records= $fn->genrep_bycat_catpie();
        break;
    case 'genrep_strgrph':
        $records= $fn->genstr_grph();
        break;
    case 'dtbl_itsup':
        $records['itsuptbldata'] = $fn->dtbl_itsup();
        break;
    case 'count_reassigned':
      $records['reassignedres'] = $fn->count_reassigned();
        break;
    case 'count_sla':
      $records['count_slares'] = $fn->count_sla();
        break;
        case 'dtbcat':
            $records['rptcat'] = $fn->tbl_cat();
              break;
    default:
        break;


}
echo json_encode($records);

 
?>