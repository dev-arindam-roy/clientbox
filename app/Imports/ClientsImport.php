<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class ClientsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $ImportRows)
    {
        $isEmailExist = Client::where('email_id', $ImportRows['email_id'])
            ->where('user_id', Auth::user()->id)
            ->exists();
        if (!$isEmailExist) {
            $client = new Client;
            $client->user_id = Auth::user()->id;
            $client->first_name = $ImportRows['first_name'];
            $client->last_name = $ImportRows['last_name'];
            $client->email_id = $ImportRows['email_id'];
            $client->phno = $ImportRows['mobile_number'];
            $client->country_id = $ImportRows['country'];
            $client->city = $ImportRows['city'];
            $client->note = $ImportRows['note'];
            $client->save();
        }
    }
}
