<?php

namespace App\Livewire\Public;

use App\Models\VisaType;
use Livewire\Component;

class VisaEligibilityChecker extends Component
{
    public int $step = 1;
    public string $nationality = '';
    public string $currentVisa = '';
    public string $iqamaStatus = '';
    public bool $sponsorConsent = false;
    public bool $hasDependents = false;
    public string $purpose = '';
    public string $intendedDuration = '';
    public string $exitHistory = '';

    public array $results = [];

    protected $rules = [
        'nationality' => 'required',
        'currentVisa' => 'required',
        'iqamaStatus' => 'required',
        'sponsorConsent' => 'required|boolean',
        'hasDependents' => 'nullable|boolean',
        'purpose' => 'required',
        'intendedDuration' => 'required',
        'exitHistory' => 'required',
    ];

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate(['nationality' => 'required']);
        } elseif ($this->step === 2) {
            $this->validate(['currentVisa' => 'required', 'iqamaStatus' => 'required']);
        } elseif ($this->step === 3) {
            $this->validate(['sponsorConsent' => 'required']);
        } elseif ($this->step === 4) {
            $this->validate(['purpose' => 'required', 'intendedDuration' => 'required']);
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function checkEligibility()
    {
        $this->validate();

        // Determine eligible visa types based on answers
        $this->results = $this->determineEligibleVisas();
    }

    private function determineEligibleVisas(): array
    {
        $visas = VisaType::where('status', 'active')->get();
        $results = [];

        foreach ($visas as $visa) {
            $eligible = true;
            $warnings = [];
            $documents = json_decode($visa->required_documents, true) ?? [];

            // Check eligibility based on current visa
            if ($this->currentVisa === 'visit' && str_contains($visa->category, 'exit_reentry')) {
                $eligible = true;
            }

            // Check sponsor consent for family visit
            if ($visa->category === 'family_visit' && !$this->sponsorConsent) {
                $eligible = false;
                $warnings[] = 'Sponsor consent is required';
            }

            if ($eligible) {
                $results[] = [
                    'visa' => $visa,
                    'eligible' => true,
                    'warnings' => $warnings,
                    'documents' => $documents,
                    'processing_days' => $visa->processing_days,
                    'estimated_fee' => $visa->total_fee,
                ];
            }
        }

        return $results;
    }

    public function applyForVisa(int $visaTypeId)
    {
        // Save as lead and redirect to application
        return redirect()->route('visa.application.create', ['type' => $visaTypeId]);
    }

    public function render()
    {
        return view('livewire.public.visa-eligibility-checker', [
            'visaTypes' => VisaType::where('status', 'active')->get(),
            'nationalities' => [
                'Bangladeshi' => 'Bangladeshi',
                'Indian' => 'Indian',
                'Pakistani' => 'Pakistani',
                'Nepalese' => 'Nepalese',
                'Filipino' => 'Filipino',
                'Sri Lankan' => 'Sri Lankan',
                'Indonesian' => 'Indonesian',
            ],
            'visaOptions' => [
                'work' => 'Work Visa (Iqama)',
                'visit' => 'Visit Visa',
                'family' => 'Family Visit',
                'transit' => 'Transit Visa',
                'none' => 'No Current Visa',
            ],
            'iqamaOptions' => [
                'valid' => 'Valid Iqama',
                'expiring' => 'Expiring Soon',
                'expired' => 'Expired',
                'none' => 'No Iqama',
            ],
            'purposeOptions' => [
                'family_visit' => 'Family Visit',
                'tourism' => 'Tourism',
                'business' => 'Business',
                'umrah' => 'Umrah',
                'transit' => 'Transit',
                'medical' => 'Medical Treatment',
            ],
        ]);
    }
}
