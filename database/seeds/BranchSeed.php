<?php

use Illuminate\Database\Seeder;

use App\Model\Branch;
use App\Model\BranchService;

class BranchSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $raw = json_decode(
            file_get_contents(__DIR__.'/data/branch.json'),
            true
        );

        foreach ($raw as $data) {
            if (empty($data['location-name'])) {
                continue;
            }

            $rawContacts = collect($data['contacts']['contact']);

            $this->command->info($data['location-name']);

            $branch                  = Branch::firstOrNew(['name' => $data['location-name']]);
            $branch->type            = 'headquarters';
            $branch->ward_code       = 0;
            $branch->district_code   = 0;
            $branch->province_code   = 0;
            $branch->address         = $data['address']['postal-address'] ?? '';
            $branch->longitude       = $data['address']['_longitude'] ?? '';
            $branch->latitude        = $data['address']['_latitude'] ?? '';
            $branch->phone_number    = $rawContacts->firstWhere('_type', 'customer-service-hotline')['__cdata'] ?? '';
            $branch->fax_number      = $rawContacts->firstWhere('_type', 'central-fax')['__cdata'] ?? '';
            $branch->email           = strtolower($rawContacts->firstWhere('_type', 'central-email')['__cdata'] ?? '');
            $branch->additional_info = $data['additional-info']['institution'] ?? '';
            $branch->saveOrFail();

            $services    = [];
            $rawServices = $data['services']['service'] ?? [];

            foreach ((array) $rawServices as $rawService) {
                if (empty($rawService['_srvid'])) {
                    continue;
                }

                $this->command->info('----> ' . $rawService['__cdata']);
                $services = BranchService::firstOrCreate(['name' => $rawService['__cdata']]);
            }

            $branch->services()->sync($services);
            $branch->save();

            unset($branch, $services);
            usleep(50000);
        }
    }
}
