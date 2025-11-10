<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Camper;
use App\Models\CamperAgreementSignature;
use App\Models\CamperInformationSnapshot;
use App\Models\CamperMedicalSnapshot;
use App\Models\ParentAgreementSignature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnnualConfirmationController extends Controller
{
    /**
     * Get annual confirmation status for the authenticated parent.
     */
    public function status(Request $request): JsonResponse
    {
        $user = Auth::user();
        $year = (int) ($request->query('year') ?? now()->year);

        $parentAgreement = $this->getActiveAgreement('parent_guardian', $year);
        $camperAgreement = $this->getActiveAgreement('camper', $year);

        $parentSignature = null;
        if ($parentAgreement) {
            $parentSignature = ParentAgreementSignature::where('user_id', $user->id)
                ->where('agreement_id', $parentAgreement->id)
                ->where('year', $year)
                ->first();
        }

        $campers = $user->defaultFamily()->campers()->orderBy('first_name')->get();

        $camperStatuses = $campers->map(function (Camper $camper) use ($camperAgreement, $year) {
            $signature = null;
            if ($camperAgreement) {
                $signature = CamperAgreementSignature::where('camper_id', $camper->id)
                    ->where('agreement_id', $camperAgreement->id)
                    ->where('year', $year)
                    ->first();
            }

            $informationSnapshot = CamperInformationSnapshot::where('camper_id', $camper->id)
                ->where('year', $year)
                ->first();
            $medicalSnapshot = CamperMedicalSnapshot::where('camper_id', $camper->id)
                ->where('year', $year)
                ->first();

            return [
                'camper_id' => $camper->id,
                'camper_name' => $camper->first_name . ' ' . $camper->last_name,
                'signed' => (bool) $signature,
                'signed_at' => $signature?->signed_at,
                'signature_name' => $signature?->typed_name,
                'information_snapshot_id' => $informationSnapshot?->id,
                'medical_snapshot_id' => $medicalSnapshot?->id,
            ];
        });

        $requiresAction = false;
        if ($parentAgreement && !$parentSignature) {
            $requiresAction = true;
        }
        if ($camperAgreement) {
            foreach ($camperStatuses as $status) {
                if (!$status['signed']) {
                    $requiresAction = true;
                    break;
                }
            }
        }

        return response()->json([
            'year' => $year,
            'parent_agreement' => [
                'agreement_id' => $parentAgreement?->id,
                'title' => $parentAgreement?->title,
                'content' => $parentAgreement?->content,
                'signed' => (bool) $parentSignature,
                'signed_at' => $parentSignature?->signed_at,
                'signature_name' => $parentSignature?->typed_name,
            ],
            'camper_agreement' => [
                'agreement_id' => $camperAgreement?->id,
                'title' => $camperAgreement?->title,
                'content' => $camperAgreement?->content,
            ],
            'campers' => $camperStatuses,
            'requires_action' => $requiresAction,
        ]);
    }

    /**
     * Submit annual confirmation, creating signatures and snapshots.
     */
    public function submit(Request $request): JsonResponse
    {
        $user = Auth::user();
        $year = (int) ($request->input('year') ?? now()->year);

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:3000',
            'parent_signature_name' => 'required|string|max:255',
            'parent_signature_agreed' => 'required|boolean|in:1,true',
            'campers' => 'required|array|min:1',
            'campers.*.camper_id' => 'required|integer|exists:campers,id',
            'campers.*.signature_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $parentAgreement = $this->getActiveAgreement('parent_guardian', $year);
        $camperAgreement = $this->getActiveAgreement('camper', $year);

        if (!$parentAgreement || !$camperAgreement) {
            return response()->json(['error' => 'Agreements for the selected year are not configured.'], 400);
        }

        $family = $user->defaultFamily();
        $camperPayload = collect($request->input('campers'));

        // Validate camper access
        $camperIds = $camperPayload->pluck('camper_id')->unique();
        $authorizedCamperIds = $family->campers()->whereIn('id', $camperIds)->pluck('id');

        if ($authorizedCamperIds->count() !== $camperIds->count()) {
            return response()->json(['error' => 'One or more campers are not associated with this family.'], 403);
        }

        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        $parentSignature = ParentAgreementSignature::updateOrCreate(
            [
                'user_id' => $user->id,
                'agreement_id' => $parentAgreement->id,
                'year' => $year,
            ],
            [
                'typed_name' => $request->input('parent_signature_name'),
                'signed_at' => now(),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]
        );

        $camperSignatures = [];
        foreach ($camperPayload as $camperItem) {
            $camperSignature = CamperAgreementSignature::updateOrCreate(
                [
                    'camper_id' => $camperItem['camper_id'],
                    'agreement_id' => $camperAgreement->id,
                    'year' => $year,
                ],
                [
                    'typed_name' => $camperItem['signature_name'],
                    'signed_at' => now(),
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]
            );
            $camperSignatures[$camperItem['camper_id']] = $camperSignature;
        }

        // Create snapshots for each camper
        foreach ($camperPayload as $camperItem) {
            $camper = $family->campers()->findOrFail($camperItem['camper_id']);
            $camperSignature = $camperSignatures[$camper->id] ?? null;

            $informationData = $this->buildCamperInformationSnapshotPayload($camper, $year);
            $medicalData = $this->buildCamperMedicalSnapshotPayload($camper, $year);

            CamperInformationSnapshot::updateOrCreate(
                [
                    'camper_id' => $camper->id,
                    'year' => $year,
                ],
                [
                    'parent_agreement_signature_id' => $parentSignature->id,
                    'data' => $informationData,
                    'form_version' => "{$year}.1",
                    'captured_at' => now(),
                    'captured_by_user_id' => $user->id,
                ]
            );

            CamperMedicalSnapshot::updateOrCreate(
                [
                    'camper_id' => $camper->id,
                    'year' => $year,
                ],
                [
                    'camper_agreement_signature_id' => $camperSignature?->id,
                    'data' => $medicalData,
                    'form_version' => "{$year}.1",
                    'captured_at' => now(),
                    'captured_by_user_id' => $user->id,
                ]
            );
        }

        return response()->json([
            'message' => 'Annual agreements confirmed successfully.',
            'year' => $year,
        ]);
    }

    /**
     * Build camper information snapshot payload.
     */
    protected function buildCamperInformationSnapshotPayload(Camper $camper, int $year): array
    {
        $family = $camper->family;

        return [
            'snapshot_version' => "{$year}.1",
            'camp_year' => $year,
            'captured_at' => now()->toIso8601String(),
            'camper_profile' => [
                'camper_id' => $camper->id,
                'first_name' => $camper->first_name,
                'last_name' => $camper->last_name,
                'date_of_birth' => optional($camper->date_of_birth)->toDateString(),
                'grade' => $camper->grade,
                'school' => $camper->school,
                't_shirt_size' => $camper->t_shirt_size,
                'phone_number' => $camper->phone_number,
                'email' => $camper->email,
            ],
            'family_profile' => [
                'family_id' => $family->id,
                'family_name' => $family->name,
                'primary_contact' => $family->owner?->name,
                'phone' => $family->phone,
                'address' => $family->address,
                'city' => $family->city,
                'state' => $family->state,
                'zip_code' => $family->zip_code,
                'emergency_contact_name' => $family->emergency_contact_name,
                'emergency_contact_phone' => $family->emergency_contact_phone,
                'emergency_contact_relationship' => $family->emergency_contact_relationship,
            ],
        ];
    }

    /**
     * Build camper medical snapshot payload.
     */
    protected function buildCamperMedicalSnapshotPayload(Camper $camper, int $year): array
    {
        return [
            'snapshot_version' => "{$year}.1",
            'camp_year' => $year,
            'captured_at' => now()->toIso8601String(),
            'medical_profile' => [
                'allergies' => $camper->allergies,
                'medical_conditions' => $camper->medical_conditions,
                'medications' => $camper->medications,
                'emergency_contact_name' => $camper->emergency_contact_name,
                'emergency_contact_phone' => $camper->emergency_contact_phone,
                'emergency_contact_relationship' => $camper->emergency_contact_relationship,
            ],
        ];
    }

    protected function getActiveAgreement(string $type, int $year): ?Agreement
    {
        return Agreement::where('type', $type)
            ->where('year', $year)
            ->where('is_active', true)
            ->orderByDesc('version')
            ->first();
    }
}

