<?php

namespace App\Traits;
use Log;
use DB;

trait BulkActionTrait {

    public function bulkAction($rowIds = [], $tabName, $action)
    {
        $returnArr = [];
        if (!empty($rowIds)) {

            switch ($action) {

                case 'delete' : 
                    DB::table($tabName)->whereIn('id', $rowIds)->delete();
                    break;
            }

            $returnArr['isExecuted'] = true;
            return $returnArr;
        }
    }
}
