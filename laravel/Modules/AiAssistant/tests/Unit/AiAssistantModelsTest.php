<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\AiAssistant\Database\Factories\AiActionProposalFactory;
use Modules\AiAssistant\Database\Factories\AiMessageFactory;
use Modules\AiAssistant\Database\Factories\AiThreadFactory;
use Modules\AiAssistant\Database\Factories\AiToolLogFactory;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;
use Modules\AiAssistant\Models\AiToolLog;
use Modules\AiAssistant\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

// No database writes here: factories are used unpersisted (makeOne()), mirroring
// the Signature/Vehicle module test convention for this repo's shared sqlite fixture.

it('builds an ai thread via its factory', function (): void {
    $thread = AiThreadFactory::new()->makeOne(['panel_id' => 'admin']);

    Assert::assertInstanceOf(AiThread::class, $thread);
    Assert::assertSame('admin', $thread->panel_id);
    Assert::assertSame('ai_threads', $thread->getTable());
});

it('exposes hasMany relations from ai thread to messages, proposals and tool logs', function (): void {
    $thread = new AiThread();

    Assert::assertInstanceOf(HasMany::class, $thread->messages());
    Assert::assertInstanceOf(HasMany::class, $thread->proposals());
    Assert::assertInstanceOf(HasMany::class, $thread->toolLogs());
});

it('builds an ai message linked to its thread', function (): void {
    $message = AiMessageFactory::new()->makeOne(['ai_thread_id' => 1, 'role' => 'assistant']);

    Assert::assertInstanceOf(AiMessage::class, $message);
    Assert::assertSame('assistant', $message->role);
    Assert::assertInstanceOf(BelongsTo::class, $message->thread());
});

it('builds a pending ai action proposal via its factory', function (): void {
    $proposal = AiActionProposalFactory::new()->makeOne([
        'ai_thread_id' => 1,
        'type' => 'start_time_punch',
        'status' => 'pending',
    ]);

    Assert::assertInstanceOf(AiActionProposal::class, $proposal);
    Assert::assertSame('start_time_punch', $proposal->type);
    Assert::assertSame('pending', $proposal->status);
    Assert::assertInstanceOf(BelongsTo::class, $proposal->thread());
    Assert::assertInstanceOf(BelongsTo::class, $proposal->proposedBy());
    Assert::assertInstanceOf(BelongsTo::class, $proposal->confirmedBy());
});

it('builds an ai tool log linked to a thread and optional proposal', function (): void {
    $log = AiToolLogFactory::new()->makeOne(['ai_thread_id' => 1, 'tool_name' => 'search_customers']);

    Assert::assertInstanceOf(AiToolLog::class, $log);
    Assert::assertSame('search_customers', $log->tool_name);
    Assert::assertInstanceOf(BelongsTo::class, $log->thread());
    Assert::assertInstanceOf(BelongsTo::class, $log->proposal());
});
