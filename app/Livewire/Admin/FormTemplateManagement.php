<?php

namespace App\Livewire\Admin;

use App\Models\FormTemplate;
use App\Models\FormField;
use App\Models\CampInstance;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class FormTemplateManagement extends Component
{
    use WithFileUploads;

    public $templates;
    public $campInstances;
    public $selectedTemplate;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showFieldsModal = false;
    
    // Template form fields
    public $name;
    public $description;
    public $scope = 'global';
    public $campInstanceId;
    public $isActive = true;
    public $requiresAnnualCompletion = false;
    public $sortOrder = 0;
    
    // Field form fields
    public $fieldType = 'text';
    public $fieldLabel;
    public $fieldRequired = false;
    public $fieldHelpText;
    public $fieldValidationRules;
    public $fieldOptions = [];
    public $fieldSort = 0;
    
    // Field management
    public $editingField;
    public $fieldIndex;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'scope' => 'required|in:global,camp_session',
        'campInstanceId' => 'nullable|exists:camp_instances,id',
        'isActive' => 'boolean',
        'requiresAnnualCompletion' => 'boolean',
        'sortOrder' => 'integer|min:0',
        'fieldType' => 'required|in:text,textarea,email,number,select,checkbox,radio,date,file',
        'fieldLabel' => 'required|string|max:255',
        'fieldRequired' => 'boolean',
        'fieldHelpText' => 'nullable|string',
        'fieldValidationRules' => 'nullable|string',
        'fieldSort' => 'integer|min:0',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->templates = FormTemplate::with(['campInstance.camp', 'fields'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        $this->campInstances = CampInstance::with('camp')
            ->where('is_active', true)
            ->orderBy('start_date')
            ->get();
    }

    public function openCreateModal()
    {
        $this->resetTemplateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(FormTemplate $template)
    {
        $this->selectedTemplate = $template;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->scope = $template->scope;
        $this->campInstanceId = $template->camp_instance_id;
        $this->isActive = $template->is_active;
        $this->requiresAnnualCompletion = $template->requires_annual_completion;
        $this->sortOrder = $template->sort_order;
        $this->showEditModal = true;
    }

    public function openFieldsModal(FormTemplate $template)
    {
        $this->selectedTemplate = $template;
        $this->resetFieldForm();
        $this->showFieldsModal = true;
    }

    public function createTemplate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scope' => 'required|in:global,camp_session',
            'campInstanceId' => 'nullable|exists:camp_instances,id',
            'isActive' => 'boolean',
            'requiresAnnualCompletion' => 'boolean',
            'sortOrder' => 'integer|min:0',
        ]);

        DB::transaction(function () {
            FormTemplate::create([
                'name' => $this->name,
                'description' => $this->description,
                'scope' => $this->scope,
                'camp_instance_id' => $this->scope === 'camp_session' ? $this->campInstanceId : null,
                'is_active' => $this->isActive,
                'requires_annual_completion' => $this->requiresAnnualCompletion,
                'sort_order' => $this->sortOrder,
            ]);
        });

        $this->showCreateModal = false;
        $this->resetTemplateForm();
        $this->loadData();
        $this->dispatch('template-created');
    }

    public function updateTemplate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scope' => 'required|in:global,camp_session',
            'campInstanceId' => 'nullable|exists:camp_instances,id',
            'isActive' => 'boolean',
            'requiresAnnualCompletion' => 'boolean',
            'sortOrder' => 'integer|min:0',
        ]);

        $this->selectedTemplate->update([
            'name' => $this->name,
            'description' => $this->description,
            'scope' => $this->scope,
            'camp_instance_id' => $this->scope === 'camp_session' ? $this->campInstanceId : null,
            'is_active' => $this->isActive,
            'requires_annual_completion' => $this->requiresAnnualCompletion,
            'sort_order' => $this->sortOrder,
        ]);

        $this->showEditModal = false;
        $this->resetTemplateForm();
        $this->loadData();
        $this->dispatch('template-updated');
    }

    public function toggleTemplateStatus(FormTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);
        $this->loadData();
        $this->dispatch('template-status-toggled');
    }

    public function deleteTemplate(FormTemplate $template)
    {
        // Check if template has responses
        if ($template->formResponses()->count() > 0) {
            $this->dispatch('error', 'Cannot delete template with existing responses.');
            return;
        }

        DB::transaction(function () use ($template) {
            $template->fields()->delete();
            $template->delete();
        });

        $this->loadData();
        $this->dispatch('template-deleted');
    }

    public function addField()
    {
        $this->validate([
            'fieldType' => 'required|in:text,textarea,email,number,select,checkbox,radio,date,file',
            'fieldLabel' => 'required|string|max:255',
            'fieldRequired' => 'boolean',
            'fieldHelpText' => 'nullable|string',
            'fieldValidationRules' => 'nullable|string',
            'fieldSort' => 'integer|min:0',
        ]);

        $options = null;
        if (in_array($this->fieldType, ['select', 'checkbox', 'radio']) && !empty($this->fieldOptions)) {
            $options = json_encode(array_filter($this->fieldOptions));
        }

        $this->selectedTemplate->fields()->create([
            'type' => $this->fieldType,
            'label' => $this->fieldLabel,
            'required' => $this->fieldRequired,
            'help_text' => $this->fieldHelpText,
            'validation_rules' => $this->fieldValidationRules,
            'options_json' => $options,
            'sort' => $this->fieldSort,
        ]);

        $this->resetFieldForm();
        $this->selectedTemplate->load('fields');
        $this->dispatch('field-added');
    }

    public function editField($fieldId)
    {
        $field = $this->selectedTemplate->fields()->find($fieldId);
        if (!$field) return;

        $this->editingField = $field;
        $this->fieldIndex = $field->id;
        $this->fieldType = $field->type;
        $this->fieldLabel = $field->label;
        $this->fieldRequired = $field->required;
        $this->fieldHelpText = $field->help_text;
        $this->fieldValidationRules = $field->validation_rules;
        $this->fieldSort = $field->sort;
        $this->fieldOptions = $field->options_json ? json_decode($field->options_json, true) : [];
    }

    public function updateField()
    {
        $this->validate([
            'fieldType' => 'required|in:text,textarea,email,number,select,checkbox,radio,date,file',
            'fieldLabel' => 'required|string|max:255',
            'fieldRequired' => 'boolean',
            'fieldHelpText' => 'nullable|string',
            'fieldValidationRules' => 'nullable|string',
            'fieldSort' => 'integer|min:0',
        ]);

        $options = null;
        if (in_array($this->fieldType, ['select', 'checkbox', 'radio']) && !empty($this->fieldOptions)) {
            $options = json_encode(array_filter($this->fieldOptions));
        }

        $this->editingField->update([
            'type' => $this->fieldType,
            'label' => $this->fieldLabel,
            'required' => $this->fieldRequired,
            'help_text' => $this->fieldHelpText,
            'validation_rules' => $this->fieldValidationRules,
            'options_json' => $options,
            'sort' => $this->fieldSort,
        ]);

        $this->resetFieldForm();
        $this->selectedTemplate->load('fields');
        $this->dispatch('field-updated');
    }

    public function deleteField($fieldId)
    {
        $field = $this->selectedTemplate->fields()->find($fieldId);
        if ($field) {
            $field->delete();
            $this->selectedTemplate->load('fields');
            $this->dispatch('field-deleted');
        }
    }

    public function addFieldOption()
    {
        $this->fieldOptions[] = '';
    }

    public function removeFieldOption($index)
    {
        unset($this->fieldOptions[$index]);
        $this->fieldOptions = array_values($this->fieldOptions);
    }

    public function reorderFields($fieldIds)
    {
        if (!$this->selectedTemplate) {
            return;
        }

        try {
            DB::transaction(function () use ($fieldIds) {
                foreach ($fieldIds as $index => $fieldId) {
                    FormField::where('id', $fieldId)
                        ->where('form_template_id', $this->selectedTemplate->id)
                        ->update(['sort' => $index + 1]);
                }
            });

            $this->selectedTemplate->load('fields');
            $this->dispatch('fields-reordered');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to reorder fields. Please try again.');
        }
    }

    public function resetTemplateForm()
    {
        $this->name = '';
        $this->description = '';
        $this->scope = 'global';
        $this->campInstanceId = null;
        $this->isActive = true;
        $this->requiresAnnualCompletion = false;
        $this->sortOrder = 0;
        $this->selectedTemplate = null;
    }

    public function resetFieldForm()
    {
        $this->fieldType = 'text';
        $this->fieldLabel = '';
        $this->fieldRequired = false;
        $this->fieldHelpText = '';
        $this->fieldValidationRules = '';
        $this->fieldOptions = [];
        $this->fieldSort = 0;
        $this->editingField = null;
        $this->fieldIndex = null;
    }

    public function render()
    {
        return view('livewire.admin.form-template-management')->layout('components.layouts.app');
    }
}
