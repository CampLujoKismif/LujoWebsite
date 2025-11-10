<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Camper;
use App\Services\AnnualComplianceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AnnualComplianceController extends Controller
{
    public function __construct(protected AnnualComplianceService $compliance)
    {
    }

    public function status(Request $request): JsonResponse
    {
        $user = $request->user();
        $year = (int) ($request->query('year') ?? $this->compliance->currentYear());

        return response()->json([
            'year' => $year,
            'parent' => $this->compliance->parentSignatureStatus($user, $year),
            'campers' => $this->compliance->camperStatuses($user, $year),
            'camper_agreement' => $this->compliance->camperAgreementDefinition($year),
        ]);
    }

    public function submit(Request $request): JsonResponse
    {
        $user = $request->user();
        $year = (int) ($request->input('year') ?? $this->compliance->currentYear());

        $payload = $request->validate([
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'parent.typed_name' => ['required', 'string', 'max:255'],
            'parent.affirmations' => ['array'],
            'parent.affirmations.*' => ['string'],
            'campers' => ['required', 'array', 'min:1'],
            'campers.*.camper_id' => [
                'required',
                Rule::exists('campers', 'id')->where(function ($query) use ($user) {
                    $familyIds = $user->families()->pluck('families.id');
                    $query->whereIn('family_id', $familyIds);
                }),
            ],
            'campers.*.typed_name' => ['required', 'string', 'max:255'],
            'campers.*.affirmations' => ['array'],
            'campers.*.affirmations.*' => ['string'],
        ]);

        $result = $this->compliance->captureAnnualForms(
            $user,
            $payload,
            $request->ip(),
            (string) $request->userAgent(),
            $year
        );

        return response()->json([
            'year' => $year,
            'parent_signature_id' => Arr::get($result, 'parent_signature_id'),
            'campers' => Arr::get($result, 'campers', []),
        ], 201);
    }
}

