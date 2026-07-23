---
title: "Componenti Table"
type: concept
tags: [table, components]
created: 2026-07-14
updated: 2026-07-14
qmd: "table-components-1-1 componenti table"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Componenti Table

## Introduzione
I componenti table forniscono una gestione efficiente e personalizzabile dei dati tabulari, con funzionalità avanzate di ordinamento, filtro e paginazione.

## Componenti Disponibili

### DataTable
```blade
<x-ui::datatable 
    :columns="[
        ['name' => 'id', 'label' => 'ID', 'sortable' => true],
        ['name' => 'name', 'label' => 'Nome', 'sortable' => true],
        ['name' => 'email', 'label' => 'Email', 'sortable' => true],
        ['name' => 'created_at', 'label' => 'Data Creazione', 'sortable' => true],
    ]"
    :data="$users"
    :per-page="10"
    :searchable="true"
    :sortable="true"
    :filterable="true"
    :exportable="true"
/>
```

### StatusBadge
```blade
<x-ui::status-badge 
    :status="$user->status"
    :options="[
        'active' => ['label' => 'Attivo', 'color' => 'success'],
        'inactive' => ['label' => 'Inattivo', 'color' => 'danger'],
        'pending' => ['label' => 'In attesa', 'color' => 'warning'],
    ]"
/>
```

### ActionButtons
```blade
<x-ui::action-buttons 
    :actions="[
        [
            'type' => 'view',
            'url' => route('users.show', $user),
            'icon' => 'eye',
            'label' => 'Visualizza'
        ],
        [
            'type' => 'edit',
            'url' => route('users.edit', $user),
            'icon' => 'pencil',
            'label' => 'Modifica'
        ],
        [
            'type' => 'delete',
            'url' => route('users.destroy', $user),
            'icon' => 'trash',
            'label' => 'Elimina',
            'confirm' => true
        ]
    ]"
/>
```

## Funzionalità

### Ordinamento
- Multi-colonna
- Direzione (asc/desc)
- Personalizzazione
- Cache risultati

### Filtri
- Testo libero
- Select multipli
- Date range
- Custom filters

### Paginazione
- Server-side
- Client-side
- Personalizzazione
- Cache pagine

## Integrazione

### Livewire
```php
use Livewire\Component;

class UserTable extends Component
{
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.user-table', compact('users'));
    }
}
```

## Best Practices

### Utilizzo
- Ottimizzazione query
- Cache risultati
- Lazy loading
- Responsive design

### Performance
- Indici database
- Query ottimizzate
- Cache paginazione
- Lazy loading colonne

## Collegamenti
- [Componenti Base](./base-components.md)
- [Componenti Form](./form-components.md)
- [Componenti Chart](./chart-components.md)
- [Componenti Layout](./layout-components.md)
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 