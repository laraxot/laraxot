<?php

declare(strict_types=1);

return [
    'navigation' => [
        'group' => [
            'name' => 'Notificações',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'Gestão de notificações por e-mail e seus modelos'],
=======
            'description' => 'Gestão de notificações por e-mail e seus modelos',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'Gestão de notificações por e-mail e seus modelos'],
>>>>>>> a988596b (first)
        'label' => 'Modelos de E-mail',
        'plural' => 'Modelos de E-mail',
        'singular' => 'Modelo de E-mail',
        'icon' => 'heroicon-o-envelope',
        'sort' => '1',
<<<<<<< HEAD
<<<<<<< HEAD
        'name' => 'Modelo de E-mail'],
=======
        'name' => 'Modelo de E-mail',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'name' => 'Modelo de E-mail'],
>>>>>>> a988596b (first)
    'fields' => [
        'id' => [
            'label' => 'ID',
            'helper_text' => 'Identificador único do modelo',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'mailable' => [
            'label' => 'Classe Mailable',
            'placeholder' => 'Insira o nome da classe Mailable',
            'help' => 'A classe PHP que lida com o envio de e-mails',
            'helper_text' => 'Classe PHP que gerencia o envio de e-mails',
            'description' => 'mailable',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'subject' => [
            'label' => 'Assunto',
            'placeholder' => 'Insira o assunto do e-mail',
            'help' => 'O assunto que aparecerá no e-mail',
            'helper_text' => 'Assunto do e-mail',
            'description' => 'subject',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'html_template' => [
            'label' => 'Conteúdo HTML',
            'placeholder' => 'Insira o conteúdo HTML do e-mail',
            'help' => 'O conteúdo do e-mail em formato HTML',
            'helper_text' => 'Conteúdo HTML do modelo de e-mail',
            'description' => 'html_template',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'text_template' => [
            'label' => 'Conteúdo de Texto',
            'placeholder' => 'Insira o conteúdo de texto do e-mail',
            'help' => 'Versão de texto do e-mail para clientes que não suportam HTML',
            'helper_text' => 'Versão de texto do modelo de e-mail',
            'description' => 'text_template',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'version' => [
            'label' => 'Versão',
            'help' => 'Número da versão do modelo',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'created_at' => [
            'label' => 'Criado em',
            'helper_text' => 'Data de criação do modelo',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'updated_at' => [
            'label' => 'Última Modificação',
            'helper_text' => 'Data da última modificação do modelo',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'from_email' => [
            'label' => 'E-mail do remetente',
            'helper_text' => 'Endereço de e-mail do remetente',
            'placeholder' => 'noreply@exemplo.com',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'from_name' => [
            'label' => 'Nome do remetente',
            'helper_text' => 'Nome exibido do remetente',
            'placeholder' => 'Nome da Empresa',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'variables' => [
            'label' => 'Variáveis disponíveis',
            'helper_text' => 'Lista de variáveis que podem ser usadas no modelo',
            'placeholder' => 'ex: {{name}}, {{email}}',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'is_markdown' => [
            'label' => 'Usar Markdown',
            'helper_text' => 'Indica se o modelo usa sintaxe Markdown',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'status' => [
            'label' => 'Status',
            'helper_text' => 'Status atual do modelo',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'toggleColumns' => [
            'label' => 'toggleColumns',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'reorderRecords' => [
            'label' => 'reorderRecords',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'resetFilters' => [
            'label' => 'resetFilters',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'applyFilters' => [
            'label' => 'applyFilters',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'openFilters' => [
            'label' => 'openFilters',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'layout' => [
            'label' => 'layout',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'slug' => [
            'label' => 'slug',
            'description' => 'slug',
            'helper_text' => 'slug',
            'placeholder' => 'slug',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'name' => [
            'description' => 'Nome do modelo',
            'helper_text' => 'Nome descritivo para identificar o modelo',
            'placeholder' => 'Ex: Bem-vindo, Confirmação de pedido, Redefinição de senha',
            'label' => 'Nome do Modelo',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'params' => [
            'label' => 'Parâmetros',
            'helper_text' => 'Insira os parâmetros separados por vírgula que podem ser usados no modelo',
            'placeholder' => 'name, email, date, company',
            'description' => 'Parâmetros disponíveis para o modelo de e-mail',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '']],
=======
            'tooltip' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => '']],
>>>>>>> a988596b (first)
    'filters' => [
        'search_placeholder' => 'Procurar modelos...',
        'version' => [
            'label' => 'Versão',
<<<<<<< HEAD
<<<<<<< HEAD
            'placeholder' => 'Selecionar versão']],
=======
            'placeholder' => 'Selecionar versão',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'placeholder' => 'Selecionar versão']],
>>>>>>> a988596b (first)
    'actions' => [
        'create' => [
            'label' => 'Novo Modelo',
            'modal' => [
                'heading' => 'Criar Modelo de E-mail',
                'description' => 'Insira os detalhes para o novo modelo de e-mail',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Criar']],
=======
                'submit' => 'Criar',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Criar']],
>>>>>>> a988596b (first)
        'edit' => [
            'label' => 'Editar',
            'modal' => [
                'heading' => 'Editar Modelo de E-mail',
                'description' => 'Modificar os detalhes do modelo de e-mail',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Salvar']],
=======
                'submit' => 'Salvar',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Salvar']],
>>>>>>> a988596b (first)
        'delete' => [
            'label' => 'Excluir',
            'modal' => [
                'heading' => 'Excluir Modelo de E-mail',
                'description' => 'Tem certeza de que deseja excluir este modelo? Esta ação não pode ser desfeita.',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Excluir']],
        'restore' => [
            'label' => 'Restaurar'],
=======
                'submit' => 'Excluir',
            ],
        ],
        'restore' => [
            'label' => 'Restaurar',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Excluir']],
        'restore' => [
            'label' => 'Restaurar'],
>>>>>>> a988596b (first)
        'force_delete' => [
            'label' => 'Excluir Permanentemente',
            'modal' => [
                'heading' => 'Excluir Permanentemente Modelo de E-mail',
                'description' => 'Tem certeza de que deseja excluir permanentemente este modelo? Esta ação não pode ser desfeita.',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Excluir Permanentemente']],
=======
                'submit' => 'Excluir Permanentemente',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Excluir Permanentemente']],
>>>>>>> a988596b (first)
        'new_version' => [
            'label' => 'Nova Versão',
            'modal' => [
                'heading' => 'Criar Nova Versão',
                'description' => 'Criar uma nova versão do modelo de e-mail',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Criar Versão']],
=======
                'submit' => 'Criar Versão',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Criar Versão']],
>>>>>>> a988596b (first)
        'preview' => [
            'label' => 'Pré-visualizar',
            'tooltip' => 'Visualizar prévia do e-mail',
            'success_message' => 'Pré-visualização gerada com sucesso',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Erro ao gerar pré-visualização'],
=======
            'error_message' => 'Erro ao gerar pré-visualização',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Erro ao gerar pré-visualização'],
>>>>>>> a988596b (first)
        'test' => [
            'label' => 'Enviar teste',
            'tooltip' => 'Enviar um e-mail de teste',
            'success_message' => 'E-mail de teste enviado com sucesso',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Erro ao enviar e-mail de teste'],
=======
            'error_message' => 'Erro ao enviar e-mail de teste',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Erro ao enviar e-mail de teste'],
>>>>>>> a988596b (first)
        'duplicate' => [
            'label' => 'Duplicar',
            'tooltip' => 'Criar uma cópia do modelo',
            'success_message' => 'Modelo duplicado com sucesso',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Erro ao duplicar modelo'],
=======
            'error_message' => 'Erro ao duplicar modelo',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Erro ao duplicar modelo'],
>>>>>>> a988596b (first)
        'export' => [
            'label' => 'Exportar',
            'tooltip' => 'Exportar modelo em formato JSON',
            'success_message' => 'Modelo exportado com sucesso',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Erro ao exportar modelo'],
=======
            'error_message' => 'Erro ao exportar modelo',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Erro ao exportar modelo'],
>>>>>>> a988596b (first)
        'import' => [
            'label' => 'Importar',
            'tooltip' => 'Importar modelo de um arquivo JSON',
            'success_message' => 'Modelo importado com sucesso',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Erro ao importar modelo']],
=======
            'error_message' => 'Erro ao importar modelo',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Erro ao importar modelo']],
>>>>>>> a988596b (first)
    'messages' => [
        'created' => 'Modelo de e-mail criado com sucesso.',
        'updated' => 'Modelo de e-mail atualizado com sucesso.',
        'deleted' => 'Modelo de e-mail excluído com sucesso.',
        'restored' => 'Modelo de e-mail restaurado com sucesso.',
        'force_deleted' => 'Modelo de e-mail excluído permanentemente.',
        'version_created' => 'Nova versão do modelo criada com sucesso.',
        'success' => 'Operação concluída com sucesso',
        'error' => 'Ocorreu um erro durante a operação',
        'confirmation' => 'Tem certeza de que deseja prosseguir com esta operação?',
        'template_created' => 'O modelo de e-mail foi criado com sucesso',
        'template_updated' => 'O modelo de e-mail foi atualizado com sucesso',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'template_deleted' => 'O modelo de e-mail foi excluído com sucesso'],
    'sections' => [
        'template' => [
            'label' => 'Modelo',
            'description' => 'Informações principais do modelo'],
        'versions' => [
            'label' => 'Versões',
            'description' => 'Histórico de versões do modelo'],
        'logs' => [
            'label' => 'Registros',
            'description' => 'Histórico de envio do modelo'],
<<<<<<< HEAD
=======
        'template_deleted' => 'O modelo de e-mail foi excluído com sucesso',
    ],
    'sections' => [
        'template' => [
            'label' => 'Modelo',
            'description' => 'Informações principais do modelo',
        ],
        'versions' => [
            'label' => 'Versões',
            'description' => 'Histórico de versões do modelo',
        ],
        'logs' => [
            'label' => 'Registros',
            'description' => 'Histórico de envio do modelo',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'main' => 'Informações Principais',
        'content' => 'Conteúdo',
        'styling' => 'Estilo',
        'settings' => 'Configurações',
<<<<<<< HEAD
<<<<<<< HEAD
        'variables' => 'Variáveis'],
=======
        'variables' => 'Variáveis',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'variables' => 'Variáveis'],
>>>>>>> a988596b (first)
    'status' => [
        'sent' => 'Enviado',
        'delivered' => 'Entregue',
        'failed' => 'Falhou',
        'opened' => 'Aberto',
        'clicked' => 'Clicado',
        'bounced' => 'Devolvido',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'spam' => 'Marcado como spam'],
    'model' => [
        'label' => 'modelo de e-mail'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
        'spam' => 'Marcado como spam',
    ],
    'model' => [
        'label' => 'modelo de e-mail',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
