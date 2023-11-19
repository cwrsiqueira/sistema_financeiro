@php
    $messageType = session('success') ? 'success' : (session('error') ? 'error' : null);
    $messageContent = session($messageType);
    $messageColors = ['success' => 'green', 'error' => 'red'];
    $messageIcons = ['success' => 'thumbs-up', 'error' => 'alert']; // Exemplo de ícones
@endphp

@if ($messageType)
    <div class="bg-{{ $messageColors[$messageType] }}-100 border-l-4 border-{{ $messageColors[$messageType] }}-500 text-{{ $messageColors[$messageType] }}-700 p-4 mb-3 mr-3 rounded"
        role="alert">
        <p>
            @if ($messageType == 'success')
                <i class="fa-solid fa-thumbs-up"></i>
            @elseif ($messageType == 'error')
                <i class="fa-solid fa-thumbs-down"></i>
            @endif{{ $messageContent }}
        </p>
    </div>
@endif
