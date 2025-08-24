<?php

namespace App\Livewire\ParentPortal;

use App\Models\FormTemplate;
use App\Models\FormResponse;
use App\Models\FormAnswer;
use App\Models\Camper;
use App\Models\Enrollment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FormFilling extends Component
{
    use WithFileUploads;

    public $campers;
    public $templates;
    public $selectedCamper;
    public $selectedTemplate;
    public $selectedEnrollment;
    public $showFormModal = false;
    public $currentResponse;
    
    // Form data
    public $formData = [];
    public $fileUploads = [];
    public $isSubmitting = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        
        // Get campers accessible to the user
        $this->campers = $user->accessibleCampers()->with(['family', 'enrollments.campInstance.camp'])->get();
        
        // Get available form templates
        $this->templates = FormTemplate::where('is_active', true)
            ->with(['fields' => function ($query) {
                $query->orderBy('sort');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function selectCamper($camperId)
    {
        $this->selectedCamper = $this->campers->find($camperId);
        $this->selectedEnrollment = null;
        $this->selectedTemplate = null;
    }

    public function selectEnrollment($enrollmentId)
    {
        $this->selectedEnrollment = $this->selectedCamper->enrollments->find($enrollmentId);
    }

    public function selectTemplate($templateId)
    {
        $this->selectedTemplate = $this->templates->find($templateId);
        $this->initializeFormData();
        $this->showFormModal = true;
    }

    public function initializeFormData()
    {
        if (!$this->selectedTemplate) return;

        $this->formData = [];
        $this->fileUploads = [];

        foreach ($this->selectedTemplate->fields as $field) {
            // Initialize based on field type
            switch ($field->type) {
                case 'checkbox':
                    $this->formData[$field->id] = [];
                    break;
                case 'date':
                    $this->formData[$field->id] = null;
                    break;
                case 'radio':
                    $this->formData[$field->id] = '';
                    break;
                default:
                    $this->formData[$field->id] = '';
                    break;
            }
        }
    }

    public function openForm($templateId, $camperId, $enrollmentId = null)
    {
        $this->selectedCamper = $this->campers->find($camperId);
        $this->selectedEnrollment = $enrollmentId ? $this->selectedCamper->enrollments->find($enrollmentId) : null;
        $this->selectTemplate($templateId);
    }

    public function submitForm()
    {
        if (!$this->selectedTemplate || !$this->selectedCamper) return;

        // Validate required fields
        $this->validateFormData();

        $this->isSubmitting = true;

        try {
            DB::transaction(function () {
                // Create or update form response
                $response = FormResponse::updateOrCreate(
                    [
                        'form_template_id' => $this->selectedTemplate->id,
                        'camper_id' => $this->selectedCamper->id,
                        'enrollment_id' => $this->selectedEnrollment?->id,
                    ],
                    [
                        'submitted_by_user_id' => auth()->id(),
                        'submitted_at' => now(),
                        'is_complete' => true,
                        'notes' => null,
                    ]
                );

                // Delete existing answers
                $response->answers()->delete();

                // Create new answers
                foreach ($this->formData as $fieldId => $value) {
                    $field = $this->selectedTemplate->fields->find($fieldId);
                    if (!$field) continue;

                    $answerData = [
                        'form_response_id' => $response->id,
                        'form_field_id' => $fieldId,
                    ];

                    // Handle different field types
                    switch ($field->type) {
                        case 'file':
                            if (isset($this->fileUploads[$fieldId]) && $this->fileUploads[$fieldId]) {
                                $path = $this->fileUploads[$fieldId]->store('form-uploads', 'public');
                                $answerData['file_path'] = $path;
                            }
                            break;
                        case 'checkbox':
                            if (is_array($value) && !empty($value)) {
                                $answerData['value_json'] = json_encode($value);
                            }
                            break;
                        case 'radio':
                            if (!empty($value)) {
                                $answerData['value_text'] = $value;
                            }
                            break;
                        case 'date':
                            if (!empty($value)) {
                                $answerData['value_text'] = $value;
                            }
                            break;
                        default:
                            if (!empty($value)) {
                                $answerData['value_text'] = $value;
                            }
                            break;
                    }

                    FormAnswer::create($answerData);
                }
            });

            $this->showFormModal = false;
            $this->resetFormData();
            $this->dispatch('form-submitted');
            
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to submit form. Please try again.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function resetFormData()
    {
        $this->formData = [];
        $this->fileUploads = [];
        $this->selectedTemplate = null;
        $this->currentResponse = null;
    }

    public function validateFormData()
    {
        if (!$this->selectedTemplate) return;

        $rules = [];
        $messages = [];

        foreach ($this->selectedTemplate->fields as $field) {
            if ($field->required) {
                $fieldRules = ['required'];
                
                // Add field-specific validation
                switch ($field->type) {
                    case 'email':
                        $fieldRules[] = 'email';
                        break;
                    case 'number':
                        $fieldRules[] = 'numeric';
                        break;
                    case 'date':
                        $fieldRules[] = 'date';
                        break;
                    case 'checkbox':
                        $fieldRules[] = 'array';
                        $fieldRules[] = 'min:1';
                        break;
                }

                $rules["formData.{$field->id}"] = $fieldRules;
                $messages["formData.{$field->id}.required"] = "The {$field->label} field is required.";
            }
        }

        if (!empty($rules)) {
            $this->validate($rules, $messages);
        }
    }

    public function hasFieldValue($fieldId)
    {
        if (!isset($this->formData[$fieldId])) {
            return false;
        }

        $value = $this->formData[$fieldId];
        
        if (is_array($value)) {
            return !empty(array_filter($value));
        }
        
        return !empty($value);
    }

    public function getAvailableTemplatesForCamper($camper)
    {
        return $this->templates->filter(function ($template) use ($camper) {
            // Check if template is global or matches camper's enrollments
            if ($template->scope === 'global') {
                return true;
            }

            if ($template->scope === 'camp_session') {
                return $camper->enrollments->contains('camp_instance_id', $template->camp_instance_id);
            }

            return false;
        });
    }

    public function getResponseStatus($template, $camper, $enrollment = null)
    {
        $query = FormResponse::where('form_template_id', $template->id)
            ->where('camper_id', $camper->id);

        if ($enrollment) {
            $query->where('enrollment_id', $enrollment->id);
        } else {
            $query->whereNull('enrollment_id');
        }

        $response = $query->first();

        if (!$response) {
            return 'not_started';
        }

        return $response->is_complete ? 'complete' : 'incomplete';
    }

    public function render()
    {
        return view('livewire.parent-portal.form-filling')->layout('components.layouts.app');
    }
}
