<?php

namespace App\Livewire\Public;

use App\Models\Setting;
use Livewire\Component;

class FloatingWidgets extends Component
{
    public bool $whatsappEnabled = false;
    public ?string $whatsappNumber = null;
    public ?string $whatsappMessage = null;
    public string $whatsappPosition = 'left';
    
    public bool $chatEnabled = true;
    public string $chatPosition = 'right';
    
    public function mount()
    {
        // WhatsApp Settings
        $this->whatsappEnabled = (bool) setting('whatsapp_widget_enabled', false);
        $this->whatsappNumber = $this->cleanPhoneNumber(setting('whatsapp_widget_number', ''));
        $this->whatsappMessage = setting('whatsapp_widget_message', 'Hi, I\'m interested in your services.');
        $this->whatsappPosition = setting('whatsapp_widget_position', 'left');
        
        // Chat Settings
        $this->chatEnabled = (bool) setting('chat_widget_enabled', true);
        $this->chatPosition = $this->whatsappEnabled ? ($this->whatsappPosition === 'left' ? 'right' : 'left') : 'right';
    }
    
    private function cleanPhoneNumber(string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }
        // Remove all non-digit characters
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);
        return $cleaned ?: null;
    }
    
    public function getWhatsappUrl(): ?string
    {
        if (!$this->whatsappEnabled || !$this->whatsappNumber) {
            return null;
        }
        
        $message = urlencode($this->whatsappMessage ?? 'Hi, I\'m interested in your services.');
        return "https://wa.me/{$this->whatsappNumber}?text={$message}";
    }
    
    public function render()
    {
        return view('livewire.public.floating-widgets');
    }
}
