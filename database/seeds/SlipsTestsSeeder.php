<?php

use Illuminate\Database\Seeder;
use App\OverridingCommissionPercentage;
use App\Product;
use App\Sale;
use App\SaleCommissionPercentage;
use App\SalesTarget;
use App\User;
use App\Models\Permission;
use App\Models\Menu;
use App\Models\Route;
use App\Models\Role;
use App\Customer;
use App\Agent;
use App\AgentPosition;

class SlipsTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $customers = [
            'mariani' => 'Mariani Tanujaya',
            'liestiani' => 'Liestiani Halim',
            'tricia' => 'Tricia Wati',
            'agusta' => 'Agusta Raharja',
            'ratana' => 'Ratana Arya',
            'evi' => 'Evi Suryani',
            'sutrisno' => 'Sutrisno',
            'adiputra' => 'Adiputra Tanujaya',
            'tan' => 'Tan Henny',
            'liliana' => 'Liliana Chandra',
            'oei' => 'Oei Mei Hoa',
            'sylvia' => 'Sylvia Tuinesia',
            'mulyono' => 'Mulyono',
            'jeffry' => 'Jeffry Wiseng Ng',
            'ronny' => 'Ronny',
            'victor' => 'Victor Soritan',
            'kusnan' => 'Kusnan',
            'tony' => 'Tony Heryanto',
            'unun' => 'Unun Supratiwi',
            'ida' => 'Ida Susilowati',
            'irnofasindi' => 'Irnofasindi',
            'fitri' => 'Fitri Nurfitriyah',
            'sutar' => 'Sutar',
            'nor' => 'Nor Ardijansah',
            'devijanti' => 'Devijanti',
            'anda' => 'Anda Sriani',
            'surjadi' => 'Surjadi',
            'imam' => 'Imam Taufik',
            'karina' => 'Karina Saraswati',
            'vonny' => 'Vonny Salim',
            'lisa' => 'Lisa Wijono',
            'ervina' => 'Ervina Kurniawati',
        ];
        $customers_obj = [];
        foreach($customers as $index => $name) {
            $newCustomer = Customer::create([
                'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'SPAJ' => "",
                'name' => $name,
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
            \App\Customer::updateSPAJ($newCustomer);
            $customers_obj[$index] = $newCustomer;
        }

        $agent_romi = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => null,
            'name' => 'SSD_ROMI',
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
        \App\Agent::updateAgentCode($agent_romi);

        // ROMI's AD
        $agent_mita = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_romi->id,
            'name' => 'AD_MITA',
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
            'agent_position_id' => 2,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_mita);

        // MITA's SM
        $agent_iwan = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_mita->id,
            'name' => 'SM_IWAN',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_iwan);

        $agent_adi = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_iwan->id,
            'name' => 'A_ADI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_adi);
        $agent_ama = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_iwan->id,
            'name' => 'A_AMA',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_ama);

        $agent_rani = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_mita->id,
            'name' => 'SM_RANI',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_rani);
        $agent_ara = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_rani->id,
            'name' => 'A_ARA',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_ara);
        $agent_afa = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_rani->id,
            'name' => 'A_AFA',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_afa);

        $agent_hermawan = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_romi->id,
            'name' => 'SSD_HERMAWAN',
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
        \App\Agent::updateAgentCode($agent_hermawan);
        $agent_hartono = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_hermawan->id,
            'name' => 'AD_HARTONO',
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
            'agent_position_id' => 2,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_hartono);

        // HARTONO's SM
        $agent_santi = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_hartono->id,
            'name' => 'SM_SANTI',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_santi);

        $agent_doni = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_santi->id,
            'name' => 'A_DONI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_doni);
        $agent_didi = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_santi->id,
            'name' => 'A_DIDI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_didi);

        $agent_varia = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_hartono->id,
            'name' => 'SM_VARIA',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_varia);
        $agent_dina = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_varia->id,
            'name' => 'A_DINA',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_dina);
        $agent_dodo = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_varia->id,
            'name' => 'A_DODO',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_dodo);

        $agent_putra = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_romi->id,
            'name' => 'SSD_PUTRA',
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
        \App\Agent::updateAgentCode($agent_putra);
        $agent_nina = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_putra->id,
            'name' => 'AD_Nina',
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
            'agent_position_id' => 2,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_nina);

        // NINA's SM
        $agent_rina = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_nina->id,
            'name' => 'SM_RINA',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_rina);

        $agent_coky = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_rina->id,
            'name' => 'A_COKY',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_coky);
        $agent_cici = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_rina->id,
            'name' => 'A_CICI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_cici);

        $agent_rama = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_nina->id,
            'name' => 'SM_RAMA',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_rama);
        $agent_caca = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_rama->id,
            'name' => 'A_CACA',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_caca);
        $agent_coco = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_rama->id,
            'name' => 'A_COCO',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_coco);



        $agent_bagus = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_romi->id,
            'name' => 'SSD_BAGUS',
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
        \App\Agent::updateAgentCode($agent_bagus);
        $agent_mita_bagus = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_bagus->id,
            'name' => 'AD_MITA_BAGUS',
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
            'agent_position_id' => 2,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_mita_bagus);

        // MITA_BAGUS's SM
        $agent_siska = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_mita_bagus->id,
            'name' => 'SM_SISKA',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_siska);

        $agent_boni = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_siska->id,
            'name' => 'A_BONI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_boni);
        $agent_binti = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_siska->id,
            'name' => 'A_BINTI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_binti);

        $agent_susi = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_mita_bagus->id,
            'name' => 'SM_SUSI',
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
            'agent_position_id' => 3,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_susi);
        $agent_budi = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_susi->id,
            'name' => 'A_BUDI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_budi);
        $agent_bani = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_susi->id,
            'name' => 'A_BANI',
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
            'agent_position_id' => 4,
            'join_date' => \Carbon\Carbon::createFromDate($faker->shuffle(range(2015,2017))[0], $faker->shuffle(range(1,12))[0], $faker->shuffle(range(1,28))[0])->format('d/m/Y'),
            'dist_channel' => $faker->state,
            'NPWP'  => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'bank'  => $faker->shuffle(['BCA', 'Mandiri', 'BNI', 'BTN'])[0],
            'bank_branch' => $faker->state,
            'account_number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'is_active' => 1
        ]);
        \App\Agent::updateAgentCode($agent_bani);




        /*
         * SALES DATA
         */
        $sales = [
            ['mulyono', 300000000, 4, $agent_caca],
            ['jeffry',  100000000, 1, $agent_caca],
            ['ronny', 350000000, 2, $agent_coco],
            ['victor', 400000000, 1, $agent_coco],
            ['tan', 1000000000, 2, $agent_coky],
            ['liliana', 100000000, 4, $agent_coky],
            ['oei', 75000000, 1, $agent_cici],
            ['sylvia', 200000000, 1, $agent_cici],
            ['mariani', 505000000, 4, $agent_budi],
            ['liestiani', 200000000, 4, $agent_budi],
            ['tricia', 50000000, 4, $agent_bani],
            ['agusta', 50000000, 2, $agent_bani],
            ['ratana', 500000000, 4, $agent_boni],
            ['evi', 400000000, 1, $agent_boni],
            ['sutrisno', 250000000,4, $agent_binti],
            ['adiputra', 150000000,2, $agent_binti],
            ['kusnan', 700000000,2, $agent_dina],
            ['tony', 350000000,2, $agent_dina],
            ['unun', 225000000,4, $agent_dodo],
            ['ida', 200000000,1, $agent_dodo],
            ['irnofasindi', 250000000, 4, $agent_doni],
            ['fitri', 200000000, 4, $agent_doni],
            ['sutar', 250000000, 2, $agent_didi],
            ['nor', 500000000, 2, $agent_didi],
            ['devijanti', 250000000, 2, $agent_ara],
            ['anda', 75000000, 2, $agent_ara],
            ['surjadi', 500000000, 4, $agent_afa],
            ['imam', 200000000, 2, $agent_afa],
            ['karina', 900000000, 2, $agent_adi],
            ['vonny', 120000000, 1, $agent_adi],
            ['lisa', 100000000, 4, $agent_ama],
            ['ervina', 150000000, 2, $agent_ama],
        ];
        foreach($sales as $sale) {
            $newSale = Sale::create([
                'agent_id' => $sale[3]->id,
                'agent_commission' => 1.0,
                'product_id' => 1,
                'number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'customer_id' => $customers_obj[$sale[0]]->id,
                'customer_name' => $customers_obj[$sale[0]]->name,
                'customer_dob' => $customers_obj[$sale[0]]->DOB,
                'MGI' => $sale[2],
                'currency' => 'IDR',
                'MGI_start_date' => \Carbon\Carbon::create(2016, 2, 17, 0, 0, 0)->format('d/m/Y'),
                'nominal' => $sale[1],
                'interest' => '10',
                'is_active' => 1
            ]);
            Sale::updateMGIMonth($newSale);
            Customer::updateLastTransaction($customers_obj[$sale[0]]);
        }

        /*
         * TAMBAHAN
         */

        $agent_lv2 = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_bagus->id,
            'name' => 'SSD_LV2',
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
        \App\Agent::updateAgentCode($agent_lv2);
        $agent_lv3 = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_lv2->id,
            'name' => 'SSD_LV3',
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
        \App\Agent::updateAgentCode($agent_lv3);
        $agent_lv4 = Agent::create([
            'agent_code' => "",
            'NIK' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
            'parent_id' => $agent_lv3->id,
            'name' => 'SSD_LV4',
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
        \App\Agent::updateAgentCode($agent_lv4);

        $sales = [
            ['mulyono', 1500000000, 2, $agent_romi],
            ['jeffry',  2000000000, 4, $agent_romi],
            ['ronny', 250000000, 1, $agent_romi],
            ['victor', 500000000, 4, $agent_bagus],
            ['adiputra', 5000000000, 2, $agent_bagus],
            ['sutrisno', 750000000, 1, $agent_lv2],
            ['evi', 325000000, 2, $agent_lv2],
            ['nor', 250000000, 4, $agent_lv3],
            ['anda', 10000000000, 2, $agent_lv3],
            ['imam', 5000000000, 1, $agent_lv4],
            ['tan', 1000000000, 4, $agent_lv4],
        ];
        foreach($sales as $sale) {
            $newSale = Sale::create([
                'agent_id' => $sale[3]->id,
                'agent_commission' => 1.0,
                'product_id' => 1,
                'number' => strval($faker->randomNumber(9)) . strval($faker->randomNumber(7)),
                'customer_id' => $customers_obj[$sale[0]]->id,
                'customer_name' => $customers_obj[$sale[0]]->name,
                'customer_dob' => $customers_obj[$sale[0]]->DOB,
                'MGI' => $sale[2],
                'currency' => 'IDR',
                'MGI_start_date' => \Carbon\Carbon::create(2016, 2, 17, 0, 0, 0)->format('d/m/Y'),
                'nominal' => $sale[1],
                'interest' => '10',
                'is_active' => 1
            ]);
            Sale::updateMGIMonth($newSale);
            Customer::updateLastTransaction($customers_obj[$sale[0]]);
        }

    }
}
