<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Fixtures;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Notify\Filament\Forms\Components\ContactSection;

final class ContactSectionTestProxy extends ContactSection
{
<<<<<<< HEAD
<<<<<<< HEAD
    /** @return array<int|string, TextInput> */
=======
    /** @return array<int|string, mixed> */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    /** @return array<int|string, mixed> */
>>>>>>> a988596b (first)
    public function exposedFormSchema(): array
    {
        return $this->getFormSchema();
    }
}
