<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Camper;
use App\Models\CampInstance;
use App\Models\Payment;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get camp instances and campers
        $campInstances = CampInstance::all();
        $campers = Camper::all();

        if ($campInstances->isEmpty() || $campers->isEmpty()) {
            $this->command->warn('No camp instances or campers found. Skipping enrollment seeding.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'waitlisted', 'cancelled'];
        $paymentMethods = ['cash', 'check', 'credit_card', 'online'];

        foreach ($campInstances as $campInstance) {
            // Get campers that don't already have an enrollment for this camp instance
            $enrolledCamperIds = Enrollment::where('camp_instance_id', $campInstance->id)->pluck('camper_id')->toArray();
            $availableCampers = $campers->whereNotIn('id', $enrolledCamperIds);
            
            if ($availableCampers->isEmpty()) {
                continue;
            }
            
            // Create enrollments for this camp instance
            $numEnrollments = min(rand(3, 8), $availableCampers->count());
            $selectedCampers = $availableCampers->random($numEnrollments);
            
            foreach ($selectedCampers as $camper) {
                $status = $statuses[array_rand($statuses)];
                $balanceCents = rand(50000, 200000); // $500 to $2000
                $amountPaidCents = 0;
                
                // If confirmed, add some payments
                if ($status === 'confirmed') {
                    $amountPaidCents = rand(0, $balanceCents);
                }

                $enrollment = Enrollment::create([
                    'camp_instance_id' => $campInstance->id,
                    'camper_id' => $camper->id,
                    'status' => $status,
                    'balance_cents' => $balanceCents,
                    'amount_paid_cents' => $amountPaidCents,
                    'forms_complete' => rand(0, 1),
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                    'notes' => rand(0, 1) ? 'Sample enrollment note for testing purposes.' : null,
                ]);

                // Create payments if there are any
                if ($amountPaidCents > 0) {
                    $numPayments = rand(1, 3);
                    $remainingAmount = $amountPaidCents;
                    
                    for ($j = 0; $j < $numPayments; $j++) {
                        $paymentAmount = $j === $numPayments - 1 ? $remainingAmount : rand(10000, $remainingAmount / 2);
                        $remainingAmount -= $paymentAmount;
                        
                        Payment::create([
                            'enrollment_id' => $enrollment->id,
                            'amount_cents' => $paymentAmount,
                            'method' => $paymentMethods[array_rand($paymentMethods)],
                            'reference' => 'REF-' . strtoupper(substr(md5(rand()), 0, 8)),
                            'paid_at' => now()->subDays(rand(1, 60)),
                            'notes' => rand(0, 1) ? 'Sample payment note.' : null,
                            'processed_by_user_id' => 1, // Assuming admin user ID 1 exists
                        ]);
                    }
                }
            }
        }

        $this->command->info('Enrollments and payments seeded successfully!');
    }
}
