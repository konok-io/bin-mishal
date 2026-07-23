<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class SocialShare extends Component
{
    public string $url;
    public string $title;
    public ?string $image;
    
    public function __construct(string $url = '', string $title = '', ?string $image = null)
    {
        $this->url = $url ?: url()->current();
        $this->title = $title;
        $this->image = $image;
    }
    
    public function getEncodedUrl(): string
    {
        return urlencode($this->url);
    }
    
    public function getEncodedTitle(): string
    {
        return urlencode($this->title);
    }
    
    public function getFacebookUrl(): string
    {
        return "https://www.facebook.com/sharer/sharer.php?u={$this->getEncodedUrl()}";
    }
    
    public function getTwitterUrl(): string
    {
        return "https://twitter.com/intent/tweet?url={$this->getEncodedUrl()}&text={$this->getEncodedTitle()}";
    }
    
    public function getLinkedInUrl(): string
    {
        return "https://www.linkedin.com/sharing/share-offsite/?url={$this->getEncodedUrl()}";
    }
    
    public function getWhatsAppUrl(): string
    {
        return "https://wa.me/?text={$this->getEncodedTitle()}%20{$this->getEncodedUrl()}";
    }
    
    public function getCopyUrl(): string
    {
        return $this->url;
    }

    public function render()
    {
        return view('components.social-share');
    }
}
