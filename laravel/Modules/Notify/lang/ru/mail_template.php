<?php

declare(strict_types=1);

return [
    'navigation' => [
        'group' => [
            'name' => 'Уведомления',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'Управление email уведомлениями и их шаблонами'],
=======
            'description' => 'Управление email уведомлениями и их шаблонами',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'Управление email уведомлениями и их шаблонами'],
>>>>>>> a988596b (first)
        'label' => 'Email шаблоны',
        'plural' => 'Email шаблоны',
        'singular' => 'Email шаблон',
        'icon' => 'heroicon-o-envelope',
        'sort' => '1',
<<<<<<< HEAD
<<<<<<< HEAD
        'name' => 'Email шаблон'],
=======
        'name' => 'Email шаблон',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'name' => 'Email шаблон'],
>>>>>>> a988596b (first)
    'fields' => [
        'id' => [
            'label' => 'ID',
            'helper_text' => 'Уникальный идентификатор шаблона',
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
            'label' => 'Класс Mailable',
            'placeholder' => 'Введите имя класса Mailable',
            'help' => 'PHP класс, который обрабатывает отправку email',
            'helper_text' => 'PHP класс, управляющий отправкой email',
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
            'label' => 'Тема',
            'placeholder' => 'Введите тему письма',
            'help' => 'Тема, которая появится в письме',
            'helper_text' => 'Тема письма',
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
            'label' => 'HTML содержимое',
            'placeholder' => 'Введите HTML содержимое письма',
            'help' => 'Содержимое письма в формате HTML',
            'helper_text' => 'HTML содержимое email шаблона',
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
            'label' => 'Текстовое содержимое',
            'placeholder' => 'Введите текстовое содержимое письма',
            'help' => 'Текстовая версия письма для клиентов, не поддерживающих HTML',
            'helper_text' => 'Текстовая версия email шаблона',
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
            'label' => 'Версия',
            'help' => 'Номер версии шаблона',
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
            'label' => 'Создано',
            'helper_text' => 'Дата создания шаблона',
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
            'label' => 'Последнее изменение',
            'helper_text' => 'Дата последнего изменения шаблона',
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
            'label' => 'Email отправителя',
            'helper_text' => 'Адрес электронной почты отправителя',
            'placeholder' => 'noreply@example.com',
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
            'label' => 'Имя отправителя',
            'helper_text' => 'Отображаемое имя отправителя',
            'placeholder' => 'Название компании',
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
            'label' => 'Доступные переменные',
            'helper_text' => 'Список переменных, которые можно использовать в шаблоне',
            'placeholder' => 'напр: {{name}}, {{email}}',
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
            'label' => 'Использовать Markdown',
            'helper_text' => 'Указывает, использует ли шаблон синтаксис Markdown',
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
            'label' => 'Статус',
            'helper_text' => 'Текущий статус шаблона',
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
            'description' => 'Название шаблона',
            'helper_text' => 'Описательное имя для идентификации шаблона',
            'placeholder' => 'Напр: Добро пожаловать, Подтверждение заказа, Сброс пароля',
            'label' => 'Название шаблона',
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
            'label' => 'Параметры',
            'helper_text' => 'Введите параметры, разделенные запятыми, которые можно использовать в шаблоне',
            'placeholder' => 'name, email, date, company',
            'description' => 'Доступные параметры для email шаблона',
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
        'search_placeholder' => 'Поиск шаблонов...',
        'version' => [
            'label' => 'Версия',
<<<<<<< HEAD
<<<<<<< HEAD
            'placeholder' => 'Выбрать версию']],
=======
            'placeholder' => 'Выбрать версию',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'placeholder' => 'Выбрать версию']],
>>>>>>> a988596b (first)
    'actions' => [
        'create' => [
            'label' => 'Новый шаблон',
            'modal' => [
                'heading' => 'Создать email шаблон',
                'description' => 'Введите данные для нового email шаблона',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Создать']],
=======
                'submit' => 'Создать',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Создать']],
>>>>>>> a988596b (first)
        'edit' => [
            'label' => 'Редактировать',
            'modal' => [
                'heading' => 'Редактировать email шаблон',
                'description' => 'Изменить данные email шаблона',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Сохранить']],
=======
                'submit' => 'Сохранить',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Сохранить']],
>>>>>>> a988596b (first)
        'delete' => [
            'label' => 'Удалить',
            'modal' => [
                'heading' => 'Удалить email шаблон',
                'description' => 'Вы уверены, что хотите удалить этот шаблон? Это действие нельзя отменить.',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Удалить']],
        'restore' => [
            'label' => 'Восстановить'],
=======
                'submit' => 'Удалить',
            ],
        ],
        'restore' => [
            'label' => 'Восстановить',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Удалить']],
        'restore' => [
            'label' => 'Восстановить'],
>>>>>>> a988596b (first)
        'force_delete' => [
            'label' => 'Полное удаление',
            'modal' => [
                'heading' => 'Полное удаление email шаблона',
                'description' => 'Вы уверены, что хотите полностью удалить этот шаблон? Это действие нельзя отменить.',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Полное удаление']],
=======
                'submit' => 'Полное удаление',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Полное удаление']],
>>>>>>> a988596b (first)
        'new_version' => [
            'label' => 'Новая версия',
            'modal' => [
                'heading' => 'Создать новую версию',
                'description' => 'Создать новую версию email шаблона',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'Создать версию']],
=======
                'submit' => 'Создать версию',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'Создать версию']],
>>>>>>> a988596b (first)
        'preview' => [
            'label' => 'Предварительный просмотр',
            'tooltip' => 'Посмотреть предварительный просмотр письма',
            'success_message' => 'Предварительный просмотр успешно создан',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Ошибка при создании предварительного просмотра'],
=======
            'error_message' => 'Ошибка при создании предварительного просмотра',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Ошибка при создании предварительного просмотра'],
>>>>>>> a988596b (first)
        'test' => [
            'label' => 'Отправить тест',
            'tooltip' => 'Отправить тестовое письмо',
            'success_message' => 'Тестовое письмо успешно отправлено',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Ошибка при отправке тестового письма'],
=======
            'error_message' => 'Ошибка при отправке тестового письма',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Ошибка при отправке тестового письма'],
>>>>>>> a988596b (first)
        'duplicate' => [
            'label' => 'Дублировать',
            'tooltip' => 'Создать копию шаблона',
            'success_message' => 'Шаблон успешно дублирован',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Ошибка при дублировании шаблона'],
=======
            'error_message' => 'Ошибка при дублировании шаблона',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Ошибка при дублировании шаблона'],
>>>>>>> a988596b (first)
        'export' => [
            'label' => 'Экспорт',
            'tooltip' => 'Экспортировать шаблон в формат JSON',
            'success_message' => 'Шаблон успешно экспортирован',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Ошибка при экспорте шаблона'],
=======
            'error_message' => 'Ошибка при экспорте шаблона',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Ошибка при экспорте шаблона'],
>>>>>>> a988596b (first)
        'import' => [
            'label' => 'Импорт',
            'tooltip' => 'Импортировать шаблон из JSON файла',
            'success_message' => 'Шаблон успешно импортирован',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Ошибка при импорте шаблона']],
=======
            'error_message' => 'Ошибка при импорте шаблона',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Ошибка при импорте шаблона']],
>>>>>>> a988596b (first)
    'messages' => [
        'created' => 'Email шаблон успешно создан.',
        'updated' => 'Email шаблон успешно обновлен.',
        'deleted' => 'Email шаблон успешно удален.',
        'restored' => 'Email шаблон успешно восстановлен.',
        'force_deleted' => 'Email шаблон полностью удален.',
        'version_created' => 'Новая версия шаблона успешно создана.',
        'success' => 'Операция успешно выполнена',
        'error' => 'Произошла ошибка во время операции',
        'confirmation' => 'Вы уверены, что хотите продолжить эту операцию?',
        'template_created' => 'Email шаблон был успешно создан',
        'template_updated' => 'Email шаблон был успешно обновлен',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'template_deleted' => 'Email шаблон был успешно удален'],
    'sections' => [
        'template' => [
            'label' => 'Шаблон',
            'description' => 'Основная информация шаблона'],
        'versions' => [
            'label' => 'Версии',
            'description' => 'История версий шаблона'],
        'logs' => [
            'label' => 'Журналы',
            'description' => 'История отправки шаблона'],
<<<<<<< HEAD
=======
        'template_deleted' => 'Email шаблон был успешно удален',
    ],
    'sections' => [
        'template' => [
            'label' => 'Шаблон',
            'description' => 'Основная информация шаблона',
        ],
        'versions' => [
            'label' => 'Версии',
            'description' => 'История версий шаблона',
        ],
        'logs' => [
            'label' => 'Журналы',
            'description' => 'История отправки шаблона',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'main' => 'Основная информация',
        'content' => 'Содержимое',
        'styling' => 'Стили',
        'settings' => 'Настройки',
<<<<<<< HEAD
<<<<<<< HEAD
        'variables' => 'Переменные'],
=======
        'variables' => 'Переменные',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'variables' => 'Переменные'],
>>>>>>> a988596b (first)
    'status' => [
        'sent' => 'Отправлено',
        'delivered' => 'Доставлено',
        'failed' => 'Неудачно',
        'opened' => 'Открыто',
        'clicked' => 'Кликнуто',
        'bounced' => 'Возвращено',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'spam' => 'Помечено как спам'],
    'model' => [
        'label' => 'email шаблон'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
        'spam' => 'Помечено как спам',
    ],
    'model' => [
        'label' => 'email шаблон',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
