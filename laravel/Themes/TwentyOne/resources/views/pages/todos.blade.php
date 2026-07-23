<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use App\Models\Todo;

name('todos');

new class extends Component {
    public string $description = '';
    public array $todos = [];

    public function mount(): void
    {
        $this->todos = Todo::all()->toArray();
    }

    public function addTodo(): void
    {
        Todo::create(['description' => $this->description]);

        $this->description = '';
        $this->todos = Todo::all()->toArray();
    }
};

?>

<html>
    <head>
        <title>Todos</title>
    </head>
    <body>
    @volt('todos')
        <div>
            <h1>Add Todo</h1>
            <form wire:submit="addTodo">
                <input type="text" wire:model="description">
                <button type="submit">Add</button>
            </form>

            <h1>Todos</h1>
            <ul>
                @foreach ($todos as $todo)
                    <li>{{ $todo['description'] }}</li>
                @endforeach
            </ul>
        </div>
    @endvolt
    </body>
</html>
