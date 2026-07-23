<?php

namespace Themes\Sixteen\Http\Livewire\Appointment;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Themes\Sixteen\Models\Appointment;
use Themes\Sixteen\Models\Citizen;
use Themes\Sixteen\Models\Office;
use Themes\Sixteen\Models\Service;
use Themes\Sixteen\Rules\AppointmentAvailability;

/**
 * Componente Livewire per creazione appuntamento multi-step
 * Conforme alle specifiche AGID per prenotazioni servizi comunali
 */
class CreateAppointment extends Component
{
    use WithPagination;

    // Step tracking
    public $currentStep = 1;

    public $totalSteps = 6;

    // Step 1: Selezione servizio
    public $serviceId;

    public $officeId;

    public $purpose;

    // Step 2: Selezione data
    public $appointmentDate;

    public $availableSlots = [];

    // Step 3: Selezione orario
    public $selectedSlot;

    // Step 4: Dati richiedente
    public $isSelf = true;

    public $citizenId;

    public $citizenData = [];

    // Step 5: Dettagli aggiuntivi
    public $notes;

    public $requiredDocuments = [];

    public $emergencyContact;

    // Step 6: Riepilogo
    public $confirmationCode;

    // Data and services
    public $services = [];

    public $offices = [];

    public $availableDates = [];

    protected $queryString = ['currentStep'];

    protected $listeners = [
        'serviceSelected' => 'loadOffices',
        'officeSelected' => 'loadAvailableDates',
        'dateSelected' => 'loadAvailableSlots',
        'slotSelected' => 'proceedToStep4',
    ];

    public function mount()
    {
        $this->services = Service::where('is_active', true)
            ->where('requires_appointment', true)
            ->orderBy('name')
            ->get();

        $this->availableDocuments = [
            'carta_identita' => 'Carta d\'Identità',
            'codice_fiscale' => 'Codice Fiscale',
            'documento_riconoscimento' => 'Documento di Riconoscimento',
            'autocertificazione' => 'Autocertificazione',
            'altro' => 'Altro Documento',
        ];
    }

    public function render()
    {
        return view('livewire.appointment.create-appointment', [
            'stepTitle' => $this->getStepTitle(),
            'stepProgress' => ($this->currentStep / $this->totalSteps) * 100,
        ]);
    }

    public function getStepTitle()
    {
        return match ($this->currentStep) {
            1 => 'Selezione Servizio e Ufficio',
            2 => 'Selezione Data',
            3 => 'Selezione Orario',
            4 => 'Dati del Richiedente',
            5 => 'Dettagli Aggiuntivi',
            6 => 'Riepilogo e Conferma',
            default => 'Prenotazione Appuntamento'
        };
    }

    // Step 1: Service selection
    public function loadOffices($serviceId)
    {
        $this->serviceId = $serviceId;
        $this->offices = Office::where('service_id', $serviceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->emit('officesLoaded', $this->offices);
    }

    public function selectOffice($officeId)
    {
        $this->officeId = $officeId;
        $this->loadAvailableDates();
        $this->currentStep = 2;
    }

    // Step 2: Date selection
    public function loadAvailableDates()
    {
        $office = Office::find($this->officeId);
        $this->availableDates = $office->getAvailableDates(30); // Next 30 days
    }

    public function selectDate($date)
    {
        $this->appointmentDate = $date;
        $this->loadAvailableSlots();
        $this->currentStep = 3;
    }

    // Step 3: Time slot selection
    public function loadAvailableSlots()
    {
        $office = Office::find($this->officeId);
        $this->availableSlots = $office->getAvailableTimeSlots($this->appointmentDate);
    }

    public function selectSlot($slot)
    {
        $this->selectedSlot = $slot;
        $this->currentStep = 4;
    }

    // Step 4: Citizen data
    public function toggleSelfBooking()
    {
        $this->isSelf = ! $this->isSelf;
        if ($this->isSelf) {
            $this->citizenId = null;
            $this->citizenData = [];
        }
    }

    public function searchCitizen($fiscalCode)
    {
        $this->citizenData = Citizen::where('fiscal_code', $fiscalCode)
            ->first()?->toArray() ?? [];
    }

    public function proceedToStep5()
    {
        $this->validateStep4();
        $this->currentStep = 5;
    }

    // Step 5: Additional details
    public function toggleDocument($document)
    {
        if (in_array($document, $this->requiredDocuments)) {
            $this->requiredDocuments = array_diff($this->requiredDocuments, [$document]);
        } else {
            $this->requiredDocuments[] = $document;
        }
    }

    public function proceedToStep6()
    {
        $this->validateStep5();
        $this->currentStep = 6;
    }

    // Step 6: Confirmation
    public function confirmAppointment()
    {
        $this->validateStep6();

        DB::transaction(function () {
            $appointment = Appointment::create([
                'user_id' => Auth::id(),
                'service_id' => $this->serviceId,
                'office_id' => $this->officeId,
                'citizen_id' => $this->isSelf ? null : $this->citizenId,
                'appointment_date' => $this->appointmentDate,
                'start_time' => $this->selectedSlot['start'],
                'end_time' => $this->selectedSlot['end'],
                'purpose' => $this->purpose,
                'notes' => $this->notes,
                'required_documents' => $this->requiredDocuments,
                'status' => Appointment::STATUS_PENDING,
                'metadata' => [
                    'emergency_contact' => $this->emergencyContact,
                    'is_self_booking' => $this->isSelf,
                ],
            ]);

            $this->confirmationCode = $appointment->confirmation_code;

            // Invia notifica email
            $appointment->sendConfirmationNotification();
        });

        $this->currentStep = 7; // Success step
    }

    // Navigation
    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function restart()
    {
        $this->resetExcept('services', 'availableDocuments');
        $this->currentStep = 1;
    }

    // Validation rules
    protected function rules()
    {
        return match ($this->currentStep) {
            1 => [
                'serviceId' => 'required|exists:services,id',
                'officeId' => 'required|exists:offices,id',
                'purpose' => 'required|string|max:500',
            ],
            2 => [
                'appointmentDate' => ['required', 'date', 'after:today', new AppointmentAvailability($this->officeId)],
            ],
            3 => [
                'selectedSlot' => 'required|array',
                'selectedSlot.start' => 'required|date_format:H:i',
                'selectedSlot.end' => 'required|date_format:H:i|after:selectedSlot.start',
            ],
            4 => [
                'isSelf' => 'required|boolean',
                'citizenId' => 'required_if:isSelf,false|exists:citizens,id',
                'citizenData.fiscal_code' => 'required_if:isSelf,false|codice_fiscale',
                'citizenData.first_name' => 'required_if:isSelf,false|string|max:100',
                'citizenData.last_name' => 'required_if:isSelf,false|string|max:100',
            ],
            5 => [
                'notes' => 'nullable|string|max:1000',
                'requiredDocuments' => 'array',
                'requiredDocuments.*' => 'in:'.implode(',', array_keys($this->availableDocuments)),
                'emergencyContact' => 'nullable|string|max:200',
            ],
            6 => [
                // Additional confirmation validations
            ],
            default => []
        };
    }

    protected function validateStep4()
    {
        $this->validate([
            'isSelf' => 'required|boolean',
            'citizenId' => 'required_if:isSelf,false|exists:citizens,id',
        ]);
    }

    protected function validateStep5()
    {
        $this->validate([
            'requiredDocuments' => 'array',
            'requiredDocuments.*' => 'in:'.implode(',', array_keys($this->availableDocuments)),
        ]);
    }

    protected function validateStep6()
    {
        // Additional validation for final confirmation
        $office = Office::find($this->officeId);
        if (! $office->isSlotAvailable($this->appointmentDate, $this->selectedSlot['start'])) {
            $this->addError('selectedSlot', 'Questo slot orario non è più disponibile.');
            throw new Exception('Slot non disponibile');
            throw new Exception('Slot non disponibile');
        }
    }

    // Computed properties
    public function getServiceProperty()
    {
        return Service::find($this->serviceId);
    }

    public function getOfficeProperty()
    {
        return Office::find($this->officeId);
    }

    public function getSelectedDateFormattedProperty()
    {
        return $this->appointmentDate
            ? Carbon::parse($this->appointmentDate)->translatedFormat('l d F Y')
            : null;
    }

    public function getSelectedTimeFormattedProperty()
    {
        return $this->selectedSlot
            ? Carbon::parse($this->selectedSlot['start'])->format('H:i').' - '.
              Carbon::parse($this->selectedSlot['end'])->format('H:i')
            : null;
    }

    public function getIsLastStepProperty()
    {
        return $this->currentStep === $this->totalSteps;
    }

    public function getIsFirstStepProperty()
    {
        return $this->currentStep === 1;
    }
}
