<?php

namespace App\Livewire;

use App\Models\ContactEntry;
use Livewire\Component;

class ContactForm extends Component
{
    public $first_name;

    public $email;

    public $message;

    public $quiz;

    protected $rules = [
        'first_name' => 'required|max:250',
        'email' => 'required|email|max:250',
        'message' => 'required',
        'quiz' => 'required',
    ];

    public $isSent = false;

    public function render()
    {
        return view('livewire.contact-form');
    }

    public function submit()
    {
        $this->validate();

        if ((string) $this->quiz !== '7') {
            $this->addError('quiz', 'The answer is not correct');

            return;
        }

        $this->isSent = true;

        ContactEntry::create([
            'first_name' => $this->first_name,
            'email' => $this->email,
            'message' => $this->message,
        ]);
    }
}
