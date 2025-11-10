<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnnualComplianceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AnnualFormController extends Controller
{
    public function __construct(protected AnnualComplianceService $compliance)
    {
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'camper_ids' => ['required', 'array', 'min:1'],
            'camper_ids.*' => [
                'integer',
                Rule::exists('campers', 'id')->where(function ($query) use ($user) {
                    $familyIds = $user->families()->pluck('families.id');
                    $query->whereIn('family_id', $familyIds);
                }),
            ],
        ]);

        $camperIds = Arr::get($validated, 'camper_ids', []);
        $year = (int) (Arr::get($validated, 'year') ?? $this->compliance->currentYear());

        return response()->json([
            'year' => $year,
            'campers' => $this->compliance->getCamperForms($user, $camperIds, $year),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'campers' => ['required', 'array', 'min:1'],
            'campers.*.camper_id' => [
                'required',
                Rule::exists('campers', 'id')->where(function ($query) use ($user) {
                    $familyIds = $user->families()->pluck('families.id');
                    $query->whereIn('family_id', $familyIds);
                }),
            ],
            'campers.*.information' => ['required', 'array'],
            'campers.*.medical' => ['required', 'array'],
        ]);

        $year = (int) (Arr::get($validated, 'year') ?? $this->compliance->currentYear());

        $result = $this->compliance->storeCamperForms(
            $user,
            Arr::get($validated, 'campers', []),
            $request->ip(),
            (string) $request->userAgent(),
            $year
        );

        return response()->json([
            'year' => $year,
            'campers' => $result,
        ], 201);
    }
}

