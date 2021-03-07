<?php

namespace App\Traits;
use Auth;
use Log;
use DB;

trait ImageDeleteTrait {

    public function deleteImage($tabName, $fieldName = '', $rowId = 0, $isAuth = false, $action = 'FIELD_UPDATE')
    {
        $returnArr = [];
        if ($tabName != '') {

            $qry = DB::table($tabName);

            if ($rowId != 0 && !$isAuth) {
                $qry = $qry->where('id', $rowId);
            }
            if ($isAuth && $rowId == 0) {
                $qry = $qry->where('id', Auth::user()->id);
            }
            
            $getRow = $qry->first();
        
            if ($action == 'FIELD_UPDATE' && $fieldName != '') {
                $qry = $qry->update([$fieldName => '']);
            }
            if ($fieldName == '') {
                $qry = $qry->delete();
            }

            if ($fieldName != '') {
                $imagePath = public_path('/uploads/images/');
                unlink($imagePath . $getRow->$fieldName);
                unlink($imagePath . 'resize/' . $getRow->$fieldName);
            }
            
            $returnArr['isExecuted'] = true;
            return $returnArr;
        }
    }
}
