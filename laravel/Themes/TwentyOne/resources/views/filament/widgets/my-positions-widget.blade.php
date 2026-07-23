<div>
    <div class="flex gap-4 mb-4">
        <button
            wire:click="$set('status', 'Active')"
            class="px-3 py-1 rounded-full text-xs {{ $status === 'Active' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
            Active
        </button>

        <button
            wire:click="$set('status', 'Closed')"
            class="px-3 py-1 rounded-full text-xs {{ $status === 'Closed' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
            Closed
        </button>

        <button
            wire:click="$set('status', 'Open')"
            class="px-3 py-1 rounded-full text-xs {{ $status === 'Open' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
            Open orders
        </button>
    </div>

    {{ $this->table }}
</div>
