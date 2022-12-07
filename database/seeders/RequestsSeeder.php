<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        //selecting first 20 users
        for($i=0 ; $i < 20 ; $i++){
            //adding next 20 users as connection request send for first selected users
            for($j=21 ; $j < 40 ; $j++){
                Connection::create([
                    "sender_id" =>$users[$i]->id,
                    "receiver_id"=>$users[$j]->id,
                    "status"=>'requested',
                ]);
            }
        }
    }
}
