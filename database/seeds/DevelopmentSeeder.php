<?php

use App\OverridingCommissionPercentage;
use App\Product;
use App\Sale;
use App\SaleCommissionPercentage;
use App\SalesTarget;
use Illuminate\Database\Seeder;
use App\User;
use App\Models\Permission;
use App\Models\Menu;
use App\Models\Route;
use App\Models\Role;
use App\Customer;
use App\Agent;
use App\AgentPosition;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // TODO: Remove this before release...
        // Look for and delete route named 'do-not-pre-load' if it exist.
        // That route is used to test a failure with the Authorization middleware and should not be loaded automatically.
        $routeToDelete = Route::where('name', 'test-acl.do-not-pre-load')->get()->first();
        if ($routeToDelete) Route::destroy($routeToDelete->id);

        $faker = \Faker\Factory::create();
        foreach(range(1,50) as $index) {
            $newCustomer = Customer::create([
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Married','Single'])[0],
                'occupation' => $faker->word,
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'id_card_image_filename' => $faker->md5 . '.' . 'png',
                'last_transaction' => \Carbon\Carbon::now()->toDateTimeString(),
                'is_active' => 1
            ]);
        }



        //parents
        foreach(range(1,10) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => null,
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Married','Single'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 1,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }

        //children
        foreach(range(1,10) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(1,10))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 1,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }

        //grandchildren
        foreach(range(1,10) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(11,20))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 1,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }

        //great-grandchildren
        foreach(range(1,5) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(21,30))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 1,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }


        foreach(range(1,10) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(1,35))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => $faker->shuffle(array_keys(config('cities')))[0],
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 2,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }
        foreach(range(1,10) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(20,45))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 2,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }
        foreach(range(1,20) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(1,55))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 3,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }
        foreach(range(1,30) as $index) {
            $newAgent = Agent::create([
                'agent_code' => "",
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'parent_id' => $faker->shuffle(range(1,75))[0],
                'name' => $faker->name,
                'birth_place' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'DOB' => \Carbon\Carbon::createFromDate($faker->shuffle(range(1930,2000))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'gender' => $faker->shuffle(['F','M'])[0],
                'address' => $faker->address,
                'state' => "Jawa Timur",
                'city' => $faker->shuffle(config('cities')["Jawa Timur"])[0],
                'religion' => $faker->shuffle(config('religions'))[0],
                'marriage_status' => $faker->shuffle(['Kawin','Belum Kawin'])[0],
                'nationality' => $faker->shuffle(['WNI','WNA'])[0],
                'id_card_expiry_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'agent_position_id' => 4,
                'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'dist_channel' => $faker->state,
                'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
                'bank_branch' => $faker->state,
                'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'is_active' => 1
            ]);
            \App\Agent::updateAgentCode($newAgent);
        }

        foreach(range(1,50) as $index){
            $customerID = $faker->shuffle(range(1,45))[0];
            $newSale = Sale::create([
                'agent_id' => $faker->shuffle(range(1,70))[0],
                'SPAJ' => strval($faker->randomNumber(9)),
                'agent_commission' => 1.0,
                'product_id' => 1,
                'number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'customer_id' => $customerID,
                'customer_name' => Customer::getCustomerFromId($customerID)->name,
                'customer_dob' => Customer::getCustomerFromId($customerID)->DOB,
                'MGI' => $faker->shuffle(array_keys(config('MGIs')))[0],
                'currency' => 'IDR',
                'MGI_start_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
                'nominal' => $faker->randomNumber(2) * pow(10, $faker->shuffle(range(6,9))[0]),
                'interest' => '10',
                'is_active' => 1
            ]);
            Sale::updateMGIMonth($newSale);
            Customer::updateLastTransaction(Customer::getCustomerFromId($customerID));
        }

    }
}
