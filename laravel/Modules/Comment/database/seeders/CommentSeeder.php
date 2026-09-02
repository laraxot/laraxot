<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Seeders;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Modules\Comment\Database\Factories\CommentFactory;
use Modules\Comment\Models\Comment;
use Modules\User\Models\User;
use Modules\Xot\Actions\Cast\SafeIntCastAction;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea alcuni utenti se non esistono
        /** @var Factory<User> $userFactory */
        $userFactory = User::factory();
        /** @var EloquentCollection<int, User> $users */
        $users = $userFactory->count(5)->create();

        // Crea commenti principali
        /** @var CommentFactory $commentFactory */
        $commentFactory = Comment::factory();
        /** @var EloquentCollection<int, Comment> $mainComments */
        $mainComments = $commentFactory
            ->count(20)
            ->approved()
            ->create([
                'commentator_id' => static function () use ($users): int {
                    /** @var User|null $user */
                    $user = $users->random();
                    $id = $user?->getKey();

                    return is_int($id) ? $id : SafeIntCastAction::cast($id ?? 1);
                },
            ]);

        // Crea risposte ai commenti principali
        /** @var EloquentCollection<int, Comment> $firstTen */
        $firstTen = $mainComments->take(10);
        foreach ($firstTen as $mainComment) {
            /** @var CommentFactory $replyFactory */
            $replyFactory = Comment::factory();
            $replyFactory
                ->count((int) fake()->numberBetween(1, 3))
                ->approved()
                ->asReply($mainComment)
                ->create([
                    'commentator_id' => static function () use ($users): int {
                        /** @var User|null $user */
                        $user = $users->random();
                        $id = $user?->getKey();

                        return is_int($id) ? $id : SafeIntCastAction::cast($id ?? 1);
                    },
                ]);
        }

        // Crea alcuni commenti in attesa di approvazione
        /** @var CommentFactory $pendingFactory */
        $pendingFactory = Comment::factory();
        $pendingFactory
            ->count(5)
            ->pending()
            ->create([
                'commentator_id' => static function () use ($users): int {
                    /** @var User|null $user */
                    $user = $users->random();
                    $id = $user?->getKey();

                    return is_int($id) ? $id : SafeIntCastAction::cast($id ?? 1);
                },
            ]);

        $this->command->info('Commenti creati con successo!');
    }
}
