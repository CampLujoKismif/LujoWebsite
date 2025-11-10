<?php

namespace Database\Seeders;

use App\Models\Agreement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = (int) now()->year;

        $agreements = [
            [
                'type' => 'parent_guardian',
                'title' => 'Parent / Guardian Agreement',
                'slug' => "parent-guardian-agreement-{$currentYear}",
                'year' => $currentYear,
                'version' => 'v1',
                'content' => <<<HTML
<p>By signing this agreement, I confirm that I have reviewed and verified all camper information and medical information for the current camp year. I acknowledge that I have read, understand, and agree to the Parent / Guardian policies and expectations.</p>
<p>I authorize camp staff to provide necessary medical care, including emergency treatment, and to contact the listed emergency contacts if needed.</p>
HTML,
                'is_active' => true,
                'published_at' => now(),
            ],
            [
                'type' => 'camper',
                'title' => 'Camper Code of Conduct',
                'slug' => "camper-code-of-conduct-{$currentYear}",
                'year' => $currentYear,
                'version' => 'v1',
                'content' => <<<HTML
<p>I understand that camp is a community environment and I agree to uphold the values of respect, safety, and cooperation. I will follow the directions of camp staff, participate fully, and treat other campers with kindness.</p>
<p>I understand that failure to uphold these expectations may result in restricted activities or being sent home at my parent or guardian's expense.</p>
HTML,
                'is_active' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($agreements as $agreementData) {
            Agreement::updateOrCreate(
                [
                    'slug' => $agreementData['slug'],
                ],
                $agreementData
            );
        }
    }
}

