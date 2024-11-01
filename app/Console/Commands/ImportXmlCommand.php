<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\VarDumper\VarDumper;

class ImportXmlCommand extends Command
{
    protected $signature = 'app:import-xml-command';
    protected $description = 'Importa dados de um xml';

    public function handle()
    {
        $path = storage_path('app/xml');

        if (!File::exists($path)) {
            $this->error("Pasta XML não encontrada.");
            return;
        }

        $files = File::glob($path . '/*.xml');

        if (empty($files)) {
            $this->error("Nenhum arquivo XML encontrado na pasta.");
            return;
        }

        $orderedFiles = [
            'hotels.xml',
            'rooms.xml',
            'reserves.xml',
            'coupons.xml',
            'dailies.xml',
            'payments.xml',
            'guests.xml',
            'reserveGuests.xml',
            'users.xml'
        ];

        $files = File::glob($path . '/*.xml');

        $files = array_intersect($orderedFiles, array_map('basename', $files));//pega e ordena os xml na lista orderedFiles

        foreach ($files as $file) {
            $this->importXmlFile($path . '/' . $file);
        }


        $this->info('XML importado.');
    }

    private function importXmlFile($filePath)
    {
        $xmlContent = simplexml_load_file($filePath);

        $json = json_encode($xmlContent);
        $data = json_decode($json, true);

        //importando os xmls
        $this->importHotels($data);
        $this->importRoom($data);
        $this->importReserves($data);
        $this->importCoupons($data);
        $this->importDailies($data);
        $this->importPayments($data);
        $this->importGuests($data);
        $this->importReserveGuests($data);
        $this->importUsers($data);



    }

    private function importHotels(array $data)
    {
        if(!isset($data['Hotel']))return null;

        foreach ($data['Hotel'] as $hotel) {
            DB::table('hotels')->updateOrInsert(
                ['id' => (int) $hotel['@attributes']['id']],
                ['name' => (string) $hotel['Name']]
            );
        }

    }

    private function importRoom(array $data)
    {
        if(!isset($data['Room']))return null;

        foreach ($data['Room'] as $room) {
            DB::table('rooms')->updateOrInsert(
                ['id' => (int) $room['@attributes']['id']],
                [
                    'hotelCode' => (int) $room['@attributes']['hotelCode'],
                    'name' => (string) $room['Name']
                ]
            );
        }

    }

    private function importReserves(array $data)
    {
        if (!isset($data['Reserve'])) return null;

        foreach ($data['Reserve'] as $reserve) {
            $reserveId = DB::table('reserves')->updateOrInsert(
                ['id' => (int) $reserve['@attributes']['id']],
                [
                    'hotelCode' => (int) $reserve['@attributes']['hotelCode'],
                    'roomCode' => (int) $reserve['@attributes']['roomCode'],
                    'checkIn' => (string) $reserve['CheckIn'],
                    'checkOut' => (string) $reserve['CheckOut'],
                    'total' => (float) $reserve['Total']
                ]
            );

            if (isset($reserve['Guest'])) {
                foreach ($reserve['Guest'] as $guest) {
                    DB::table('guests')->updateOrInsert(
                        [
                            'reserveId' => $reserveId,
                            'phone' => (string) $guest['Phone']
                        ],
                        [
                            'name' => (string) $guest['Name'],
                            'lastName' => (string) $guest['LastName'],
                            'phone' => (string) $guest['Phone']
                        ]
                    );
                }
            }

            if (isset($reserve['Daily'])) {
                foreach ($reserve['Daily'] as $daily) {
                    DB::table('dailies')->updateOrInsert(
                        [
                            'reserveId' => $reserveId,
                            'date' => (string) $daily['Date']
                        ],
                        [
                            'value' => (float) $daily['Value']
                        ]
                    );
                }
            }

            if (isset($reserve['Payment'])) {
                foreach ($reserve['Payment'] as $payment) {
                    DB::table('payments')->updateOrInsert(
                        [
                            'reserveId' => $reserveId,
                            'method' => (int) $payment['Method']
                        ],
                        [
                            'value' => (float) $payment['Value']
                        ]
                    );
                }
            }
        }
    }

    private function importCoupons(array $data)
    {
        if (!isset($data['Coupon'])) return null;

        foreach ($data['Coupon'] as $coupon) {
            DB::table('coupons')->updateOrInsert(
                ['id' => (int) $coupon['@attributes']['id']],
                [
                    'code' => (string) $coupon['Code'],
                    'discount_value' => (float) $coupon['DiscountValue']
                ]
            );
        }
    }

    private function importDailies(array $data)
    {
        if (!isset($data['Daily'])) return null;
        foreach ($data['Daily'] as $daily) {
            DB::table('dailies')->updateOrInsert(
                ['id'=>(int) $daily['@attributes']['id']],
                [
                    'reserveId' => (int) $daily['@attributes']['reserveId'],
                    'date' => (string) $daily['Date'],
                    'value' => (float) $daily['Value']
                ],
            );
        }
    }

    private function importGuests(array $data)
    {
        if (!isset($data['Guest'])) return null;

        foreach ($data['Guest'] as $guest) {
            DB::table('guests')->updateOrInsert(
                ['id' => (int) $guest['@attributes']['id']],
                [
                    'name' => (string) $guest['Name'],
                    'lastName' => (string) $guest['LastName'],
                    'phone' => (string) $guest['Phone']
                ]
            );
        }
    }

    private function importPayments(array $data)
    {
        if (!isset($data['Payment'])) return null;
        foreach ($data['Payment'] as $payment) {
            DB::table('payments')->updateOrInsert(
                [
                    'id' => (int) $payment['@attributes']['id'],
                    'reserveId' => (int) $payment['@attributes']['reserveId']
                ],
                [
                    'value' => (float) $payment['Value']
                ]
            );
        }
    }

    private function importReserveGuests(array $data)
    {
        if (!isset($data['ReserveGuest'])) return null;

        foreach ($data['ReserveGuest'] as $reserveGuest) {
            DB::table('reserve_guests')->updateOrInsert(
                [
                    'reserveId' => (int) $reserveGuest['@attributes']['reserveId'],
                    'guestId' => (int) $reserveGuest['@attributes']['guestId']
                ],
                []
            );
        }
    }

    private function importUsers(array $data)
    {
        if (!isset($data['User'])) return null;

        foreach ($data['User'] as $user) {
            DB::table('users')->updateOrInsert(
                ['id' => (int) $user['@attributes']['id']],
                [
                    'name' => (string) $user['Name'],
                    'email' => (string) $user['Email'],
                    'password' => bcrypt((string) $user['Password']),
                    'role' => (string) $user['Role']
                ]
            );
         }
    }

}



