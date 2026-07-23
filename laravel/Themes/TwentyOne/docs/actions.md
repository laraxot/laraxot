# Azioni del Tema TwentyOne

## Introduzione
Questa documentazione descrive l'implementazione delle azioni nel tema TwentyOne utilizzando Spatie Queueable Actions. Le azioni sono utilizzate per incapsulare la logica di business in classi singole e riutilizzabili.

## Struttura delle Azioni

### Organizzazione
Le azioni sono organizzate nella cartella `app/Actions` seguendo il pattern di Spatie Queueable Actions:

```
app/
└── Actions/
    ├── Auth/
    ├── Content/
    ├── Media/
    └── Settings/
```

## Implementazione Base

### Esempio di Action
```php
use Spatie\QueueableAction\QueueableAction;

class CreatePostAction
{
    use QueueableAction;

    public function __construct(
        private readonly PostRepository $repository
    ) {}

    public function execute(PostData $data): Post
    {
        return $this->repository->create($data->toArray());
    }
}
```

## Utilizzo delle Azioni

### Esecuzione Sincrona
```php
$post = app(CreatePostAction::class)->execute(
    PostData::from([
        'title' => 'Titolo Post',
        'content' => 'Contenuto del post'
    ])
);
```

### Esecuzione Asincrona
```php
CreatePostAction::dispatch(
    PostData::from([
        'title' => 'Titolo Post',
        'content' => 'Contenuto del post'
    ])
);
```

## Data Objects per le Azioni

### Definizione
```php
use Spatie\LaravelData\Data;

class PostData extends Data
{
    public function __construct(
        public string $title,
        public string $content,
        public ?string $slug = null,
        public ?string $status = 'draft'
    ) {}
}
```

## Best Practices

### 1. Type Safety
- Utilizza sempre Data Objects per i parametri delle azioni
- Definisci tipi di ritorno espliciti
- Utilizza readonly properties quando possibile

### 2. Dependency Injection
```php
class UpdatePostAction
{
    use QueueableAction;

    public function __construct(
        private readonly PostRepository $repository,
        private readonly CacheManager $cache
    ) {}
}
```

### 3. Error Handling
```php
class DeletePostAction
{
    use QueueableAction;

    public function execute(Post $post): bool
    {
        try {
            return $this->repository->delete($post);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
```

### 4. Validation
```php
class CreateUserAction
{
    use QueueableAction;

    public function execute(UserData $data): User
    {
        $validated = $data->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        return $this->repository->create($validated);
    }
}
```

## Testing

### Unit Test Example
```php
class CreatePostActionTest extends TestCase
{
    public function test_it_creates_a_post()
    {
        $action = new CreatePostAction(
            $this->mock(PostRepository::class)
        );

        $post = $action->execute(
            PostData::from([
                'title' => 'Test Post',
                'content' => 'Test Content'
            ])
        );

        $this->assertInstanceOf(Post::class, $post);
    }
}
```

## Caching

### Implementazione Cache
```php
class GetPostAction
{
    use QueueableAction;

    public function execute(int $id): ?Post
    {
        return $this->cache->remember(
            "post.{$id}",
            now()->addHours(24),
            fn () => $this->repository->find($id)
        );
    }
}
```

## Eventi

### Dispatch Events
```php
class PublishPostAction
{
    use QueueableAction;

    public function execute(Post $post): void
    {
        $post->publish();
        
        event(new PostPublished($post));
    }
}
```

## Contribuire
Per aggiungere nuove azioni:

1. Crea il Data Object corrispondente
2. Implementa l'azione nella cartella appropriata
3. Aggiungi test unitari
4. Documenta l'utilizzo dell'azione

## Troubleshooting

### Problemi Comuni

1. **Serializzazione**
   - Assicurati che i Data Objects siano serializzabili
   - Evita di passare oggetti complessi nelle azioni

2. **Queue Connection**
   - Verifica la configurazione della coda
   - Controlla i log per errori di serializzazione

3. **Performance**
   - Monitora l'utilizzo della memoria
   - Implementa il caching quando appropriato 