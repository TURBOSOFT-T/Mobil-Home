<div class="header-notifications-list">
    @forelse ($notifications as $notification)
        <div class="dropdown-item">
            <div class="d-flex align-items-center">
                <div class="notify bg-light-primary text-primary">
                    @if ($notification->type == 'commande')
                        <i class="ri-shopping-bag-line text-primary-color"></i>
                    @else
                        <i class="bx bx-group text-primary-color"></i>
                    @endif
                </div>
                <div class="flex-grow-1" onclick="url('{{ $notification->url }}')">
                    <h6 class="msg-name">
                        {{ $notification->titre }}
                        <span class="msg-time float-end">
                            {{ $notification->created_at }}
                        </span>
                    </h6>
                    <p class="msg-info">
                        {{ $notification->message }}
                    </p>
                </div>
                <div>
                    <i class="ri-close-fill" wire:click="delete( {{ $notification->id }})"></i>
                </div>
            </div>
        </div>
    @empty
        <a class="dropdown-item d-flex w-100 py-3 text-muted text-center fw-bold border-bottom border-gray-200">
            Aucune notification en ce moment !
        </a>
    @endforelse
</div>
