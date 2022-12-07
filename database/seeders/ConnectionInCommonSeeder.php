<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConnectionInCommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users= User::get();

        //selecting first 20 users
        for($i=0 ; $i < 40 ; $i++){
            if ($i < 21) {
                //adding next 20 users as accepted connections for first 20 users
                for($j=41 ; $j < 60 ; $j++){
                    Connection::create([
                        "sender_id" =>$users[$i]->id,
                        "receiver_id"=>$users[$j]->id,
                        "status"=>'accepted',
                    ]);
                }
            } else {
                //adding next 20 users as accepted connections for users with id 21 to 40
                for($j=51 ; $j < 70 ; $j++){
                    Connection::create([
                        "sender_id" =>$users[$i]->id,
                        "receiver_id"=>$users[$j]->id,
                        "status"=>'accepted',
                    ]);
                }
            }
            
        }
    }
}
