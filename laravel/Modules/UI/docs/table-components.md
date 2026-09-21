# Componenti Table

## Introduzione
I componenti table forniscono una gestione efficiente e personalizzabile dei dati tabulari, con funzionalità avanzate di ordinamento, filtro e paginazione.

## Componenti Disponibili

### DataTable
```blade
<<<<<<< HEAD
<x-ui::datatable
=======
<<<<<<< HEAD
<x-ui::datatable 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
<x-ui::datatable
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
<x-ui::datatable 
=======
<x-ui::datatable
>>>>>>> laraxot/dev
=======
<x-ui::datatable 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
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
<<<<<<< HEAD
<x-ui::status-badge
=======
<<<<<<< HEAD
<x-ui::status-badge 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
<x-ui::status-badge
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
<x-ui::status-badge 
=======
<x-ui::status-badge
>>>>>>> laraxot/dev
=======
<x-ui::status-badge 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
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
<<<<<<< HEAD
<x-ui::action-buttons
=======
<<<<<<< HEAD
<x-ui::action-buttons 
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
<x-ui::action-buttons
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
<x-ui::action-buttons 
=======
<x-ui::action-buttons
>>>>>>> laraxot/dev
=======
<x-ui::action-buttons 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
    
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======

=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
    
=======

>>>>>>> laraxot/dev
=======
    
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
    
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======

=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
    
=======

>>>>>>> laraxot/dev
=======
    
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
            
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======

=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
            
=======

>>>>>>> laraxot/dev
=======
            
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md)
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
<x-ui::status-badge
    :status="$user->status"
    :options="[
        'active' => ['label' => 'Attivo', 'color' => 'success'],
        'inactive' => ['label' => 'Inattivo', 'color' => 'danger'],
        'pending' => ['label' => 'In attesa', 'color' => 'warning'],
### ActionButtons
<x-ui::action-buttons
    :actions="[
        [
            'type' => 'view',
            'url' => route('users.show', $user),
            'icon' => 'eye',
            'label' => 'Visualizza'
        ],
            'type' => 'edit',
            'url' => route('users.edit', $user),
            'icon' => 'pencil',
            'label' => 'Modifica'
            'type' => 'delete',
            'url' => route('users.destroy', $user),
            'icon' => 'trash',
            'label' => 'Elimina',
            'confirm' => true
        ]
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
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.user-table', compact('users'));
}
## Best Practices
### Utilizzo
- Ottimizzazione query
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
<<<<<<< HEAD

```
=======
<<<<<<< HEAD

```
=======
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md)
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md)
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
