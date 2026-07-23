<?php

namespace App\Livewire\Public;

use App\Models\Lead;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class ChatAssistant extends Component
{
    public bool $isOpen = false;
    public array $messages = [];
    public string $inputMessage = '';
    public bool $isTyping = false;

    protected $rules = [
        'inputMessage' => 'required_without:inputMessage|max:500',
    ];

    public function mount()
    {
        // Add initial greeting
        $this->messages[] = [
            'role' => 'assistant',
            'content' => 'Assalamu Alaikum! Welcome to Bin Mishal Travel & Services. How can I help you today? I can assist with Umrah packages, visa services, flight bookings, and general inquiries about Saudi Arabia.',
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (empty(trim($this->inputMessage))) {
            return;
        }

        $userMessage = trim($this->inputMessage);
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        $this->inputMessage = '';
        $this->isTyping = true;

        // Process with AI or predefined responses
        $this->processMessage($userMessage);
    }

    private function processMessage(string $message)
    {
        $this->isTyping = false;

        // Simple keyword-based responses for demo
        $response = $this->getKeywordResponse($message);

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response,
        ];
    }

    private function getKeywordResponse(string $message): string
    {
        $message = strtolower($message);

        // Umrah related
        if (str_contains($message, 'umrah')) {
            return "We offer Umrah packages starting from SAR 2,500 per person. Our packages include 3-star and 5-star hotel options near Haram, transport, and visa. Would you like to see our Umrah packages? You can visit /umrah-packages or I can help you book a consultation.";
        }

        // Visa related
        if (str_contains($message, 'visa')) {
            return "We provide various visa services including Exit/Re-entry, Final Exit, Family Visit, and Tourist visas. Our processing time is typically 7-14 days. Would you like to check your visa eligibility? I recommend using our Visa Eligibility Checker tool.";
        }

        // Flight related
        if (str_contains($message, 'flight') || str_contains($message, 'ticket')) {
            return "We book flights to Bangladesh, India, Pakistan, Nepal, and more destinations. Our partner airlines include Saudi Airlines, Biman Bangladesh, and others. Would you like to request a flight quote?";
        }

        // Pricing related
        if (str_contains($message, 'price') || str_contains($message, 'cost') || str_contains($message, 'fee')) {
            return "Our fees vary by service. For current pricing, please visit our services page or contact us directly via WhatsApp at +966 XX XXX XXXX for accurate quotes.";
        }

        // Contact related
        if (str_contains($message, 'contact') || str_contains($message, 'phone') || str_contains($message, 'whatsapp')) {
            return "You can reach us via:\n📞 Phone: +966 XX XXX XXXX\n📱 WhatsApp: +966 XX XXX XXXX\n📧 Email: info@binmishal.com\n\nWe'd be happy to assist you!";
        }

        // Greetings
        if (str_contains($message, 'hello') || str_contains($message, 'hi') || str_contains($message, 'assalam')) {
            return "Wa Alaikum Assalam! Welcome! How can I help you today?";
        }

        // Default
        return "Thank you for your question! For detailed assistance, please contact our team at +966 XX XXX XXXX or WhatsApp us. You can also:\n\n• Visit our Umrah packages: /umrah-packages\n• Request a flight quote: /flights\n• Check visa eligibility: /visa-checker\n• Book an appointment: /appointments";
    }

    public function createLead()
    {
        // Create a lead from the conversation
        if (auth()->check()) {
            return;
        }

        // Redirect to contact/lead form
        return redirect()->route('contact');
    }

    public function render()
    {
        return view('livewire.public.chat-assistant');
    }
}
