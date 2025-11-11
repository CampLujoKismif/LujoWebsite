<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $campInstance->full_display_name ?? $campInstance->display_name }} • Camper Registration Report</title>
    <style>
        @page {
            margin: 32px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            background-color: #f8fafc;
            margin: 0;
            font-size: 11px;
            line-height: 1.5;
        }

        .header {
            padding: 0 0 16px;
            border-bottom: 3px solid #2563eb;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #111827;
        }

        .header p {
            margin: 4px 0 0;
            color: #4b5563;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-blue {
            background-color: #e0f2fe;
            color: #1d4ed8;
        }

        .badge-green {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-yellow {
            background-color: #fef9c3;
            color: #92400e;
        }

        .badge-orange {
            background-color: #ffedd5;
            color: #9a3412;
        }

        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .summary-card {
            flex: 1 1 140px;
            background: #ffffff;
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #dbeafe;
        }

        .summary-card h3 {
            margin: 0;
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .summary-card p {
            margin: 8px 0 0;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .camper-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
        }

        .camper-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 10px;
        }

        .camper-title {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .meta {
            color: #64748b;
            font-size: 10px;
        }

        .camper-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .header-left {
            flex: 1 1 auto;
        }

        .header-right {
            text-align: right;
            min-width: 180px;
        }

        .header-right .status-badge {
            display: inline-block;
            margin-bottom: 6px;
        }

        .header-right .contact-summary {
            font-size: 10px;
            color: #334155;
            line-height: 1.4;
        }

        .header-right .contact-summary strong {
            display: block;
            font-size: 11px;
            color: #1e293b;
        }

        .header-right .contact-summary span {
            display: block;
            margin-top: 2px;
        }

        .meta-secondary {
            color: #475569;
            font-size: 9px;
            margin-top: 2px;
        }

        .camper-details {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 0;
        }

        .camper-details td {
            width: 33.333%;
            vertical-align: top;
            padding: 0 6px;
        }

        .camper-details td:first-child {
            padding-left: 0;
        }

        .camper-details td:last-child {
            padding-right: 0;
        }

        .field {
            margin-bottom: 6px;
        }

        .field-label {
            font-size: 9px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 2px;
        }

        .field-value {
            font-size: 11px;
            font-weight: 500;
            color: #0f172a;
        }

        .notes {
            margin-top: 10px;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 12px;
            color: #1f2937;
            font-size: 11px;
        }

        .med-alert {
            display: inline-block;
            margin-top: 4px;
            padding: 5px 9px;
            border-radius: 9px;
            font-size: 9px;
            font-weight: 700;
            background: #fef3c7;
            color: #b45309;
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $camp->display_name }} — {{ $campInstance->name ?? $campInstance->year }}</h1>
        <p>
            {{ $campInstance->start_date?->format('M j, Y') ?? 'TBD' }}
            @if($campInstance->end_date)
                – {{ $campInstance->end_date->format('M j, Y') }}
            @endif
            &nbsp;•&nbsp;
            Generated {{ $generatedAt->timezone(config('app.timezone'))->format('M j, Y g:i A') }}
        </p>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <h3>Total Enrollments</h3>
            <p>{{ $summary['total'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Confirmed</h3>
            <p>{{ $summary['confirmed'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Pending</h3>
            <p>{{ $summary['pending'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Waitlisted</h3>
            <p>{{ $summary['waitlisted'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Cancelled</h3>
            <p>{{ $summary['cancelled'] }}</p>
        </div>
    </div>

    @foreach($enrollments as $enrollment)
        @php
            $camper = $enrollment->camper;
            $family = $camper?->family;
            $snapshot = $enrollment->informationSnapshot;
            $snapshotData = $snapshot?->data ?? [];
            $grade = $camper?->gradeForYear($campInstance->year) ?? data_get($snapshotData, 'camper.grade');
            $shirtSize = $camper?->tShirtSizeForYear($campInstance->year) ?? data_get($snapshotData, 'camper.t_shirt_size');
            $status = ucfirst(str_replace('_', ' ', $enrollment->status ?? 'unknown'));
            $statusBadge = match ($enrollment->status) {
                'confirmed' => 'badge badge-green',
                'pending' => 'badge badge-yellow',
                'waitlisted' => 'badge badge-orange',
                'cancelled' => 'badge badge-red',
                default => 'badge badge-blue',
            };
            $stringify = function ($value) {
                if (is_array($value)) {
                    return collect($value)
                        ->map(fn ($entry) => trim((string) $entry))
                        ->filter()
                        ->implode(', ');
                }

                if ($value instanceof \Illuminate\Support\Collection) {
                    return $value->map(fn ($entry) => trim((string) $entry))
                        ->filter()
                        ->implode(', ');
                }

                return trim((string) $value);
            };

            $medicalSnapshot = $enrollment->medicalSnapshot?->data ?? [];
            $medicalData = data_get($medicalSnapshot, 'medical', []);
            $insuranceData = data_get($medicalSnapshot, 'insurance', []);
            $emergencyData = data_get($medicalSnapshot, 'emergency_contact', []);
            $otcPermissions = collect(data_get($medicalSnapshot, 'otc_permissions', []))
                ->filter(fn ($allowed) => (bool) $allowed)
                ->keys()
                ->map(fn ($key) => \Illuminate\Support\Str::headline(str_replace('_', ' ', $key)))
                ->implode(', ');
            $allergiesList = collect(\Illuminate\Support\Arr::wrap(data_get($medicalData, 'allergies', [])))
                ->map(fn ($entry) => trim($entry))
                ->filter();
            $conditionsList = collect(\Illuminate\Support\Arr::wrap(data_get($medicalData, 'conditions', [])))
                ->map(fn ($entry) => trim($entry))
                ->filter();
            $medicationsList = collect(\Illuminate\Support\Arr::wrap(data_get($medicalData, 'medications', [])))
                ->map(fn ($entry) => trim($entry))
                ->filter();
            $lifeHealthIssues = $stringify(data_get($medicalData, 'life_health_issues', ''));
            $medicalNotes = $stringify(data_get($medicalData, 'notes', ''));
            $lifeChanges = $stringify(data_get($medicalData, 'life_changes', ''));
            $medicationsPrescribed = $stringify(data_get($medicalData, 'medications_prescribed', ''));
            $otcMedications = $stringify(data_get($medicalData, 'otc_medications', ''));
            $physicianName = $stringify(data_get($medicalData, 'physician_name', ''));
            $physicianPhone = $stringify(data_get($medicalData, 'physician_phone', ''));
            $tetanusDate = $stringify(data_get($medicalData, 'tetanus_date', ''));
            $insuranceCompany = $stringify(data_get($insuranceData, 'company', ''));
            $insurancePolicy = $stringify(data_get($insuranceData, 'policy_number', ''));
            $insuranceGroup = $stringify(data_get($insuranceData, 'group_number', ''));
            $insurancePhone = $stringify(data_get($insuranceData, 'phone', ''));
            $insuredName = $stringify(data_get($insuranceData, 'insured_name', ''));
            $emergencyName = $stringify(data_get($emergencyData, 'name', ''));
            $emergencyRelationship = $stringify(data_get($emergencyData, 'relationship', ''));
            $emergencyPhone = $stringify(data_get($emergencyData, 'phone', ''));
            $emergencyAltPhone = $stringify(data_get($emergencyData, 'all_hours_phone', ''));
            $emergencyAddress = collect([
                trim((string) data_get($emergencyData, 'address', '')),
                trim((string) data_get($emergencyData, 'city', '')),
                trim((string) data_get($emergencyData, 'state', '')),
                trim((string) data_get($emergencyData, 'zip', '')),
            ])->filter()->implode(', ');
            $medicalAlerts = collect([
                $allergiesList->isNotEmpty() ? 'Allergies: ' . $allergiesList->implode(', ') : null,
                $conditionsList->isNotEmpty() ? 'Conditions: ' . $conditionsList->implode(', ') : null,
                $medicationsList->isNotEmpty() ? 'Medications: ' . $medicationsList->implode(', ') : null,
            ])->filter()->implode("\n");
            $contacts = $family?->contacts ?? collect();
            $primaryContact = $contacts instanceof \Illuminate\Support\Collection
                ? ($contacts->sortByDesc(fn ($contact) => (bool) $contact->is_primary)->first() ?? $contacts->first())
                : null;
            $primaryName = $emergencyName
                ?: $primaryContact?->name
                ?: $family?->emergency_contact_name
                ?: $family?->owner?->name;
            $primaryPhone = $emergencyPhone
                ?: $primaryContact?->cell_phone
                ?? $primaryContact?->home_phone
                ?? $primaryContact?->work_phone
                ?? $family?->emergency_contact_phone
                ?? $family?->phone;
            $primaryEmail = $primaryContact?->email ?? $family?->owner?->email ?? $camper?->email;
            $enrollmentDate = $enrollment->created_at
                ? $enrollment->created_at->timezone(config('app.timezone'))->format('M j, Y g:i A')
                : null;
            $formsStatus = $enrollment->forms_complete ? 'Forms complete' : 'Forms pending';
            $paymentSummary = 'Paid $' . number_format($enrollment->amount_paid ?? 0, 2) . ' / Bal $' . number_format($enrollment->balance ?? 0, 2);
            $discountSummary = $enrollment->discountCode
                ? $enrollment->discountCode->code . ' (‑$' . number_format($enrollment->discount_amount ?? 0, 2) . ')'
                : null;
            $familyOwnerName = $family?->owner?->name;
            $familySummary = collect([
                $family?->name,
                $familyOwnerName ? 'Owner ' . $familyOwnerName : null,
            ])->filter()->implode(' • ');
            $registrationSummary = collect([
                $enrollmentDate ? 'Registered ' . $enrollmentDate : null,
                $formsStatus,
                $paymentSummary,
                $discountSummary ? 'Discount ' . $discountSummary : null,
            ])->filter()->implode(' • ');
            $hasPrimaryContact = !empty($primaryName) || !empty($primaryPhone) || !empty($primaryEmail);
            $primaryPhoneDisplay = $primaryPhone ?: ($primaryEmail ? 'No phone on file' : null);
        @endphp

        <div class="camper-card">
            <div class="camper-header">
                <div class="header-left">
                    <p class="camper-title">{{ $camper->full_name ?? 'Unknown Camper' }}</p>
                    <p class="meta">
                        Age {{ $camper?->age ?? '—' }} 
                        @if($grade) • Grade {{ $grade }} @endif
                        @if($shirtSize) • Shirt {{ $shirtSize }} @endif
                    </p>
                    @if($familySummary)
                        <p class="meta meta-secondary">{{ $familySummary }}</p>
                    @endif
                    @if($registrationSummary)
                        <p class="meta meta-secondary">{{ $registrationSummary }}</p>
                    @endif
                </div>
                <div class="header-right">
                    <span class="status-badge {{ $statusBadge }}">{{ $status }}</span>
                    @if($hasPrimaryContact)
                        <div class="contact-summary">
                            <strong>{{ $primaryName ?: 'Primary Contact' }}</strong>
                            @if($primaryPhoneDisplay)
                                <span>{{ $primaryPhoneDisplay }}</span>
                            @endif
                            @if($primaryEmail)
                                <span>{{ $primaryEmail }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <table class="camper-details">
                <tr>
                    <td>
                        <div class="field">
                            <div class="field-label">Family</div>
                            <div class="field-value">{{ $family?->name ?? '—' }}</div>
                        </div>
                        @if($family?->owner)
                            <div class="field">
                                <div class="field-label">Family Owner</div>
                                <div class="field-value">{{ $family->owner->name }}</div>
                            </div>
                            @if($family->owner->email)
                                <div class="field">
                                    <div class="field-label">Owner Email</div>
                                    <div class="field-value">{{ $family->owner->email }}</div>
                                </div>
                            @endif
                        @endif
                        @if($family?->phone)
                            <div class="field">
                                <div class="field-label">Family Phone</div>
                                <div class="field-value">{{ $family->phone }}</div>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="field">
                            <div class="field-label">Enrollment Date</div>
                            <div class="field-value">
                                {{ $enrollmentDate ?? '—' }}
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-label">Forms Complete</div>
                            <div class="field-value">{{ $enrollment->forms_complete ? 'Yes' : 'No' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Payments</div>
                            <div class="field-value">
                                Paid ${{ number_format($enrollment->amount_paid ?? 0, 2) }} / Balance ${{ number_format($enrollment->balance ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-label">Discount</div>
                            <div class="field-value">
                                @if($enrollment->discountCode)
                                    {{ $enrollment->discountCode->code }} (‑${{ number_format($enrollment->discount_amount ?? 0, 2) }})
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="field">
                            <div class="field-label">Medical Alerts</div>
                            <div class="field-value">
                                @if($medicalAlerts)
                                    <span class="med-alert">{!! nl2br(e($medicalAlerts)) !!}</span>
                                @else
                                    None reported
                                @endif
                            </div>
                        </div>
                        @if($allergiesList->isNotEmpty())
                            <div class="field">
                                <div class="field-label">Allergies</div>
                                <div class="field-value">{{ $allergiesList->implode(', ') }}</div>
                            </div>
                        @endif
                        @if($conditionsList->isNotEmpty())
                            <div class="field">
                                <div class="field-label">Chronic Conditions</div>
                                <div class="field-value">{{ $conditionsList->implode(', ') }}</div>
                            </div>
                        @endif
                        @if($medicationsList->isNotEmpty())
                            <div class="field">
                                <div class="field-label">Medications</div>
                                <div class="field-value">{{ $medicationsList->implode(', ') }}</div>
                            </div>
                        @endif
                        @if($medicationsPrescribed)
                            <div class="field">
                                <div class="field-label">Prescribed Treatments</div>
                                <div class="field-value">{{ $medicationsPrescribed }}</div>
                            </div>
                        @endif
                        @if($otcMedications)
                            <div class="field">
                                <div class="field-label">OTC Medications</div>
                                <div class="field-value">{{ $otcMedications }}</div>
                            </div>
                        @endif
                        @if($lifeHealthIssues)
                            <div class="field">
                                <div class="field-label">Life &amp; Health Issues</div>
                                <div class="field-value">{{ $lifeHealthIssues }}</div>
                            </div>
                        @endif
                        @if($lifeChanges)
                            <div class="field">
                                <div class="field-label">Recent Life Changes</div>
                                <div class="field-value">{{ $lifeChanges }}</div>
                            </div>
                        @endif
                        @if($physicianName || $physicianPhone)
                            <div class="field">
                                <div class="field-label">Physician</div>
                                <div class="field-value">
                                    {{ $physicianName ?: '—' }}
                                    @if($physicianPhone)
                                        • {{ $physicianPhone }}
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if($tetanusDate)
                            <div class="field">
                                <div class="field-label">Tetanus Date</div>
                                <div class="field-value">{{ $tetanusDate }}</div>
                            </div>
                        @endif
                        @if($insuranceCompany || $insurancePolicy || $insuranceGroup || $insurancePhone || $insuredName)
                            <div class="field">
                                <div class="field-label">Insurance</div>
                                <div class="field-value">
                                    {{ $insuranceCompany ?: '—' }}
                                    @if($insurancePolicy) • Policy {{ $insurancePolicy }} @endif
                                    @if($insuranceGroup) • Group {{ $insuranceGroup }} @endif
                                    @if($insurancePhone) • {{ $insurancePhone }} @endif
                                    @if($insuredName) <br><span style="font-size: 10px;">Insured: {{ $insuredName }}</span> @endif
                                </div>
                            </div>
                        @endif
                        @if($otcPermissions)
                            <div class="field">
                                <div class="field-label">OTC Permissions</div>
                                <div class="field-value">{{ $otcPermissions }}</div>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>

            @if($emergencyName || $emergencyRelationship || $emergencyPhone || $emergencyAltPhone || $emergencyAddress)
                <div class="field" style="margin-top: 8px;">
                    <div class="field-label">Emergency Contact</div>
                    <div class="field-value">
                        <div><strong>{{ $emergencyName ?: '—' }}</strong></div>
                        <div>
                            @if($emergencyRelationship)
                                <span>{{ $emergencyRelationship }}</span>
                            @endif
                            @if($emergencyPhone)
                                <span> • {{ $emergencyPhone }}</span>
                            @endif
                            @if($emergencyAltPhone)
                                <span> • Alt {{ $emergencyAltPhone }}</span>
                            @endif
                        </div>
                        @if($emergencyAddress)
                            <div>{{ $emergencyAddress }}</div>
                        @endif
                    </div>
                </div>
            @endif

            @if($medicalNotes || $enrollment->notes)
                <div class="notes">
                    @if($medicalNotes)
                        <div class="field-label" style="margin-bottom: 4px;">Medical Notes</div>
                        <div class="field-value">{{ $medicalNotes }}</div>
                    @endif
                    @if($enrollment->notes)
                        <div class="field-label" style="margin: 8px 0 4px;">Staff Notes</div>
                        <div class="field-value">{{ $enrollment->notes }}</div>
                    @endif
                </div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        Prepared for camp leadership • {{ $camp->display_name }}
    </div>
</body>
</html>

