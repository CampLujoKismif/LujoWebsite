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
        $medicalForm = FormTemplate::create([
            'scope' => 'global',
            'name' => 'Medical Information Form',
            'description' => 'Comprehensive medical information required for all campers',
            'is_active' => true,
            'requires_annual_completion' => true,
            'sort_order' => 1,
        ]);

        // Medical form fields
        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'text',
            'label' => 'Primary Care Physician Name',
            'required' => true,
            'sort' => 1,
            'help_text' => 'Name of the camper\'s primary care physician',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'text',
            'label' => 'Physician Phone Number',
            'required' => true,
            'sort' => 2,
            'help_text' => 'Contact number for the primary care physician',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'text',
            'label' => 'Insurance Provider',
            'required' => false,
            'sort' => 3,
            'help_text' => 'Name of health insurance provider',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'text',
            'label' => 'Policy Number',
            'required' => false,
            'sort' => 4,
            'help_text' => 'Health insurance policy number',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'textarea',
            'label' => 'Allergies',
            'required' => false,
            'sort' => 5,
            'help_text' => 'List any allergies (food, environmental, medication, etc.)',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'textarea',
            'label' => 'Medical Conditions',
            'required' => false,
            'sort' => 6,
            'help_text' => 'List any ongoing medical conditions',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'textarea',
            'label' => 'Current Medications',
            'required' => false,
            'sort' => 7,
            'help_text' => 'List all current medications and dosages',
        ]);

        FormField::create([
            'form_template_id' => $medicalForm->id,
            'type' => 'textarea',
            'label' => 'Dietary Restrictions',
            'required' => false,
            'sort' => 8,
            'help_text' => 'Any dietary restrictions or special dietary needs',
        ]);

        // Emergency Contact Form (Global)
        $emergencyForm = FormTemplate::create([
            'scope' => 'global',
            'name' => 'Emergency Contact Form',
            'description' => 'Emergency contact information for campers',
            'is_active' => true,
            'requires_annual_completion' => true,
            'sort_order' => 2,
        ]);

        FormField::create([
            'form_template_id' => $emergencyForm->id,
            'type' => 'text',
            'label' => 'Emergency Contact Name',
            'required' => true,
            'sort' => 1,
        ]);

        FormField::create([
            'form_template_id' => $emergencyForm->id,
            'type' => 'text',
            'label' => 'Emergency Contact Phone',
            'required' => true,
            'sort' => 2,
        ]);

        FormField::create([
            'form_template_id' => $emergencyForm->id,
            'type' => 'text',
            'label' => 'Relationship to Camper',
            'required' => true,
            'sort' => 3,
        ]);

        FormField::create([
            'form_template_id' => $emergencyForm->id,
            'type' => 'text',
            'label' => 'Secondary Emergency Contact Name',
            'required' => false,
            'sort' => 4,
        ]);

        FormField::create([
            'form_template_id' => $emergencyForm->id,
            'type' => 'text',
            'label' => 'Secondary Emergency Contact Phone',
            'required' => false,
            'sort' => 5,
        ]);

        // Photo Release Form (Global)
        $photoForm = FormTemplate::create([
            'scope' => 'global',
            'name' => 'Photo Release Form',
            'description' => 'Permission to use camper photos in camp materials',
            'is_active' => true,
            'requires_annual_completion' => true,
            'sort_order' => 3,
        ]);

        FormField::create([
            'form_template_id' => $photoForm->id,
            'type' => 'radio',
            'label' => 'Photo Release Permission',
            'required' => true,
            'options_json' => json_encode(['Yes, I grant permission', 'No, I do not grant permission']),
            'sort' => 1,
            'help_text' => 'Do you grant permission for your child\'s photo to be used in camp materials?',
        ]);

        FormField::create([
            'form_template_id' => $photoForm->id,
            'type' => 'textarea',
            'label' => 'Special Instructions',
            'required' => false,
            'sort' => 2,
            'help_text' => 'Any special instructions regarding photo usage',
        ]);

        // Activity Preferences Form (Session-specific example)
        $activityForm = FormTemplate::create([
            'scope' => 'camp_session',
            'camp_instance_id' => 1, // Assuming camp instance ID 1 exists
            'name' => 'Activity Preferences',
            'description' => 'Help us plan activities your camper will enjoy',
            'is_active' => true,
            'requires_annual_completion' => false,
            'sort_order' => 4,
        ]);

        FormField::create([
            'form_template_id' => $activityForm->id,
            'type' => 'checkbox',
            'label' => 'Preferred Activities',
            'required' => false,
            'options_json' => json_encode(['Arts & Crafts', 'Sports', 'Nature Exploration', 'Music', 'Drama', 'Science', 'Cooking', 'Outdoor Adventures']),
            'sort' => 1,
            'help_text' => 'Select all activities your camper enjoys',
        ]);

        FormField::create([
            'form_template_id' => $activityForm->id,
            'type' => 'select',
            'label' => 'Swimming Ability',
            'required' => true,
            'options_json' => json_encode(['Non-swimmer', 'Beginner', 'Intermediate', 'Advanced']),
            'sort' => 2,
        ]);

        FormField::create([
            'form_template_id' => $activityForm->id,
            'type' => 'textarea',
            'label' => 'Special Interests',
            'required' => false,
            'sort' => 3,
            'help_text' => 'Any special interests or hobbies we should know about',
        ]);

        // Waiver Form (Global)
        $waiverForm = FormTemplate::create([
            'scope' => 'global',
            'name' => 'Liability Waiver',
            'description' => 'Standard liability waiver for camp participation',
            'is_active' => true,
            'requires_annual_completion' => true,
            'sort_order' => 5,
        ]);

        FormField::create([
            'form_template_id' => $waiverForm->id,
            'type' => 'radio',
            'label' => 'I acknowledge and agree to the terms',
            'required' => true,
            'options_json' => json_encode(['I agree', 'I do not agree']),
            'sort' => 1,
            'help_text' => 'By selecting "I agree", you acknowledge that you have read and understood the liability waiver terms.',
        ]);

        FormField::create([
            'form_template_id' => $waiverForm->id,
            'type' => 'text',
            'label' => 'Parent/Guardian Name',
            'required' => true,
            'sort' => 2,
        ]);

        FormField::create([
            'form_template_id' => $waiverForm->id,
            'type' => 'date',
            'label' => 'Date',
            'required' => true,
            'sort' => 3,
        ]);

        $this->command->info('Form templates and fields seeded successfully!');
    }
}
