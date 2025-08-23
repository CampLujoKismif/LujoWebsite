<?php

namespace App\Livewire\ParentPortal;

use App\Models\Camper;
use App\Models\Family;
use App\Models\MedicalRecord;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class CamperManagement extends Component
{
    use WithFileUploads;

    public $families;
    public $campers;
    public $selectedCamper;
    public $showAddCamperModal = false;
    public $showEditCamperModal = false;
    public $showMedicalModal = false;
    
    // Camper form fields
    public $familyId;
    public $firstName;
    public $lastName;
    public $dateOfBirth;
    public $dateOfBaptism;
    public $biologicalGender;
    public $phoneNumber;
    public $email;
    public $grade;
    public $school;
    public $allergies = [];
    public $medicalConditions = [];
    public $medications = [];
    public $emergencyContactName;
    public $emergencyContactPhone;
    public $emergencyContactRelationship;
    public $notes;
    
    // Medical form fields
    public $medicalAllergies = [];
    public $medicalMedications = [];
    public $physicianName;
    public $physicianPhone;
    public $policyNumber;
    public $insuranceProvider;
    public $medicalEmergencyContactName;
    public $medicalEmergencyContactPhone;
    public $medicalEmergencyContactRelationship;
    public $dietaryRestrictions = [];
    public $medicalNotes;

    protected $rules = [
        'familyId' => 'required|exists:families,id',
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'dateOfBirth' => 'required|date|before:today',
        'dateOfBaptism' => 'nullable|date|before:today',
        'biologicalGender' => 'nullable|in:Male,Female',
        'phoneNumber' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'grade' => 'required|integer|min:1|max:12',
        'school' => 'nullable|string|max:255',
        'allergies' => 'array',
        'medicalConditions' => 'array',
        'medications' => 'array',
        'emergencyContactName' => 'nullable|string|max:255',
        'emergencyContactPhone' => 'nullable|string|max:20',
        'emergencyContactRelationship' => 'nullable|string|max:100',
        'notes' => 'nullable|string',
        'physicianName' => 'nullable|string|max:255',
        'physicianPhone' => 'nullable|string|max:20',
        'policyNumber' => 'nullable|string|max:255',
        'insuranceProvider' => 'nullable|string|max:255',
        'medicalEmergencyContactName' => 'nullable|string|max:255',
        'medicalEmergencyContactPhone' => 'nullable|string|max:20',
        'medicalEmergencyContactRelationship' => 'nullable|string|max:100',
        'dietaryRestrictions' => 'array',
        'medicalNotes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        $this->families = $user->families()->with(['campers.medicalRecord'])->get();
        $this->campers = $user->accessibleCampers()->with(['family', 'medicalRecord', 'enrollments.campInstance.camp'])->get();
    }

    public function openAddCamperModal()
    {
        $this->resetCamperForm();
        $this->showAddCamperModal = true;
    }

    public function openEditCamperModal(Camper $camper)
    {
        $this->selectedCamper = $camper;
        $this->familyId = $camper->family_id;
        $this->firstName = $camper->first_name;
        $this->lastName = $camper->last_name;
        $this->dateOfBirth = $camper->date_of_birth ? $camper->date_of_birth->format('Y-m-d') : '';
        $this->dateOfBaptism = $camper->date_of_baptism ? $camper->date_of_baptism->format('Y-m-d') : '';
        $this->biologicalGender = $camper->biological_gender;
        $this->phoneNumber = $camper->phone_number;
        $this->email = $camper->email;
        $this->grade = $camper->grade;
        $this->school = $camper->school;
        $this->allergies = $camper->allergies ? json_decode($camper->allergies, true) : [];
        $this->medicalConditions = $camper->medical_conditions ? json_decode($camper->medical_conditions, true) : [];
        $this->medications = $camper->medications ? json_decode($camper->medications, true) : [];
        $this->emergencyContactName = $camper->emergency_contact_name;
        $this->emergencyContactPhone = $camper->emergency_contact_phone;
        $this->emergencyContactRelationship = $camper->emergency_contact_relationship;
        $this->notes = $camper->notes;
        $this->showEditCamperModal = true;
    }

    public function openMedicalModal(Camper $camper)
    {
        $this->selectedCamper = $camper;
        $medicalRecord = $camper->medicalRecord()->first();
        
        if ($medicalRecord) {
            $this->medicalAllergies = $medicalRecord->allergies ? json_decode($medicalRecord->allergies, true) : [];
            $this->medicalMedications = $medicalRecord->medications ? json_decode($medicalRecord->medications, true) : [];
            $this->physicianName = $medicalRecord->physician_name;
            $this->physicianPhone = $medicalRecord->physician_phone;
            $this->policyNumber = $medicalRecord->policy_number;
            $this->insuranceProvider = $medicalRecord->insurance_provider;
            $this->medicalEmergencyContactName = $medicalRecord->emergency_contact_name;
            $this->medicalEmergencyContactPhone = $medicalRecord->emergency_contact_phone;
            $this->medicalEmergencyContactRelationship = $medicalRecord->emergency_contact_relationship;
            $this->medicalConditions = $medicalRecord->medical_conditions ? json_decode($medicalRecord->medical_conditions, true) : [];
            $this->dietaryRestrictions = $medicalRecord->dietary_restrictions ? json_decode($medicalRecord->dietary_restrictions, true) : [];
            $this->medicalNotes = $medicalRecord->notes;
        } else {
            $this->resetMedicalForm();
        }
        
        $this->showMedicalModal = true;
    }

    public function createCamper()
    {
        $this->validate([
            'familyId' => 'required|exists:families,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'dateOfBirth' => 'required|date|before:today',
            'dateOfBaptism' => 'nullable|date|before:today',
            'biologicalGender' => 'nullable|in:Male,Female',
            'phoneNumber' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'grade' => 'required|integer|min:1|max:12',
            'school' => 'nullable|string|max:255',
            'allergies' => 'array',
            'medicalConditions' => 'array',
            'medications' => 'array',
            'emergencyContactName' => 'nullable|string|max:255',
            'emergencyContactPhone' => 'nullable|string|max:20',
            'emergencyContactRelationship' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () {
            $camper = Camper::create([
                'family_id' => $this->familyId,
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'date_of_birth' => $this->dateOfBirth,
                'date_of_baptism' => $this->dateOfBaptism,
                'biological_gender' => $this->biologicalGender,
                'phone_number' => $this->phoneNumber,
                'email' => $this->email,
                'grade' => $this->grade,
                'school' => $this->school,
                'allergies' => json_encode($this->allergies),
                'medical_conditions' => json_encode($this->medicalConditions),
                'medications' => json_encode($this->medications),
                'emergency_contact_name' => $this->emergencyContactName,
                'emergency_contact_phone' => $this->emergencyContactPhone,
                'emergency_contact_relationship' => $this->emergencyContactRelationship,
                'notes' => $this->notes,
            ]);
        });

        $this->showAddCamperModal = false;
        $this->resetCamperForm();
        $this->loadData();
        $this->dispatch('camper-created');
    }

    public function updateCamper()
    {
        $this->validate([
            'familyId' => 'required|exists:families,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'dateOfBirth' => 'required|date|before:today',
            'dateOfBaptism' => 'nullable|date|before:today',
            'biologicalGender' => 'nullable|in:Male,Female',
            'phoneNumber' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'grade' => 'required|integer|min:1|max:12',
            'school' => 'nullable|string|max:255',
            'allergies' => 'array',
            'medicalConditions' => 'array',
            'medications' => 'array',
            'emergencyContactName' => 'nullable|string|max:255',
            'emergencyContactPhone' => 'nullable|string|max:20',
            'emergencyContactRelationship' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $this->selectedCamper->update([
            'family_id' => $this->familyId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'date_of_birth' => $this->dateOfBirth,
            'date_of_baptism' => $this->dateOfBaptism,
            'biological_gender' => $this->biologicalGender,
            'phone_number' => $this->phoneNumber,
            'email' => $this->email,
            'grade' => $this->grade,
            'school' => $this->school,
            'allergies' => json_encode($this->allergies),
            'medical_conditions' => json_encode($this->medicalConditions),
            'medications' => json_encode($this->medications),
            'emergency_contact_name' => $this->emergencyContactName,
            'emergency_contact_phone' => $this->emergencyContactPhone,
            'emergency_contact_relationship' => $this->emergencyContactRelationship,
            'notes' => $this->notes,
        ]);

        $this->showEditCamperModal = false;
        $this->resetCamperForm();
        $this->loadData();
        $this->dispatch('camper-updated');
    }

    public function saveMedicalRecord()
    {
        $this->validate([
            'physicianName' => 'nullable|string|max:255',
            'physicianPhone' => 'nullable|string|max:20',
            'policyNumber' => 'nullable|string|max:255',
            'insuranceProvider' => 'nullable|string|max:255',
            'medicalEmergencyContactName' => 'nullable|string|max:255',
            'medicalEmergencyContactPhone' => 'nullable|string|max:20',
            'medicalEmergencyContactRelationship' => 'nullable|string|max:100',
            'dietaryRestrictions' => 'array',
            'medicalNotes' => 'nullable|string',
        ]);

        $medicalRecord = $this->selectedCamper->medicalRecord()->first();
        
        if ($medicalRecord) {
            $medicalRecord->update([
                'allergies' => json_encode($this->medicalAllergies),
                'medications' => json_encode($this->medicalMedications),
                'physician_name' => $this->physicianName,
                'physician_phone' => $this->physicianPhone,
                'policy_number' => $this->policyNumber,
                'insurance_provider' => $this->insuranceProvider,
                'emergency_contact_name' => $this->medicalEmergencyContactName,
                'emergency_contact_phone' => $this->medicalEmergencyContactPhone,
                'emergency_contact_relationship' => $this->medicalEmergencyContactRelationship,
                'medical_conditions' => json_encode($this->medicalConditions),
                'dietary_restrictions' => json_encode($this->dietaryRestrictions),
                'notes' => $this->medicalNotes,
                'last_updated_by_user_id' => auth()->id(),
            ]);
        } else {
            MedicalRecord::create([
                'camper_id' => $this->selectedCamper->id,
                'allergies' => json_encode($this->medicalAllergies),
                'medications' => json_encode($this->medicalMedications),
                'physician_name' => $this->physicianName,
                'physician_phone' => $this->physicianPhone,
                'policy_number' => $this->policyNumber,
                'insurance_provider' => $this->insuranceProvider,
                'emergency_contact_name' => $this->medicalEmergencyContactName,
                'emergency_contact_phone' => $this->medicalEmergencyContactPhone,
                'emergency_contact_relationship' => $this->medicalEmergencyContactRelationship,
                'medical_conditions' => json_encode($this->medicalConditions),
                'dietary_restrictions' => json_encode($this->dietaryRestrictions),
                'notes' => $this->medicalNotes,
                'last_updated_by_user_id' => auth()->id(),
            ]);
        }

        $this->showMedicalModal = false;
        $this->resetMedicalForm();
        $this->loadData();
        $this->dispatch('medical-record-saved');
    }

    public function addAllergy()
    {
        $this->allergies[] = '';
    }

    public function removeAllergy($index)
    {
        unset($this->allergies[$index]);
        $this->allergies = array_values($this->allergies);
    }

    public function addMedicalCondition()
    {
        $this->medicalConditions[] = '';
    }

    public function removeMedicalCondition($index)
    {
        unset($this->medicalConditions[$index]);
        $this->medicalConditions = array_values($this->medicalConditions);
    }

    public function addMedication()
    {
        $this->medications[] = '';
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    public function addMedicalAllergy()
    {
        $this->medicalAllergies[] = '';
    }

    public function removeMedicalAllergy($index)
    {
        unset($this->medicalAllergies[$index]);
        $this->medicalAllergies = array_values($this->medicalAllergies);
    }

    public function addMedicalMedication()
    {
        $this->medicalMedications[] = '';
    }

    public function removeMedicalMedication($index)
    {
        unset($this->medicalMedications[$index]);
        $this->medicalMedications = array_values($this->medicalMedications);
    }

    public function addDietaryRestriction()
    {
        $this->dietaryRestrictions[] = '';
    }

    public function removeDietaryRestriction($index)
    {
        unset($this->dietaryRestrictions[$index]);
        $this->dietaryRestrictions = array_values($this->dietaryRestrictions);
    }

    public function resetCamperForm()
    {
        $this->familyId = '';
        $this->firstName = '';
        $this->lastName = '';
        $this->dateOfBirth = '';
        $this->dateOfBaptism = '';
        $this->biologicalGender = '';
        $this->phoneNumber = '';
        $this->email = '';
        $this->grade = '';
        $this->school = '';
        $this->allergies = [];
        $this->medicalConditions = [];
        $this->medications = [];
        $this->emergencyContactName = '';
        $this->emergencyContactPhone = '';
        $this->emergencyContactRelationship = '';
        $this->notes = '';
        $this->selectedCamper = null;
    }

    public function resetMedicalForm()
    {
        $this->medicalAllergies = [];
        $this->medicalMedications = [];
        $this->physicianName = '';
        $this->physicianPhone = '';
        $this->policyNumber = '';
        $this->insuranceProvider = '';
        $this->medicalEmergencyContactName = '';
        $this->medicalEmergencyContactPhone = '';
        $this->medicalEmergencyContactRelationship = '';
        $this->medicalConditions = [];
        $this->dietaryRestrictions = [];
        $this->medicalNotes = '';
    }

    public function render()
    {
        return view('livewire.parent-portal.camper-management')->layout('components.layouts.app');
    }
}
