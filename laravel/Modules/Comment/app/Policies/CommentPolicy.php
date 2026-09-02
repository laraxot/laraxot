<?php

declare(strict_types=1);

namespace Modules\Comment\Policies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Contracts\CanComment;

/**
 * Policy motore commenti Laraxot — {@see CanComment} owner User, non Spatie\Comments\…\CanComment.
 */
class CommentPolicy
{
    public function create(?CanComment $user): bool
    {
        if (CommentConfigData::make()->allowAnonymous) {
            return true;
        }

        return $user !== null;
    }

    public function update(?CanComment $user, Model $comment): bool
    {
        if ($user !== null && $this->isApprovingUser($user, $comment)) {
            return true;
        }

        return $this->commentMadeBy($user, $comment);
    }

    public function delete(?CanComment $user, Model $comment): bool
    {
        if ($user !== null && $this->isApprovingUser($user, $comment)) {
            return true;
        }

        return $this->commentMadeBy($user, $comment);
    }

    public function react(CanComment $user, Model $commentableModel): bool
    {
        return $this->create($user) && $commentableModel->exists();
    }

    public function see(?CanComment $user, Model $comment): bool
    {
        if (method_exists($comment, 'isApproved') && $comment->isApproved()) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        if ($this->commentMadeBy($user, $comment)) {
            return true;
        }

        return $this->isApprovingUser($user, $comment);
    }

    public function approve(CanComment $user, Model $comment): bool
    {
        return $this->isApprovingUser($user, $comment);
    }

    public function reject(CanComment $user, Model $comment): bool
    {
        return $this->isApprovingUser($user, $comment);
    }

    private function commentMadeBy(?CanComment $user, Model $comment): bool
    {
        if ($user === null) {
            return false;
        }

        $commentator = $comment->getRelationValue('commentator');

        if (! is_object($commentator)) {
            return false;
        }

        if (! method_exists($commentator, 'getMorphClass') || ! method_exists($commentator, 'getKey')) {
            return false;
        }

        return $user->getMorphClass() === $commentator->getMorphClass()
            && $user->getKey() === $commentator->getKey();
    }

    private function isApprovingUser(CanComment $user, Model $comment): bool
    {
        return $this->approvingUsers($comment)->contains(
            static fn (CanComment $approver): bool => $approver->getMorphClass() === $user->getMorphClass()
                && $approver->getKey() === $user->getKey(),
        );
    }

    /** @return Collection<int, CanComment> */
    /** @phpstan-return Collection<int, CanComment> */
    private function approvingUsers(Model $comment): Collection
    {
        if (! method_exists($comment, 'getApprovingUsers')) {
            return new Collection;
        }

        $users = $comment->getApprovingUsers();
        if (! $users instanceof Collection) {
            if (! is_iterable($users)) {
                return new Collection;
            }

            $users = new Collection(is_array($users) ? $users : iterator_to_array($users));
        }

        $approvers = [];
        foreach ($users as $approver) {
            if ($approver instanceof CanComment) {
                $approvers[] = $approver;
            }
        }

        return new Collection($approvers);
    }
}
