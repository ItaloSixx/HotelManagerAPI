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

        foreach ($files as $file) {
            $this->importXmlFile($file);
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
                            'reserve_id' => $reserveId,
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
                            'reserve_id' => $reserveId,
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
                            'reserve_id' => $reserveId,
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

}



