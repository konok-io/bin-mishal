<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <!-- Chat Toggle Button -->
    <button wire:click="toggleChat" class="btn btn-primary rounded-circle shadow-lg p-3" style="width: 60px; height: 60px;">
        <i class="bi bi-chat-dots fs-4"></i>
    </button>

    @if($isOpen)
    <div class="card shadow-lg rounded-3 mt-3" style="width: 350px; max-height: 500px;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <div>
                <strong><i class="bi bi-robot me-1"></i> AI Assistant</strong>
                <small class="d-block text-white-50">Bin Mishal Support</small>
            </div>
            <button wire:click="toggleChat" class="btn btn-link text-white p-0">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="card-body p-0 d-flex flex-column" style="height: 350px;">
            <!-- Messages -->
            <div class="flex-grow-1 overflow-auto p-3" style="background: #f8f9fa;">
                @foreach($messages as $msg)
                    <div class="mb-3 {{ $msg['role'] === 'user' ? 'text-end' : '' }}">
                        <div class="d-inline-block p-3 rounded-3 {{ $msg['role'] === 'user' ? 'bg-primary text-white' : 'bg-white' }}" style="max-width: 85%;">
                            <p class="mb-0" style="white-space: pre-line;">{{ $msg['content'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input -->
            <form wire:submit.prevent="sendMessage" class="p-3 border-top">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Type a message..." wire:model="inputMessage">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
