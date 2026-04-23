<?php

namespace App\Console\Commands;

use App\Models\IpRange;
use Illuminate\Console\Command;

class ImportIpJson extends Command
{
    protected $signature = 'import:ip-json {path}';

    protected $description = 'Import large JSON file into database';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error('File not found');
            return;
        }

        $handle = fopen($path, 'r');

        $batchSize = 1000;
        $data = [];

        while (($line = fgets($handle)) !== false) {
            $json = json_decode($line, true);

            if (!$json) continue;

            $data[] = [
                'network' => $json['network'] ?? null,
                'country' => $json['country'] ?? null,
                'country_code' => $json['country_code'] ?? null,
                'continent' => $json['continent'] ?? null,
                'continent_code' => $json['continent_code'] ?? null,
                'asn' => $json['asn'] ?? null,
                'as_name' => $json['as_name'] ?? null,
                'as_domain' => $json['as_domain'] ?? null,
            ];

            if (count($data) >= $batchSize) {
                IpRange::insert($data);
                echo "Inserted: " . json_encode($data, JSON_PRETTY_PRINT);
                $data = [];
                $this->info('Inserted batch...');
            }
        }

        if (!empty($data)) {
            IpRange::insert($data);
        }

        fclose($handle);

        $this->info('Import completed!');
    }

}
