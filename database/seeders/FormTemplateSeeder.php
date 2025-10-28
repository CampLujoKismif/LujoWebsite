<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormTemplate;
use App\Models\FormField;

class FormTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Medical Information Form (Global)
        $medicalForm = FormTemplate::firstOrCreate(
            ['name' => 'Medical Information Form'],
            [
                'scope' => 'global',
                'description' => 'Comprehensive medical information required for all campers',
                'is_active' => true,
                'requires_annual_completion' => true,
                'sort_order' => 1,
            ]
        );

        // Medical form fields
        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Primary Care Physician Name',
            ],
            [
                'type' => 'text',
                'required' => true,
                'sort' => 1,
                'help_text' => 'Name of the camper\'s primary care physician',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Physician Phone Number',
            ],
            [
                'type' => 'text',
                'required' => true,
                'sort' => 2,
                'help_text' => 'Contact number for the primary care physician',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Insurance Provider',
            ],
            [
                'type' => 'text',
                'required' => false,
                'sort' => 3,
                'help_text' => 'Name of health insurance provider',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Policy Number',
            ],
            [
                'type' => 'text',
                'required' => false,
                'sort' => 4,
                'help_text' => 'Health insurance policy number',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Allergies',
            ],
            [
                'type' => 'textarea',
                'required' => false,
                'sort' => 5,
                'help_text' => 'List any allergies (food, environmental, medication, etc.)',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Medical Conditions',
            ],
            [
                'type' => 'textarea',
                'required' => false,
                'sort' => 6,
                'help_text' => 'List any ongoing medical conditions',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Current Medications',
            ],
            [
                'type' => 'textarea',
                'required' => false,
                'sort' => 7,
                'help_text' => 'List all current medications and dosages',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $medicalForm->id,
                'label' => 'Dietary Restrictions',
            ],
            [
                'type' => 'textarea',
                'required' => false,
                'sort' => 8,
                'help_text' => 'Any dietary restrictions or special dietary needs',
            ]
        );

        // Emergency Contact Form (Global)
        $emergencyForm = FormTemplate::firstOrCreate(
            ['name' => 'Emergency Contact Form'],
            [
                'scope' => 'global',
                'description' => 'Emergency contact information for campers',
                'is_active' => true,
                'requires_annual_completion' => true,
                'sort_order' => 2,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $emergencyForm->id,
                'label' => 'Emergency Contact Name',
            ],
            [
                'type' => 'text',
                'required' => true,
                'sort' => 1,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $emergencyForm->id,
                'label' => 'Emergency Contact Phone',
            ],
            [
                'type' => 'text',
                'required' => true,
                'sort' => 2,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $emergencyForm->id,
                'label' => 'Relationship to Camper',
            ],
            [
                'type' => 'text',
                'required' => true,
                'sort' => 3,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $emergencyForm->id,
                'label' => 'Secondary Emergency Contact Name',
            ],
            [
                'type' => 'text',
                'required' => false,
                'sort' => 4,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $emergencyForm->id,
                'label' => 'Secondary Emergency Contact Phone',
            ],
            [
                'type' => 'text',
                'required' => false,
                'sort' => 5,
            ]
        );

        // Photo Release Form (Global)
        $photoForm = FormTemplate::firstOrCreate(
            ['name' => 'Photo Release Form'],
            [
                'scope' => 'global',
                'description' => 'Permission to use camper photos in camp materials',
                'is_active' => true,
                'requires_annual_completion' => true,
                'sort_order' => 3,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $photoForm->id,
                'label' => 'Photo Release Permission',
            ],
            [
                'type' => 'radio',
                'required' => true,
                'options_json' => json_encode(['Yes, I grant permission', 'No, I do not grant permission']),
                'sort' => 1,
                'help_text' => 'Do you grant permission for your child\'s photo to be used in camp materials?',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $photoForm->id,
                'label' => 'Special Instructions',
            ],
            [
                'type' => 'textarea',
                'required' => false,
                'sort' => 2,
                'help_text' => 'Any special instructions regarding photo usage',
            ]
        );

        // Activity Preferences Form (Session-specific example)
        $activityForm = FormTemplate::firstOrCreate(
            ['name' => 'Activity Preferences'],
            [
                'scope' => 'camp_session',
                'camp_instance_id' => 1, // Assuming camp instance ID 1 exists
                'description' => 'Help us plan activities your camper will enjoy',
                'is_active' => true,
                'requires_annual_completion' => false,
                'sort_order' => 4,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $activityForm->id,
                'label' => 'Preferred Activities',
            ],
            [
                'type' => 'checkbox',
                'required' => false,
                'options_json' => json_encode(['Arts & Crafts', 'Sports', 'Nature Exploration', 'Music', 'Drama', 'Science', 'Cooking', 'Outdoor Adventures']),
                'sort' => 1,
                'help_text' => 'Select all activities your camper enjoys',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $activityForm->id,
                'label' => 'Swimming Ability',
            ],
            [
                'type' => 'select',
                'required' => true,
                'options_json' => json_encode(['Non-swimmer', 'Beginner', 'Intermediate', 'Advanced']),
                'sort' => 2,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $activityForm->id,
                'label' => 'Special Interests',
            ],
            [
                'type' => 'textarea',
                'required' => false,
                'sort' => 3,
                'help_text' => 'Any special interests or hobbies we should know about',
            ]
        );

        // Waiver Form (Global)
        $waiverForm = FormTemplate::firstOrCreate(
            ['name' => 'Liability Waiver'],
            [
                'scope' => 'global',
                'description' => 'Standard liability waiver for camp participation',
                'is_active' => true,
                'requires_annual_completion' => true,
                'sort_order' => 5,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $waiverForm->id,
                'label' => 'I acknowledge and agree to the terms',
            ],
            [
                'type' => 'radio',
                'required' => true,
                'options_json' => json_encode(['I agree', 'I do not agree']),
                'sort' => 1,
                'help_text' => 'By selecting "I agree", you acknowledge that you have read and understood the liability waiver terms.',
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $waiverForm->id,
                'label' => 'Parent/Guardian Name',
            ],
            [
                'type' => 'text',
                'required' => true,
                'sort' => 2,
            ]
        );

        FormField::firstOrCreate(
            [
                'form_template_id' => $waiverForm->id,
                'label' => 'Date',
            ],
            [
                'type' => 'date',
                'required' => true,
                'sort' => 3,
            ]
        );

        $this->command->info('Form templates and fields seeded successfully!');
    }
}
