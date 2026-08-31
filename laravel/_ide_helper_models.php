<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Modules\AI\Models{
/**
 * Class AiActionProposal.
 *
 * An action the AI proposes to execute on the domain, subject to human
 * confirmation before being executed. Status lifecycle:
 * pending -> confirmed -> executed
 *        \-> cancelled
 *        \-> failed
 *
 * @property int $id
 * @property string $public_id
 * @property int $ai_thread_id
 * @property string $proposed_by_user_id
 * @property string $type
 * @property array<string, mixed> $payload
 * @property string|null $preview
 * @property string $status
 * @property string|null $confirmed_by_user_id
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $executed_at
 * @property array<string, mixed>|null $result
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AiThread|null $thread
 * @method static Builder<static>|AiActionProposal newModelQuery()
 * @method static Builder<static>|AiActionProposal newQuery()
 * @method static Builder<static>|AiActionProposal query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\AI\Database\Factories\AiActionProposalFactory factory($count = null, $state = [])
 */
	class AiActionProposal extends \Eloquent {}
}

namespace Modules\AI\Models{
/**
 * Class AiMessage.
 *
 * A single message (user|assistant|tool|system) within an AiThread.
 *
 * @property-read AiThread|null $thread
 * @method static Builder<static>|AiMessage newModelQuery()
 * @method static Builder<static>|AiMessage newQuery()
 * @method static Builder<static>|AiMessage query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\AI\Database\Factories\AiMessageFactory factory($count = null, $state = [])
 */
	class AiMessage extends \Eloquent {}
}

namespace Modules\AI\Models{
/**
 * Class AiThread.
 *
 * A persisted conversation thread between a user and the AI assistant.
 *
 * @property int $id
 * @property string $public_id
 * @property string $created_by_user_id
 * @property string $panel_id
 * @property \Carbon\Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Collection<int, AiMessage> $messages
 * @property-read int|null $messages_count
 * @property-read Collection<int, AiActionProposal> $proposals
 * @property-read int|null $proposals_count
 * @property-read Collection<int, AiToolLog> $toolLogs
 * @property-read int|null $tool_logs_count
 * @method static Builder<static>|AiThread newModelQuery()
 * @method static Builder<static>|AiThread newQuery()
 * @method static Builder<static>|AiThread query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\AI\Database\Factories\AiThreadFactory factory($count = null, $state = [])
 */
	class AiThread extends \Eloquent {}
}

namespace Modules\AI\Models{
/**
 * Class AiToolLog.
 *
 * Audit trail of tool calls performed by the AI assistant.
 *
 * @property-read AiActionProposal|null $proposal
 * @property-read AiThread|null $thread
 * @method static Builder<static>|AiToolLog newModelQuery()
 * @method static Builder<static>|AiToolLog newQuery()
 * @method static Builder<static>|AiToolLog query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\AI\Database\Factories\AiToolLogFactory factory($count = null, $state = [])
 */
	class AiToolLog extends \Eloquent {}
}

namespace Modules\Activity\Models{
/**
 * Class Activity.
 *
 * This class extends the BaseActivity model to represent activities in the application.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property string|null $subject_id
 * @property string|null $causer_type
 * @property string|null $causer_id
 * @property array<string, mixed>|Collection<array-key, mixed>|null $properties
 * @property Collection<int, mixed>|null $attribute_changes
 * @property string|null $batch_uuid
 * @property string|null $event
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @property-read Model|null $causer
 * @property-read Collection<int, mixed> $changes
 * @property-read Model|null $subject
 * @method static ActivityFactory factory($count = null, $state = [])
 * @method static Builder<static>|Activity forBatch(string $batchUuid)
 * @method static Builder<static>|Activity forEvent(string $event)
 * @method static Builder<static>|Activity forSubject(Model $subject)
 * @method static Builder<static>|Activity hasBatch()
 * @method static Builder<static>|Activity inLog(...$logNames)
 * @method static Builder<static>|Activity newModelQuery()
 * @method static Builder<static>|Activity newQuery()
 * @method static Builder<static>|Activity query()
 * @method static Builder<static>|Activity whereBatchUuid($value)
 * @method static Builder<static>|Activity whereCauserId($value)
 * @method static Builder<static>|Activity whereCauserType($value)
 * @method static Builder<static>|Activity whereCreatedAt($value)
 * @method static Builder<static>|Activity whereCreatedBy($value)
 * @method static Builder<static>|Activity whereDeletedAt($value)
 * @method static Builder<static>|Activity whereDeletedBy($value)
 * @method static Builder<static>|Activity whereDescription($value)
 * @method static Builder<static>|Activity whereEvent($value)
 * @method static Builder<static>|Activity whereId($value)
 * @method static Builder<static>|Activity whereLogName($value)
 * @method static Builder<static>|Activity whereProperties($value)
 * @method static Builder<static>|Activity whereSubjectId($value)
 * @method static Builder<static>|Activity whereSubjectType($value)
 * @method static Builder<static>|Activity whereUpdatedAt($value)
 * @method static Builder<static>|Activity whereUpdatedBy($value)
 * @method static Builder<static>|Activity where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Activity create(array<string, mixed> $attributes = [])
 * @method static Builder<static>|Activity clone()
 * @method static Builder<static>|Activity selectRaw(string $expression)
 * @method static Builder<static>|Activity whereDate(string $column, string $operator, mixed $value = null)
 * @method static Builder<static>|Activity whereBetween(string $column, array<int, mixed> $values)
 * @method static Builder<static>|Activity whereMonth(string $column, string $operator, mixed $value = null)
 * @method static Builder<static>|Activity whereYear(string $column, string $operator, mixed $value = null)
 * @method static Builder<static>|Activity latest(string $column = 'created_at')
 * @method static Builder<static>|Activity limit(int $value)
 * @method static Builder<static>|Activity with(array<string, mixed>|string $relations)
 * @method static int sum(string $column)
 * @method static Collection<int, static> get(array<string>|string $columns = ['*'])
 * @method static static|null first(array<string>|string $columns = ['*'])
 * @method static static find(mixed $id, array<string>|string $columns = ['*'])
 * @method static static|null firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static Builder<static>|Activity orderBy(string $column, string $direction = 'asc')
 * @method static Builder<static>|Activity groupBy(array<string>|string $groups)
 * @method static Builder<static>|Activity having(string $column, string $operator, mixed $value)
 * @method static Builder<static>|Activity orWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static Builder<static>|Activity whereIn(string $column, array<int, mixed> $values)
 * @method static Builder<static>|Activity whereNotIn(string $column, array<int, mixed> $values)
 * @method static Builder<static>|Activity whereNull(string $column)
 * @method static Builder<static>|Activity whereNotNull(string $column)
 * @method static int count(string $columns = '*')
 * @method static Collection<int, mixed> pluck(string $column, string|null $key = null)
 * @method static mixed max(string $column)
 * @method static mixed min(string $column)
 * @method static mixed avg(string $column)
 * @method static int sum(string $column)
 * @method static bool exists()
 * @method static bool doesntExist()
 * @method static Builder<static>|Activity distinct()
 * @method static Builder<static>|Activity join(string $table, string $first, string $operator = null, string $second = null)
 * @method static Builder<static>|Activity leftJoin(string $table, string $first, string $operator = null, string $second = null)
 * @method static Builder<static>|Activity rightJoin(string $table, string $first, string $operator = null, string $second = null)
 * @method static Builder<static>|Activity crossJoin(string $table)
 * @method static Builder<static>|Activity causedBy(Model $causer)
 * @mixin \Eloquent
 */
	class Activity extends \Eloquent {}
}

namespace Modules\Activity\Models{
/**
 * Modules\Activity\Models\Snapshot.
 *
 * @property int $id
 * @property string $aggregate_uuid
 * @property int $aggregate_version
 * @property array<array-key, mixed> $state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder<static>|Snapshot newModelQuery()
 * @method static Builder<static>|Snapshot newQuery()
 * @method static Builder<static>|Snapshot query()
 * @method static Builder<static>|Snapshot uuid(string $uuid)
 * @method static Builder<static>|Snapshot whereAggregateUuid($value)
 * @method static Builder<static>|Snapshot whereAggregateVersion($value)
 * @method static Builder<static>|Snapshot whereCreatedAt($value)
 * @method static Builder<static>|Snapshot whereCreatedBy($value)
 * @method static Builder<static>|Snapshot whereId($value)
 * @method static Builder<static>|Snapshot whereState($value)
 * @method static Builder<static>|Snapshot whereUpdatedAt($value)
 * @method static Builder<static>|Snapshot whereUpdatedBy($value)
 * @method static SnapshotFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Snapshot extends \Eloquent {}
}

namespace Modules\Activity\Models{
/**
 * Class StoredEvent.
 *
 * Represents a stored event in the activity module.
 *
 * @property int $id
 * @property string|null $aggregate_uuid
 * @property int|null $aggregate_version
 * @property int $event_version
 * @property string $event_class
 * @property array<array-key, mixed> $event_properties
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $meta_data
 * @property string $created_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property-read ShouldBeStored|null $event
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent afterVersion(int $version)
 * @method static EloquentStoredEventCollection<static> all($columns = ['*'])
 * @method static EloquentStoredEventCollection<static> get($columns = ['*'])
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent lastEvent(string ...$eventClasses)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent newModelQuery()
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent newQuery()
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent query()
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent startingFrom(int $storedEventId)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereAggregateRoot(string $uuid)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereAggregateUuid($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereAggregateVersion($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereCreatedAt($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereCreatedBy($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereEvent(string ...$eventClasses)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereEventClass($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereEventProperties($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereEventVersion($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereId($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereMetaData($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent wherePropertyIs(string $property, ?mixed $value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent wherePropertyIsNot(string $property, ?mixed $value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereUpdatedBy($value)
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent withMetaDataAttributes()
 * @method static StoredEventFactory factory($count = null, $state = [])
 * @property string|null $updated_at
 * @method static EloquentStoredEventQueryBuilder<static>|StoredEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class StoredEvent extends \Eloquent {}
}

namespace Modules\Activity\Models{
/**
 * Test model for Activity module tests.
 *
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel query()
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	final class TestModel extends \Eloquent {}
}

namespace Modules\AiAssistant\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $ai_thread_id
 * @property int $proposed_by_user_id
 * @property string $type
 * @property array<string, mixed> $payload
 * @property string|null $preview
 * @property string $status
 * @property int|null $confirmed_by_user_id
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $executed_at
 * @property array<string, mixed>|null $result
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $confirmedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\User\Models\User|null $proposedBy
 * @property-read \Modules\AiAssistant\Models\AiThread|null $thread
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\AiAssistant\Database\Factories\AiActionProposalFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiActionProposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiActionProposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiActionProposal query()
 */
	final class AiActionProposal extends \Eloquent {}
}

namespace Modules\AiAssistant\Models{
/**
 * @property int $id
 * @property int $ai_thread_id
 * @property int|null $user_id
 * @property string $role
 * @property string|null $content
 * @property array<string, mixed>|null $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\AiAssistant\Models\AiThread|null $thread
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Modules\AiAssistant\Database\Factories\AiMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiMessage query()
 */
	final class AiMessage extends \Eloquent {}
}

namespace Modules\AiAssistant\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $created_by_user_id
 * @property string $panel_id
 * @property Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\AiAssistant\Models\AiMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\AiAssistant\Models\AiActionProposal> $proposals
 * @property-read int|null $proposals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\AiAssistant\Models\AiToolLog> $toolLogs
 * @property-read int|null $tool_logs_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\AiAssistant\Database\Factories\AiThreadFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiThread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiThread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiThread query()
 */
	final class AiThread extends \Eloquent {}
}

namespace Modules\AiAssistant\Models{
/**
 * @property int $id
 * @property int $ai_thread_id
 * @property int|null $ai_action_proposal_id
 * @property int|null $user_id
 * @property string $tool_name
 * @property array<string, mixed>|null $arguments
 * @property array<string, mixed>|null $response
 * @property string $status
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\AiAssistant\Models\AiActionProposal|null $proposal
 * @property-read \Modules\AiAssistant\Models\AiThread|null $thread
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Modules\AiAssistant\Database\Factories\AiToolLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiToolLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiToolLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiToolLog query()
 */
	final class AiToolLog extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * Documento di trasporto ricevuto dal fornitore.
 *
 * @property int $id
 * @property int $supplier_id
 * @property int|null $work_order_id
 * @property string $number
 * @property Carbon|null $received_on
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static \Modules\Billing\Database\Factories\DeliveryNoteFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Billing\Models\DeliveryNoteLine> $lines
 * @property-read int|null $lines_count
 * @property-read \Modules\Billing\Models\Supplier|null $supplier
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNote onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNote withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNote withoutTrashed()
 */
	final class DeliveryNote extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * @property int $id
 * @property int $delivery_note_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\Billing\Database\Factories\DeliveryNoteLineFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Billing\Models\DeliveryNote|null $deliveryNote
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNoteLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNoteLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryNoteLine query()
 */
	final class DeliveryNoteLine extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $work_order_id
 * @property string|null $number
 * @property Carbon|null $issued_on
 * @property string $total
 * @property string|null $notes
 * @property string|null $electronic_invoice_provider
 * @property string|null $electronic_invoice_external_id
 * @property Carbon|null $electronic_invoice_sent_at
 * @property string|null $electronic_invoice_last_error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static \Modules\Billing\Database\Factories\InvoiceFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Billing\Models\InvoiceWorkOrderLink> $workOrderLinks
 * @property-read int|null $work_order_links_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice withoutTrashed()
 */
	final class Invoice extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * @property int $id
 * @property int $invoice_id
 * @property int $work_order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Billing\Models\Invoice|null $invoice
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Billing\Database\Factories\InvoiceWorkOrderLinkFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceWorkOrderLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceWorkOrderLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceWorkOrderLink query()
 */
	final class InvoiceWorkOrderLink extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * @property int $id
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static \Modules\Billing\Database\Factories\SupplierFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Billing\Models\DeliveryNote> $deliveryNotes
 * @property-read int|null $delivery_notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Billing\Models\SupplierInvoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
 */
	final class Supplier extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * Fattura ricevuta dal fornitore (ciclo passivo).
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $number
 * @property Carbon|null $issued_on
 * @property string $total
 * @property string $status_code
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static \Modules\Billing\Database\Factories\SupplierInvoiceFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Billing\Models\SupplierInvoiceLine> $lines
 * @property-read int|null $lines_count
 * @property-read \Modules\Billing\Models\Supplier|null $supplier
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoice withoutTrashed()
 */
	final class SupplierInvoice extends \Eloquent {}
}

namespace Modules\Billing\Models{
/**
 * @property int $id
 * @property int $supplier_invoice_id
 * @property int|null $delivery_note_line_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\Billing\Database\Factories\SupplierInvoiceLineFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Billing\Models\DeliveryNoteLine|null $deliveryNoteLine
 * @property-read \Modules\Billing\Models\SupplierInvoice|null $invoice
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoiceLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoiceLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierInvoiceLine query()
 */
	final class SupplierInvoiceLine extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Article.
 *
 * @property Profile|null                $author
 * @property Collection<int, Category>   $categories
 * @property int|null                    $categories_count
 * @property Collection<int, Comment>    $comments
 * @property int|null                    $comments_count
 * @property string                      $human_read_time
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property Collection<int, Tag>        $tags
 * @property Collection<int, Status>     $statuses
 * @property int|null                    $statuses_count
 * @property int|null                    $tags_count
 * @property UserContract|null           $user
 * @property string                      $body
 * @property Carbon                      $published_at
 * @property Carbon                      $updated_at
 * @property string                      $slug
 * @property string                      $title
 * @property string                      $description
 * @property string                      $main_image_upload
 * @property string                      $main_image_url
 * @property array<string, mixed>|string $content_blocks
 * @method static \Illuminate\Database\Eloquent\Builder|Article article(string $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article author(string $profile_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article category(string $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article currentStatus(...$names)
 * @method static \Illuminate\Database\Eloquent\Builder|Article differentFromCurrentArticle(string $current_article)
 * @method static ArticleFactory                                factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Article otherCurrentStatus(...$names)
 * @method static \Illuminate\Database\Eloquent\Builder|Article published()
 * @method static \Illuminate\Database\Eloquent\Builder|Article publishedUntilToday()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article search(string $searching)
 * @method static \Illuminate\Database\Eloquent\Builder|Article showHomepage()
 * @method static \Illuminate\Database\Eloquent\Builder|Article tag(string $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAllTags((\ArrayAccess<int|string, Tag>|Tag|array<int|string, Tag>|string) $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAllTagsOfAnyType(array<int|string, Tag>|string $tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAnyTags((\ArrayAccess<int|string, Tag>|Tag|array<int|string, Tag>|string) $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAnyTagsOfAnyType(array<int|string, Tag>|string $tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Article withoutTags((\ArrayAccess<int|string, Tag>|Tag|array<int|string, Tag>|string) $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withoutTrashed()
 * @property string                          $id
 * @property string                          $uuid
 * @property string|null                     $content
 * @property string|null                     $picture
 * @property int|null                        $category_id
 * @property int|null                        $author_id
 * @property string|null                     $status
 * @property int                             $show_on_homepage
 * @property int|null                        $read_time
 * @property string|null                     $excerpt
 * @property string                          $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null                     $updated_by
 * @property string|null                     $created_by
 * @property string|null                     $deleted_by
 * @property array<string, mixed>|null       $footer_blocks
 * @property array<string, mixed>|null       $sidebar_blocks
 * @property int                             $is_featured
 * @property string|null                     $closed_at
 * @property Category|null                   $category
 * @property string                          $main_image
 * @property Collection<int, Rating>         $ratings
 * @property int|null                        $ratings_count
 * @property mixed                           $translations
 * @property string|null                     $rewarded_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContentBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereFooterBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLocales(string $column, array<string, mixed> $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMainImageUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMainImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereReadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereShowOnHomepage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUuid($value)
 * @property int         $status_display
 * @property string|null $bet_end_date
 * @property string|null $event_start_date
 * @property string|null $event_end_date
 * @property int         $is_wagerable
 * @property int|null    $wagers_count
 * @property int|null    $wagers_count_canonical
 * @property int|null    $wagers_count_total
 * @property int|null    $wagers
 * @property string|null $brier_score
 * @property string|null $brier_score_play_money
 * @property string|null $brier_score_real_money
 * @property float|null  $volume_play_money
 * @property float|null  $volume_real_money
 * @property int         $is_following
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBetEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBrierScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBrierScorePlayMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBrierScoreRealMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEventEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEventStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsWagerable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSidebarBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatusDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereVolumePlayMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereVolumeRealMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagersCountCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagersCountTotal($value)
 * @property RatingMorph $pivot
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereJsonContainsLocales(string $column, array<string, mixed> $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereRewardedAt($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @mixin Model
 * @property string|null                                      $type
 * @property string|null                                      $extra
 * @property string|null                                      $resolved_at
 * @property string|null                                      $liquidity
 * @property float|null                                       $stocks_count
 * @property float|null                                       $stocks_value
 * @property string                                           $sum_credit_yes
 * @property string                                           $sum_credit_no
 * @property int                                              $count_credit_yes
 * @property int                                              $count_credit_no
 * @property Collection<int, CommentNotificationSubscription> $notificationSubscriptions
 * @property int|null                                         $notification_subscriptions_count
 * @method static EloquentBuilder<static>|Article                       whereCountCreditNo($value)
 * @method static EloquentBuilder<static>|Article                       whereCountCreditYes($value)
 * @method static EloquentBuilder<static>|Article                       whereExtra($value)
 * @method static EloquentBuilder<static>|Article                       whereLiquidity($value)
 * @method static EloquentBuilder<static>|Article                       whereResolvedAt($value)
 * @method static EloquentBuilder<static>|Article                       whereStocksCount($value)
 * @method static EloquentBuilder<static>|Article                       whereStocksValue($value)
 * @method static EloquentBuilder<static>|Article                       whereSumCreditNo($value)
 * @method static EloquentBuilder<static>|Article                       whereSumCreditYes($value)
 * @method static EloquentBuilder<static>|Article                       whereType($value)
 * @method static EloquentBuilder<static>|Article                       withAnyTagsOfType(array<string, mixed>|string $type)
 * @method static Article|null                                          first()
 * @method static Collection<int, Article>                              get()
 * @method static Article                                               create(array<string, mixed> $attributes = [])
 * @method static Article                                               firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article where((string|\Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereNotNull((string|\Illuminate\Contracts\Database\Query\Expression) $columns)
 * @method static int                                                   count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @method static EloquentBuilder<static>|Article childrenWith(array<string, mixed> $relations)
 * @method static EloquentBuilder<static>|Article childrenWithCount(array<string, mixed> $relations)
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Article extends \Eloquent implements \Spatie\Feed\Feedable, \Modules\Lang\Models\Contracts\HasTranslationsContract, \Modules\Comment\Models\Contracts\SupportsCommentNotifications {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\ArticleCategory.
 *
 * @property string      $id
 * @property int         $category_id
 * @property int         $article_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|ArticleCategory newModelQuery()
 * @method static Builder|ArticleCategory newQuery()
 * @method static Builder|ArticleCategory onlyTrashed()
 * @method static Builder|ArticleCategory query()
 * @method static Builder|ArticleCategory whereArticleId($value)
 * @method static Builder|ArticleCategory whereCategoryId($value)
 * @method static Builder|ArticleCategory whereCreatedAt($value)
 * @method static Builder|ArticleCategory whereCreatedBy($value)
 * @method static Builder|ArticleCategory whereId($value)
 * @method static Builder|ArticleCategory whereUpdatedAt($value)
 * @method static Builder|ArticleCategory whereUpdatedBy($value)
 * @method static Builder|ArticleCategory withTrashed()
 * @method static Builder|ArticleCategory withoutTrashed()
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|ArticleCategory whereDeletedAt($value)
 * @method static Builder|ArticleCategory whereDeletedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static ArticleCategory|null             first()
 * @method static Collection<int, ArticleCategory> get()
 * @method static ArticleCategory                  create(array<string, mixed> $attributes = [])
 * @method static ArticleCategory                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|ArticleCategory  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|ArticleCategory  whereNotNull((string|Expression) $columns)
 * @method static int                              count(string $columns = '*')
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Blog\Database\Factories\ArticleCategoryFactory factory($count = null, $state = [])
 */
	class ArticleCategory extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Cms\Models\Menu.
 *
 * @property int                       $id
 * @property string                    $name
 * @property array<string, mixed>|null $items
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $updated_by
 * @property string|null               $created_by
 * @property Carbon|null               $deleted_at
 * @property string|null               $deleted_by
 * @method static BannerFactory factory($count = null, $state = [])
 * @method static Builder|Menu  newModelQuery()
 * @method static Builder|Menu  newQuery()
 * @method static Builder|Menu  onlyTrashed()
 * @method static Builder|Menu  query()
 * @method static Builder|Menu  whereCreatedAt($value)
 * @method static Builder|Menu  whereCreatedBy($value)
 * @method static Builder|Menu  whereDeletedAt($value)
 * @method static Builder|Menu  whereDeletedBy($value)
 * @method static Builder|Menu  whereId($value)
 * @method static Builder|Menu  whereItems($value)
 * @method static Builder|Menu  whereName($value)
 * @method static Builder|Menu  whereUpdatedAt($value)
 * @method static Builder|Menu  whereUpdatedBy($value)
 * @method static Builder|Menu  withTrashed()
 * @method static Builder|Menu  withoutTrashed()
 * @property string|null                                       $link
 * @property string|null                                       $title
 * @property string|null                                       $description
 * @property string|null                                       $action_text
 * @property string|null                                       $category_id
 * @property Carbon|null                                       $start_date
 * @property Carbon|null                                       $end_date
 * @property bool                                              $hot_topic
 * @property int|null                                          $open_markets_count
 * @property bool                                              $landing_banner
 * @property int|null                                          $pos
 * @property Category|null                                     $category
 * @property string                                            $desktop_thumbnail
 * @property string                                            $desktop_thumbnail_webp
 * @property string                                            $mobile_thumbnail
 * @property string                                            $mobile_thumbnail_webp
 * @property MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property int|null                                          $media_count
 * @method static Builder|Banner whereActionText($value)
 * @method static Builder|Banner whereCategoryId($value)
 * @method static Builder|Banner whereDescription($value)
 * @method static Builder|Banner whereEndDate($value)
 * @method static Builder|Banner whereHotTopic($value)
 * @method static Builder|Banner whereLandingBanner($value)
 * @method static Builder|Banner whereLink($value)
 * @method static Builder|Banner whereOpenMarketsCount($value)
 * @method static Builder|Banner wherePos($value)
 * @method static Builder|Banner whereStartDate($value)
 * @method static Builder|Banner whereTitle($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @mixin Model
 * @method static Banner|null             first()
 * @method static Collection<int, Banner> get()
 * @method static Banner                  create(array<string, mixed> $attributes = [])
 * @method static Banner                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Banner  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Banner  whereNotNull((string|Expression) $columns)
 * @method static int                     count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Banner extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Category.
 *
 * @property int                         $id
 * @property string                      $title
 * @property string                      $slug
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property Collection<int, Article>    $articles
 * @property int|null                    $articles_count
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @method static CategoryFactory  factory($count = null, $state = [])
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category onlyTrashed()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereCreatedBy($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereSlug($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category whereUpdatedBy($value)
 * @method static Builder|Category withTrashed()
 * @method static Builder|Category withoutTrashed()
 * @property array<string, mixed>|null                                            $description
 * @property Carbon|null                                                          $deleted_at
 * @property string|null                                                          $deleted_by
 * @property string|null                                                          $parent_id
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $children
 * @property int|null                                                             $children_count
 * @property Category|null                                                        $parent
 * @property mixed                                                                $post_counter
 * @property mixed                                                                $translations
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestors                  The model's recursive parents.
 * @property int|null                                                             $ancestors_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                                                             $ancestors_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                                                             $bloodline_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $childrenAndSelf            The model's direct children and itself.
 * @property int|null                                                             $children_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendants                The model's recursive children.
 * @property int|null                                                             $descendants_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                                                             $descendants_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $parentAndSelf              The model's direct parent and itself.
 * @property int|null                                                             $parent_and_self_count
 * @property Category|null                                                        $rootAncestor               The model's topmost parent.
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblings                   The parent's other children.
 * @property int|null                                                             $siblings_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblingsAndSelf            All the parent's children.
 * @property int|null                                                             $siblings_and_self_count
 * @property array<string, mixed>|null                                            $description
 * @property Carbon|null                                                          $deleted_at
 * @property string|null                                                          $deleted_by
 * @property string|null                                                          $parent_id
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $children
 * @property int|null                                                             $children_count
 * @property Category|null                                                        $parent
 * @property mixed                                                                $post_counter
 * @property mixed                                                                $translations
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestors                  The model's recursive parents.
 * @property int|null                                                             $ancestors_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                                                             $ancestors_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                                                             $bloodline_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $childrenAndSelf            The model's direct children and itself.
 * @property int|null                                                             $children_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendants                The model's recursive children.
 * @property int|null                                                             $descendants_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                                                             $descendants_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $parentAndSelf              The model's direct parent and itself.
 * @property int|null                                                             $parent_and_self_count
 * @property Category|null                                                        $rootAncestor               The model's topmost parent.
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblings                   The parent's other children.
 * @property int|null                                                             $siblings_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblingsAndSelf            All the parent's children.
 * @property int|null                                                             $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        treeOf((Model|callable) $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDeletedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDeletedBy($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereLocale(string $column, string $locale)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereLocales(string $column, array<string, mixed> $locales)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        withGlobalScopes(array<string, mixed> $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @property string|null              $icon
 * @property Banner|null              $banner
 * @property Collection<int, Article> $categoryArticles
 * @property int|null                 $category_articles_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereIcon($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereJsonContainsLocales(string $column, array<string, mixed> $locales, ?mixed $value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @property string|null $name
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @mixin Model
 * @property-read int $depth
 * @property-read string $path
 * @method static Category|null             first()
 * @method static Collection<int, Category> get()
 * @method static Category                  create(array<string, mixed> $attributes = [])
 * @method static Category                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Category  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Category  whereNotNull((string|Expression) $columns)
 * @method static int                       count(string $columns = '*')
 * @property int $is_active
 * @property int $sort_order
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Category whereSortOrder($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Category extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\CategoryPost.
 *
 * @property string      $id
 * @property int         $category_id
 * @property int         $post_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|CategoryPost newModelQuery()
 * @method static Builder|CategoryPost newQuery()
 * @method static Builder|CategoryPost onlyTrashed()
 * @method static Builder|CategoryPost query()
 * @method static Builder|CategoryPost whereCategoryId($value)
 * @method static Builder|CategoryPost whereCreatedAt($value)
 * @method static Builder|CategoryPost whereCreatedBy($value)
 * @method static Builder|CategoryPost whereId($value)
 * @method static Builder|CategoryPost wherePostId($value)
 * @method static Builder|CategoryPost whereUpdatedAt($value)
 * @method static Builder|CategoryPost whereUpdatedBy($value)
 * @method static Builder|CategoryPost withTrashed()
 * @method static Builder|CategoryPost withoutTrashed()
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|CategoryPost whereDeletedAt($value)
 * @method static Builder|CategoryPost whereDeletedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static CategoryPost|null             first()
 * @method static Collection<int, CategoryPost> get()
 * @method static CategoryPost                  create(array<string, mixed> $attributes = [])
 * @method static CategoryPost                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|CategoryPost  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|CategoryPost  whereNotNull((string|Expression) $columns)
 * @method static int                           count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @method static \Modules\Blog\Database\Factories\CategoryPostFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class CategoryPost extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Comment.
 *
 * @property int                      $id
 * @property string                   $comment
 * @property int                      $post_id
 * @property int                      $user_id
 * @property Carbon|null              $created_at
 * @property Carbon|null              $updated_at
 * @property int|null                 $parent_id
 * @property Article|null             $article
 * @property Profile|null             $author
 * @property Collection<int, Comment> $childrens
 * @property int|null                 $childrens_count
 * @property Collection<int, Comment> $comments
 * @property int|null                 $comments_count
 * @property Comment|null             $parentComment
 * @property UserContract|null        $user
 * @method static CommentFactory  factory($count = null, $state = [])
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment onlyTrashed()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereComment($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereParentId($value)
 * @method static Builder|Comment wherePostId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @method static Builder|Comment withTrashed()
 * @method static Builder|Comment withoutTrashed()
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|Comment whereCreatedBy($value)
 * @method static Builder|Comment whereDeletedAt($value)
 * @method static Builder|Comment whereDeletedBy($value)
 * @method static Builder|Comment whereUpdatedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string|null          $commentable_type
 * @property string|null          $commentable_id
 * @property string|null          $commentator_type
 * @property string|null          $commentator_id
 * @property string               $text
 * @property string|null          $extra
 * @property string|null          $approved_at
 * @property string               $original_text
 * @method static Builder|Comment whereApprovedAt($value)
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCommentatorId($value)
 * @method static Builder|Comment whereCommentatorType($value)
 * @method static Builder|Comment whereExtra($value)
 * @method static Builder|Comment whereOriginalText($value)
 * @method static Builder|Comment whereText($value)
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @method static Comment|null             first()
 * @method static Collection<int, Comment> get()
 * @method static Comment                  create(array<string, mixed> $attributes = [])
 * @method static Comment                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Comment  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Comment  whereNotNull((string|Expression) $columns)
 * @method static int                      count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Comment extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Menu.
 *
 * @property int                       $id
 * @property string                    $name
 * @property array<string, mixed>|null $items
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $updated_by
 * @property string|null               $created_by
 * @property Carbon|null               $deleted_at
 * @property string|null               $deleted_by
 * @method static MenuFactory  factory($count = null, $state = [])
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu onlyTrashed()
 * @method static Builder|Menu query()
 * @method static Builder|Menu whereCreatedAt($value)
 * @method static Builder|Menu whereCreatedBy($value)
 * @method static Builder|Menu whereDeletedAt($value)
 * @method static Builder|Menu whereDeletedBy($value)
 * @method static Builder|Menu whereId($value)
 * @method static Builder|Menu whereItems($value)
 * @method static Builder|Menu whereName($value)
 * @method static Builder|Menu whereUpdatedAt($value)
 * @method static Builder|Menu whereUpdatedBy($value)
 * @method static Builder|Menu withTrashed()
 * @method static Builder|Menu withoutTrashed()
 * @property string                      $title
 * @property int|null                    $parent_id
 * @property Collection<int, Menu>       $children
 * @property int|null                    $children_count
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property Menu|null                   $parent
 * @property Collection<int, Menu>       $ancestors                  The model's recursive parents.
 * @property int|null                    $ancestors_count
 * @property Collection<int, Menu>       $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                    $ancestors_and_self_count
 * @property Collection<int, Menu>       $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                    $bloodline_count
 * @property Collection<int, Menu>       $childrenAndSelf            The model's direct children and itself.
 * @property int|null                    $children_and_self_count
 * @property Collection<int, Menu>       $descendants                The model's recursive children.
 * @property int|null                    $descendants_count
 * @property Collection<int, Menu>       $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                    $descendants_and_self_count
 * @property Collection<int, Menu>       $parentAndSelf              The model's direct parent and itself.
 * @property int|null                    $parent_and_self_count
 * @property Menu|null                   $rootAncestor               The model's topmost parent.
 * @property Collection<int, Menu>       $siblings                   The parent's other children.
 * @property int|null                    $siblings_count
 * @property Collection<int, Menu>       $siblingsAndSelf            All the parent's children.
 * @property int|null                    $siblings_and_self_count
 * @property string                      $title
 * @property int|null                    $parent_id
 * @property Collection<int, Menu>       $children
 * @property int|null                    $children_count
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property Menu|null                   $parent
 * @property Collection<int, Menu>       $ancestors                  The model's recursive parents.
 * @property int|null                    $ancestors_count
 * @property Collection<int, Menu>       $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                    $ancestors_and_self_count
 * @property Collection<int, Menu>       $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                    $bloodline_count
 * @property Collection<int, Menu>       $childrenAndSelf            The model's direct children and itself.
 * @property int|null                    $children_and_self_count
 * @property Collection<int, Menu>       $descendants                The model's recursive children.
 * @property int|null                    $descendants_count
 * @property Collection<int, Menu>       $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                    $descendants_and_self_count
 * @property Collection<int, Menu>       $parentAndSelf              The model's direct parent and itself.
 * @property int|null                    $parent_and_self_count
 * @property Menu|null                   $rootAncestor               The model's topmost parent.
 * @property Collection<int, Menu>       $siblings                   The parent's other children.
 * @property int|null                    $siblings_count
 * @property Collection<int, Menu>       $siblingsAndSelf            All the parent's children.
 * @property int|null                    $siblings_and_self_count
 * @method static Collection<int, static>                                 all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu doesntHaveChildren()
 * @method static Collection<int, static>                                 get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu treeOf((Model|callable) $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereTitle($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu withGlobalScopes(array<string, mixed> $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @property Profile|null $creator
 * @property Profile|null $updater
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 * @property-read int $depth
 * @property-read string $path
 * @method static Menu|null                                           first()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Menu> get()
 * @method static Menu                                                create(array<string, mixed> $attributes = [])
 * @method static Menu                                                firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Menu                                where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Menu                                whereNotNull((string|Expression) $columns)
 * @method static int                                                 count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Menu extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\PostView.
 *
 * @property int         $id
 * @property string      $ip_address
 * @property string      $user_agent
 * @property int         $post_id
 * @property int         $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static PostViewFactory  factory($count = null, $state = [])
 * @method static Builder|PostView newModelQuery()
 * @method static Builder|PostView newQuery()
 * @method static Builder|PostView onlyTrashed()
 * @method static Builder|PostView query()
 * @method static Builder|PostView whereCreatedAt($value)
 * @method static Builder|PostView whereId($value)
 * @method static Builder|PostView whereIpAddress($value)
 * @method static Builder|PostView wherePostId($value)
 * @method static Builder|PostView whereUpdatedAt($value)
 * @method static Builder|PostView whereUserAgent($value)
 * @method static Builder|PostView whereUserId($value)
 * @method static Builder|PostView withTrashed()
 * @method static Builder|PostView withoutTrashed()
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|PostView whereCreatedBy($value)
 * @method static Builder|PostView whereDeletedAt($value)
 * @method static Builder|PostView whereDeletedBy($value)
 * @method static Builder|PostView whereUpdatedBy($value)
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @method static PostView|null             first()
 * @method static Collection<int, PostView> get()
 * @method static PostView                  create(array<string, mixed> $attributes = [])
 * @method static PostView                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|PostView  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|PostView  whereNotNull((string|Expression) $columns)
 * @method static int                       count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class PostView extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Profile.
 *
 * @property float                                                      $credits
 * @property int                                                        $id
 * @property string|null                                                $user_id
 * @property string|null                                                $first_name
 * @property string|null                                                $last_name
 * @property string|null                                                $email
 * @property Carbon|null                                                $created_at
 * @property Carbon|null                                                $updated_at
 * @property string|null                                                $updated_by
 * @property string|null                                                $created_by
 * @property string|null                                                $deleted_at
 * @property string|null                                                $deleted_by
 * @property string|null                                                $slug
 * @property SchemalessAttributes|null                                  $extra
 * @property \Illuminate\Database\Eloquent\Collection<int, Article>     $articles
 * @property int|null                                                   $articles_count
 * @property string                                                     $avatar
 * @property \Illuminate\Database\Eloquent\Collection<int, DeviceUser>  $deviceUsers
 * @property int|null                                                   $device_users_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Device>      $devices
 * @property int|null                                                   $devices_count
 * @property string|null                                                $full_name
 * @property MediaCollection<int, Media>                                $media
 * @property int|null                                                   $media_count
 * @property \Illuminate\Database\Eloquent\Collection<int, DeviceUser>  $mobileDeviceUsers
 * @property int|null                                                   $mobile_device_users_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Device>      $mobileDevices
 * @property int|null                                                   $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification>  $notifications
 * @property int|null                                                   $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Permission>  $permissions
 * @property int|null                                                   $permissions_count
 * @property \Illuminate\Database\Eloquent\Collection<int, RatingMorph> $ratingMorphs
 * @property int|null                                                   $rating_morphs_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Rating>      $ratings
 * @property int|null                                                   $ratings_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Role>        $roles
 * @property int|null                                                   $roles_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Team>        $teams
 * @property int|null                                                   $teams_count
 * @property UserContract|null                                          $user
 * @property string|null                                                $user_name
 * @method static ProfileFactory                                factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static EloquentBuilder|BaseProfile                   permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static EloquentBuilder|BaseProfile                   role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 * @method static EloquentBuilder|BaseProfile                   withExtraAttributes()
 * @method static EloquentBuilder|BaseProfile                   withoutPermission($permissions)
 * @method static EloquentBuilder|BaseProfile                   withoutRole($roles, $guard = null)
 * @property DeviceUser           $pivot
 * @property Membership           $membership
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property int                  $oauth_enable
 * @property int                  $credentials_enable
 * @method static \Illuminate\Database\Eloquent\Builder|Profile          whereCredentialsEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile          whereOauthEnable($value)
 * @method static Profile|null                                           first()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Profile> get()
 * @method static Profile                                                create(array<string, mixed> $attributes = [])
 * @method static Profile                                                firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile  whereNotNull((string|Expression) $columns)
 * @method static int                                                    count(string $columns = '*')
 * @property string               $display_name
 * @property ProfileContract|null $deleter
 * @method static \Illuminate\Database\Eloquent\Builder<static> byUuid(string $uuid)
 * @method static \Illuminate\Database\Eloquent\Builder<static> childrenWith(array<string, mixed> $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static> childrenWithCount(array<string, mixed> $relations)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutTeam($teams)
 */
	class Profile extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Status.
 *
 * @property int             $id
 * @property string          $name
 * @property string|null     $reason
 * @property string          $model_type
 * @property int             $model_id
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property string|null     $updated_by
 * @property string|null     $created_by
 * @property Model|\Eloquent $model
 * @method static Builder|Status newModelQuery()
 * @method static Builder|Status newQuery()
 * @method static Builder|Status query()
 * @method static Builder|Status whereCreatedAt($value)
 * @method static Builder|Status whereCreatedBy($value)
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereModelId($value)
 * @method static Builder|Status whereModelType($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereReason($value)
 * @method static Builder|Status whereUpdatedAt($value)
 * @method static Builder|Status whereUpdatedBy($value)
 * @property string $ip_address
 * @property string $user_agent
 * @property int    $post_id
 * @property int    $user_id
 * @method static Builder|Status whereIpAddress($value)
 * @method static Builder|Status wherePostId($value)
 * @method static Builder|Status whereUserAgent($value)
 * @method static Builder|Status whereUserId($value)
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|Status whereDeletedAt($value)
 * @method static Builder|Status whereDeletedBy($value)
 * @mixin Eloquent
 */
	class Status extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Tag.
 *
 * @property int                  $id
 * @property array<string, mixed> $name
 * @property array<string, mixed> $slug
 * @property string|null          $type
 * @property int|null             $order_column
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @method static EloquentBuilder|Tag                       containing(string $name, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static EloquentBuilder|Tag                       ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static EloquentBuilder|Tag                       whereLocale(string $column, string $locale)
 * @method static EloquentBuilder|Tag                       whereLocales(string $column, array<string, mixed> $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedBy($value)
 * @method static EloquentBuilder|Tag                       withType(?string $type = null)
 * @property mixed       $translations
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static \Illuminate\Database\Eloquent\Builder|Tag         whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag         whereDeletedBy($value)
 * @method static EloquentBuilder|Tag                               whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static EloquentBuilder|Tag                               whereJsonContainsLocales(string $column, array<string, mixed> $locales, ?mixed $value)
 * @method static Tag|null                                          first()
 * @method static Collection<int, Tag>                              get()
 * @method static Tag                                               create(array<string, mixed> $attributes = [])
 * @method static Tag                                               firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Tag                                               findOrCreate(array<string, string>|string $name, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereNotNull((string|Expression) $columns)
 * @method static int                                               count(string $columns = '*')
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Tag extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Taggable.
 *
 * @property int                  $id
 * @property int                  $tag_id
 * @property string               $taggable_type
 * @property int                  $taggable_id
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property array<string, mixed> $custom_properties
 * @method static Builder|Taggable newModelQuery()
 * @method static Builder|Taggable newQuery()
 * @method static Builder|Taggable onlyTrashed()
 * @method static Builder|Taggable query()
 * @method static Builder|Taggable whereCreatedAt($value)
 * @method static Builder|Taggable whereCreatedBy($value)
 * @method static Builder|Taggable whereId($value)
 * @method static Builder|Taggable whereTagId($value)
 * @method static Builder|Taggable whereTaggableId($value)
 * @method static Builder|Taggable whereTaggableType($value)
 * @method static Builder|Taggable whereUpdatedAt($value)
 * @method static Builder|Taggable whereUpdatedBy($value)
 * @method static Builder|Taggable withTrashed()
 * @method static Builder|Taggable withoutTrashed()
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|Taggable whereDeletedAt($value)
 * @method static Builder|Taggable whereDeletedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Taggable|null             first()
 * @method static Collection<int, Taggable> get()
 * @method static Taggable                  create(array<string, mixed> $attributes = [])
 * @method static Taggable                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Taggable  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Taggable  whereNotNull((string|Expression) $columns)
 * @method static int                       count(string $columns = '*')
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Blog\Database\Factories\TaggableFactory factory($count = null, $state = [])
 */
	class Taggable extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\TextWidget.
 *
 * @property int                         $id
 * @property string                      $key
 * @property string|null                 $image
 * @property string|null                 $title
 * @property string|null                 $content
 * @property int                         $active
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @method static TextWidgetFactory  factory($count = null, $state = [])
 * @method static Builder|TextWidget newModelQuery()
 * @method static Builder|TextWidget newQuery()
 * @method static Builder|TextWidget onlyTrashed()
 * @method static Builder|TextWidget query()
 * @method static Builder|TextWidget whereActive($value)
 * @method static Builder|TextWidget whereContent($value)
 * @method static Builder|TextWidget whereCreatedAt($value)
 * @method static Builder|TextWidget whereCreatedBy($value)
 * @method static Builder|TextWidget whereId($value)
 * @method static Builder|TextWidget whereImage($value)
 * @method static Builder|TextWidget whereKey($value)
 * @method static Builder|TextWidget whereTitle($value)
 * @method static Builder|TextWidget whereUpdatedAt($value)
 * @method static Builder|TextWidget whereUpdatedBy($value)
 * @method static Builder|TextWidget withTrashed()
 * @method static Builder|TextWidget withoutTrashed()
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|TextWidget whereDeletedAt($value)
 * @method static Builder|TextWidget whereDeletedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @mixin Model
 * @method static TextWidget|null             first()
 * @method static Collection<int, TextWidget> get()
 * @method static TextWidget                  create(array<string, mixed> $attributes = [])
 * @method static TextWidget                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|TextWidget  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|TextWidget  whereNotNull((string|Expression) $columns)
 * @method static int                         count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class TextWidget extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * @property int         $id
 * @property string|null $model_type
 * @property int|null    $model_id
 * @property int|null    $credits
 * @property string|null $user_id
 * @property string|null $note
 * @property string|null $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static TransactionFactory  factory($count = null, $state = [])
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction onlyTrashed()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereCreatedBy($value)
 * @method static Builder|Transaction whereCredits($value)
 * @method static Builder|Transaction whereDate($value)
 * @method static Builder|Transaction whereDeletedAt($value)
 * @method static Builder|Transaction whereDeletedBy($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereModelId($value)
 * @method static Builder|Transaction whereModelType($value)
 * @method static Builder|Transaction whereNote($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @method static Builder|Transaction whereUpdatedBy($value)
 * @method static Builder|Transaction whereUserId($value)
 * @method static Builder|Transaction withTrashed()
 * @method static Builder|Transaction withoutTrashed()
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property float|null                  $stocks_count
 * @property float|null                  $stocks_value
 * @method static Builder<static>|Transaction  whereStocksCount($value)
 * @method static Builder<static>|Transaction  whereStocksValue($value)
 * @method static Transaction|null             first()
 * @method static Collection<int, Transaction> get()
 * @method static Transaction                  create(array<string, mixed> $attributes = [])
 * @method static Transaction                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Transaction  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Transaction  whereNotNull((string|Expression) $columns)
 * @method static int                          count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Transaction extends \Eloquent {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\UpvoteDownvote.
 *
 * @property int         $id
 * @property int         $is_upvote
 * @property int         $post_id
 * @property int         $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static UpvoteDownvoteFactory  factory($count = null, $state = [])
 * @method static Builder|UpvoteDownvote newModelQuery()
 * @method static Builder|UpvoteDownvote newQuery()
 * @method static Builder|UpvoteDownvote onlyTrashed()
 * @method static Builder|UpvoteDownvote query()
 * @method static Builder|UpvoteDownvote whereCreatedAt($value)
 * @method static Builder|UpvoteDownvote whereId($value)
 * @method static Builder|UpvoteDownvote whereIsUpvote($value)
 * @method static Builder|UpvoteDownvote wherePostId($value)
 * @method static Builder|UpvoteDownvote whereUpdatedAt($value)
 * @method static Builder|UpvoteDownvote whereUserId($value)
 * @method static Builder|UpvoteDownvote withTrashed()
 * @method static Builder|UpvoteDownvote withoutTrashed()
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|UpvoteDownvote whereCreatedBy($value)
 * @method static Builder|UpvoteDownvote whereDeletedAt($value)
 * @method static Builder|UpvoteDownvote whereDeletedBy($value)
 * @method static Builder|UpvoteDownvote whereUpdatedBy($value)
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @method static UpvoteDownvote|null             first()
 * @method static Collection<int, UpvoteDownvote> get()
 * @method static UpvoteDownvote                  create(array<string, mixed> $attributes = [])
 * @method static UpvoteDownvote                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|UpvoteDownvote  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|UpvoteDownvote  whereNotNull((string|Expression) $columns)
 * @method static int                             count(string $columns = '*')
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class UpvoteDownvote extends \Eloquent {}
}

namespace Modules\Bom\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property bool $is_active
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Bom\Models\BomItem> $items
 * @property-read int|null $items_count
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Bom\Database\Factories\BomFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bom query()
 */
	final class Bom extends \Eloquent {}
}

namespace Modules\Bom\Models{
/**
 * @property int $id
 * @property int $bom_id
 * @property int $component_product_id
 * @property numeric-string $quantity
 * @property-read \Modules\Bom\Models\Bom|null $bom
 * @property-read \Modules\Catalog\Models\Product|null $componentProduct
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Bom\Database\Factories\BomItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BomItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BomItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BomItem query()
 */
	final class BomItem extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @method static CatalogFrequencyFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogFrequency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogFrequency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogFrequency query()
 */
	final class CatalogFrequency extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @method static CatalogMeasureUnitFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogMeasureUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogMeasureUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogMeasureUnit query()
 */
	final class CatalogMeasureUnit extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @method static CatalogModelFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogModel query()
 */
	final class CatalogModel extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @method static CatalogPartTypeFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogPartType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogPartType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatalogPartType query()
 */
	final class CatalogPartType extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @property int $id
 * @property string $code
 * @property string|null $brand_code
 * @property string $name
 * @property string|null $manufacturer
 * @property string $source_type
 * @property string|null $version
 * @property Carbon|null $imported_at
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Catalog\Models\MetelCatalogItem> $items
 * @property-read int|null $items_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Catalog\Database\Factories\MetelCatalogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetelCatalog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetelCatalog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetelCatalog query()
 */
	final class MetelCatalog extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @property int $id
 * @property int $metel_catalog_id
 * @property int|null $product_id
 * @property string $metel_code
 * @property string|null $barcode
 * @property string|null $etim_code
 * @property string $description
 * @property string|null $unit_of_measure
 * @property string|null $packaging
 * @property float $gross_price
 * @property float|null $net_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\Catalog\Models\MetelCatalog|null $catalog
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Catalog\Database\Factories\MetelCatalogItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetelCatalogItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetelCatalogItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetelCatalogItem query()
 */
	final class MetelCatalogItem extends \Eloquent {}
}

namespace Modules\Catalog\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string|null $description
 * @property float|string $unit_price
 * @property bool $is_active
 * @property int|null $catalog_model_id
 * @property int|null $catalog_part_type_id
 * @property int|null $catalog_measure_unit_id
 * @property int|null $catalog_frequency_id
 * @method static ProductFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Catalog\Models\CatalogFrequency|null $frequency
 * @property-read \Modules\Catalog\Models\CatalogMeasureUnit|null $measureUnit
 * @property-read \Modules\Catalog\Models\CatalogModel|null $model
 * @property-read \Modules\Catalog\Models\CatalogPartType|null $partType
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 */
	final class Product extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * ---.
 *
 * @property string                       $id
 * @property array<array-key, mixed>|null $title
 * @property array<array-key, mixed>|null $description
 * @property string|null                  $slug
 * @property string|null                  $disk
 * @property array<array-key, mixed>|null $attachment
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property ProfileContract|null         $creator
 * @property MediaCollection<int, Media>  $media
 * @property int|null                     $media_count
 * @property mixed                        $translations
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|Attachment newModelQuery()
 * @method static Builder<static>|Attachment newQuery()
 * @method static Builder<static>|Attachment query()
 * @method static Builder<static>|Attachment whereAttachment($value)
 * @method static Builder<static>|Attachment whereCreatedAt($value)
 * @method static Builder<static>|Attachment whereCreatedBy($value)
 * @method static Builder<static>|Attachment whereDescription($value)
 * @method static Builder<static>|Attachment whereDisk($value)
 * @method static Builder<static>|Attachment whereId($value)
 * @method static Builder<static>|Attachment whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Attachment whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Attachment whereLocale(string $column, string $locale)
 * @method static Builder<static>|Attachment whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Attachment whereSlug($value)
 * @method static Builder<static>|Attachment whereTitle($value)
 * @method static Builder<static>|Attachment whereUpdatedAt($value)
 * @method static Builder<static>|Attachment whereUpdatedBy($value)
 * @method static static|null                firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @property ProfileContract|null $deleter
 * @method static AttachmentFactory                factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Attachment extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Conf.
 *
 * @property int         $id
 * @property string|null $name
 * @method static Builder<static>|Conf newModelQuery()
 * @method static Builder<static>|Conf newQuery()
 * @method static Builder<static>|Conf query()
 * @method static Builder<static>|Conf whereId($value)
 * @method static Builder<static>|Conf whereName($value)
 * @method static int                  count()
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static ConfFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Conf extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Menu.
 *
 * @property string                 $id
 * @property string|null            $title
 * @property array<int, mixed>|null $items
 * @property int|null               $parent_id
 * @property string|null            $created_at
 * @property string|null            $updated_at
 * @property string|null            $created_by
 * @property string|null            $updated_by
 * @property Collection<int, Menu>  $children
 * @property int|null               $children_count
 * @property ProfileContract|null   $creator
 * @property Menu|null              $parent
 * @property ProfileContract|null   $updater
 * @property int                    $depth
 * @property string                 $path
 * @property Collection<int, Menu>  $ancestors      The model's recursive parents.
 * @property string                 $id
 * @property string|null            $title
 * @property int|null               $parent_id
 * @property string|null            $created_at
 * @property string|null            $updated_at
 * @property string|null            $created_by
 * @property string|null            $updated_by
 * @property Collection<int, Menu>  $children
 * @property int|null               $children_count
 * @property ProfileContract|null   $creator
 * @property Menu|null              $parent
 * @property ProfileContract|null   $updater
 * @property int                    $depth
 * @property string                 $path
 * @property Collection<int, Menu>  $ancestors      The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read Collection<int, Menu> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read Collection<int, Menu> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read Collection<int, Menu> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read Collection<int, Menu> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read Collection<int, Menu> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read Collection<int, Menu> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read Menu|null $rootAncestor The model's topmost parent.
 * @property-read Collection<int, Menu> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read Collection<int, Menu> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Builder<static>|Menu    breadthFirst()
 * @method static Builder<static>|Menu    depthFirst()
 * @method static Builder<static>|Menu    doesntHaveChildren()
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static Builder<static>|Menu    getExpressionGrammar()
 * @method static Builder<static>|Menu    hasChildren()
 * @method static Builder<static>|Menu    hasParent()
 * @method static Builder<static>|Menu    isLeaf()
 * @method static Builder<static>|Menu    isRoot()
 * @method static Builder<static>|Menu    newModelQuery()
 * @method static Builder<static>|Menu    newQuery()
 * @method static Builder<static>|Menu    query()
 * @method static Builder<static>|Menu    tree($maxDepth = null)
 * @method static Builder<static>|Menu    treeOf((Model|callable) $constraint, $maxDepth = null)
 * @method static Builder<static>|Menu    whereCreatedAt($value)
 * @method static Builder<static>|Menu    whereCreatedBy($value)
 * @method static Builder<static>|Menu    whereDepth($operator, $value = null)
 * @method static Builder<static>|Menu    whereId($value)
 * @method static Builder<static>|Menu    whereParentId($value)
 * @method static Builder<static>|Menu    whereTitle($value)
 * @method static Builder<static>|Menu    whereUpdatedAt($value)
 * @method static Builder<static>|Menu    whereUpdatedBy($value)
 * @method static Builder<static>|Menu    whereDepth($operator, $value = null)
 * @method static Builder<static>|Menu    withGlobalScopes(array<string, mixed> $scopes)
 * @method static Builder<static>|Menu    withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static static                  firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                  create(array<string, mixed> $attributes = [])
 * @method static static                  updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static Builder<static>|Menu    delete()
 * @method static Builder<static>|Menu    where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Menu    whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Menu    whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Menu    whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Menu    orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Menu    latest($column = 'created_at')
 * @method static Builder<static>|Menu    oldest($column = 'created_at')
 * @method static Builder<static>|Menu    limit($value)
 * @method static Builder<static>|Menu    take($value)
 * @method static Builder<static>|Menu    skip($value)
 * @method static Builder<static>|Menu    offset($value)
 * @method static int                     count()
 * @method static int                     max($column)
 * @method static int                     min($column)
 * @method static int                     sum($column)
 * @method static float                   avg($column)
 * @method static mixed                   pluck($column, $key = null)
 * @method static Builder<static>|Menu    join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Menu    orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Menu    whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Menu    whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Menu    whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Menu    with($relations)
 * @method static Builder<static>|Menu    without($relations)
 * @method static Builder<static>|Menu    withCount($relations)
 * @method static Builder<static>|Menu    withSum($relation, $column)
 * @method static Builder<static>|Menu    withAvg($relation, $column)
 * @method static Builder<static>|Menu    withMin($relation, $column)
 * @method static Builder<static>|Menu    withMax($relation, $column)
 * @method static Builder<static>|Menu    findOrFail($id, $columns = ['*'])
 * @method static static                  findOrFail($id, $columns = ['*'])
 * @method static static                  firstOrFail($columns = ['*'])
 * @method static static                  update($attributes)
 * @method static int                     increment($column, $amount = 1, $extra = [])
 * @method static int                     decrement($column, $amount = 1, $extra = [])
 * @method static bool                    truncate()
 * @method static static                  destroy($ids)
 * @method static static                  restore()
 * @method static static                  forceDelete()
 * @method static static                  onlyTrashed()
 * @method static static                  withTrashed()
 * @method static static                  withoutTrashed()
 * @property ProfileContract|null $deleter
 * @method static MenuFactory                      factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 */
	class Menu extends \Eloquent implements \Modules\Xot\Contracts\HasRecursiveRelationshipsContract {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Module.
 *
 * @property string               $id
 * @property string|null          $name
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Module newModelQuery()
 * @method static Builder<static>|Module newQuery()
 * @method static Builder<static>|Module query()
 * @method static Builder<static>|Module whereId($value)
 * @method static Builder<static>|Module whereName($value)
 * @method static int                    count()
 * @property ProfileContract|null $deleter
 * @method static ModuleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Module extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Page.
 *
 * @property string $id
 * @method static array<int, array<string, mixed>>            getMiddlewareBySlug(string $slug)
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @property string                       $id
 * @property array<array-key, mixed>|null $title
 * @property string|null                  $slug
 * @property array<array-key, mixed>|null $middleware
 * @property string|null                  $content
 * @property string|null                  $description
 * @property array<array-key, mixed>|null $blocks
 * @property array<array-key, mixed>|null $content_blocks
 * @property array<array-key, mixed>|null $sidebar_blocks
 * @property array<array-key, mixed>|null $footer_blocks
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property string|null                  $deleted_by
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @property ProfileContract|null         $deleter
 * @property mixed                        $translations
 * @method static Builder<static>|Page                                  newModelQuery()
 * @method static Builder<static>|Page                                  newQuery()
 * @method static Builder<static>|Page                                  query()
 * @method static Builder<static>|Page                                  whereContent($value)
 * @method static Builder<static>|Page                                  whereContentBlocks($value)
 * @method static Builder<static>|Page                                  whereCreatedAt($value)
 * @method static Builder<static>|Page                                  whereCreatedBy($value)
 * @method static Builder<static>|Page                                  whereDescription($value)
 * @method static Builder<static>|Page                                  whereFooterBlocks($value)
 * @method static Builder<static>|Page                                  whereId($value)
 * @method static Builder<static>|Page                                  whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereLocale(string $column, string $locale)
 * @method static Builder<static>|Page                                  whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Page                                  whereMiddleware($value)
 * @method static Builder<static>|Page                                  whereSidebarBlocks($value)
 * @method static Builder<static>|Page                                  whereSlug($value)
 * @method static Builder<static>|Page                                  whereTitle($value)
 * @method static Builder<static>|Page                                  whereUpdatedAt($value)
 * @method static Builder<static>|Page                                  whereUpdatedBy($value)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static static|null                                           first(array|string $columns = ['*'])
 * @method static static|null                                           firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static Builder<static>|Page                                  delete()
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static int                                                   increment($column, $amount = 1, $extra = [])
 * @method static int                                                   decrement($column, $amount = 1, $extra = [])
 * @method static bool                                                  truncate()
 * @method static static                                                destroy($ids)
 * @method static static                                                restore()
 * @method static static                                                forceDelete()
 * @method static static                                                onlyTrashed()
 * @method static static                                                withTrashed()
 * @method static static                                                withoutTrashed()
 * @method static Collection<int, static>                               all($columns = ['*'])
 * @method static Collection<int, static>                               get($columns = ['*'])
 * @method static static|null                                           first($columns = ['*'])
 * @method static static|null                                           find($id, $columns = ['*'])
 * @method static Builder<static>|Page                                  newModelQuery()
 * @method static Builder<static>|Page                                  newQuery()
 * @method static Builder<static>|Page                                  query()
 * @method static Builder<static>|Page                                  whereContent($value)
 * @method static Builder<static>|Page                                  whereContentBlocks($value)
 * @method static Builder<static>|Page                                  whereCreatedAt($value)
 * @method static Builder<static>|Page                                  whereCreatedBy($value)
 * @method static Builder<static>|Page                                  whereDescription($value)
 * @method static Builder<static>|Page                                  whereFooterBlocks($value)
 * @method static Builder<static>|Page                                  whereId($value)
 * @method static Builder<static>|Page                                  whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereLocale(string $column, string $locale)
 * @method static Builder<static>|Page                                  whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Page                                  whereMiddleware($value)
 * @method static Builder<static>|Page                                  whereSidebarBlocks($value)
 * @method static Builder<static>|Page                                  whereSlug($value)
 * @method static Builder<static>|Page                                  whereTitle($value)
 * @method static Builder<static>|Page                                  whereUpdatedAt($value)
 * @method static Builder<static>|Page                                  whereUpdatedBy($value)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static static|null                                           first(array|string $columns = ['*'])
 * @method static static|null                                           firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static Builder<static>|Page                                  delete()
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static int                                                   increment($column, $amount = 1, $extra = [])
 * @method static int                                                   decrement($column, $amount = 1, $extra = [])
 * @method static bool                                                  truncate()
 * @method static static                                                destroy($ids)
 * @method static static                                                restore()
 * @method static static                                                forceDelete()
 * @method static static                                                onlyTrashed()
 * @method static static                                                withTrashed()
 * @method static static                                                withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static static|null                                           first($columns = ['*'])
 * @method static static|null                                           find($id, $columns = ['*'])
 * @property ProfileContract|null $deleter
 * @method static PageFactory factory($count = null, $state = [])
 * @property array<array-key, mixed>|null $blocks
 * @method static Builder<static>|Page                        whereBlocks($value)
 * @method array<int, array<string, mixed>>            getSushiRows()
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @method static array<int, string>                          getMiddlewareBySlug(string $slug)
 * @method static PageFactory                                 factory($count = null, $state = [])
 * @method static Builder<static>|Page                        newModelQuery()
 * @method static Builder<static>|Page                        newQuery()
 * @method static Builder<static>|Page                        query()
 * @method static Builder<static>|Page                        whereBlocks($value)
 * @method static Builder<static>|Page                        whereContent($value)
 * @method static Builder<static>|Page                        whereContentBlocks($value)
 * @method static Builder<static>|Page                        whereCreatedAt($value)
 * @method static Builder<static>|Page                        whereCreatedBy($value)
 * @method static Builder<static>|Page                        whereDescription($value)
 * @method static Builder<static>|Page                        whereFooterBlocks($value)
 * @method static Builder<static>|Page                        whereId($value)
 * @method static Builder<static>|Page                        whereMiddleware($value)
 * @method static Builder<static>|Page                        whereSidebarBlocks($value)
 * @method static Builder<static>|Page                        whereSlug($value)
 * @method static Builder<static>|Page                        whereTitle($value)
 * @method static Builder<static>|Page                        whereUpdatedAt($value)
 * @method static Builder<static>|Page                        whereUpdatedBy($value)
 * @method static Builder<static>|Page                        whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                        whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                        whereLocale(string $column, string $locale)
 * @method static Builder<static>|Page                        whereLocales(string $column, array<int, string> $locales)
 * @method static Collection<int, static>                     all($columns = ['*'])
 * @method static Collection<int, static>                     get($columns = ['*'])
 * @method static static|null                                 first($columns = ['*'])
 * @method static static|null                                 find($id, $columns = ['*'])
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @method static array<int, string>                          getMiddlewareBySlug(string $slug)
 * @method array<int, array<string, mixed>>            getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Page extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\PageContent.
 *
 * @property string                       $id
 * @property array<array-key, mixed>|null $name
 * @property string|null                  $slug
 * @property array<array-key, mixed>|null $blocks
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property ProfileContract|null         $creator
 * @property mixed                        $translations
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|PageContent newModelQuery()
 * @method static Builder<static>|PageContent newQuery()
 * @method static Builder<static>|PageContent query()
 * @method static Builder<static>|PageContent whereBlocks($value)
 * @method static Builder<static>|PageContent whereCreatedAt($value)
 * @method static Builder<static>|PageContent whereCreatedBy($value)
 * @method static Builder<static>|PageContent whereId($value)
 * @method static Builder<static>|PageContent whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|PageContent whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|PageContent whereLocale(string $column, string $locale)
 * @method static Builder<static>|PageContent whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|PageContent whereName($value)
 * @method static Builder<static>|PageContent whereSlug($value)
 * @method static Builder<static>|PageContent whereUpdatedAt($value)
 * @method static Builder<static>|PageContent whereUpdatedBy($value)
 * @method static int                         count()
 * @property ProfileContract|null $deleter
 * @method static PageContentFactory               factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class PageContent extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Section.
 *
 * @property string $id
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @property string                       $id
 * @property array<array-key, mixed>|null $name
 * @property string|null                  $slug
 * @property array<array-key, mixed>|null $blocks
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property ProfileContract|null         $creator
 * @property mixed                        $translations
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|Section newModelQuery()
 * @method static Builder<static>|Section newQuery()
 * @method static Builder<static>|Section query()
 * @method static Builder<static>|Section whereBlocks($value)
 * @method static Builder<static>|Section whereCreatedAt($value)
 * @method static Builder<static>|Section whereCreatedBy($value)
 * @method static Builder<static>|Section whereId($value)
 * @method static Builder<static>|Section whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Section whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Section whereLocale(string $column, string $locale)
 * @method static Builder<static>|Section whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Section whereName($value)
 * @method static Builder<static>|Section whereSlug($value)
 * @method static Builder<static>|Section whereUpdatedAt($value)
 * @method static Builder<static>|Section whereUpdatedBy($value)
 * @method static int                     count()
 * @method static Builder<static>|Section where($column, $operator = null, $value = null, $boolean = 'and')
 * @property ProfileContract|null $deleter
 * @method static SectionFactory                   factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Section extends \Eloquent {}
}

namespace Modules\Comment\Models{
/**
 * @property int $id
 * @property string $original_text
 * @property string|null $text
 * @property string|null $commentator_type
 * @property int|string|null $commentator_id
 * @property string|null $commentable_type
 * @property int|string|null $commentable_id
 * @property int|null $parent_id
 * @property Carbon|null $approved_at
 * @property array<mixed, mixed>|null $extra
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Model|null $commentable
 * @property-read Model|null $commentator
 * @method static CommentFactory factory($count = null, $state = [])
 * @method static Builder<Comment> newModelQuery()
 * @method static Builder<Comment> newQuery()
 * @method static Builder<Comment> query()
 * @method static Builder<Comment> approved()
 * @method static Builder<Comment> pending()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $nestedComments
 * @property-read int|null $nested_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Comment\Models\CommentNotificationSubscription> $notificationSubscriptions
 * @property-read int|null $notification_subscriptions_count
 * @property-read Comment|null $parentComment
 * @property-read \Modules\Comment\Models\Collections\ReactionCollection<int, \Modules\Comment\Models\Reaction> $reactions
 * @property-read int|null $reactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment topLevel()
 */
	class Comment extends \Eloquent implements \Modules\Comment\Models\Contracts\Commentable, \Modules\Comment\Models\Contracts\SupportsCommentNotifications {}
}

namespace Modules\Comment\Models{
/**
 * @property int|string|null $commentator_id
 * @property string|null $commentator_type
 * @property-read Model|null $commentable
 * @property-read Model|null $commentator
 * @method static CommentNotificationOptOutFactory factory($count = null, $state = [])
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> query()
 * @property-read \Modules\User\Models\User|null $user
 */
	class CommentNotificationOptOut extends \Eloquent {}
}

namespace Modules\Comment\Models{
/**
 * @property int $id
 * @property string $commentable_type
 * @property int|string $commentable_id
 * @property string $subscriber_type
 * @property int|string $subscriber_id
 * @property NotificationSubscriptionType $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|null $commentable
 * @property-read Model|null $subscriber
 * @method static CommentNotificationSubscriptionFactory factory($count = null, $state = [])
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> query()
 */
	class CommentNotificationSubscription extends \Eloquent {}
}

namespace Modules\Comment\Models{
/**
 * @property int $id
 * @property string|null $commentator_type
 * @property int|null $commentator_id
 * @property int $comment_id
 * @property string $reaction
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static ReactionFactory factory($count = null, $state = [])
 * @method static Builder|Reaction newModelQuery()
 * @method static Builder|Reaction newQuery()
 * @method static Builder|Reaction query()
 * @method static Builder|Reaction whereCommentId($value)
 * @method static Builder|Reaction whereCommentatorId($value)
 * @method static Builder|Reaction whereCommentatorType($value)
 * @method static Builder|Reaction whereCreatedAt($value)
 * @method static Builder|Reaction whereCreatedBy($value)
 * @method static Builder|Reaction whereDeletedAt($value)
 * @method static Builder|Reaction whereDeletedBy($value)
 * @method static Builder|Reaction whereId($value)
 * @method static Builder|Reaction whereReaction($value)
 * @method static Builder|Reaction whereUpdatedAt($value)
 * @method static Builder|Reaction whereUpdatedBy($value)
 * @property-read Comment|null $comment
 * @property-read Model|\Eloquent $commentator
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static Reaction|null first()
 * @method static Reaction create(array<string, mixed> $attributes = [])
 * @method static Reaction firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Reaction where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Reaction whereNotNull((string|Expression) $columns)
 * @method static int count(string $columns = '*')
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Reaction extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property int $id
 * @property int $heating_system_id
 * @property string|null $brand
 * @property string|null $model
 * @property string|null $serial_number
 * @property string|null $generator_type
 * @property string|null $fuel_type
 * @property string|null $power_kw
 * @property CarbonInterface|null $installation_date
 * @property int|null $maintenance_interval_months
 * @property int|null $efficiency_check_interval_months
 * @property CarbonInterface|null $next_maintenance_due_on
 * @property CarbonInterface|null $next_efficiency_check_due_on
 * @property string|null $notes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\MaintenanceControl> $controls
 * @property-read int|null $controls_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Compliance\Models\HeatingSystem|null $heatingSystem
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\BoilerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Boiler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Boiler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Boiler onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Boiler query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Boiler withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Boiler withoutTrashed()
 */
	final class Boiler extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $status
 * @property bool $is_required
 * @property Carbon|null $issued_on
 * @property Carbon|null $expires_on
 * @property int|null $work_order_id
 * @property int|null $customer_id
 * @property int|null $employee_id
 * @property int|null $vehicle_id
 * @property int|null $subcontractor_id
 * @property int|null $equipment_id
 * @property int|null $media_id
 * @property string|null $uploaded_by_user_id
 * @property string|null $extracted_text
 * @property array<string, mixed>|null $ai_classification
 * @property array<string, mixed>|null $ai_extracted_fields
 * @property Carbon|null $ai_processed_at
 * @property array<string, mixed>|null $meta
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\HR\Models\Employee|null $employee
 * @property-read \Modules\Compliance\Models\Equipment|null $equipment
 * @property-read \Modules\Document\Models\Media|null $media
 * @property-read \Modules\Compliance\Models\Subcontractor|null $subcontractor
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $uploadedBy
 * @property-read \Modules\Vehicle\Models\Vehicle|null $vehicle
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\Compliance\Database\Factories\ComplianceDocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceDocument onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceDocument withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceDocument withoutTrashed()
 */
	final class ComplianceDocument extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * Impianto installato presso un cliente (videosorveglianza, quadro elettrico, fotovoltaico).
 *
 * Gli schemi/allegati sono {@see Media} polimorfici (kind = "schema").
 *
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property string $system_type
 * @property string|null $description
 * @property string|null $brand
 * @property string|null $model
 * @property string|null $serial_number
 * @property string|null $power_kw
 * @property CarbonInterface|null $installation_date
 * @property bool $is_active
 * @property string|null $notes
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Document\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\CustomerSystemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSystem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSystem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSystem withoutTrashed()
 */
	final class CustomerSystem extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $type
 * @property string|null $serial_number
 * @property string $status
 * @property Carbon|null $last_checked_on
 * @property Carbon|null $next_check_due_on
 * @property Carbon|null $maintenance_due_on
 * @property int|null $assigned_work_order_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $assignedWorkOrder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\ComplianceDocument> $complianceDocuments
 * @property-read int|null $compliance_documents_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\EquipmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipment withoutTrashed()
 */
	final class Equipment extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * One recorded leak check on a refrigerant circuit.
 *
 * @property-read \Modules\Compliance\Models\RefrigerantCircuit|null $circuit
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\FGasLeakCheckFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FGasLeakCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FGasLeakCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FGasLeakCheck query()
 */
	final class FGasLeakCheck extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property string|null $description
 * @property string|null $fuel_type
 * @property string|null $total_power_kw
 * @property string|null $plant_registry_code
 * @property CarbonInterface|null $installation_date
 * @property string|null $notes
 * @property bool $is_active
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\Boiler> $boilers
 * @property-read int|null $boilers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\MaintenanceControl> $controls
 * @property-read int|null $controls_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\HeatingSystemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeatingSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeatingSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeatingSystem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeatingSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeatingSystem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeatingSystem withoutTrashed()
 */
	final class HeatingSystem extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property int $id
 * @property int $heating_system_id
 * @property int|null $boiler_id
 * @property string $control_type
 * @property CarbonInterface $performed_on
 * @property string|null $performed_by_user_id
 * @property int|null $intervention_id
 * @property string|null $rcee_type
 * @property string|null $outcome
 * @property string|null $co_ppm
 * @property string|null $co2_percent
 * @property string|null $o2_percent
 * @property string|null $flue_gas_temp_c
 * @property string|null $air_temp_c
 * @property string|null $combustion_efficiency_percent
 * @property int|null $smoke_index
 * @property string|null $draft_pa
 * @property string|null $bollino_code
 * @property bool $bollino_paid
 * @property CarbonInterface|null $next_due_on
 * @property string|null $notes
 * @property-read \Modules\Compliance\Models\Boiler|null $boiler
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Compliance\Models\HeatingSystem|null $heatingSystem
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\MaintenanceControlFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceControl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceControl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceControl query()
 */
	final class MaintenanceControl extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * A sealed refrigerant circuit subject to the F-Gas leak-check regime.
 *
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Compliance\Models\HeatingSystem|null $heatingSystem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\FGasLeakCheck> $leakChecks
 * @property-read int|null $leak_checks_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\RefrigerantCircuitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefrigerantCircuit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefrigerantCircuit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefrigerantCircuit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefrigerantCircuit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefrigerantCircuit withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefrigerantCircuit withoutTrashed()
 */
	final class RefrigerantCircuit extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\ComplianceDocument> $complianceDocuments
 * @property-read int|null $compliance_documents_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Compliance\Database\Factories\SubcontractorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcontractor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcontractor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcontractor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcontractor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcontractor withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcontractor withoutTrashed()
 */
	final class Subcontractor extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property int $id
 * @property int $work_order_id
 * @property string|null $requested_by_user_id
 * @property string|null $approved_by_user_id
 * @property string $title
 * @property string|null $description
 * @property string|null $estimated_amount
 * @property string $status
 * @property Carbon|null $approved_at
 * @property Carbon|null $executed_at
 * @property Carbon|null $billed_at
 * @property int|null $signature_media_id
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\User\Models\User|null $approvedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\User\Models\User|null $requestedBy
 * @property-read \Modules\Document\Models\Media|null $signatureMedia
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\Compliance\Database\Factories\WorkOrderChangeOrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderChangeOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderChangeOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderChangeOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderChangeOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderChangeOrder withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderChangeOrder withoutTrashed()
 */
	final class WorkOrderChangeOrder extends \Eloquent {}
}

namespace Modules\Compliance\Models{
/**
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\Compliance\Database\Factories\WorkOrderReadinessItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderReadinessItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderReadinessItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderReadinessItem query()
 */
	final class WorkOrderReadinessItem extends \Eloquent {}
}

namespace Modules\Costing\Models{
/**
 * Un centro di lavoro di un modulo di preventivazione: dove si esegue una riga di
 * distinta e a che margine si vende. Interrogarlo sempre da {@see scopeForContext()}
 * — la tabella e' condivisa e una lettura senza filtro mescolerebbe le officine di
 * due aziende diverse.
 *
 * @property int $id
 * @property string $context_code
 * @property string $code
 * @property string $name
 * @property bool $is_internal
 * @property numeric-string $hourly_rate
 * @property numeric-string|null $default_markup_percent
 * @property int $sort_order
 * @property bool $is_active
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Costing\Database\Factories\WorkCenterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkCenter forContext(string $contextCode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkCenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkCenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkCenter query()
 */
	final class WorkCenter extends \Eloquent {}
}

namespace Modules\Customer\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_role_id
 * @property string $full_name
 * @property string|null $email
 * @property string|null $phone
 * @method static \Modules\Customer\Database\Factories\ContactFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Customer\Models\ContactRole|null $role
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact withoutTrashed()
 */
	final class Contact extends \Eloquent {}
}

namespace Modules\Customer\Models{
/**
 * @property int $id
 * @property string $name
 * @method static \Modules\Customer\Database\Factories\ContactRoleFactory factory($count = null, $state = [])
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Customer\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactRole query()
 */
	final class ContactRole extends \Eloquent {}
}

namespace Modules\Customer\Models{
/**
 * @property int $id
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $tax_code
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $city
 * @property bool $is_active
 * @method static CustomerFactory factory($count = null, $state = [])
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Customer\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Customer\Models\CustomerSite> $sites
 * @property-read int|null $sites_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\WorkOrder\Models\WorkOrder> $workOrders
 * @property-read int|null $work_orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withoutTrashed()
 */
	final class Customer extends \Eloquent {}
}

namespace Modules\Customer\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property string|null $name
 * @method static CustomerSiteFactory factory($count = null, $state = [])
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Customer\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSite withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSite withoutTrashed()
 */
	final class CustomerSite extends \Eloquent {}
}

namespace Modules\Customer\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $notes
 * @method static \Modules\Customer\Database\Factories\ResellerFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reseller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reseller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reseller onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reseller query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reseller withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reseller withoutTrashed()
 */
	final class Reseller extends \Eloquent {}
}

namespace Modules\Document\Models{
/**
 * @property int $id
 * @property string $mediable_type
 * @property int $mediable_id
 * @property string|null $uploaded_by_user_id
 * @property string $kind
 * @property string|null $title
 * @property string $disk
 * @property string $path
 * @property string|null $mime_type
 * @property int|null $size_bytes
 * @property array<string, mixed>|null $meta
 * @method static \Modules\Document\Database\Factories\MediaFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $mediable
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $uploadedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media withoutTrashed()
 */
	final class Media extends \Eloquent {}
}

namespace Modules\Email\Models{
/**
 * @property int $id
 * @property string $email
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\Email\Database\Factories\EmailContactIdentityFactory factory($count = null, $state = [])
 * @property-read \Modules\Customer\Models\Contact|null $contact
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailContactIdentity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailContactIdentity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailContactIdentity query()
 */
	final class EmailContactIdentity extends \Eloquent {}
}

namespace Modules\Email\Models{
/**
 * @property int $id
 * @property string $email
 * @property string|null $display_name
 * @property int|null $identity_id
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property string $status
 * @property Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\Email\Database\Factories\EmailConversationFactory factory($count = null, $state = [])
 * @property-read \Modules\Customer\Models\Contact|null $contact
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Email\Models\EmailContactIdentity|null $identity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Email\Models\EmailMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailConversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailConversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailConversation query()
 */
	final class EmailConversation extends \Eloquent {}
}

namespace Modules\Email\Models{
/**
 * @property int $id
 * @property int $conversation_id
 * @property int|null $work_order_id
 * @property int|null $intervention_id
 * @property int|null $ai_action_proposal_id
 * @property string|null $external_message_id
 * @property string $direction
 * @property string|null $from_email
 * @property string|null $from_name
 * @property string|null $to_email
 * @property string|null $subject
 * @property string|null $body
 * @property string $status
 * @property string $processing_status
 * @property array<string, mixed>|null $payload
 * @property array<string, mixed>|null $ai_result
 * @property string|null $error
 * @property Carbon|null $received_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $processed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\Email\Database\Factories\EmailMessageFactory factory($count = null, $state = [])
 * @property-read \Modules\Email\Models\EmailConversation|null $conversation
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Intervention\Models\Intervention|null $intervention
 * @property-read \Modules\AiAssistant\Models\AiActionProposal|null $proposal
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailMessage query()
 */
	final class EmailMessage extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class AbsenceRequest.
 *
 * Richiesta di assenza (ferie/permesso/malattia/infortunio) di un dipendente,
 * soggetta ad approvazione/rifiuto da parte di un responsabile.
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property string|null $notes
 * @property string $status
 * @property string|null $decided_by_user_id
 * @property Carbon|null $decided_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Employee|null $user
 * @property-read Employee|null $decidedBy
 * @method static AbsenceRequestFactory factory($count = null, $state = [])
 * @method static Builder<static>|AbsenceRequest newModelQuery()
 * @method static Builder<static>|AbsenceRequest newQuery()
 * @method static Builder<static>|AbsenceRequest query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest forUser(string|int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest withoutTrashed()
 */
	class AbsenceRequest extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class Admin
 *
 * NOTA: Il trait HasFactory è stato rimosso perché già incluso nella catena di ereditarietà (BaseUser -> User -> Admin).
 * Dichiararlo qui è ridondante e può causare warning o confusione.
 * Vedi docs/DRY-model-traits.md
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $date_of_birth
 *                                      Employee Module Admin Model
 *
 * Admin user type using Single Table Inheritance with Parental package.
 * Child class of User model for administrative users.
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string|null $first_name
 * @property string|null $last_name
 * @property Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Modules\User\Models\User|null $user
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereAddress($value)
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereCreatedBy($value)
 * @method static Builder|Admin whereDateOfBirth($value)
 * @method static Builder|Admin whereGender($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin wherePhone($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @method static Builder|Admin whereUpdatedBy($value)
 * @method static Builder|Admin whereUserId($value)
 * @property string|null $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $city
 * @property string|null $registration_number
 * @property string|null $status
 * @property array<array-key, mixed>|null $certifications
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $state
 * @property array<array-key, mixed>|null $moderation_data
 * @property string|null $lang
 * @property string|null $type
 * @property bool $is_active
 * @property bool $is_otp
 * @property \Illuminate\Support\Carbon|null $password_expires_at
 * @property string|null $uuid
 * @property string|null $full_name
 * @property string|null $deleted_by
 * @property-read Collection<int, Consent> $activeConsents
 * @property-read int|null $active_consents_count
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, Authentication> $authentications
 * @property-read int|null $authentications_count
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, Consent> $consents
 * @property-read int|null $consents_count
 * @property-read Team|null $currentTeam
 * @property-read Collection<int, Model> $all_team_users
 * @property-read AuthenticationLog|null $latestAuthentication
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read ProfileContract|null $profile
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, SocialiteUser> $socialiteUsers
 * @property-read int|null $socialite_users_count
 * @property-read mixed|null $pivot
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection<int, Treatment> $treatments
 * @property-read int|null $treatments_count
 * @method static Builder<static>|Admin admins()
 * @method static Builder<static>|Admin doctors()
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|Admin orWhereNotState(string $column, $states)
 * @method static Builder<static>|Admin orWhereState(string $column, $states)
 * @method static Builder<static>|Admin patients()
 * @method static Builder<static>|Admin permission($permissions, $without = false)
 * @method static Builder<static>|Admin role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Admin whereCertifications($value)
 * @method static Builder<static>|Admin whereCity($value)
 * @method static Builder<static>|Admin whereCurrentTeamId($value)
 * @method static Builder<static>|Admin whereDeletedAt($value)
 * @method static Builder<static>|Admin whereDeletedBy($value)
 * @method static Builder<static>|Admin whereEmail($value)
 * @method static Builder<static>|Admin whereEmailVerifiedAt($value)
 * @method static Builder<static>|Admin whereFirstName($value)
 * @method static Builder<static>|Admin whereFullName($value)
 * @method static Builder<static>|Admin whereIsActive($value)
 * @method static Builder<static>|Admin whereIsOtp($value)
 * @method static Builder<static>|Admin whereLang($value)
 * @method static Builder<static>|Admin whereLastName($value)
 * @method static Builder<static>|Admin whereModerationData($value)
 * @method static Builder<static>|Admin whereName($value)
 * @method static Builder<static>|Admin whereNotState(string $column, $states)
 * @method static Builder<static>|Admin wherePassword($value)
 * @method static Builder<static>|Admin wherePasswordExpiresAt($value)
 * @method static Builder<static>|Admin whereProfilePhotoPath($value)
 * @method static Builder<static>|Admin whereRegistrationNumber($value)
 * @method static Builder<static>|Admin whereRememberToken($value)
 * @method static Builder<static>|Admin whereState($value)
 * @method static Builder<static>|Admin whereStatus($value)
 * @method static Builder<static>|Admin whereType($value)
 * @method static Builder<static>|Admin whereUuid($value)
 * @method static Builder<static>|Admin withoutPermission($permissions)
 * @method static Builder<static>|Admin withoutRole($roles, $guard = null)
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property string|null $dental_problems
 * @property string|null $last_dental_visit
 * @property string|null $pregnancy_certificate
 * @property string|null $isee_certificate
 * @property string|null $identity_document
 * @property string|null $health_card
 * @property string|null $certificates
 * @property-read Collection<int, Membership> $teamUsers
 * @property-read int|null $team_users_count
 * @method static Builder<static>|Admin whereCertificates($value)
 * @method static Builder<static>|Admin whereDentalProblems($value)
 * @method static Builder<static>|Admin whereHealthCard($value)
 * @method static Builder<static>|Admin whereIdentityDocument($value)
 * @method static Builder<static>|Admin whereIseeCertificate($value)
 * @method static Builder<static>|Admin whereLastDentalVisit($value)
 * @method static Builder<static>|Admin wherePregnancyCertificate($value)
 * @property string|null $country_code
 * @property string|null $children_count
 * @property string|null $family_members
 * @property string|null $years_in_italy
 * @property string|null $nationality
 * @property string|null $fiscal_code
 * @property string|null $data_privacy_form
 * @property string|null $doctor_certificate
 * @property array<array-key, mixed>|null $certification
 * @property string|null $last_dental_visit_period
 * @method static Builder<static>|Admin whereCertification($value)
 * @method static Builder<static>|Admin whereChildrenCount($value)
 * @method static Builder<static>|Admin whereCountryCode($value)
 * @method static Builder<static>|Admin whereDataPrivacyForm($value)
 * @method static Builder<static>|Admin whereDoctorCertificate($value)
 * @method static Builder<static>|Admin whereFamilyMembers($value)
 * @method static Builder<static>|Admin whereFiscalCode($value)
 * @method static Builder<static>|Admin whereLastDentalVisitPeriod($value)
 * @method static Builder<static>|Admin whereNationality($value)
 * @method static Builder<static>|Admin whereYearsInItaly($value)
 * @property string|null $age_range
 * @method static Builder<static>|Admin whereAgeRange($value)
 * @property-read Collection<int, Tenant> $tenants
 * @property-read int|null $tenants_count
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Team> $membershipTeams
 * @property-read int|null $membership_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\OauthClient> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin childrenWith(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin childrenWithCount(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withoutTeam($teams)
 */
	class Admin extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class Department.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $manager_id
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Employee> $employees
 * @property-read int|null $employees_count
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $deleter
 * @property-read ProfileContract|null $updater
 * @method static DepartmentFactory factory($count = null, $state = [])
 * @method static Builder<static>|Department newModelQuery()
 * @method static Builder<static>|Department newQuery()
 * @method static Builder<static>|Department query()
 * @mixin \Eloquent
 */
	class Department extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class Employee.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $employee_code
 * @property array<string, mixed> $personal_data
 * @property array<string, mixed> $contact_data
 * @property array<string, mixed> $work_data
 * @property array<string, mixed> $documents
 * @property string|null $photo_url
 * @property string $status
 * @property int|null $department_id
 * @property int|null $manager_id
 * @property int|null $position_id
 * @property array<string, mixed> $salary_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $user
 * @property-read Collection<int, WorkHour> $workHours
 * @property string|null $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string|null $deleted_at
 * @property string|null $lang
 * @property int $is_active
 * @property int $is_otp
 * @property string|null $password_expires_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property string|null $type
 * @property-read Collection<int, Consent> $activeConsents
 * @property-read int|null $active_consents_count
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, AuthenticationLog> $authentications
 * @property-read int|null $authentications_count
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, Consent> $consents
 * @property-read int|null $consents_count
 * @property-read Team|null $currentTeam
 * @property-read TenantUser|Membership|DeviceUser|null $pivot
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property-read Collection<int, \Modules\User\Models\User> $all_team_users
 * @property-read string|null $full_name
 * @property-read string $status_label
 * @property-read AuthenticationLog|null $latestAuthentication
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read ProfileContract|null $profile
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, SocialiteUser> $socialiteUsers
 * @property-read int|null $socialite_users_count
 * @property-read int|null $subordinates_count
 * @property-read Collection<int, Membership> $teamUsers
 * @property-read int|null $team_users_count
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, Domain> $tenants
 * @property-read int|null $tenants_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection<int, Treatment> $treatments
 * @property-read int|null $treatments_count
 * @property-read int|null $work_hours_count
 * @method static EmployeeFactory factory($count = null, $state = [])
 * @method static Builder<static>|Employee newModelQuery()
 * @method static Builder<static>|Employee newQuery()
 * @method static Builder<static>|Employee orWhereNotState(string $column, $states)
 * @method static Builder<static>|Employee orWhereState(string $column, $states)
 * @method static Builder<static>|Employee permission($permissions, $without = false)
 * @method static Builder<static>|Employee query()
 * @method static Builder<static>|Employee role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Employee whereCreatedAt($value)
 * @method static Builder<static>|Employee whereCreatedBy($value)
 * @method static Builder<static>|Employee whereCurrentTeamId($value)
 * @method static Builder<static>|Employee whereDeletedAt($value)
 * @method static Builder<static>|Employee whereDeletedBy($value)
 * @method static Builder<static>|Employee whereEmail($value)
 * @method static Builder<static>|Employee whereEmailVerifiedAt($value)
 * @method static Builder<static>|Employee whereFirstName($value)
 * @method static Builder<static>|Employee whereId($value)
 * @method static Builder<static>|Employee whereIsActive($value)
 * @method static Builder<static>|Employee whereIsOtp($value)
 * @method static Builder<static>|Employee whereLang($value)
 * @method static Builder<static>|Employee whereLastName($value)
 * @method static Builder<static>|Employee whereName($value)
 * @method static Builder<static>|Employee whereNotState(string $column, $states)
 * @method static Builder<static>|Employee wherePassword($value)
 * @method static Builder<static>|Employee wherePasswordExpiresAt($value)
 * @method static Builder<static>|Employee whereProfilePhotoPath($value)
 * @method static Builder<static>|Employee whereRememberToken($value)
 * @method static Builder<static>|Employee whereState(string $column, $states)
 * @method static Builder<static>|Employee whereType($value)
 * @method static Builder<static>|Employee whereUpdatedAt($value)
 * @method static Builder<static>|Employee whereUpdatedBy($value)
 * @method static Builder<static>|Employee withoutPermission($permissions)
 * @method static Builder<static>|Employee withoutRole($roles, $guard = null)
 * @property-read Employee|null $manager
 * @property-read Collection<int, Employee> $subordinates
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Team> $membershipTeams
 * @property-read int|null $membership_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\OauthClient> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee childrenWith(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee childrenWithCount(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withoutTeam($teams)
 */
	class Employee extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class Position.
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $department
 * @property int|null $level
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Employee> $employees
 * @property-read int|null $employees_count
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $deleter
 * @property-read ProfileContract|null $updater
 * @method static PositionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Position newModelQuery()
 * @method static Builder<static>|Position newQuery()
 * @method static Builder<static>|Position query()
 * @mixin \Eloquent
 */
	class Position extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class TimeEntry.
 *
 * @property int $id
 * @property int $employee_id
 * @property Carbon $clock_in
 * @property Carbon|null $clock_out
 * @property Carbon|null $break_start
 * @property Carbon|null $break_end
 * @property int $break_duration
 * @property float|null $total_hours
 * @property float|null $regular_hours
 * @property float|null $overtime_hours
 * @property array<string, mixed>|null $location_in
 * @property array<string, mixed>|null $location_out
 * @property array<string, mixed>|null $device_info
 * @property string|null $notes
 * @property string|null $employee_notes
 * @property string|null $supervisor_notes
 * @property string $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property array<string, mixed>|null $anomalies
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee $employee
 * @property-read Employee|null $approvedBy
 * @property string $type Type of time entry
 * @property string $timestamp Exact time of entry
 * @property numeric|null $location_lat GPS latitude coordinate
 * @property numeric|null $location_lng GPS longitude coordinate
 * @property string|null $location_name Human readable location name
 * @property string|null $photo_path Path to verification photo
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $deleter
 * @property-read ProfileContract|null $updater
 * @method static TimeEntryFactory factory($count = null, $state = [])
 * @method static Builder<static>|TimeEntry forEmployee(int $employeeId)
 * @method static Builder<static>|TimeEntry newModelQuery()
 * @method static Builder<static>|TimeEntry newQuery()
 * @method static Builder<static>|TimeEntry pending()
 * @method static Builder<static>|TimeEntry query()
 * @method static Builder<static>|TimeEntry whereApprovedAt($value)
 * @method static Builder<static>|TimeEntry whereApprovedBy($value)
 * @method static Builder<static>|TimeEntry whereClockIn($value)
 * @method static Builder<static>|TimeEntry whereClockOut($value)
 * @method static Builder<static>|TimeEntry whereBreakStart($value)
 * @method static Builder<static>|TimeEntry whereBreakEnd($value)
 * @method static Builder<static>|TimeEntry whereBreakDuration($value)
 * @method static Builder<static>|TimeEntry whereTotalHours($value)
 * @method static Builder<static>|TimeEntry whereRegularHours($value)
 * @method static Builder<static>|TimeEntry whereOvertimeHours($value)
 * @method static Builder<static>|TimeEntry whereLocationIn($value)
 * @method static Builder<static>|TimeEntry whereLocationOut($value)
 * @method static Builder<static>|TimeEntry whereDeviceInfo($value)
 * @method static Builder<static>|TimeEntry whereNotes($value)
 * @method static Builder<static>|TimeEntry whereEmployeeNotes($value)
 * @method static Builder<static>|TimeEntry whereSupervisorNotes($value)
 * @method static Builder<static>|TimeEntry whereStatus($value)
 * @method static Builder<static>|TimeEntry whereRejectionReason($value)
 * @method static Builder<static>|TimeEntry whereAnomalies($value)
 * @method static Builder<static>|TimeEntry whereCreatedAt($value)
 * @method static Builder<static>|TimeEntry whereUpdatedAt($value)
 * @method static Builder<static>|TimeEntry whereId($value)
 * @method static Builder<static>|TimeEntry whereEmployeeId($value)
 * @method static Builder<static>|TimeEntry whereLocationLat($value)
 * @method static Builder<static>|TimeEntry whereLocationLng($value)
 * @method static Builder<static>|TimeEntry whereLocationName($value)
 * @method static Builder<static>|TimeEntry wherePhotoPath($value)
 * @method static Builder<static>|TimeEntry whereTimestamp($value)
 * @method static Builder<static>|TimeEntry whereType($value)
 * @method static Builder<static>|TimeEntry withAnomalies()
 * @mixin \Eloquent
 */
	final class TimeEntry extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Class TimeRecord.
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon $timestamp
 * @property string $type
 * @property string $method
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $address
 * @property string|null $notes
 * @property string $status
 * @property bool $is_manual
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 * @property-read ProfileContract|null $creator
 * @property-read string $formatted_date
 * @property-read string $formatted_time
 * @property-read string $formatted_timestamp
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|TimeRecord forDate(\Carbon\Carbon $date)
 * @method static Builder<static>|TimeRecord forUser(int|string $userId)
 * @method static Builder<static>|TimeRecord newModelQuery()
 * @method static Builder<static>|TimeRecord newQuery()
 * @method static Builder<static>|TimeRecord ofType(string $type)
 * @method static Builder<static>|TimeRecord query()
 * @method static Builder<static>|TimeRecord valid()
 * @property-read ProfileContract|null $deleter
 * @method static TimeRecordFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class TimeRecord extends \Eloquent {}
}

namespace Modules\Employee\Models{
/**
 * Employee Module User Model
 *
 * Extends BaseUser with Single Table Inheritance for Employee module.
 * Parent class for Admin and Employee models using Parental STI.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string|null $first_name
 * @property string|null $last_name
 * @property Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $city
 * @property string|null $phone
 * @property string|null $lang
 * @property int|null $current_team_id
 * @property bool $is_active
 * @property bool $is_otp
 * @property Carbon|null $password_expires_at
 * @property Carbon|null $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $remember_token
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property-read Collection<int, Consent> $activeConsents
 * @property-read int|null $active_consents_count
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, AuthenticationLog> $authentications
 * @property-read int|null $authentications_count
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, Consent> $consents
 * @property-read int|null $consents_count
 * @property-read Team|null $currentTeam
 * @property-read TenantUser|Membership|DeviceUser|null $pivot
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property-read Collection<int, User> $all_team_users
 * @property-read string $full_name
 * @property-read AuthenticationLog|null $latestAuthentication
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read ProfileContract|null $profile
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, SocialiteUser> $socialiteUsers
 * @property-read int|null $socialite_users_count
 * @property-read Collection<int, Membership> $teamUsers
 * @property-read int|null $team_users_count
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, Tenant> $tenants
 * @property-read int|null $tenants_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection<int, Treatment> $treatments
 * @property-read int|null $treatments_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User orWhereNotState(string $column, $states)
 * @method static Builder<static>|User orWhereState(string $column, $states)
 * @method static Builder<User> permission($permissions, $without = false)
 * @method static Builder<User> query()
 * @method static Builder<User> role($roles, $guard = null, $without = false)
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereCreatedBy($value)
 * @method static Builder<static>|User whereCurrentTeamId($value)
 * @method static Builder<static>|User whereDeletedAt($value)
 * @method static Builder<static>|User whereDeletedBy($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereFirstName($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereIsActive($value)
 * @method static Builder<static>|User whereIsOtp($value)
 * @method static Builder<static>|User whereLang($value)
 * @method static Builder<static>|User whereLastName($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User whereNotState(string $column, $states)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User wherePasswordExpiresAt($value)
 * @method static Builder<static>|User whereProfilePhotoPath($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereState(string $column, $states)
 * @method static Builder<static>|User whereType($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @method static Builder<static>|User whereUpdatedBy($value)
 * @method static Builder<User> withoutPermission($permissions)
 * @method static Builder<User> withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Team> $membershipTeams
 * @property-read int|null $membership_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\OauthClient> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User childrenWith(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User childrenWithCount(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTeam($teams)
 */
	class User extends \Eloquent implements \Spatie\ModelStates\HasStatesContract {}
}

namespace Modules\Employee\Models{
/**
 * Class WorkHour.
 *
 * @property int $id
 * @property int $employee_id
 * @property WorkHourTypeEnum $type
 * @property Carbon $timestamp
 * @property float|null $location_lat
 * @property float|null $location_lng
 * @property string|null $location_name
 * @property array<string, mixed>|null $device_info
 * @property string|null $photo_path
 * @property string|null $notes
 * @property WorkHourStatusEnum $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee $employee
 * @property-read User|null $approvedBy
 * @property-read ProfileContract|null $creator
 * @property-read string $formatted_date
 * @property-read string $formatted_date_time
 * @property-read string $formatted_time
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|WorkHour forDate(\Carbon\Carbon $date)
 * @method static Builder<static>|WorkHour forEmployee(int $employeeId)
 * @method static Builder<static>|WorkHour newModelQuery()
 * @method static Builder<static>|WorkHour newQuery()
 * @method static Builder<static>|WorkHour ofType(string $type)
 * @method static Builder<static>|WorkHour query()
 * @method static Builder<static>|WorkHour today()
 * @method static Builder<static>|WorkHour whereApprovedAt($value)
 * @method static Builder<static>|WorkHour whereApprovedBy($value)
 * @method static Builder<static>|WorkHour whereCreatedAt($value)
 * @method static Builder<static>|WorkHour whereDeviceInfo($value)
 * @method static Builder<static>|WorkHour whereEmployeeId($value)
 * @method static Builder<static>|WorkHour whereId($value)
 * @method static Builder<static>|WorkHour whereLocationLat($value)
 * @method static Builder<static>|WorkHour whereLocationLng($value)
 * @method static Builder<static>|WorkHour whereLocationName($value)
 * @method static Builder<static>|WorkHour whereNotes($value)
 * @method static Builder<static>|WorkHour wherePhotoPath($value)
 * @method static Builder<static>|WorkHour whereStatus($value)
 * @method static Builder<static>|WorkHour whereTimestamp($value)
 * @method static Builder<static>|WorkHour whereType($value)
 * @method static Builder<static>|WorkHour whereUpdatedAt($value)
 * @property-read ProfileContract|null $deleter
 * @method static WorkHourFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class WorkHour extends \Eloquent {}
}

namespace Modules\EnergyBroker\Models{
/**
 * @property int $id
 * @property int $energy_broker_document_id
 * @property int|null $customer_id
 * @property string $score
 * @property string $status
 * @property array<array-key, mixed>|null $matched_on
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\EnergyBroker\Models\EnergyBrokerDocument|null $document
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\EnergyBroker\Database\Factories\EnergyBrokerCustomerMatchFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerCustomerMatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerCustomerMatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerCustomerMatch query()
 */
	final class EnergyBrokerCustomerMatch extends \Eloquent {}
}

namespace Modules\EnergyBroker\Models{
/**
 * @property int $id
 * @property string|null $public_id
 * @property int|null $energy_broker_import_batch_id
 * @property int|null $customer_id
 * @property int|null $media_id
 * @property string $source_file_name
 * @property string|null $file_hash
 * @property string $document_type
 * @property string|null $service_type
 * @property string|null $supplier_name
 * @property string $analysis_status
 * @property string|null $confidence_score
 * @property string|null $raw_text
 * @property array<array-key, mixed>|null $analysis_payload
 * @property array<array-key, mixed>|null $normalized_payload
 * @property array<array-key, mixed>|null $reviewed_payload
 * @property Carbon|null $processed_at
 * @property Carbon|null $imported_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\EnergyBroker\Models\EnergyBrokerImportBatch|null $batch
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\EnergyBroker\Models\EnergyBrokerCustomerMatch> $customerMatches
 * @property-read int|null $customer_matches_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Document\Models\Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\EnergyBroker\Database\Factories\EnergyBrokerDocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerDocument onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerDocument withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerDocument withoutTrashed()
 */
	final class EnergyBrokerDocument extends \Eloquent {}
}

namespace Modules\EnergyBroker\Models{
/**
 * @property int $id
 * @property string|null $uploaded_by_user_id
 * @property string $source_name
 * @property string $source_type
 * @property string $status
 * @property int $total_files
 * @property int $processed_files
 * @property array<array-key, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\EnergyBroker\Models\EnergyBrokerDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $uploadedBy
 * @method static \Modules\EnergyBroker\Database\Factories\EnergyBrokerImportBatchFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerImportBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerImportBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerImportBatch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerImportBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerImportBatch withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerImportBatch withoutTrashed()
 */
	final class EnergyBrokerImportBatch extends \Eloquent {}
}

namespace Modules\EnergyBroker\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string|null $uploaded_by_user_id
 * @property int|null $energy_broker_import_batch_id
 * @property string $original_name
 * @property string|null $mime_type
 * @property int $total_size
 * @property int $chunk_size
 * @property int $total_chunks
 * @property int $received_chunks
 * @property string $status
 * @property string|null $stored_path
 * @property string|null $file_hash
 * @property array<array-key, mixed>|null $received_chunk_indexes
 * @property array<array-key, mixed>|null $meta
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\EnergyBroker\Models\EnergyBrokerImportBatch|null $batch
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\EnergyBroker\Database\Factories\EnergyBrokerLargeUploadFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerLargeUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerLargeUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerLargeUpload onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerLargeUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerLargeUpload withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerLargeUpload withoutTrashed()
 */
	final class EnergyBrokerLargeUpload extends \Eloquent {}
}

namespace Modules\EnergyBroker\Models{
/**
 * @property int $id
 * @property int $energy_broker_supply_point_id
 * @property int|null $energy_broker_document_id
 * @property string $supplier_name
 * @property string|null $offer_name
 * @property string|null $offer_code
 * @property string $status
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property array<array-key, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\EnergyBroker\Models\EnergyBrokerDocument|null $document
 * @property-read \Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint|null $supplyPoint
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\EnergyBroker\Database\Factories\EnergyBrokerSupplyHistoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyHistory query()
 */
	final class EnergyBrokerSupplyHistory extends \Eloquent {}
}

namespace Modules\EnergyBroker\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property string $service_type
 * @property string|null $pod
 * @property string|null $pdr
 * @property string|null $supply_code
 * @property string|null $supplier_name
 * @property string|null $offer_name
 * @property string|null $annual_consumption
 * @property string|null $power_kw
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\EnergyBroker\Models\EnergyBrokerSupplyHistory> $histories
 * @property-read int|null $histories_count
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\EnergyBroker\Database\Factories\EnergyBrokerSupplyPointFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyPoint onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyPoint withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnergyBrokerSupplyPoint withoutTrashed()
 */
	final class EnergyBrokerSupplyPoint extends \Eloquent {}
}

namespace Modules\Gdpr\Models{
/**
 * Modules\Gdpr\Models\Consent.
 *
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $subject_id
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $user_type
 * @property string|null          $user_id
 * @property string|null          $type
 * @property string|null          $accepted_at
 * @property ProfileContract|null $creator
 * @property Treatment|null       $treatment
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $subject_id
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $user_type
 * @property string|null          $user_id
 * @property string|null          $type
 * @property string|null          $accepted_at
 * @property ProfileContract|null $creator
 * @property Treatment|null       $treatment
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Consent newModelQuery()
 * @method static Builder<static>|Consent newQuery()
 * @method static Builder<static>|Consent query()
 * @method static Builder<static>|Consent whereAcceptedAt($value)
 * @method static Builder<static>|Consent whereCreatedAt($value)
 * @method static Builder<static>|Consent whereCreatedBy($value)
 * @method static Builder<static>|Consent whereDeletedAt($value)
 * @method static Builder<static>|Consent whereDeletedBy($value)
 * @method static Builder<static>|Consent whereId($value)
 * @method static Builder<static>|Consent whereSubjectId($value)
 * @method static Builder<static>|Consent whereTreatmentId($value)
 * @method static Builder<static>|Consent whereType($value)
 * @method static Builder<static>|Consent whereUpdatedAt($value)
 * @method static Builder<static>|Consent whereUpdatedBy($value)
 * @method static Builder<static>|Consent whereUserId($value)
 * @method static Builder<static>|Consent whereUserType($value)
 * @property ProfileContract|null $deleter
 * @property string|null          $ip_address
 * @property string|null          $user_agent
 * @method static \Modules\Gdpr\Database\Factories\ConsentFactory factory($count = null, $state = [])
 * @method static Builder<static>|Consent                         whereIpAddress($value)
 * @method static Builder<static>|Consent                         whereUserAgent($value)
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @method static \Modules\Gdpr\Database\Factories\ConsentFactory factory($count = null, $state = [])
 * @method static Builder<static>|Consent                         whereIpAddress($value)
 * @method static Builder<static>|Consent                         whereUserAgent($value)
 * @mixin \Eloquent
 */
	class Consent extends \Eloquent {}
}

namespace Modules\Gdpr\Models{
/**
 * Modules\Gdpr\Models\Event.
 *
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property string               $id
 * @property string|null          $treatment_id
 * @property string|null          $consent_id
 * @property string               $subject_id
 * @property string               $ip
 * @property string               $action
 * @property string               $payload
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property Consent|null         $consent
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event whereAction($value)
 * @method static Builder<static>|Event whereConsentId($value)
 * @method static Builder<static>|Event whereCreatedAt($value)
 * @method static Builder<static>|Event whereCreatedBy($value)
 * @method static Builder<static>|Event whereDeletedAt($value)
 * @method static Builder<static>|Event whereDeletedBy($value)
 * @method static Builder<static>|Event whereId($value)
 * @method static Builder<static>|Event whereIp($value)
 * @method static Builder<static>|Event wherePayload($value)
 * @method static Builder<static>|Event whereSubjectId($value)
 * @method static Builder<static>|Event whereTreatmentId($value)
 * @method static Builder<static>|Event whereUpdatedAt($value)
 * @method static Builder<static>|Event whereUpdatedBy($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\Gdpr\Database\Factories\EventFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Event extends \Eloquent {}
}

namespace Modules\Gdpr\Models{
/**
 * Modules\Gdpr\Models\Profile.
 *
 * @property string                                                    $id
 * @property string|null                                               $post_type
 * @property string|null                                               $bio
 * @property Carbon|null                                               $created_at
 * @property Carbon|null                                               $updated_at
 * @property string|null                                               $created_by
 * @property string|null                                               $updated_by
 * @property string|null                                               $deleted_by
 * @property string|null                                               $first_name
 * @property string|null                                               $surname
 * @property string|null                                               $email
 * @property string|null                                               $phone
 * @property string|null                                               $address
 * @property string|null                                               $user_id
 * @property string|null                                               $last_name
 * @property string|null                                               $tax_code
 * @property string|null                                               $vat_number
 * @property Carbon|null                                               $deleted_at
 * @property SchemalessAttributes                                      $extra
 * @property string                                                    $avatar
 * @property ProfileContract|null                                      $creator
 * @property Collection<int, DeviceUser>                               $deviceUsers
 * @property int|null                                                  $device_users_count
 * @property DeviceProfile|null                                        $pivot
 * @property Collection<int, Device>                                   $devices
 * @property int|null                                                  $devices_count
 * @property string|null                                               $full_name
 * @property MediaCollection<int, Media>                               $media
 * @property int|null                                                  $media_count
 * @property Collection<int, DeviceUser>                               $mobileDeviceUsers
 * @property int|null                                                  $mobile_device_users_count
 * @property Collection<int, Device>                                   $mobileDevices
 * @property int|null                                                  $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property string                                                    $id
 * @property string|null                                               $post_type
 * @property string|null                                               $bio
 * @property Carbon|null                                               $created_at
 * @property Carbon|null                                               $updated_at
 * @property string|null                                               $created_by
 * @property string|null                                               $updated_by
 * @property string|null                                               $deleted_by
 * @property string|null                                               $first_name
 * @property string|null                                               $surname
 * @property string|null                                               $email
 * @property string|null                                               $phone
 * @property string|null                                               $address
 * @property string|null                                               $user_id
 * @property string|null                                               $last_name
 * @property string|null                                               $tax_code
 * @property string|null                                               $vat_number
 * @property Carbon|null                                               $deleted_at
 * @property SchemalessAttributes                                      $extra
 * @property string                                                    $avatar
 * @property ProfileContract|null                                      $creator
 * @property Collection<int, DeviceUser>                               $deviceUsers
 * @property int|null                                                  $device_users_count
 * @property DeviceProfile|null                                        $pivot
 * @property Collection<int, Device>                                   $devices
 * @property int|null                                                  $devices_count
 * @property string|null                                               $full_name
 * @property MediaCollection<int, Media>                               $media
 * @property int|null                                                  $media_count
 * @property Collection<int, DeviceUser>                               $mobileDeviceUsers
 * @property int|null                                                  $mobile_device_users_count
 * @property Collection<int, Device>                                   $mobileDevices
 * @property int|null                                                  $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null                                                  $notifications_count
 * @property Collection<int, Permission>                               $permissions
 * @property int|null                                                  $permissions_count
 * @property Collection<int, Role>                                     $roles
 * @property int|null                                                  $roles_count
 * @property ProfileContract|null                                      $updater
 * @property User|null                                                 $user
 * @property string|null                                               $user_name
 * @property int|null                                                  $notifications_count
 * @property Collection<int, Permission>                               $permissions
 * @property int|null                                                  $permissions_count
 * @property Collection<int, Role>                                     $roles
 * @property int|null                                                  $roles_count
 * @property ProfileContract|null                                      $updater
 * @property User|null                                                 $user
 * @property string|null                                               $user_name
 * @method static Builder<static>|Profile newModelQuery()
 * @method static Builder<static>|Profile newQuery()
 * @method static Builder<static>|Profile permission($permissions, $without = false)
 * @method static Builder<static>|Profile query()
 * @method static Builder<static>|Profile role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Profile whereAddress($value)
 * @method static Builder<static>|Profile whereBio($value)
 * @method static Builder<static>|Profile whereCreatedAt($value)
 * @method static Builder<static>|Profile whereCreatedBy($value)
 * @method static Builder<static>|Profile whereDeletedAt($value)
 * @method static Builder<static>|Profile whereDeletedBy($value)
 * @method static Builder<static>|Profile whereEmail($value)
 * @method static Builder<static>|Profile whereFirstName($value)
 * @method static Builder<static>|Profile whereId($value)
 * @method static Builder<static>|Profile whereLastName($value)
 * @method static Builder<static>|Profile wherePhone($value)
 * @method static Builder<static>|Profile wherePostType($value)
 * @method static Builder<static>|Profile whereSurname($value)
 * @method static Builder<static>|Profile whereTaxCode($value)
 * @method static Builder<static>|Profile whereUpdatedAt($value)
 * @method static Builder<static>|Profile whereUpdatedBy($value)
 * @method static Builder<static>|Profile whereUserId($value)
 * @method static Builder<static>|Profile whereVatNumber($value)
 * @method static Builder<static>|Profile withExtraAttributes()
 * @method static Builder<static>|Profile withoutPermission($permissions)
 * @method static Builder<static>|Profile withoutRole($roles, $guard = null)
 * @property ProfileContract|null $deleter
 * @property string|null          $fiscal_code
 * @property string|null          $notes
 * @property string|null          $fiscal_code
 * @property string|null          $notes
 * @method static Builder<static>|Profile                         childrenWith(array<int|string, mixed> $relations)
 * @method static Builder<static>|Profile                         childrenWithCount(array<int|string, mixed> $relations)
 * @method static \Modules\Gdpr\Database\Factories\ProfileFactory factory($count = null, $state = [])
 * @method static Builder<static>|Profile                         whereFiscalCode($value)
 * @method static Builder<static>|Profile                         whereNotes($value)
 * @method static Builder<static>|Profile                         byUuid(string $uuid)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Team> $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutTeam($teams)
 */
	class Profile extends \Eloquent {}
}

namespace Modules\Gdpr\Models{
/**
 * Modules\Gdpr\Models\Treatment.
 *
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property string               $id
 * @property int                  $active
 * @property int                  $required
 * @property string               $name
 * @property string               $description
 * @property string|null          $documentVersion
 * @property string|null          $documentUrl
 * @property int                  $weight
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Treatment newModelQuery()
 * @method static Builder<static>|Treatment newQuery()
 * @method static Builder<static>|Treatment query()
 * @method static Builder<static>|Treatment whereActive($value)
 * @method static Builder<static>|Treatment whereCreatedAt($value)
 * @method static Builder<static>|Treatment whereCreatedBy($value)
 * @method static Builder<static>|Treatment whereDeletedAt($value)
 * @method static Builder<static>|Treatment whereDeletedBy($value)
 * @method static Builder<static>|Treatment whereDescription($value)
 * @method static Builder<static>|Treatment whereDocumentUrl($value)
 * @method static Builder<static>|Treatment whereDocumentVersion($value)
 * @method static Builder<static>|Treatment whereId($value)
 * @method static Builder<static>|Treatment whereName($value)
 * @method static Builder<static>|Treatment whereRequired($value)
 * @method static Builder<static>|Treatment whereUpdatedAt($value)
 * @method static Builder<static>|Treatment whereUpdatedBy($value)
 * @method static Builder<static>|Treatment whereWeight($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\Gdpr\Database\Factories\TreatmentFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Treatment extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * Class Address.
 *
 * Implementazione di Schema.org PostalAddress
 *
 * @property int $id
 * @property Carbon|null $deleted_at
 * @property string|null $model_type
 * @property int|string|null $model_id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $phone
 * @property string|null $route
 * @property string|null $street_number
 * @property string|null $locality
 * @property string|null $administrative_area_level_3
 * @property string|null $administrative_area_level_2
 * @property string|null $administrative_area_level_1
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $formatted_address
 * @property string|null $place_id
 * @property float|null $latitude
 * @property float|null $longitude
 * @property AddressTypeEnum $type
 * @property bool $is_primary
 * @property array<string, mixed>|null $extra_data
 * @property-read Model $addressable
 * @property-read ProfileContract|null $creator
 * @property-read string $full_address
 * @property-read string $street_address
 * @property-read Model $model
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Address nearby(float $latitude, float $longitude, float $radiusKm = 10)
 * @method static Builder<static>|Address newModelQuery()
 * @method static Builder<static>|Address newQuery()
 * @method static Builder<static>|Address ofType(\Modules\Geo\Enums\AddressTypeEnum|string $type)
 * @method static Builder<static>|Address primary()
 * @method static Builder<static>|Address query()
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @method static Builder<static>|Address onlyTrashed()
 * @method static Builder<static>|Address whereAdministrativeAreaLevel1($value)
 * @method static Builder<static>|Address whereAdministrativeAreaLevel2($value)
 * @method static Builder<static>|Address whereAdministrativeAreaLevel3($value)
 * @method static Builder<static>|Address whereCountry($value)
 * @method static Builder<static>|Address whereCreatedAt($value)
 * @method static Builder<static>|Address whereCreatedBy($value)
 * @method static Builder<static>|Address whereDeletedAt($value)
 * @method static Builder<static>|Address whereDeletedBy($value)
 * @method static Builder<static>|Address whereDescription($value)
 * @method static Builder<static>|Address whereExtraData($value)
 * @method static Builder<static>|Address whereFormattedAddress($value)
 * @method static Builder<static>|Address whereId($value)
 * @method static Builder<static>|Address whereIsPrimary($value)
 * @method static Builder<static>|Address whereLatitude($value)
 * @method static Builder<static>|Address whereLocality($value)
 * @method static Builder<static>|Address whereLongitude($value)
 * @method static Builder<static>|Address whereModelId($value)
 * @method static Builder<static>|Address whereModelType($value)
 * @method static Builder<static>|Address whereName($value)
 * @method static Builder<static>|Address wherePhone($value)
 * @method static Builder<static>|Address wherePlaceId($value)
 * @method static Builder<static>|Address wherePostalCode($value)
 * @method static Builder<static>|Address whereRoute($value)
 * @method static Builder<static>|Address whereStreetNumber($value)
 * @method static Builder<static>|Address whereType($value)
 * @method static Builder<static>|Address whereUpdatedAt($value)
 * @method static Builder<static>|Address whereUpdatedBy($value)
 * @method static Builder<static>|Address withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Address withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\AddressFactory factory($count = null, $state = [])
 */
	class Address extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * Modello per i comuni italiani con Sushi.
 *
 * Implementa il pattern Facade per fornire un'interfaccia unificata a tutti i dati geografici:
 * regioni, province, città, CAP, codici ISTAT, ecc.
 * Tutti i dati sono estratti da file JSON e gestiti tramite Sushi.
 *
 * @property string|null $nome
 * @property float|null $codice
 * @property array<array-key, mixed>|null $zona
 * @property array<array-key, mixed>|null $regione
 * @property array<array-key, mixed>|null $provincia
 * @property string|null $sigla
 * @property string|null $codiceCatastale
 * @property array<array-key, mixed>|null $cap
 * @property int|null $popolazione
 * @property int|null $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $content
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Comune newModelQuery()
 * @method static Builder<static>|Comune newQuery()
 * @method static Builder<static>|Comune query()
 * @method static Builder<static>|Comune whereCap($value)
 * @method static Builder<static>|Comune whereCodice($value)
 * @method static Builder<static>|Comune whereCodiceCatastale($value)
 * @method static Builder<static>|Comune whereContent($value)
 * @method static Builder<static>|Comune whereCreatedAt($value)
 * @method static Builder<static>|Comune whereCreatedBy($value)
 * @method static Builder<static>|Comune whereId($value)
 * @method static Builder<static>|Comune whereNome($value)
 * @method static Builder<static>|Comune wherePopolazione($value)
 * @method static Builder<static>|Comune whereProvincia($value)
 * @method static Builder<static>|Comune whereRegione($value)
 * @method static Builder<static>|Comune whereSigla($value)
 * @method static Builder<static>|Comune whereSlug($value)
 * @method static Builder<static>|Comune whereTitle($value)
 * @method static Builder<static>|Comune whereUpdatedAt($value)
 * @method static Builder<static>|Comune whereUpdatedBy($value)
 * @method static Builder<static>|Comune whereZona($value)
 * @property ProfileContract|null $deleter
 * @method static ComuneFactory factory($count = null, $state = [])
 * @property int|null $altitudine
 * @property string|null $codice_catastale
 * @property float|null $lat
 * @property float|null $lng
 * @property string|null $sigla_provincia
 * @property float|null $superficie
 * @property string|null $zona_altimetrica
 * @method static Builder<static>|Comune whereAltitudine($value)
 * @method static Builder<static>|Comune whereLat($value)
 * @method static Builder<static>|Comune whereLng($value)
 * @method static Builder<static>|Comune whereSiglaProvincia($value)
 * @method static Builder<static>|Comune whereSuperficie($value)
 * @method static Builder<static>|Comune whereZonaAltimetrica($value)
 * @mixin \Eloquent
 */
	class Comune extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * Suddivisione tipo “county” (contesto USA / geonames), non il comune italiano.
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|County newModelQuery()
 * @method static Builder<static>|County newQuery()
 * @method static Builder<static>|County query()
 * @property int $id
 * @property int|null $state_id Stato/regione di appartenenza
 * @property string $county Nome della suddivisione (county/provincia)
 * @property string|null $county_code Codice della suddivisione
 * @property int|null $state_index Indice progressivo entro lo stato
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|County whereCounty($value)
 * @method static Builder<static>|County whereCountyCode($value)
 * @method static Builder<static>|County whereCreatedAt($value)
 * @method static Builder<static>|County whereCreatedBy($value)
 * @method static Builder<static>|County whereDeletedAt($value)
 * @method static Builder<static>|County whereDeletedBy($value)
 * @method static Builder<static>|County whereId($value)
 * @method static Builder<static>|County whereStateId($value)
 * @method static Builder<static>|County whereStateIndex($value)
 * @method static Builder<static>|County whereUpdatedAt($value)
 * @method static Builder<static>|County whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\CountyFactory factory($count = null, $state = [])
 */
	class County extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * Modules\Geo\Models\GeoNamesCap.
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|GeoNamesCap newModelQuery()
 * @method static Builder<static>|GeoNamesCap newQuery()
 * @method static Builder<static>|GeoNamesCap query()
 * @property int $id
 * @property string|null $country_code Codice paese ISO (es. IT)
 * @property string|null $postal_code CAP / codice postale
 * @property string|null $place_name Nome della località
 * @property string|null $admin_name1 Regione
 * @property string|null $admin_code1 Codice regione
 * @property string|null $admin_name2 Provincia
 * @property string|null $admin_code2 Codice provincia
 * @property string|null $admin_name3 Comune
 * @property string|null $admin_code3 Codice comune
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property int|null $accuracy Accuratezza coordinate GeoNames
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|GeoNamesCap whereAccuracy($value)
 * @method static Builder<static>|GeoNamesCap whereAdminCode1($value)
 * @method static Builder<static>|GeoNamesCap whereAdminCode2($value)
 * @method static Builder<static>|GeoNamesCap whereAdminCode3($value)
 * @method static Builder<static>|GeoNamesCap whereAdminName1($value)
 * @method static Builder<static>|GeoNamesCap whereAdminName2($value)
 * @method static Builder<static>|GeoNamesCap whereAdminName3($value)
 * @method static Builder<static>|GeoNamesCap whereCountryCode($value)
 * @method static Builder<static>|GeoNamesCap whereCreatedAt($value)
 * @method static Builder<static>|GeoNamesCap whereCreatedBy($value)
 * @method static Builder<static>|GeoNamesCap whereDeletedAt($value)
 * @method static Builder<static>|GeoNamesCap whereDeletedBy($value)
 * @method static Builder<static>|GeoNamesCap whereId($value)
 * @method static Builder<static>|GeoNamesCap whereLatitude($value)
 * @method static Builder<static>|GeoNamesCap whereLongitude($value)
 * @method static Builder<static>|GeoNamesCap wherePlaceName($value)
 * @method static Builder<static>|GeoNamesCap wherePostalCode($value)
 * @method static Builder<static>|GeoNamesCap whereUpdatedAt($value)
 * @method static Builder<static>|GeoNamesCap whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\GeoNamesCapFactory factory($count = null, $state = [])
 */
	class GeoNamesCap extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int $id
 * @property string|null $name
 * @property array<array-key, mixed>|null $postal_code
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Locality newModelQuery()
 * @method static Builder<static>|Locality newQuery()
 * @method static Builder<static>|Locality query()
 * @method static Builder<static>|Locality whereId($value)
 * @method static Builder<static>|Locality whereName($value)
 * @method static Builder<static>|Locality wherePostalCode($value)
 * @method static Builder<static>|Locality whereProvinceId($value)
 * @method static Builder<static>|Locality whereRegionId($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\LocalityFactory factory($count = null, $state = [])
 */
	class Locality extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * Class Location.
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $lat
 * @property float|null $lng
 * @property string|null $street
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $formatted_address
 * @property bool $processed
 * @property string|null $description
 * @property-read ProfileContract|null $creator
 * @property-read array<string, mixed> $location
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Location newModelQuery()
 * @method static Builder<static>|Location newQuery()
 * @method static Builder<static>|Location query()
 * @method static Builder<static>|Location withinDistance(float $latitude, float $longitude, float $distanceInKm)
 * @property string|null $model_type
 * @property string|null $model_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|Location whereCity($value)
 * @method static Builder<static>|Location whereCreatedAt($value)
 * @method static Builder<static>|Location whereCreatedBy($value)
 * @method static Builder<static>|Location whereDeletedAt($value)
 * @method static Builder<static>|Location whereDeletedBy($value)
 * @method static Builder<static>|Location whereDescription($value)
 * @method static Builder<static>|Location whereFormattedAddress($value)
 * @method static Builder<static>|Location whereId($value)
 * @method static Builder<static>|Location whereLat($value)
 * @method static Builder<static>|Location whereLng($value)
 * @method static Builder<static>|Location whereModelId($value)
 * @method static Builder<static>|Location whereModelType($value)
 * @method static Builder<static>|Location whereName($value)
 * @method static Builder<static>|Location whereProcessed($value)
 * @method static Builder<static>|Location whereState($value)
 * @method static Builder<static>|Location whereStreet($value)
 * @method static Builder<static>|Location whereUpdatedAt($value)
 * @method static Builder<static>|Location whereUpdatedBy($value)
 * @method static Builder<static>|Location whereZip($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\LocationFactory factory($count = null, $state = [])
 */
	class Location extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property-read Address|null $address
 * @property-read ProfileContract|null $creator
 * @property-read string $formatted_address
 * @property-read float|null $latitude
 * @property-read float|null $longitude
 * @property-read Model $linked
 * @property string|null $name
 * @property string|null $description
 * @property-read PlaceType|null $placeType
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Place newModelQuery()
 * @method static Builder<static>|Place newQuery()
 * @method static Builder<static>|Place query()
 * @property int $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $nearest_street
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $post_type
 * @method static Builder<static>|Place whereAddress($value)
 * @method static Builder<static>|Place whereCreatedAt($value)
 * @method static Builder<static>|Place whereCreatedBy($value)
 * @method static Builder<static>|Place whereDeletedBy($value)
 * @method static Builder<static>|Place whereFormattedAddress($value)
 * @method static Builder<static>|Place whereId($value)
 * @method static Builder<static>|Place whereLatitude($value)
 * @method static Builder<static>|Place whereLongitude($value)
 * @method static Builder<static>|Place whereModelId($value)
 * @method static Builder<static>|Place whereModelType($value)
 * @method static Builder<static>|Place whereNearestStreet($value)
 * @method static Builder<static>|Place wherePostType($value)
 * @method static Builder<static>|Place whereUpdatedAt($value)
 * @method static Builder<static>|Place whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\PlaceFactory factory($count = null, $state = [])
 */
	class Place extends \Eloquent implements \Modules\Geo\Contracts\HasGeolocation {}
}

namespace Modules\Geo\Models{
/**
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Geo\Database\Factories\PlaceTypeFactory factory($count = null, $state = [])
 * @method static Builder<static>|PlaceType newModelQuery()
 * @method static Builder<static>|PlaceType newQuery()
 * @method static Builder<static>|PlaceType query()
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|PlaceType whereCreatedAt($value)
 * @method static Builder<static>|PlaceType whereDescription($value)
 * @method static Builder<static>|PlaceType whereId($value)
 * @method static Builder<static>|PlaceType whereName($value)
 * @method static Builder<static>|PlaceType whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class PlaceType extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property int|null $region_id
 * @property int $id
 * @property string|null $name
 * @property-read ProfileContract|null $creator
 * @property-read Collection<int, Locality> $localities
 * @property-read int|null $localities_count
 * @property-read Region|null $region
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Geo\Database\Factories\ProvinceFactory factory($count = null, $state = [])
 * @method static Builder<static>|Province newModelQuery()
 * @method static Builder<static>|Province newQuery()
 * @method static Builder<static>|Province query()
 * @method static Builder<static>|Province whereId($value)
 * @method static Builder<static>|Province whereName($value)
 * @method static Builder<static>|Province whereRegionId($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Province extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property int|null $id
 * @property string|null $name
 * @property-read ProfileContract|null $creator
 * @property-read Collection<int, Province> $provinces
 * @property-read int|null $provinces_count
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Geo\Database\Factories\RegionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Region newModelQuery()
 * @method static Builder<static>|Region newQuery()
 * @method static Builder<static>|Region query()
 * @method static Builder<static>|Region whereId($value)
 * @method static Builder<static>|Region whereName($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Region extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|State newModelQuery()
 * @method static Builder<static>|State newQuery()
 * @method static Builder<static>|State query()
 * @property int $id
 * @property string $state Nome dello stato/regione
 * @property string|null $state_code Codice dello stato/regione
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|State whereCreatedAt($value)
 * @method static Builder<static>|State whereCreatedBy($value)
 * @method static Builder<static>|State whereDeletedAt($value)
 * @method static Builder<static>|State whereDeletedBy($value)
 * @method static Builder<static>|State whereId($value)
 * @method static Builder<static>|State whereState($value)
 * @method static Builder<static>|State whereStateCode($value)
 * @method static Builder<static>|State whereUpdatedAt($value)
 * @method static Builder<static>|State whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\StateFactory factory($count = null, $state = [])
 */
	class State extends \Eloquent {}
}

namespace Modules\HR\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property string|null $notes
 * @property string $status
 * @property int|null $decided_by_user_id
 * @property Carbon|null $decided_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\User\Models\User|null $decidedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Modules\HR\Database\Factories\AbsenceRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsenceRequest withoutTrashed()
 */
	final class AbsenceRequest extends \Eloquent {}
}

namespace Modules\HR\Models{
/**
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\HR\Models\EmployeeDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\HR\Models\EmployeeMedicalVisit> $medicalVisits
 * @property-read int|null $medical_visits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\HR\Models\EmployeeTraining> $trainings
 * @property-read int|null $trainings_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Modules\HR\Database\Factories\EmployeeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withoutTrashed()
 */
	final class Employee extends \Eloquent {}
}

namespace Modules\HR\Models{
/**
 * @property int $id
 * @property int $employee_id
 * @property int $media_id
 * @property string|null $title
 * @property string|null $kind
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Media $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\HR\Models\Employee|null $employee
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\HR\Database\Factories\EmployeeDocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeDocument query()
 */
	final class EmployeeDocument extends \Eloquent {}
}

namespace Modules\HR\Models{
/**
 * @property int $id
 * @property int $employee_id
 * @property int|null $media_id
 * @property string|null $title
 * @property Carbon $visited_at
 * @property Carbon|null $expires_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\HR\Models\Employee|null $employee
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\HR\Database\Factories\EmployeeMedicalVisitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeMedicalVisit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeMedicalVisit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeMedicalVisit query()
 */
	final class EmployeeMedicalVisit extends \Eloquent {}
}

namespace Modules\HR\Models{
/**
 * @property int $id
 * @property int $employee_id
 * @property int|null $media_id
 * @property string|null $title
 * @property string $name
 * @property Carbon|null $completed_at
 * @property Carbon|null $expires_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\HR\Models\Employee|null $employee
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\HR\Database\Factories\EmployeeTrainingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeTraining newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeTraining newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmployeeTraining query()
 */
	final class EmployeeTraining extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property Carbon $note_date
 * @property string $body
 * @property string|null $body_html
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read Collection<int, Media> $media
 * @method static DailyNoteFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read int|null $media_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyNote query()
 */
	final class DailyNote extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * @property int $id
 * @property int|null $work_order_id
 * @property int|null $customer_id
 * @property string|null $performed_by_user_id
 * @property string|null $validated_by_user_id
 * @property Carbon|null $scheduled_at
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property int|null $travel_km
 * @property int|null $travel_minutes
 * @property int|null $work_minutes
 * @property numeric-string|float|null $amount
 * @property numeric-string|float|null $callout_fee
 * @property numeric-string|float|null $materials_total
 * @property numeric-string|float|null $total_amount
 * @property string|null $report_payment_session_id
 * @property Carbon|null $report_paid_at
 * @property string|null $notes
 * @property string|null $notes_html
 * @property string|null $google_event_id
 * @property string|null $google_calendar_id
 * @property string|null $google_sync_status
 * @property Carbon|null $google_synced_at
 * @property string|null $outcome_code
 * @property Carbon|null $validated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, InventoryMovement> $inventoryMovements
 * @property-read Collection<int, InterventionMaterialLine> $materialLines
 * @property-read Collection<int, User> $assignedUsers
 * @property-read Collection<int, TimePunch> $timePunches
 * @property-read Collection<int, IssueReport> $issueReports
 * @property-read Collection<int, Media> $media
 * @property-read Collection<int, Signature> $signatures
 * @property-read Collection<int, Sketch> $sketches
 * @property-read User|null $performedBy
 * @property-read WorkOrder|null $workOrder
 * @property-read Customer|null $customer
 * @method static InterventionFactory factory($count = null, $state = [])
 * @property-read \Modules\Intervention\Models\InterventionUser|null $pivot
 * @property-read int|null $assigned_users_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read int|null $inventory_movements_count
 * @property-read int|null $issue_reports_count
 * @property-read int|null $material_lines_count
 * @property-read int|null $media_count
 * @property-read int|null $signatures_count
 * @property-read int|null $sketches_count
 * @property-read int|null $time_punches_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intervention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intervention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intervention onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intervention query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intervention withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intervention withoutTrashed()
 */
	final class Intervention extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * @property int $id
 * @property int $intervention_id
 * @property int|null $quotation_item_id
 * @property string $description
 * @property numeric-string|float $quantity
 * @property string $unit
 * @property numeric-string|float $unit_price
 * @property numeric-string|float $line_total
 * @property numeric-string|float $extra_quantity
 * @property numeric-string|float $extra_amount
 * @property string $extra_status_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read QuotationItem|null $quotationItem
 * @method static InterventionMaterialLineFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionMaterialLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionMaterialLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionMaterialLine query()
 */
	final class InterventionMaterialLine extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * A tokenized, optionally-expiring public link to an intervention's report PDF.
 *
 * @property int $id
 * @property int $intervention_id
 * @property string $token
 * @property string|null $created_by_user_id
 * @property CarbonImmutable|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @method static InterventionReportShareFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionReportShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionReportShare newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionReportShare query()
 */
	final class InterventionReportShare extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * Pivot intervento↔operatore assegnato.
 *
 * La tabella storica del gestionale è `intervention_user_assignments`
 * (non il convenzionale `intervention_user`): il nome resta esplicito
 * perché rinominarla romperebbe le installazioni esistenti.
 *
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Intervention\Database\Factories\InterventionUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionUser query()
 */
	class InterventionUser extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * @property int $id
 * @property int $intervention_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read User|null $user
 * @method static \Modules\Intervention\Database\Factories\InterventionUserAssignmentFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionUserAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionUserAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterventionUserAssignment query()
 */
	final class InterventionUserAssignment extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * @property int $id
 * @property int $intervention_id
 * @property string|null $created_by_user_id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read User|null $createdBy
 * @method static IssueReportFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IssueReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IssueReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IssueReport query()
 */
	final class IssueReport extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * @property int $id
 * @property int $intervention_id
 * @property int $user_id
 * @property Carbon $started_at
 * @property Carbon|null $ended_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read User|null $user
 * @method static TimePunchFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimePunch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimePunch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimePunch query()
 */
	final class TimePunch extends \Eloquent {}
}

namespace Modules\Intervention\Models{
/**
 * Per-user Google Calendar connection (OAuth tokens + target calendar). One row
 * per user. Access/refresh tokens are encrypted at rest via the app key.
 *
 * @property int $id
 * @property int $user_id
 * @property bool $enabled
 * @property string|null $google_email
 * @property string|null $calendar_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property Carbon|null $token_expires_at
 * @property Carbon|null $last_synced_at
 * @property string|null $last_synced_events
 * @property Carbon|null $last_sync_error_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @method static UserGoogleCalendarSettingFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserGoogleCalendarSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserGoogleCalendarSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserGoogleCalendarSetting query()
 */
	final class UserGoogleCalendarSetting extends \Eloquent {}
}

namespace Modules\Inventory\Models{
/**
 * @property int $id
 * @property int|null $product_id
 * @property int|null $intervention_id
 * @property int|null $work_order_id
 * @property int|null $to_warehouse_id
 * @property int|null $from_warehouse_id
 * @property string|null $movement_type
 * @property int|float|string|null $quantity
 * @property numeric-string|float|null $unit_price
 * @property numeric-string|float|null $line_total
 * @property Carbon|null $occurred_at
 * @property-read Product|null $product
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Inventory\Models\WarehouseLocation|null $fromLocation
 * @property-read \Modules\Inventory\Models\Warehouse|null $fromWarehouse
 * @property-read \Modules\Intervention\Models\Intervention|null $intervention
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Inventory\Models\ProductUnit> $productUnits
 * @property-read int|null $product_units_count
 * @property-read \Modules\Inventory\Models\WarehouseLocation|null $toLocation
 * @property-read \Modules\Inventory\Models\Warehouse|null $toWarehouse
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\Inventory\Database\Factories\InventoryMovementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement query()
 */
	final class InventoryMovement extends \Eloquent {}
}

namespace Modules\Inventory\Models{
/**
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Inventory\Database\Factories\InventoryOverviewRowFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryOverviewRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryOverviewRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryOverviewRow query()
 */
	final class InventoryOverviewRow extends \Eloquent {}
}

namespace Modules\Inventory\Models{
/**
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Inventory\Models\WarehouseLocation|null $currentLocation
 * @property-read \Modules\Inventory\Models\Warehouse|null $currentWarehouse
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Inventory\Models\InventoryMovement> $inventoryMovements
 * @property-read int|null $inventory_movements_count
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Inventory\Database\Factories\ProductUnitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit query()
 */
	final class ProductUnit extends \Eloquent {}
}

namespace Modules\Inventory\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $is_active
 * @property bool $is_final_destination
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Inventory\Models\WarehouseLocation> $locations
 * @property-read int|null $locations_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\Vehicle\Models\Vehicle|null $vehicle
 * @method static \Modules\Inventory\Database\Factories\WarehouseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse query()
 */
	final class Warehouse extends \Eloquent {}
}

namespace Modules\Inventory\Models{
/**
 * @property int $id
 * @property int $warehouse_id
 * @property string $code
 * @property string|null $label
 * @property bool $is_active
 * @property-read Warehouse|null $warehouse
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Inventory\Models\InventoryMovement> $incomingMovements
 * @property-read int|null $incoming_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Inventory\Models\InventoryMovement> $outgoingMovements
 * @property-read int|null $outgoing_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Inventory\Models\ProductUnit> $productUnits
 * @property-read int|null $product_units_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Inventory\Database\Factories\WarehouseLocationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseLocation query()
 */
	final class WarehouseLocation extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * @property string $id
 * @property Carbon|null $completed_at
 * @property string $file_disk
 * @property string|null $file_name
 * @property string $exporter
 * @property int $processed_rows
 * @property int $total_rows
 * @property int $successful_rows
 * @property string|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @property string|null $user_type
 * @property-read Model|Eloquent|null $user
 * @method static Builder<static>|Export newModelQuery()
 * @method static Builder<static>|Export newQuery()
 * @method static Builder<static>|Export query()
 * @method static Builder<static>|Export whereCompletedAt($value)
 * @method static Builder<static>|Export whereCreatedAt($value)
 * @method static Builder<static>|Export whereCreatedBy($value)
 * @method static Builder<static>|Export whereDeletedAt($value)
 * @method static Builder<static>|Export whereDeletedBy($value)
 * @method static Builder<static>|Export whereExporter($value)
 * @method static Builder<static>|Export whereFileDisk($value)
 * @method static Builder<static>|Export whereFileName($value)
 * @method static Builder<static>|Export whereId($value)
 * @method static Builder<static>|Export whereProcessedRows($value)
 * @method static Builder<static>|Export whereSuccessfulRows($value)
 * @method static Builder<static>|Export whereTotalRows($value)
 * @method static Builder<static>|Export whereUpdatedAt($value)
 * @method static Builder<static>|Export whereUpdatedBy($value)
 * @method static Builder<static>|Export whereUserId($value)
 * @method static Builder<static>|Export whereUserType($value)
 * @mixin Eloquent
 */
	class Export extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * @property string $id
 * @property array<array-key, mixed> $data
 * @property int $import_id
 * @property string|null $validation_error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static FailedImportRowFactory factory($count = null, $state = [])
 * @method static Builder<static>|FailedImportRow newModelQuery()
 * @method static Builder<static>|FailedImportRow newQuery()
 * @method static Builder<static>|FailedImportRow query()
 * @method static Builder<static>|FailedImportRow whereCreatedAt($value)
 * @method static Builder<static>|FailedImportRow whereCreatedBy($value)
 * @method static Builder<static>|FailedImportRow whereData($value)
 * @method static Builder<static>|FailedImportRow whereId($value)
 * @method static Builder<static>|FailedImportRow whereImportId($value)
 * @method static Builder<static>|FailedImportRow whereUpdatedAt($value)
 * @method static Builder<static>|FailedImportRow whereUpdatedBy($value)
 * @method static Builder<static>|FailedImportRow whereValidationError($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class FailedImportRow extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\FailedJob.
 *
 * @property string $id
 * @property string $uuid
 * @property string $connection
 * @property string $queue
 * @property array<array-key, mixed> $payload
 * @property string $exception
 * @property string $failed_at
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static FailedJobFactory factory($count = null, $state = [])
 * @method static Builder<static>|FailedJob newModelQuery()
 * @method static Builder<static>|FailedJob newQuery()
 * @method static Builder<static>|FailedJob query()
 * @method static Builder<static>|FailedJob whereConnection($value)
 * @method static Builder<static>|FailedJob whereException($value)
 * @method static Builder<static>|FailedJob whereFailedAt($value)
 * @method static Builder<static>|FailedJob whereId($value)
 * @method static Builder<static>|FailedJob wherePayload($value)
 * @method static Builder<static>|FailedJob whereQueue($value)
 * @method static Builder<static>|FailedJob whereUuid($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class FailedJob extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\Frequency.
 *
 * @property string $id
 * @property int $task_id
 * @property string $label
 * @property string $interval
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read Collection<int, Parameter> $parameters
 * @property-read int|null $parameters_count
 * @property-read Task|null $task
 * @property-read ProfileContract|null $updater
 * @method static FrequencyFactory factory($count = null, $state = [])
 * @method static Builder<static>|Frequency newModelQuery()
 * @method static Builder<static>|Frequency newQuery()
 * @method static Builder<static>|Frequency query()
 * @method static Builder<static>|Frequency whereCreatedAt($value)
 * @method static Builder<static>|Frequency whereCreatedBy($value)
 * @method static Builder<static>|Frequency whereId($value)
 * @method static Builder<static>|Frequency whereInterval($value)
 * @method static Builder<static>|Frequency whereLabel($value)
 * @method static Builder<static>|Frequency whereTaskId($value)
 * @method static Builder<static>|Frequency whereUpdatedAt($value)
 * @method static Builder<static>|Frequency whereUpdatedBy($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Frequency extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * @property string $id
 * @property Carbon|null $completed_at
 * @property string $file_name
 * @property string $file_path
 * @property string $importer
 * @property int $processed_rows
 * @property int $total_rows
 * @property int $successful_rows
 * @property string|null $user_type
 * @property string|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static ImportFactory factory($count = null, $state = [])
 * @method static Builder<static>|Import newModelQuery()
 * @method static Builder<static>|Import newQuery()
 * @method static Builder<static>|Import query()
 * @method static Builder<static>|Import whereCompletedAt($value)
 * @method static Builder<static>|Import whereCreatedAt($value)
 * @method static Builder<static>|Import whereCreatedBy($value)
 * @method static Builder<static>|Import whereDeletedAt($value)
 * @method static Builder<static>|Import whereDeletedBy($value)
 * @method static Builder<static>|Import whereFileName($value)
 * @method static Builder<static>|Import whereFilePath($value)
 * @method static Builder<static>|Import whereId($value)
 * @method static Builder<static>|Import whereImporter($value)
 * @method static Builder<static>|Import whereProcessedRows($value)
 * @method static Builder<static>|Import whereSuccessfulRows($value)
 * @method static Builder<static>|Import whereTotalRows($value)
 * @method static Builder<static>|Import whereUpdatedAt($value)
 * @method static Builder<static>|Import whereUpdatedBy($value)
 * @method static Builder<static>|Import whereUserId($value)
 * @method static Builder<static>|Import whereUserType($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Import extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\Job.
 *
 * @property int $id
 * @property string $queue
 * @property array<array-key, mixed> $payload
 * @property int $attempts
 * @property int|null $reserved_at
 * @property int $available_at
 * @property Carbon $created_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read string|null $display_name
 * @property-read string $status
 * @property-read ProfileContract|null $updater
 * @method static JobFactory factory($count = null, $state = [])
 * @method static Builder<static>|Job newModelQuery()
 * @method static Builder<static>|Job newQuery()
 * @method static Builder<static>|Job query()
 * @method static Builder<static>|Job whereAttempts($value)
 * @method static Builder<static>|Job whereAvailableAt($value)
 * @method static Builder<static>|Job whereCreatedAt($value)
 * @method static Builder<static>|Job whereCreatedBy($value)
 * @method static Builder<static>|Job whereId($value)
 * @method static Builder<static>|Job wherePayload($value)
 * @method static Builder<static>|Job whereQueue($value)
 * @method static Builder<static>|Job whereReservedAt($value)
 * @method static Builder<static>|Job whereUpdatedAt($value)
 * @method static Builder<static>|Job whereUpdatedBy($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Job extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\JobBatch.
 *
 * @property string $id
 * @property string $name
 * @property int $total_jobs
 * @property int $pending_jobs
 * @property int $failed_jobs
 * @property string $failed_job_ids
 * @property Collection<array-key, mixed>|null $options
 * @property Carbon|null $cancelled_at
 * @property Carbon $created_at
 * @property Carbon|null $finished_at
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static JobBatchFactory factory($count = null, $state = [])
 * @method static Builder<static>|JobBatch newModelQuery()
 * @method static Builder<static>|JobBatch newQuery()
 * @method static Builder<static>|JobBatch query()
 * @method static Builder<static>|JobBatch whereCancelledAt($value)
 * @method static Builder<static>|JobBatch whereCreatedAt($value)
 * @method static Builder<static>|JobBatch whereFailedJobIds($value)
 * @method static Builder<static>|JobBatch whereFailedJobs($value)
 * @method static Builder<static>|JobBatch whereFinishedAt($value)
 * @method static Builder<static>|JobBatch whereId($value)
 * @method static Builder<static>|JobBatch whereName($value)
 * @method static Builder<static>|JobBatch whereOptions($value)
 * @method static Builder<static>|JobBatch wherePendingJobs($value)
 * @method static Builder<static>|JobBatch whereTotalJobs($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class JobBatch extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * @property string $id
 * @property string $job_id
 * @property string|null $name
 * @property string|null $queue
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @property bool $failed
 * @property int $attempt
 * @property int|null $progress
 * @property string|null $exception_message
 * @property-read ProfileContract|null $creator
 * @property-read string $status
 * @property-read ProfileContract|null $updater
 * @method static JobManagerFactory factory($count = null, $state = [])
 * @method static Builder<static>|JobManager newModelQuery()
 * @method static Builder<static>|JobManager newQuery()
 * @method static Builder<static>|JobManager query()
 * @method static Builder<static>|JobManager whereAttempt($value)
 * @method static Builder<static>|JobManager whereExceptionMessage($value)
 * @method static Builder<static>|JobManager whereFailed($value)
 * @method static Builder<static>|JobManager whereFinishedAt($value)
 * @method static Builder<static>|JobManager whereId($value)
 * @method static Builder<static>|JobManager whereJobId($value)
 * @method static Builder<static>|JobManager whereName($value)
 * @method static Builder<static>|JobManager whereProgress($value)
 * @method static Builder<static>|JobManager whereQueue($value)
 * @method static Builder<static>|JobManager whereStartedAt($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class JobManager extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\JobsWaiting.
 *
 * @property int $id
 * @property string $queue
 * @property array<array-key, mixed> $payload
 * @property int $attempts
 * @property int|null $reserved_at
 * @property int $available_at
 * @property Carbon $created_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read string|null $display_name
 * @property-read string $status
 * @property-read ProfileContract|null $updater
 * @method static JobsWaitingFactory factory($count = null, $state = [])
 * @method static Builder<static>|JobsWaiting newModelQuery()
 * @method static Builder<static>|JobsWaiting newQuery()
 * @method static Builder<static>|JobsWaiting query()
 * @method static Builder<static>|JobsWaiting whereAttempts($value)
 * @method static Builder<static>|JobsWaiting whereAvailableAt($value)
 * @method static Builder<static>|JobsWaiting whereCreatedAt($value)
 * @method static Builder<static>|JobsWaiting whereCreatedBy($value)
 * @method static Builder<static>|JobsWaiting whereId($value)
 * @method static Builder<static>|JobsWaiting wherePayload($value)
 * @method static Builder<static>|JobsWaiting whereQueue($value)
 * @method static Builder<static>|JobsWaiting whereReservedAt($value)
 * @method static Builder<static>|JobsWaiting whereUpdatedAt($value)
 * @method static Builder<static>|JobsWaiting whereUpdatedBy($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class JobsWaiting extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\Parameter.
 *
 * @property string $id
 * @property int $frequency_id
 * @property string $name
 * @property string $value
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read Frequency|null $task
 * @property-read ProfileContract|null $updater
 * @method static ParameterFactory factory($count = null, $state = [])
 * @method static Builder<static>|Parameter newModelQuery()
 * @method static Builder<static>|Parameter newQuery()
 * @method static Builder<static>|Parameter query()
 * @method static Builder<static>|Parameter whereCreatedAt($value)
 * @method static Builder<static>|Parameter whereCreatedBy($value)
 * @method static Builder<static>|Parameter whereFrequencyId($value)
 * @method static Builder<static>|Parameter whereId($value)
 * @method static Builder<static>|Parameter whereName($value)
 * @method static Builder<static>|Parameter whereUpdatedAt($value)
 * @method static Builder<static>|Parameter whereUpdatedBy($value)
 * @method static Builder<static>|Parameter whereValue($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Parameter extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\Result.
 *
 * @property string $id
 * @property int $task_id
 * @property Carbon $ran_at
 * @property string $duration
 * @property string $result
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read Task|null $task
 * @property-read ProfileContract|null $updater
 * @method static Factory<static> factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereRanAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereUpdatedBy($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Result extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\Schedule.
 *
 * @property string $id
 * @property string $command
 * @property string|null $command_custom
 * @property array<array-key, array{name?: string, value?: bool|float|int|string|null, required?: bool, type?: string}>|null $params
 * @property string $expression
 * @property array<array-key, bool|float|int|string|null>|null $environments
 * @property array<array-key, array{name?: string, value?: bool|float|int|string|null}|bool|float|int|string|null>|null $options
 * @property array<array-key, array{name?: string, value?: bool|float|int|string|null, required?: bool, type?: string}>|null $options_with_value
 * @property string|null $log_filename
 * @property int $even_in_maintenance_mode
 * @property int $without_overlapping
 * @property int $on_one_server
 * @property string|null $webhook_before
 * @property string|null $webhook_after
 * @property string|null $email_output
 * @property int $sendmail_error
 * @property int $log_success
 * @property int $log_error
 * @property Status $status
 * @property int $run_in_background
 * @property int $sendmail_success
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property ProfileContract|null $creator
 * @property \Illuminate\Database\Eloquent\Collection<int, ScheduleHistory> $histories
 * @property int|null $histories_count
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Schedule active()
 * @method static ScheduleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Schedule inactive()
 * @method static Builder<static>|Schedule newModelQuery()
 * @method static Builder<static>|Schedule newQuery()
 * @method static Builder<static>|Schedule onlyTrashed()
 * @method static Builder<static>|Schedule query()
 * @method static Builder<static>|Schedule whereCommand($value)
 * @method static Builder<static>|Schedule whereCommandCustom($value)
 * @method static Builder<static>|Schedule whereCreatedAt($value)
 * @method static Builder<static>|Schedule whereCreatedBy($value)
 * @method static Builder<static>|Schedule whereDeletedAt($value)
 * @method static Builder<static>|Schedule whereDeletedBy($value)
 * @method static Builder<static>|Schedule whereEmailOutput($value)
 * @method static Builder<static>|Schedule whereEnvironments($value)
 * @method static Builder<static>|Schedule whereEvenInMaintenanceMode($value)
 * @method static Builder<static>|Schedule whereExpression($value)
 * @method static Builder<static>|Schedule whereId($value)
 * @method static Builder<static>|Schedule whereLogError($value)
 * @method static Builder<static>|Schedule whereLogFilename($value)
 * @method static Builder<static>|Schedule whereLogSuccess($value)
 * @method static Builder<static>|Schedule whereOnOneServer($value)
 * @method static Builder<static>|Schedule whereOptions($value)
 * @method static Builder<static>|Schedule whereOptionsWithValue($value)
 * @method static Builder<static>|Schedule whereParams($value)
 * @method static Builder<static>|Schedule whereRunInBackground($value)
 * @method static Builder<static>|Schedule whereSendmailError($value)
 * @method static Builder<static>|Schedule whereSendmailSuccess($value)
 * @method static Builder<static>|Schedule whereStatus($value)
 * @method static Builder<static>|Schedule whereUpdatedAt($value)
 * @method static Builder<static>|Schedule whereUpdatedBy($value)
 * @method static Builder<static>|Schedule whereWebhookAfter($value)
 * @method static Builder<static>|Schedule whereWebhookBefore($value)
 * @method static Builder<static>|Schedule whereWithoutOverlapping($value)
 * @method static Builder<static>|Schedule withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Schedule withoutTrashed()
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Schedule extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\ScheduleHistory.
 *
 * @property string $id
 * @property Schedule|null $command
 * @property array<array-key, mixed>|null $params
 * @property string $output
 * @property array<array-key, mixed>|null $options
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $schedule_id
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static ScheduleHistoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|ScheduleHistory newModelQuery()
 * @method static Builder<static>|ScheduleHistory newQuery()
 * @method static Builder<static>|ScheduleHistory query()
 * @method static Builder<static>|ScheduleHistory whereCommand($value)
 * @method static Builder<static>|ScheduleHistory whereCreatedAt($value)
 * @method static Builder<static>|ScheduleHistory whereCreatedBy($value)
 * @method static Builder<static>|ScheduleHistory whereDeletedAt($value)
 * @method static Builder<static>|ScheduleHistory whereDeletedBy($value)
 * @method static Builder<static>|ScheduleHistory whereId($value)
 * @method static Builder<static>|ScheduleHistory whereOptions($value)
 * @method static Builder<static>|ScheduleHistory whereOutput($value)
 * @method static Builder<static>|ScheduleHistory whereParams($value)
 * @method static Builder<static>|ScheduleHistory whereScheduleId($value)
 * @method static Builder<static>|ScheduleHistory whereUpdatedAt($value)
 * @method static Builder<static>|ScheduleHistory whereUpdatedBy($value)
 * @property-read ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class ScheduleHistory extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Modules\Job\Models\Task.
 *
 * @property string $id
 * @property string $description
 * @property string $command
 * @property string|null $parameters
 * @property string|null $expression
 * @property string $timezone
 * @property int $is_active
 * @property int $dont_overlap
 * @property int $run_in_maintenance
 * @property string|null $notification_email_address
 * @property string|null $notification_phone_number
 * @property string $notification_slack_webhook
 * @property int $auto_cleanup_num
 * @property string|null $auto_cleanup_type
 * @property int $run_on_one_server
 * @property int $run_in_background
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read Collection<int, Frequency> $frequencies
 * @property-read int|null $frequencies_count
 * @property-read bool $activated
 * @property-read float $average_runtime
 * @property-read Result|null $last_result
 * @property-read string $upcoming
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Result> $results
 * @property-read int|null $results_count
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Task newModelQuery()
 * @method static Builder<static>|Task newQuery()
 * @method static Builder<static>|Task query()
 * @method static Builder<static>|Task sortableBy(array<string> $sortableColumns, array<string, 'asc'|'desc'> $defaultSort = [])
 * @method static Builder<static>|Task whereAutoCleanupNum($value)
 * @method static Builder<static>|Task whereAutoCleanupType($value)
 * @method static Builder<static>|Task whereCommand($value)
 * @method static Builder<static>|Task whereCreatedAt($value)
 * @method static Builder<static>|Task whereCreatedBy($value)
 * @method static Builder<static>|Task whereDescription($value)
 * @method static Builder<static>|Task whereDontOverlap($value)
 * @method static Builder<static>|Task whereExpression($value)
 * @method static Builder<static>|Task whereId($value)
 * @method static Builder<static>|Task whereIsActive($value)
 * @method static Builder<static>|Task whereNotificationEmailAddress($value)
 * @method static Builder<static>|Task whereNotificationPhoneNumber($value)
 * @method static Builder<static>|Task whereNotificationSlackWebhook($value)
 * @method static Builder<static>|Task whereParameters($value)
 * @method static Builder<static>|Task whereRunInBackground($value)
 * @method static Builder<static>|Task whereRunInMaintenance($value)
 * @method static Builder<static>|Task whereRunOnOneServer($value)
 * @method static Builder<static>|Task whereTimezone($value)
 * @method static Builder<static>|Task whereUpdatedAt($value)
 * @method static Builder<static>|Task whereUpdatedBy($value)
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property-read ProfileContract|null $deleter
 * @method static TaskFactory factory($count = null, $state = [])
 * @method static Builder<static>|Task whereDeletedAt($value)
 * @method static Builder<static>|Task whereDeletedBy($value)
 * @mixin \Eloquent
 */
	class Task extends \Eloquent {}
}

namespace Modules\Job\Models{
/**
 * Class TaskComment.
 *
 * @property ProfileContract|null $creator
 * @property Task|null $task
 * @property ProfileContract|null $updater
 * @property User|null $user
 * @method static Builder<static>|TaskComment newModelQuery()
 * @method static Builder<static>|TaskComment newQuery()
 * @method static Builder<static>|TaskComment onlyTrashed()
 * @method static Builder<static>|TaskComment query()
 * @method static Builder<static>|TaskComment withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|TaskComment withoutTrashed()
 * @property-read ProfileContract|null $deleter
 * @method static TaskCommentFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class TaskComment extends \Eloquent {}
}

namespace Modules\Lang\Models{
/**
 * Modules\Lang\Models\LanguageLine.
 *
 * @property int                             $id
 * @property string                          $group
 * @property string                          $key
 * @property array<array-key, mixed>         $text
 * @property string                          $locale
 * @property string|null                     $created_by
 * @property string|null                     $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static EloquentBuilder<static>|LanguageLine newModelQuery()
 * @method static EloquentBuilder<static>|LanguageLine newQuery()
 * @method static EloquentBuilder<static>|LanguageLine query()
 * @method static EloquentBuilder<static>|LanguageLine whereId($value)
 * @method static EloquentBuilder<static>|LanguageLine whereGroup($value)
 * @method static EloquentBuilder<static>|LanguageLine whereKey($value)
 * @method static EloquentBuilder<static>|LanguageLine whereText($value)
 * @method static EloquentBuilder<static>|LanguageLine whereLocale($value)
 * @method static EloquentBuilder<static>|LanguageLine whereCreatedAt($value)
 * @method static EloquentBuilder<static>|LanguageLine whereUpdatedAt($value)
 * @method static EloquentBuilder<static>|LanguageLine whereCreatedBy($value)
 * @method static EloquentBuilder<static>|LanguageLine whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Lang\Database\Factories\LanguageLineFactory factory($count = null, $state = [])
 */
	class LanguageLine extends \Eloquent {}
}

namespace Modules\Lang\Models{
/**
 * Modules\Lang\Models\Post.
 *
 * @property string                       $id
 * @property int|null                     $user_id
 * @property string|null                  $post_type
 * @property int|null                     $post_id
 * @property string|null                  $lang
 * @property string|null                  $title
 * @property string|null                  $subtitle
 * @property string|null                  $guid
 * @property string|null                  $txt
 * @property string|null                  $image_src
 * @property string|null                  $image_alt
 * @property string|null                  $image_title
 * @property string|null                  $meta_description
 * @property string|null                  $meta_keywords
 * @property int|null                     $author_id
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property int|null                     $category_id
 * @property string|null                  $image
 * @property string|null                  $content
 * @property int|null                     $published
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property string|null                  $url
 * @property array<array-key, mixed>|null $url_lang
 * @property array<array-key, mixed>|null $image_resize_src
 * @property string|null                  $linked_count
 * @property string|null                  $related_count
 * @property string|null                  $relatedrev_count
 * @property string|null                  $linkable_type
 * @property int|null                     $views_count
 * @property ProfileContract|null         $creator
 * @property Model|null                   $linkable
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|Post newModelQuery()
 * @method static Builder<static>|Post newQuery()
 * @method static Builder<static>|Post query()
 * @method static Builder<static>|Post whereAuthorId($value)
 * @method static Builder<static>|Post whereCategoryId($value)
 * @method static Builder<static>|Post whereContent($value)
 * @method static Builder<static>|Post whereCreatedAt($value)
 * @method static Builder<static>|Post whereCreatedBy($value)
 * @method static Builder<static>|Post whereGuid($value)
 * @method static Builder<static>|Post whereId($value)
 * @method static Builder<static>|Post whereImage($value)
 * @method static Builder<static>|Post whereImageAlt($value)
 * @method static Builder<static>|Post whereImageResizeSrc($value)
 * @method static Builder<static>|Post whereImageSrc($value)
 * @method static Builder<static>|Post whereImageTitle($value)
 * @method static Builder<static>|Post whereLang($value)
 * @method static Builder<static>|Post whereLinkableType($value)
 * @method static Builder<static>|Post whereLinkedCount($value)
 * @method static Builder<static>|Post whereMetaDescription($value)
 * @method static Builder<static>|Post whereMetaKeywords($value)
 * @method static Builder<static>|Post wherePostId($value)
 * @method static Builder<static>|Post wherePostType($value)
 * @method static Builder<static>|Post wherePublished($value)
 * @method static Builder<static>|Post whereRelatedCount($value)
 * @method static Builder<static>|Post whereRelatedrevCount($value)
 * @method static Builder<static>|Post whereSubtitle($value)
 * @method static Builder<static>|Post whereTitle($value)
 * @method static Builder<static>|Post whereTxt($value)
 * @method static Builder<static>|Post whereUpdatedAt($value)
 * @method static Builder<static>|Post whereUpdatedBy($value)
 * @method static Builder<static>|Post whereUrl($value)
 * @method static Builder<static>|Post whereUrlLang($value)
 * @method static Builder<static>|Post whereUserId($value)
 * @method static Builder<static>|Post whereViewsCount($value)
 * @property ProfileContract|null $deleter
 * @method static PostFactory factory($count = null, $state = [])
 * @mixin Model
 * @property string|null $excerpt
 * @property string|null $slug
 * @property string|null $status
 * @property Carbon|null $published_at
 * @property string|null $locale
 * @property string|null $category
 * @property string|null $meta_title
 * @method static Builder<static>|Post whereCategory($value)
 * @method static Builder<static>|Post whereExcerpt($value)
 * @method static Builder<static>|Post whereLocale($value)
 * @method static Builder<static>|Post whereMetaTitle($value)
 * @method static Builder<static>|Post wherePublishedAt($value)
 * @method static Builder<static>|Post whereSlug($value)
 * @method static Builder<static>|Post whereStatus($value)
 * @mixin \Eloquent
 */
	class Post extends \Eloquent {}
}

namespace Modules\Lang\Models{
/**
 * Modules\Lang\Models\Translation.
 *
 * @property string               $id
 * @property string|null          $lang
 * @property string|null          $key
 * @property string|null          $value
 * @property string|null          $created_by
 * @property string|null          $updated_by
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string               $namespace
 * @property string               $group
 * @property string|null          $item
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static TranslationFactory                  factory($count = null, $state = [])
 * @method static EloquentBuilder<static>|Translation newModelQuery()
 * @method static EloquentBuilder<static>|Translation newQuery()
 * @method static EloquentBuilder<static>|Translation ofTranslatedGroup(string $group)
 * @method static EloquentBuilder<static>|Translation orderByGroupKeys(bool $ordered)
 * @method static EloquentBuilder<static>|Translation query()
 * @method static EloquentBuilder<static>|Translation selectDistinctGroup()
 * @method static EloquentBuilder<static>|Translation whereCreatedAt($value)
 * @method static EloquentBuilder<static>|Translation whereCreatedBy($value)
 * @method static EloquentBuilder<static>|Translation whereGroup($value)
 * @method static EloquentBuilder<static>|Translation whereId($value)
 * @method static EloquentBuilder<static>|Translation whereItem($value)
 * @method static EloquentBuilder<static>|Translation whereKey($value)
 * @method static EloquentBuilder<static>|Translation whereLang($value)
 * @method static EloquentBuilder<static>|Translation whereNamespace($value)
 * @method static EloquentBuilder<static>|Translation whereUpdatedAt($value)
 * @method static EloquentBuilder<static>|Translation whereUpdatedBy($value)
 * @method static EloquentBuilder<static>|Translation whereValue($value)
 * @property ProfileContract|null $deleter
 * @property string|null          $locale
 * @property int|null             $user_id
 * @method static EloquentBuilder<static>|Translation whereLocale($value)
 * @method static EloquentBuilder<static>|Translation whereUserId($value)
 * @mixin \Eloquent
 */
	class Translation extends \Eloquent {}
}

namespace Modules\Lang\Models{
/**
 * @property string|null                  $key
 * @property string|null                  $path
 * @property string|null                  $id
 * @property string|null                  $name
 * @property array<array-key, mixed>|null $content
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @method static TranslationFileFactory          factory($count = null, $state = [])
 * @method static Builder<static>|TranslationFile newModelQuery()
 * @method static Builder<static>|TranslationFile newQuery()
 * @method static Builder<static>|TranslationFile query()
 * @method static Builder<static>|TranslationFile whereContent($value)
 * @method static Builder<static>|TranslationFile whereId($value)
 * @method static Builder<static>|TranslationFile whereKey($value)
 * @method static Builder<static>|TranslationFile whereName($value)
 * @method static Builder<static>|TranslationFile wherePath($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class TranslationFile extends \Eloquent {}
}

namespace Modules\Media\Models{
/**
 * @property-read User|null $creator
 * @property-read mixed $extension
 * @property-read array<int, array{name: string, generated: bool, src: string}> $entry_conversions
 * @property-read string $path
 * @property-read mixed $human_readable_size
 * @property-read EloquentCollection<int, MediaConvert> $mediaConverts
 * @property-read int|null $media_converts_count
 * @property-read Model|Eloquent $model
 * @property-read mixed $original_url
 * @property-read mixed $preview_url
 * @property-read TemporaryUpload|null $temporaryUpload
 * @property-read mixed $type
 * @property-read ProfileContract|null $updater
 * @method static MediaCollection<int, static> all($columns = ['*'])
 * @method static \Modules\Media\Database\Factories\MediaFactory factory($count = null, $state = [])
 * @method static MediaCollection<int, static> get($columns = ['*'])
 * @method static Builder<static>|Media newModelQuery()
 * @method static Builder<static>|Media newQuery()
 * @method static Builder<static>|Media ordered()
 * @method static Builder<static>|Media query()
 * @property int $id
 * @property string $model_type
 * @property string|null $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array<array-key, mixed> $manipulations
 * @property array<array-key, mixed> $custom_properties
 * @property array<array-key, mixed> $generated_conversions
 * @property array<array-key, mixed> $responsive_images
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property int|string|null $user_id
 * @method static Builder<static>|Media whereCollectionName($value)
 * @method static Builder<static>|Media whereConversionsDisk($value)
 * @method static Builder<static>|Media whereCreatedAt($value)
 * @method static Builder<static>|Media whereCreatedBy($value)
 * @method static Builder<static>|Media whereCustomProperties($value)
 * @method static Builder<static>|Media whereDeletedAt($value)
 * @method static Builder<static>|Media whereDeletedBy($value)
 * @method static Builder<static>|Media whereDisk($value)
 * @method static Builder<static>|Media whereFileName($value)
 * @method static Builder<static>|Media whereGeneratedConversions($value)
 * @method static Builder<static>|Media whereId($value)
 * @method static Builder<static>|Media whereManipulations($value)
 * @method static Builder<static>|Media whereMimeType($value)
 * @method static Builder<static>|Media whereModelId($value)
 * @method static Builder<static>|Media whereModelType($value)
 * @method static Builder<static>|Media whereName($value)
 * @method static Builder<static>|Media whereOrderColumn($value)
 * @method static Builder<static>|Media whereResponsiveImages($value)
 * @method static Builder<static>|Media whereSize($value)
 * @method static Builder<static>|Media whereUpdatedAt($value)
 * @method static Builder<static>|Media whereUpdatedBy($value)
 * @method static Builder<static>|Media whereUuid($value)
 * @mixin Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Media extends \Eloquent {}
}

namespace Modules\Media\Models{
/**
 * @property-read ProfileContract|null $creator
 * @property-read string|null $converted_file
 * @property-read string|null $disk
 * @property-read string|null $file
 * @property-read Media|null $media
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|MediaConvert newModelQuery()
 * @method static Builder<static>|MediaConvert newQuery()
 * @method static Builder<static>|MediaConvert query()
 * @property string $id
 * @property int $media_id
 * @property string|null $format
 * @property string|null $codec_video
 * @property string|null $codec_audio
 * @property string|null $preset
 * @property string|null $bitrate
 * @property int|null $width
 * @property int|null $height
 * @property int|null $threads
 * @property int|null $speed
 * @property numeric|null $percentage
 * @property numeric|null $remaining
 * @property numeric|null $rate
 * @property numeric|null $execution_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|MediaConvert whereBitrate($value)
 * @method static Builder<static>|MediaConvert whereCodecAudio($value)
 * @method static Builder<static>|MediaConvert whereCodecVideo($value)
 * @method static Builder<static>|MediaConvert whereCreatedAt($value)
 * @method static Builder<static>|MediaConvert whereCreatedBy($value)
 * @method static Builder<static>|MediaConvert whereDeletedAt($value)
 * @method static Builder<static>|MediaConvert whereDeletedBy($value)
 * @method static Builder<static>|MediaConvert whereExecutionTime($value)
 * @method static Builder<static>|MediaConvert whereFormat($value)
 * @method static Builder<static>|MediaConvert whereHeight($value)
 * @method static Builder<static>|MediaConvert whereId($value)
 * @method static Builder<static>|MediaConvert whereMediaId($value)
 * @method static Builder<static>|MediaConvert wherePercentage($value)
 * @method static Builder<static>|MediaConvert wherePreset($value)
 * @method static Builder<static>|MediaConvert whereRate($value)
 * @method static Builder<static>|MediaConvert whereRemaining($value)
 * @method static Builder<static>|MediaConvert whereSpeed($value)
 * @method static Builder<static>|MediaConvert whereThreads($value)
 * @method static Builder<static>|MediaConvert whereUpdatedAt($value)
 * @method static Builder<static>|MediaConvert whereUpdatedBy($value)
 * @method static Builder<static>|MediaConvert whereWidth($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Media\Database\Factories\MediaConvertFactory factory($count = null, $state = [])
 */
	class MediaConvert extends \Eloquent {}
}

namespace Modules\Media\Models{
/**
 * Modules\Media\Models\TemporaryUpload.
 *
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Media\Database\Factories\TemporaryUploadFactory factory($count = null, $state = [])
 * @method static Builder<static>|TemporaryUpload newModelQuery()
 * @method static Builder<static>|TemporaryUpload newQuery()
 * @method static Builder<static>|TemporaryUpload query()
 * @property string $id
 * @property string $session_id
 * @property string|null $user_id
 * @property string $file_name
 * @property int|null $file_size
 * @property string|null $mime_type
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|TemporaryUpload whereCreatedAt($value)
 * @method static Builder<static>|TemporaryUpload whereCreatedBy($value)
 * @method static Builder<static>|TemporaryUpload whereDeletedAt($value)
 * @method static Builder<static>|TemporaryUpload whereDeletedBy($value)
 * @method static Builder<static>|TemporaryUpload whereFileName($value)
 * @method static Builder<static>|TemporaryUpload whereFileSize($value)
 * @method static Builder<static>|TemporaryUpload whereId($value)
 * @method static Builder<static>|TemporaryUpload whereMimeType($value)
 * @method static Builder<static>|TemporaryUpload whereSessionId($value)
 * @method static Builder<static>|TemporaryUpload whereStatus($value)
 * @method static Builder<static>|TemporaryUpload whereUpdatedAt($value)
 * @method static Builder<static>|TemporaryUpload whereUpdatedBy($value)
 * @method static Builder<static>|TemporaryUpload whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class TemporaryUpload extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace Modules\MetalWork\Models{
/**
 * @property int $id
 * @property string $part_code
 * @property string|null $revision
 * @property string $name
 * @property string $source_file
 * @property string|null $storage_path
 * @property string|null $content_hash
 * @property string|null $cad_system
 * @property string $length_unit_code
 * @property numeric-string|null $bbox_length_mm
 * @property numeric-string|null $bbox_width_mm
 * @property numeric-string|null $bbox_thickness_mm
 * @property int $point_count
 * @property int $face_count
 * @property int $plane_count
 * @property int $cylinder_count
 * @property int $solid_count
 * @property bool $is_assembly
 * @property int|null $imported_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\MetalWork\Models\MetalDrawingComponent> $components
 * @property-read int|null $components_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\MetalWork\Models\MetalPartEstimate> $estimates
 * @property-read int|null $estimates_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\MetalWork\Database\Factories\MetalDrawingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalDrawing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalDrawing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalDrawing query()
 */
	final class MetalDrawing extends \Eloquent {}
}

namespace Modules\MetalWork\Models{
/**
 * @property int $id
 * @property int $drawing_id
 * @property int $position
 * @property int $quantity
 * @property numeric-string $length_mm
 * @property numeric-string $width_mm
 * @property numeric-string $thickness_mm
 * @property string $shape_code
 * @property int $plane_count
 * @property int $cylinder_count
 * @property int $hole_count
 * @property bool $has_profiled_cut
 * @property int|null $raw_material_id
 * @property bool $is_material_confirmed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\MetalWork\Models\MetalDrawing|null $drawing
 * @property-read \Modules\MetalWork\Models\MetalRawMaterial|null $rawMaterial
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\MetalWork\Database\Factories\MetalDrawingComponentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalDrawingComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalDrawingComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalDrawingComponent query()
 */
	final class MetalDrawingComponent extends \Eloquent {}
}

namespace Modules\MetalWork\Models{
/**
 * @property int $id
 * @property int $part_estimate_id
 * @property int $position
 * @property string $work_center_code
 * @property string $description
 * @property string $unit_code
 * @property numeric-string $quantity
 * @property numeric-string $unit_cost
 * @property numeric-string $line_cost
 * @property numeric-string $markup_percent
 * @property numeric-string $line_price
 * @property string $markup_source_code
 * @property string|null $driver_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\MetalWork\Models\MetalPartEstimate|null $partEstimate
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\MetalWork\Database\Factories\MetalEstimateLineFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalEstimateLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalEstimateLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalEstimateLine query()
 */
	final class MetalEstimateLine extends \Eloquent {}
}

namespace Modules\MetalWork\Models{
/**
 * @property int $id
 * @property int|null $drawing_id
 * @property int|null $raw_material_id
 * @property int|null $quotation_id
 * @property string $part_code
 * @property string $name
 * @property int $quantity
 * @property numeric-string|null $thickness_mm
 * @property numeric-string|null $occupancy_percent
 * @property numeric-string|null $scrap_percent
 * @property numeric-string $net_weight_kg
 * @property numeric-string $gross_weight_kg
 * @property numeric-string $total_cost
 * @property numeric-string $sale_price
 * @property numeric-string|null $manual_sale_price
 * @property numeric-string|null $markup_percent
 * @property string|null $notes
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\MetalWork\Models\MetalDrawing|null $drawing
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\MetalWork\Models\MetalEstimateLine> $lines
 * @property-read int|null $lines_count
 * @property-read \Modules\Quotation\Models\Quotation|null $quotation
 * @property-read \Modules\MetalWork\Models\MetalRawMaterial|null $rawMaterial
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\MetalWork\Database\Factories\MetalPartEstimateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalPartEstimate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalPartEstimate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalPartEstimate query()
 */
	final class MetalPartEstimate extends \Eloquent {}
}

namespace Modules\MetalWork\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $description
 * @property string|null $supplier_name
 * @property string $family_code
 * @property string $unit_code
 * @property string|null $steel_grade
 * @property numeric-string|null $thickness_mm
 * @property numeric-string|null $width_mm
 * @property numeric-string|null $height_mm
 * @property numeric-string|null $length_mm
 * @property numeric-string|null $density_kg_dm3
 * @property numeric-string|null $weight_per_meter_kg
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\MetalWork\Models\MetalSupplierPrice> $supplierPrices
 * @property-read int|null $supplier_prices_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\MetalWork\Database\Factories\MetalRawMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalRawMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalRawMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalRawMaterial query()
 */
	final class MetalRawMaterial extends \Eloquent {}
}

namespace Modules\MetalWork\Models{
/**
 * @property int $id
 * @property int $raw_material_id
 * @property string $supplier_name
 * @property string|null $invoice_number
 * @property string|null $delivery_note_number
 * @property Carbon $recorded_on
 * @property numeric-string $quantity
 * @property string $unit_code
 * @property numeric-string $unit_price
 * @property numeric-string $line_total
 * @property string|null $source_file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\MetalWork\Models\MetalRawMaterial|null $rawMaterial
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\MetalWork\Database\Factories\MetalSupplierPriceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalSupplierPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalSupplierPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetalSupplierPrice query()
 */
	final class MetalSupplierPrice extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * Modules\Notify\Models\Contact.
 *
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Contact newModelQuery()
 * @method static Builder<static>|Contact newQuery()
 * @method static Builder<static>|Contact query()
 * @property string $id
 * @property string $model_type
 * @property string $model_id
 * @property string|null $contact_type
 * @property string|null $value
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $user_id
 * @property string|null $verified_at
 * @property string|null $token
 * @property int|null $sms_count
 * @property string|null $sms_status_code
 * @property string|null $sms_status_txt
 * @property Carbon|null $sms_sent_at
 * @property Carbon|null $mail_sent_at
 * @property int|null $mail_count
 * @property int|null $usesleft
 * @property int|null $order_column
 * @property int|null $duplicate_count
 * @property string|null $attribute_1
 * @property string|null $attribute_2
 * @property string|null $attribute_3
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|Contact whereContactType($value)
 * @method static Builder<static>|Contact whereCreatedAt($value)
 * @method static Builder<static>|Contact whereCreatedBy($value)
 * @method static Builder<static>|Contact whereDeletedAt($value)
 * @method static Builder<static>|Contact whereDeletedBy($value)
 * @method static Builder<static>|Contact whereId($value)
 * @method static Builder<static>|Contact whereModelId($value)
 * @method static Builder<static>|Contact whereModelType($value)
 * @method static Builder<static>|Contact whereToken($value)
 * @method static Builder<static>|Contact whereUpdatedAt($value)
 * @method static Builder<static>|Contact whereUpdatedBy($value)
 * @method static Builder<static>|Contact whereUserId($value)
 * @method static Builder<static>|Contact whereValue($value)
 * @method static Builder<static>|Contact whereVerifiedAt($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\ContactFactory factory($count = null, $state = [])
 */
	class Contact extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate query()
 * @mixin \Eloquent
 */
	class EmailTemplate extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property-read list<string> $translatable_columns_from
 * @property-read array<string, mixed> $variables
 * @property-read mixed $translations
 * @method static Builder<static>|MailTemplate forMailable(\Illuminate\Contracts\Mail\Mailable $mailable)
 * @method static Builder<static>|MailTemplate newModelQuery()
 * @method static Builder<static>|MailTemplate newQuery()
 * @method static Builder<static>|MailTemplate query()
 * @method static Builder<static>|MailTemplate whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereLocale(string $column, string $locale)
 * @method static Builder<static>|MailTemplate whereLocales(string $column, array<int, string> $locales)
 * @property int $id
 * @property string|null $name
 * @property string|null $mailable
 * @property string|null $slug
 * @property string|array<array-key, mixed>|null $subject
 * @property string|array<array-key, mixed>|null $html_template
 * @property string|array<array-key, mixed>|null $text_template
 * @property string|int $version
 * @property string|null $params
 * @property array<array-key, mixed>|null $sms_template
 * @property int $counter
 * @property string|null $html_layout_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|MailTemplate whereCounter($value)
 * @method static Builder<static>|MailTemplate whereCreatedAt($value)
 * @method static Builder<static>|MailTemplate whereCreatedBy($value)
 * @method static Builder<static>|MailTemplate whereDeletedAt($value)
 * @method static Builder<static>|MailTemplate whereDeletedBy($value)
 * @method static Builder<static>|MailTemplate whereHtmlLayoutPath($value)
 * @method static Builder<static>|MailTemplate whereHtmlTemplate($value)
 * @method static Builder<static>|MailTemplate whereId($value)
 * @method static Builder<static>|MailTemplate whereMailable($value)
 * @method static Builder<static>|MailTemplate whereName($value)
 * @method static Builder<static>|MailTemplate whereParams($value)
 * @method static Builder<static>|MailTemplate whereSlug($value)
 * @method static Builder<static>|MailTemplate whereSmsTemplate($value)
 * @method static Builder<static>|MailTemplate whereSubject($value)
 * @method static Builder<static>|MailTemplate whereTextTemplate($value)
 * @method static Builder<static>|MailTemplate whereUpdatedAt($value)
 * @method static Builder<static>|MailTemplate whereUpdatedBy($value)
 * @method static Builder<static>|MailTemplate whereVersion($value)
 * @mixin \Eloquent
 */
	class MailTemplate extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property int|null $template_id
 * @property string|null $mailable_type
 * @property int|string|null $mailable_id
 * @property string|null $status
 * @property string|null $status_message
 * @property array<string, mixed>|null $data
 * @property array<string, mixed>|null $metadata
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $failed_at
 * @property Carbon|null $opened_at
 * @property Carbon|null $clicked_at
 * @property-read ProfileContract|null $creator
 * @property-read Model $mailable
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read MailTemplate|null $template
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|MailTemplateLog newModelQuery()
 * @method static Builder<static>|MailTemplateLog newQuery()
 * @method static Builder<static>|MailTemplateLog query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\MailTemplateLogFactory factory($count = null, $state = [])
 */
	class MailTemplateLog extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read MailTemplate|null $template
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|MailTemplateVersion newModelQuery()
 * @method static Builder<static>|MailTemplateVersion newQuery()
 * @method static Builder<static>|MailTemplateVersion onlyTrashed()
 * @method static Builder<static>|MailTemplateVersion query()
 * @method static Builder<static>|MailTemplateVersion withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|MailTemplateVersion withoutTrashed()
 * @property string $id
 * @property int $mail_template_id
 * @property int $version
 * @property string|null $subject
 * @property string $html_template
 * @property string|null $text_template
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $created_by
 * @property string|null $change_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|MailTemplateVersion whereChangeNotes($value)
 * @method static Builder<static>|MailTemplateVersion whereCreatedAt($value)
 * @method static Builder<static>|MailTemplateVersion whereCreatedBy($value)
 * @method static Builder<static>|MailTemplateVersion whereDeletedAt($value)
 * @method static Builder<static>|MailTemplateVersion whereDeletedBy($value)
 * @method static Builder<static>|MailTemplateVersion whereHtmlTemplate($value)
 * @method static Builder<static>|MailTemplateVersion whereId($value)
 * @method static Builder<static>|MailTemplateVersion whereMailTemplateId($value)
 * @method static Builder<static>|MailTemplateVersion whereMetadata($value)
 * @method static Builder<static>|MailTemplateVersion whereSubject($value)
 * @method static Builder<static>|MailTemplateVersion whereTextTemplate($value)
 * @method static Builder<static>|MailTemplateVersion whereUpdatedAt($value)
 * @method static Builder<static>|MailTemplateVersion whereUpdatedBy($value)
 * @method static Builder<static>|MailTemplateVersion whereVersion($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\MailTemplateVersionFactory factory($count = null, $state = [])
 */
	class MailTemplateVersion extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * Notification model for the Notify module.
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Notify\Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Notification newModelQuery()
 * @method static Builder<static>|Notification newQuery()
 * @method static Builder<static>|Notification query()
 * @property string $id
 * @property string|null $message
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string|int|null $tenant_id
 * @property string|int|null $user_id
 * @property string|null $subject_type
 * @property string|int|null $subject_id
 * @property array<int, string>|array<string, mixed>|null $channels
 * @property string|null $status
 * @property array<array-key, mixed> $data
 * @property Carbon|null $read_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|Notification whereCreatedAt($value)
 * @method static Builder<static>|Notification whereCreatedBy($value)
 * @method static Builder<static>|Notification whereData($value)
 * @method static Builder<static>|Notification whereDeletedAt($value)
 * @method static Builder<static>|Notification whereDeletedBy($value)
 * @method static Builder<static>|Notification whereId($value)
 * @method static Builder<static>|Notification whereNotifiableId($value)
 * @method static Builder<static>|Notification whereNotifiableType($value)
 * @method static Builder<static>|Notification whereReadAt($value)
 * @method static Builder<static>|Notification whereType($value)
 * @method static Builder<static>|Notification whereUpdatedAt($value)
 * @method static Builder<static>|Notification whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Notification extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property string|null $name
 * @property string|null $driver
 * @property array<string, mixed>|null $config
 * @property bool $is_enabled
 * @property int|null $priority
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read ProfileContract|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationChannel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationChannel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationChannel query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\NotificationChannelFactory factory($count = null, $state = [])
 */
	class NotificationChannel extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Model $notifiable
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|NotificationLog forChannel(string $channel)
 * @method static Builder<static>|NotificationLog forNotifiable(\Illuminate\Database\Eloquent\Model $notifiable)
 * @method static Builder<static>|NotificationLog newModelQuery()
 * @method static Builder<static>|NotificationLog newQuery()
 * @method static Builder<static>|NotificationLog query()
 * @method static Builder<static>|NotificationLog withStatus(string $status)
 * @property string $id
 * @property string|null $template_id
 * @property string $notifiable_type
 * @property string $notifiable_id
 * @property string $channel
 * @property string $status
 * @property string|null $status_message
 * @property array<array-key, mixed>|null $data
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $tenant_id
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $failed_at
 * @property Carbon|null $opened_at
 * @property Carbon|null $clicked_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder<static>|NotificationLog whereChannel($value)
 * @method static Builder<static>|NotificationLog whereClickedAt($value)
 * @method static Builder<static>|NotificationLog whereCreatedAt($value)
 * @method static Builder<static>|NotificationLog whereCreatedBy($value)
 * @method static Builder<static>|NotificationLog whereData($value)
 * @method static Builder<static>|NotificationLog whereDeliveredAt($value)
 * @method static Builder<static>|NotificationLog whereFailedAt($value)
 * @method static Builder<static>|NotificationLog whereId($value)
 * @method static Builder<static>|NotificationLog whereMetadata($value)
 * @method static Builder<static>|NotificationLog whereNotifiableId($value)
 * @method static Builder<static>|NotificationLog whereNotifiableType($value)
 * @method static Builder<static>|NotificationLog whereOpenedAt($value)
 * @method static Builder<static>|NotificationLog whereSentAt($value)
 * @method static Builder<static>|NotificationLog whereStatus($value)
 * @method static Builder<static>|NotificationLog whereStatusMessage($value)
 * @method static Builder<static>|NotificationLog whereTemplateId($value)
 * @method static Builder<static>|NotificationLog whereTenantId($value)
 * @method static Builder<static>|NotificationLog whereUpdatedAt($value)
 * @method static Builder<static>|NotificationLog whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\NotificationLogFactory factory($count = null, $state = [])
 */
	class NotificationLog extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * Class NotificationTemplate.
 *
 * @property string|null $name
 * @property string|null $code
 * @property string|null $subject
 * @property bool $is_active
 * @property array<string, mixed>|null $variables
 * @property string|null $body_html
 * @property string|null $body_text
 * @property array<int, string>|null $channels
 * @property array<string, mixed>|null $conditions
 * @property array<string, mixed>|null $preview_data
 * @property array<string, mixed>|null $grapesjs_data
 * @property NotificationTypeEnum $type
 * @property-read ProfileContract|null $creator
 * @property-read string $channels_label
 * @property-read list<string> $translatable_columns_from
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read mixed $translations
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|NotificationTemplate active()
 * @method static Builder<static>|NotificationTemplate forCategory(string $category)
 * @method static Builder<static>|NotificationTemplate forChannel(string $channel)
 * @method static Builder<static>|NotificationTemplate newModelQuery()
 * @method static Builder<static>|NotificationTemplate newQuery()
 * @method static Builder<static>|NotificationTemplate query()
 * @method static Builder<static>|NotificationTemplate whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|NotificationTemplate whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|NotificationTemplate whereLocale(string $column, string $locale)
 * @method static Builder<static>|NotificationTemplate whereLocales(string $column, array<int, string> $locales)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\NotificationTemplateFactory factory($count = null, $state = [])
 */
	class NotificationTemplate extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property int|null $template_id
 * @property string|null $subject
 * @property string|null $body_html
 * @property string|null $body_text
 * @property array<int, string>|null $channels
 * @property array<string, mixed>|null $variables
 * @property array<string, mixed>|null $conditions
 * @property int|string|null $version
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|NotificationTemplateVersion newModelQuery()
 * @method static Builder<static>|NotificationTemplateVersion newQuery()
 * @method static Builder<static>|NotificationTemplateVersion query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Notify\Database\Factories\NotificationTemplateVersionFactory factory($count = null, $state = [])
 */
	class NotificationTemplateVersion extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $category
 * @property bool $is_active
 * @property array<string, mixed>|null $channels
 * @property array<string, mixed>|null $settings
 * @property string|null $template
 * @method static Builder<static>|NotificationType newModelQuery()
 * @method static Builder<static>|NotificationType newQuery()
 * @method static Builder<static>|NotificationType query()
 * @mixin \Eloquent
 */
	class NotificationType extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * Modules\Notify\Models\NotifyTheme.
 *
 * @method static NotifyThemeFactory factory($count = null, $state = [])
 * @property-read ProfileContract|null $creator
 * @property-read array{path: string, width: int, height: int} $logo
 * @property-read Model $linkable
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|NotifyTheme newModelQuery()
 * @method static Builder<static>|NotifyTheme newQuery()
 * @method static Builder<static>|NotifyTheme query()
 * @property string $id
 * @property string|null $lang
 * @property string|null $type
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $from
 * @property string|null $post_type
 * @property int|null $post_id
 * @property string|null $body_html
 * @property string|null $theme
 * @property string|null $from_email
 * @property string|null $logo_src
 * @property int|null $logo_width
 * @property int|null $logo_height
 * @property array<array-key, mixed>|null $view_params
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|NotifyTheme whereBody($value)
 * @method static Builder<static>|NotifyTheme whereBodyHtml($value)
 * @method static Builder<static>|NotifyTheme whereCreatedAt($value)
 * @method static Builder<static>|NotifyTheme whereCreatedBy($value)
 * @method static Builder<static>|NotifyTheme whereDeletedAt($value)
 * @method static Builder<static>|NotifyTheme whereDeletedBy($value)
 * @method static Builder<static>|NotifyTheme whereFrom($value)
 * @method static Builder<static>|NotifyTheme whereFromEmail($value)
 * @method static Builder<static>|NotifyTheme whereId($value)
 * @method static Builder<static>|NotifyTheme whereLang($value)
 * @method static Builder<static>|NotifyTheme whereLogoHeight($value)
 * @method static Builder<static>|NotifyTheme whereLogoSrc($value)
 * @method static Builder<static>|NotifyTheme whereLogoWidth($value)
 * @method static Builder<static>|NotifyTheme wherePostId($value)
 * @method static Builder<static>|NotifyTheme wherePostType($value)
 * @method static Builder<static>|NotifyTheme whereSubject($value)
 * @method static Builder<static>|NotifyTheme whereTheme($value)
 * @method static Builder<static>|NotifyTheme whereType($value)
 * @method static Builder<static>|NotifyTheme whereUpdatedAt($value)
 * @method static Builder<static>|NotifyTheme whereUpdatedBy($value)
 * @method static Builder<static>|NotifyTheme whereViewParams($value)
 * @mixin Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class NotifyTheme extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * Modules\Notify\Models\NotifyThemeable.
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|NotifyThemeable newModelQuery()
 * @method static Builder<static>|NotifyThemeable newQuery()
 * @method static Builder<static>|NotifyThemeable query()
 * @property string $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int|null $notify_theme_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder<static>|NotifyThemeable whereCreatedAt($value)
 * @method static Builder<static>|NotifyThemeable whereCreatedBy($value)
 * @method static Builder<static>|NotifyThemeable whereDeletedAt($value)
 * @method static Builder<static>|NotifyThemeable whereDeletedBy($value)
 * @method static Builder<static>|NotifyThemeable whereId($value)
 * @method static Builder<static>|NotifyThemeable whereModelId($value)
 * @method static Builder<static>|NotifyThemeable whereModelType($value)
 * @method static Builder<static>|NotifyThemeable whereNotifyThemeId($value)
 * @method static Builder<static>|NotifyThemeable whereUpdatedAt($value)
 * @method static Builder<static>|NotifyThemeable whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class NotifyThemeable extends \Eloquent {}
}

namespace Modules\Notify\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Theme query()
 * @mixin \Eloquent
 */
	class Theme extends \Eloquent {}
}

namespace Modules\Platform\Models{
/**
 * @property int $id
 * @property Carbon $created_at
 * @property string|null $actor_user_id
 * @property string $action
 * @property string $subject_type
 * @property string $subject_id
 * @property string|null $panel_id
 * @property string|null $route
 * @property string|null $ip
 * @property string|null $user_agent
 * @property array<string,mixed>|null $before
 * @property array<string,mixed>|null $after
 * @property-read \Modules\User\Models\User|null $actor
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $subject
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Platform\Database\Factories\AuditLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 */
	final class AuditLog extends \Eloquent {}
}

namespace Modules\Platform\Models{
/**
 * Runtime on/off state for a single navigation node (module, group or menu item).
 *
 * A missing row means "enabled": only explicit overrides are persisted, so newly
 * added resources/pages default to visible without any migration.
 *
 * @property int $id
 * @property string $node_key
 * @property bool $is_enabled
 * @property string|null $updated_by_user_id
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\User\Models\User|null $updatedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Platform\Database\Factories\NavigationPreferenceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NavigationPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NavigationPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NavigationPreference query()
 */
	final class NavigationPreference extends \Eloquent {}
}

namespace Modules\Platform\Models{
/**
 * @property int $id
 * @property string $key
 * @property array<string,mixed>|null $value_json
 * @property string|null $updated_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\User\Models\User|null $updatedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Platform\Database\Factories\PlatformSettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlatformSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlatformSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlatformSetting query()
 */
	final class PlatformSetting extends \Eloquent {}
}

namespace Modules\Production\Models{
/**
 * @property int $id
 * @property string|null $code
 * @property int $product_id
 * @property int $quantity
 * @property string $status
 * @property int|null $work_order_id
 * @property string|null $notes
 * @property Carbon|null $due_at
 * @method static ProductionOrderFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Production\Models\ProductionPhase> $phases
 * @property-read int|null $phases_count
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionOrder query()
 */
	final class ProductionOrder extends \Eloquent {}
}

namespace Modules\Production\Models{
/**
 * @property int $id
 * @property int $production_order_id
 * @property string $name
 * @property int $sort_order
 * @property string $status
 * @property int|null $assigned_to_user_id
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @method static ProductionPhaseFactory factory($count = null, $state = [])
 * @property-read \Modules\User\Models\User|null $assignedTo
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Production\Models\ProductionOrder|null $order
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionPhase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionPhase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionPhase query()
 */
	final class ProductionPhase extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $media_id
 * @property int|null $created_by_user_id
 * @property string $document_type
 * @property string $status
 * @property string $confidence
 * @property array<string, mixed>|null $extracted_payload
 * @property string|null $raw_text
 * @property Carbon|null $expires_at
 * @property Carbon|null $applied_at
 * @property Carbon|null $deleted_at
 * @method static PublicProcurementAiExtractionFactory factory($count = null, $state = [])
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Document\Models\Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementAiExtraction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementAiExtraction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementAiExtraction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementAiExtraction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementAiExtraction withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementAiExtraction withoutTrashed()
 */
	final class PublicProcurementAiExtraction extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementContactFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContact withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContact withoutTrashed()
 */
	final class PublicProcurementContact extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @property int $id
 * @property int|null $work_order_id
 * @property int|null $tender_opportunity_id
 * @property int|null $customer_id
 * @property string $status
 * @property string|null $code
 * @property string $title
 * @property string $contracting_authority
 * @property string|null $procedure_type
 * @property string|null $cig
 * @property string|null $cup
 * @property string|null $rup_name
 * @property string|null $works_director_name
 * @property string|null $safety_coordinator_name
 * @property Carbon|null $awarded_on
 * @property Carbon|null $signed_on
 * @property Carbon|null $site_handover_on
 * @property Carbon|null $works_start_on
 * @property Carbon|null $works_due_on
 * @property Carbon|null $works_completed_on
 * @property string $contract_amount
 * @property string $security_costs
 * @property string $vat_rate
 * @property string $discount_rate
 * @property string $retention_rate
 * @property array<string, mixed>|null $meta
 * @property string|null $description
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 * @method static PublicProcurementContractFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementDeadline> $deadlines
 * @property-read int|null $deadlines_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementDocumentRequirement> $documentRequirements
 * @property-read int|null $document_requirements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementGuarantee> $guarantees
 * @property-read int|null $guarantees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementMeasurement> $measurements
 * @property-read int|null $measurements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementOfficialRecord> $officialRecords
 * @property-read int|null $official_records_count
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity|null $opportunity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementPriceItem> $priceItems
 * @property-read int|null $price_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementProgressReport> $progressReports
 * @property-read int|null $progress_reports_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContract onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContract withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementContract withoutTrashed()
 */
	final class PublicProcurementContract extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementDeadlineFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDeadline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDeadline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDeadline onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDeadline query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDeadline withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDeadline withoutTrashed()
 */
	final class PublicProcurementDeadline extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementDocumentRequirementFactory factory($count = null, $state = [])
 * @property-read \Modules\Compliance\Models\ComplianceDocument|null $complianceDocument
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity|null $opportunity
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDocumentRequirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDocumentRequirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDocumentRequirement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDocumentRequirement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDocumentRequirement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementDocumentRequirement withoutTrashed()
 */
	final class PublicProcurementDocumentRequirement extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementEconomicFrameFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementEconomicFrame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementEconomicFrame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementEconomicFrame query()
 */
	final class PublicProcurementEconomicFrame extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementGuaranteeFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Document\Models\Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementGuarantee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementGuarantee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementGuarantee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementGuarantee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementGuarantee withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementGuarantee withoutTrashed()
 */
	final class PublicProcurementGuarantee extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementMeasurementFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Intervention\Models\Intervention|null $intervention
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementPriceItem|null $priceItem
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementMeasurement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementMeasurement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementMeasurement withoutTrashed()
 */
	final class PublicProcurementMeasurement extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementOfficialRecordFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Document\Models\Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementOfficialRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementOfficialRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementOfficialRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementOfficialRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementOfficialRecord withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementOfficialRecord withoutTrashed()
 */
	final class PublicProcurementOfficialRecord extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementPaymentCertificateFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Billing\Models\Invoice|null $invoice
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementProgressReport|null $progressReport
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPaymentCertificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPaymentCertificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPaymentCertificate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPaymentCertificate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPaymentCertificate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPaymentCertificate withoutTrashed()
 */
	final class PublicProcurementPaymentCertificate extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementPriceItemFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPriceItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPriceItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPriceItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPriceItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPriceItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementPriceItem withoutTrashed()
 */
	final class PublicProcurementPriceItem extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementProgressReportFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementPaymentCertificate|null $paymentCertificate
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementProgressReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementProgressReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementProgressReport onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementProgressReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementProgressReport withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementProgressReport withoutTrashed()
 */
	final class PublicProcurementProgressReport extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @property int $id
 * @property string $status
 * @property int $score
 * @property array<int, string>|null $expired_documents
 * @property array<int, string>|null $missing_documents
 * @property Carbon|null $evaluated_at
 * @method static PublicProcurementTenderEligibilityCheckFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\User\Models\User|null $evaluatedBy
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity|null $opportunity
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderEligibilityCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderEligibilityCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderEligibilityCheck onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderEligibilityCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderEligibilityCheck withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderEligibilityCheck withoutTrashed()
 */
	final class PublicProcurementTenderEligibilityCheck extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @property int $id
 * @property int|null $tender_source_id
 * @property int|null $customer_id
 * @property string $status
 * @property string|null $external_id
 * @property string|null $source_url
 * @property string $contracting_authority
 * @property string $title
 * @property string|null $cig
 * @property string|null $cup
 * @property string|null $cpv_code
 * @property string|null $category
 * @property string|null $territory
 * @property string|null $estimated_amount
 * @property Carbon|null $published_at
 * @property Carbon|null $submission_deadline_at
 * @property Carbon|null $site_visit_due_at
 * @property array<int, string>|null $keywords
 * @property array<string, mixed>|null $ai_summary
 * @property string|null $raw_excerpt
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 * @method static PublicProcurementTenderOpportunityFactory factory($count = null, $state = [])
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementContract|null $contract
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementTenderEligibilityCheck> $eligibilityChecks
 * @property-read int|null $eligibility_checks_count
 * @property-read \Modules\PublicProcurement\Models\PublicProcurementTenderSource|null $source
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderOpportunity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderOpportunity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderOpportunity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderOpportunity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderOpportunity withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderOpportunity withoutTrashed()
 */
	final class PublicProcurementTenderOpportunity extends \Eloquent {}
}

namespace Modules\PublicProcurement\Models{
/**
 * @method static PublicProcurementTenderSourceFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity> $opportunities
 * @property-read int|null $opportunities_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderSource onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderSource query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderSource withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicProcurementTenderSource withoutTrashed()
 */
	final class PublicProcurementTenderSource extends \Eloquent {}
}

namespace Modules\Quotation\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $source_quotation_id
 * @property int|null $work_order_id
 * @property string|null $project
 * @property float|string|null $cost_total
 * @property Carbon|null $delivery_at
 * @property int|null $lead_time_days
 * @property string|null $code
 * @property string $status_code
 * @property string|null $title
 * @property string|null $notes
 * @property string|null $voice_transcript
 * @property array<string, mixed>|null $voice_ai_response
 * @property array<string, mixed>|null $technical_params
 * @property string $subtotal
 * @property string $discount_amount
 * @property string $taxable_amount
 * @property string $tax_percent
 * @property string $tax_amount
 * @property string $total
 * @property Carbon|null $issued_at
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Quotation\Models\QuotationItem> $items
 * @property-read int|null $items_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\Quotation\Database\Factories\QuotationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation withoutTrashed()
 */
	final class Quotation extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace Modules\Quotation\Models{
/**
 * @property int $id
 * @property int $quotation_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property string $discount_percent
 * @property string $line_total
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\Quotation\Models\Quotation|null $quotation
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Quotation\Database\Factories\QuotationItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationItem query()
 */
	final class QuotationItem extends \Eloquent {}
}

namespace Modules\Quotation\Models{
/**
 * @property int $id
 * @property string $title
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Quotation\Models\QuotationTemplateItem> $items
 * @property-read int|null $items_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Quotation\Database\Factories\QuotationTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplate withoutTrashed()
 */
	final class QuotationTemplate extends \Eloquent {}
}

namespace Modules\Quotation\Models{
/**
 * @property int $id
 * @property int $quotation_template_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property string $discount_percent
 * @property string $line_total
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\Quotation\Models\QuotationTemplate|null $template
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Quotation\Database\Factories\QuotationTemplateItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplateItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplateItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationTemplateItem query()
 */
	final class QuotationTemplateItem extends \Eloquent {}
}

namespace Modules\Quotation\Models{
/**
 * Sopralluogo in loco: rilievo con foto, video e note audio da cui nasce il preventivo.
 *
 * Allegati gestiti via Spatie MediaLibrary (collections: photos, videos,
 * voice_notes, documents) — non tramite il morph custom di Documents.
 *
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property int|null $work_order_id
 * @property int|null $quotation_id
 * @property int|null $performed_by_user_id
 * @property string|null $title
 * @property string|null $notes
 * @property string $status_code
 * @property Carbon|null $scheduled_at
 * @property Carbon|null $performed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\Customer\Models\CustomerSite|null $customerSite
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\User\Models\User|null $performedBy
 * @property-read \Modules\Quotation\Models\Quotation|null $quotation
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\Quotation\Database\Factories\SiteSurveyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSurvey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSurvey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSurvey onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSurvey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSurvey withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSurvey withoutTrashed()
 */
	final class SiteSurvey extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace Modules\Rating\Models{
/**
 * Modules\Rating\Models\Rating.
 *
 * Estende BaseRating per ereditare casts, fillable, scope e media conversions (DRY).
 *
 * @see BaseRating
 * @see https://github.com/spatie/laravel-schemaless-attributes
 * @see /Modules/Rating/docs/schemaless-attributes-errors.md
 * @property SchemalessAttributes $extra_attributes
 * @property RuleEnum             $rule
 * @property RuleEnum             $rule
 * @method static Builder|Rating newModelQuery()
 * @method static Builder|Rating newQuery()
 * @method static Builder|Rating query()
 * @method static Builder|Rating withExtraAttributes(array<string, mixed>|string $attributes = [], mixed $value = null)
 * @property int             $id
 * @property int             $user_id
 * @property float           $value
 * @property string|null     $related_type
 * @property string|null     $created_by
 * @property string|null     $updated_by
 * @property string|null     $deleted_by
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property int|null        $post_id
 * @property string|null     $title
 * @property string|null     $color
 * @property string|null     $icon
 * @property string|null     $txt
 * @property bool|null       $is_disabled
 * @property bool|null       $is_readonly
 * @property int|null        $order_column
 * @property Model|\Eloquent $linkedTo
 * @property int             $id
 * @property int             $user_id
 * @property float           $value
 * @property string|null     $related_type
 * @property string|null     $created_by
 * @property string|null     $updated_by
 * @property string|null     $deleted_by
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property int|null        $post_id
 * @property string|null     $title
 * @property string|null     $color
 * @property string|null     $icon
 * @property string|null     $txt
 * @property bool|null       $is_disabled
 * @property bool|null       $is_readonly
 * @property int|null        $order_column
 * @property Model|\Eloquent $linkedTo
 * @method static Builder|Rating whereColor($value)
 * @method static Builder|Rating whereCreatedAt($value)
 * @method static Builder|Rating whereCreatedBy($value)
 * @method static Builder|Rating whereDeletedBy($value)
 * @method static Builder|Rating whereIcon($value)
 * @method static Builder|Rating whereId($value)
 * @method static Builder|Rating whereIsDisabled($value)
 * @method static Builder|Rating whereIsReadonly($value)
 * @method static Builder|Rating whereOrderColumn($value)
 * @method static Builder|Rating wherePostId($value)
 * @method static Builder|Rating whereRelatedType($value)
 * @method static Builder|Rating whereRule($value)
 * @method static Builder|Rating whereTitle($value)
 * @method static Builder|Rating whereTxt($value)
 * @method static Builder|Rating whereUpdatedAt($value)
 * @method static Builder|Rating whereUpdatedBy($value)
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property int|null                    $media_count
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @method static RatingFactory factory($count = null, $state = [])
 * @property ProfileContract|null $deleter
 * @mixin Eloquent
 */
	class Rating extends \Eloquent {}
}

namespace Modules\Rating\Models{
/**
 * Modules\Rating\Models\RatingMorph.
 *
 * @property int         $id
 * @property bool        $is_winner
 * @property string|null $post_type
 * @property int|null    $post_id
 * @property string|null $related_type
 * @property int|null    $related_id
 * @property Rating|null $rating
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null    $auth_user_id
 * @method static Builder|RatingMorph newModelQuery()
 * @method static Builder|RatingMorph newQuery()
 * @method static Builder|RatingMorph query()
 * @method static Builder|RatingMorph whereAuthUserId($value)
 * @method static Builder|RatingMorph whereCreatedAt($value)
 * @method static Builder|RatingMorph whereCreatedBy($value)
 * @method static Builder|RatingMorph whereDeletedBy($value)
 * @method static Builder|RatingMorph whereId($value)
 * @method static Builder|RatingMorph wherePostId($value)
 * @method static Builder|RatingMorph wherePostType($value)
 * @method static Builder|RatingMorph whereRating($value)
 * @method static Builder|RatingMorph whereRelatedId($value)
 * @method static Builder|RatingMorph whereRelatedType($value)
 * @method static Builder|RatingMorph whereUpdatedAt($value)
 * @method static Builder|RatingMorph whereUpdatedBy($value)
 * @property string|null $user_id
 * @property string|null $model_type
 * @property int|null    $model_id
 * @property int         $rating_id
 * @property int|null    $value
 * @property string|null $note
 * @property string|null $deleted_at
 * @method static Builder|RatingMorph whereDeletedAt($value)
 * @method static Builder|RatingMorph whereIsWinner($value)
 * @method static Builder|RatingMorph whereModelId($value)
 * @method static Builder|RatingMorph whereModelType($value)
 * @method static Builder|RatingMorph whereNote($value)
 * @method static Builder|RatingMorph whereRatingId($value)
 * @method static Builder|RatingMorph whereUserId($value)
 * @method static Builder|RatingMorph whereValue($value)
 * @property Model|\Eloquent   $model
 * @property Model|null        $profile
 * @property UserContract|null $user
 * @property string            $reward
 * @method static Builder|RatingMorph whereReward($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string               $sum_credit_yes
 * @property string               $sum_credit_no
 * @property int                  $count_credit_yes
 * @property int                  $count_credit_no
 * @property string               $percentage
 * @method static Builder<static>|RatingMorph whereCountCreditNo($value)
 * @method static Builder<static>|RatingMorph whereCountCreditYes($value)
 * @method static Builder<static>|RatingMorph whereHasYesNo($value)
 * @method static Builder<static>|RatingMorph wherePercentage($value)
 * @method static Builder<static>|RatingMorph whereSumCreditNo($value)
 * @method static Builder<static>|RatingMorph whereSumCreditYes($value)
 * @property ProfileContract|null $deleter
 * @mixin Eloquent
 */
	class RatingMorph extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * @property int $id
 * @property int $sign_price_list_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $notes
 * @property string|null $family_code
 * @property string|null $color_code
 * @property numeric-string|null $thickness_mm
 * @property int|null $letter_height_min_mm
 * @property int|null $letter_height_max_mm
 * @property string|null $led_temperature_code
 * @property string $unit_code
 * @property numeric-string|null $markup_percent
 * @property numeric-string|null $manual_sale_price
 * @property int $sort_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Signage\Models\SignCatalogItemComponent> $components
 * @property-read int|null $components_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Signage\Models\SignPriceList|null $priceList
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignCatalogItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignCatalogItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignCatalogItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignCatalogItem query()
 */
	final class SignCatalogItem extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * @property int $id
 * @property int $sign_catalog_item_id
 * @property int $position
 * @property string $work_center_code
 * @property int|null $sign_price_list_item_id
 * @property string $description
 * @property string $unit_code
 * @property numeric-string $quantity
 * @property numeric-string|null $width_mm
 * @property numeric-string|null $height_mm
 * @property numeric-string $unit_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\Signage\Models\SignCatalogItem|null $catalogItem
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Signage\Models\SignPriceListItem|null $supplierItem
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignCatalogItemComponentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignCatalogItemComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignCatalogItemComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignCatalogItemComponent query()
 */
	final class SignCatalogItemComponent extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * One component/material row of a sign spec (distinta base). Priced by linking
 * to a price-list item; line_total feeds the quotation total.
 *
 * @property int $id
 * @property int $sign_spec_id
 * @property int $position
 * @property string $role_code
 * @property string $label
 * @property string|null $description
 * @property string|null $material
 * @property string|null $color_ral
 * @property numeric-string|null $thickness_mm
 * @property string|null $lighting
 * @property string $source_code
 * @property int|null $price_list_item_id
 * @property numeric-string|null $quantity
 * @property string|null $unit_code
 * @property numeric-string|null $unit_cost
 * @property numeric-string|null $line_total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Signage\Models\SignPriceListItem|null $priceListItem
 * @property-read \Modules\Signage\Models\SignSpec|null $signSpec
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignComponentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignComponent query()
 */
	final class SignComponent extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * How to derive a component quantity from the drawing geometry:
 *   quantity_per_piece = base(strategy) * factor + base_value  (floored at min_per_piece)
 *   quantity           = quantity_per_piece * pieces
 * where base() is the sheet area (m²), the bounding-box perimeter (m) or 0.
 *
 * @property int $id
 * @property string $role_code
 * @property string $unit_code
 * @property string $strategy_code
 * @property numeric-string $factor
 * @property numeric-string $base_value
 * @property numeric-string|null $min_per_piece
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignEstimationRuleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignEstimationRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignEstimationRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignEstimationRule query()
 */
	final class SignEstimationRule extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $brand
 * @property int|null $customer_id
 * @property numeric-string|null $default_markup_percent
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Signage\Models\SignCatalogItem> $items
 * @property-read int|null $items_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignPriceListFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignPriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignPriceList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignPriceList query()
 */
	final class SignPriceList extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $category_code
 * @property string|null $work_center_code
 * @property string|null $supplier_name
 * @property string|null $supplier_code
 * @property string $unit_code
 * @property numeric-string $unit_cost
 * @property string $currency
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Signage\Models\SignComponent> $components
 * @property-read int|null $components_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Signage\Models\SignSupplierPriceEntry> $priceEntries
 * @property-read int|null $price_entries_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignPriceListItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignPriceListItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignPriceListItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignPriceListItem query()
 */
	final class SignPriceListItem extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * The extracted head of a Signage sign drawing: title-block data plus the
 * decoded sign code. Owns the component rows that become quotation lines.
 *
 * @property int $id
 * @property string $source_file
 * @property string|null $drawing_title
 * @property string|null $customer_name
 * @property string|null $referent_name
 * @property string|null $project
 * @property string|null $brand
 * @property string|null $shop
 * @property string|null $sign_type_code
 * @property int|null $quantity
 * @property string|null $family
 * @property string|null $color_ral
 * @property numeric-string|null $thickness_mm
 * @property int|null $width_mm
 * @property int|null $height_mm
 * @property string|null $painting
 * @property string|null $finishing
 * @property string|null $lighting
 * @property string|null $scale
 * @property string|null $sheet_size
 * @property string|null $revision
 * @property string|null $creator
 * @property array<string, mixed>|null $layers
 * @property array<string, mixed>|null $notes
 * @property array<string, mixed>|null $raw_extraction
 * @property string|null $created_by_user_id
 * @property string|null $artwork_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Signage\Models\SignComponent> $components
 * @property-read int|null $components_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignSpecFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignSpec newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignSpec newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignSpec query()
 */
	final class SignSpec extends \Eloquent {}
}

namespace Modules\Signage\Models{
/**
 * @property int $id
 * @property int $sign_price_list_item_id
 * @property string|null $invoice_number
 * @property Carbon $recorded_on
 * @property numeric-string $amount
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Signage\Models\SignPriceListItem|null $supplierItem
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Signage\Database\Factories\SignSupplierPriceEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignSupplierPriceEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignSupplierPriceEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignSupplierPriceEntry query()
 */
	final class SignSupplierPriceEntry extends \Eloquent {}
}

namespace Modules\Signature\Models{
/**
 * @property int $id
 * @property string $signable_type
 * @property int $signable_id
 * @property int $user_id
 * @property string|null $disk
 * @property string|null $path
 * @property string|null $mime_type
 * @property Carbon|null $signed_at
 * @property array<string, mixed>|null $payload
 * @method static SignatureFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $signable
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signature query()
 */
	final class Signature extends \Eloquent {}
}

namespace Modules\Signature\Models{
/**
 * @property int $id
 * @property string $sketchable_type
 * @property int $sketchable_id
 * @property int $user_id
 * @property string|null $disk
 * @property string|null $path
 * @property string|null $mime_type
 * @property array<string, mixed>|null $payload
 * @method static SketchFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $sketchable
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sketch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sketch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sketch query()
 */
	final class Sketch extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Tenant\Database\Factories\DatabaseConfigFactory factory($count = null, $state = [])
 */
	class DatabaseConfig extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property string|null $id
 * @property string|null $name
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static \Modules\Tenant\Database\Factories\DomainFactory factory($count = null, $state = [])
 * @method static Builder<static>|Domain newModelQuery()
 * @method static Builder<static>|Domain newQuery()
 * @method static Builder<static>|Domain query()
 * @method static Builder<static>|Domain whereId($value)
 * @method static Builder<static>|Domain whereName($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Domain extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * Modello Tenant per la gestione multi-tenant dell'applicazione.
 *
 * @property-read User|null $creator
 * @property string|null $name
 * @property string|null $domain
 * @property string|null $database
 * @property string|null $slug
 * @property array<array-key, mixed>|null $settings
 * @property bool $is_active
 * @property Carbon|null $last_activity_at
 * @property string|null $logo
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $province
 * @property string|null $country
 * @property string|null $tax_code
 * @property string|null $vat_number
 * @property-read string $url
 * @property-read User|null $updater
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Modules\Tenant\Database\Factories\TenantFactory factory($count = null, $state = [])
 * @method static Builder<static>|Tenant newModelQuery()
 * @method static Builder<static>|Tenant newQuery()
 * @method static Builder<static>|Tenant query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Tenant extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property string|null $id
 * @property string|null $tenant_id
 * @property string|null $name
 * @property string|null $domain
 * @property bool $is_primary
 * @property string|null $status
 * @property string|null $verification_token
 * @property Carbon|null $verified_at
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static Builder<static>|TenantDomain newModelQuery()
 * @method static Builder<static>|TenantDomain newQuery()
 * @method static Builder<static>|TenantDomain query()
 * @method static Builder<static>|TenantDomain whereId($value)
 * @method static Builder<static>|TenantDomain whereName($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Tenant\Database\Factories\TenantDomainFactory factory($count = null, $state = [])
 */
	class TenantDomain extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property string|null $tenant_id
 * @property string|null $key
 * @property mixed $value
 * @property string|null $type
 * @property-read User|null $creator
 * @property-read Tenant|null $tenant
 * @property-read User|null $updater
 * @method static Builder<static>|TenantSetting newModelQuery()
 * @method static Builder<static>|TenantSetting newQuery()
 * @method static Builder<static>|TenantSetting query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Tenant\Database\Factories\TenantSettingFactory factory($count = null, $state = [])
 */
	class TenantSetting extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property string|null $tenant_id
 * @property string|null $plan_name
 * @property string|null $status
 * @property int|null $max_users
 * @property int|null $current_users
 * @property int|null $max_storage_gb
 * @property int|null $current_storage_gb
 * @property string|null $billing_cycle
 * @property float|null $billing_amount
 * @property Carbon|null $next_billing_date
 * @property Carbon|null $expires_at
 * @property-read User|null $creator
 * @property-read Tenant|null $tenant
 * @property-read User|null $updater
 * @method static Builder<static>|TenantSubscription newModelQuery()
 * @method static Builder<static>|TenantSubscription newQuery()
 * @method static Builder<static>|TenantSubscription query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Tenant\Database\Factories\TenantSubscriptionFactory factory($count = null, $state = [])
 */
	class TenantSubscription extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $status
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static \Modules\Tenant\Database\Factories\TestSushiModelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class TestSushiModel extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $zone
 * @property string|null $commission_percent
 * @property bool $is_active
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberAgentCommission> $commissions
 * @property-read int|null $commissions_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberAgentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgent withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgent withoutTrashed()
 */
	final class TimberAgent extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $agent_id
 * @property string $scope_type
 * @property string|null $category
 * @property int|null $product_id
 * @property string $rate_percent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\Timber\Models\TimberAgent|null $agent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberProduct|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberAgentCommissionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgentCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgentCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberAgentCommission query()
 */
	final class TimberAgentCommission extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $body
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $noteable
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberContactNoteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberContactNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberContactNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberContactNote query()
 */
	final class TimberContactNote extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string|null $code
 * @property string $business_name
 * @property string|null $vat_number
 * @property string|null $tax_code
 * @property bool $is_customer
 * @property bool $is_supplier
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $province
 * @property string $country
 * @property string|null $sdi_recipient_code
 * @property string|null $sdi_admin_ref
 * @property string|null $pec
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $fax
 * @property string|null $email
 * @property string|null $default_discount_percent
 * @property string|null $price_list_code
 * @property string|null $credit_limit
 * @property int|null $agent_id
 * @property string|null $payment_terms
 * @property string|null $iban
 * @property string|null $our_bank
 * @property string|null $carriage_code
 * @property string|null $vat_rate_code
 * @property string|null $intent_declaration_number
 * @property Carbon|null $intent_declaration_date
 * @property bool $send_documents_by_email
 * @property bool $invoice_with_withholding
 * @property string|null $document_notice
 * @property string|null $document_note
 * @property string|null $notes
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\Timber\Models\TimberAgent|null $agent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberContactNote> $contactNotes
 * @property-read int|null $contact_notes_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberCustomerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomer withoutTrashed()
 */
	final class TimberCustomer extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $customer_id
 * @property int $product_id
 * @property string $net_price
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Timber\Models\TimberCustomer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberProduct|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberCustomerPriceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomerPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomerPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberCustomerPrice query()
 */
	final class TimberCustomerPrice extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $processing_step_id
 * @property string $output_code
 * @property string $wood_species
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $status_code
 * @property string $current_area_code
 * @property bool $is_waste
 * @property array<string, mixed>|null $meta
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $root_lot_id
 * @property string|null $traceable_code
 * @property string|null $stage_type_code
 * @property array<int, mixed>|null $stage_history
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberProcessingStep> $consumingProcessingSteps
 * @property-read int|null $consuming_processing_steps_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberDryingCycle> $dryingCycles
 * @property-read int|null $drying_cycles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberLabelPrint> $labelPrints
 * @property-read int|null $label_prints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberPackagedUnit> $packagedUnits
 * @property-read int|null $packaged_units_count
 * @property-read \Modules\Timber\Models\TimberProcessingStep|null $processingStep
 * @property-read \Modules\Timber\Models\TimberLot|null $rootLot
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read string $wood_species_name
 * @method static \Modules\Timber\Database\Factories\TimberDerivedMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDerivedMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDerivedMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDerivedMaterial query()
 */
	final class TimberDerivedMaterial extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $document_type_code
 * @property string|null $number
 * @property Carbon $document_date
 * @property int|null $customer_id
 * @property string $status_code
 * @property string|null $reference
 * @property string $total_amount
 * @property string|null $notes
 * @property Carbon|null $stock_posted_at
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Timber\Models\TimberCustomer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberDocumentLine> $lines
 * @property-read int|null $lines_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberDocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocument onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocument withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocument withoutTrashed()
 */
	final class TimberDocument extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $document_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $unit_price
 * @property string $discount_percent
 * @property string|null $lot_code
 * @property string $line_total
 * @property int $position
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberDocument|null $document
 * @property-read \Modules\Timber\Models\TimberProduct|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberDocumentLineFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocumentLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocumentLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDocumentLine query()
 */
	final class TimberDocumentLine extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property Carbon $entered_at
 * @property Carbon|null $expected_exit_at
 * @property Carbon|null $actual_exit_at
 * @property string|null $target_humidity_percent
 * @property string $available_quantity
 * @property string $status_code
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $dryable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberHumidityCheck> $humidityChecks
 * @property-read int|null $humidity_checks_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberDryingCycleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDryingCycle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDryingCycle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberDryingCycle query()
 */
	final class TimberDryingCycle extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $id
 * @property int $invoice_id
 * @property string $direction
 * @property string $sdi_status
 * @property string|null $sdi_identifier
 * @property string|null $file_name
 * @property string|null $xml_content
 * @property Carbon|null $transmitted_at
 * @property Carbon|null $delivered_at
 * @property string|null $error_message
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberInvoice|null $invoice
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberEInvoiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberEInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberEInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberEInvoice query()
 */
	final class TimberEInvoice extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $drying_cycle_id
 * @property Carbon $checked_at
 * @property string $humidity_percent
 * @property int|null $checked_by_user_id
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $checkedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberDryingCycle|null $dryingCycle
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberHumidityCheckFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberHumidityCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberHumidityCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberHumidityCheck query()
 */
	final class TimberHumidityCheck extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $id
 * @property string $kind
 * @property string|null $number
 * @property Carbon $invoice_date
 * @property int|null $customer_id
 * @property int|null $source_document_id
 * @property string $status_code
 * @property string|null $payment_method_code
 * @property string|null $payment_terms
 * @property string $taxable_amount
 * @property string $vat_amount
 * @property string $total_amount
 * @property string|null $notes
 * @property Carbon|null $issued_at
 * @property Carbon|null $stock_posted_at
 * @property int|null $created_by_user_id
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberInvoiceLine> $lines
 * @property-read int|null $lines_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberInvoiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoice withoutTrashed()
 */
	final class TimberInvoice extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $id
 * @property int $invoice_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $unit_price
 * @property string $discount_percent
 * @property string $vat_rate
 * @property string|null $lot_code
 * @property string $line_total
 * @property int $position
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberInvoice|null $invoice
 * @property-read \Modules\Catalog\Models\Product|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberInvoiceLineFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoiceLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoiceLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberInvoiceLine query()
 */
	final class TimberInvoiceLine extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $label_code
 * @property string $qr_payload
 * @property string $print_reason_code
 * @property int|null $printed_by_user_id
 * @property Carbon $printed_at
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $labelable
 * @property-read \Modules\User\Models\User|null $printedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberLabelPrintFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberLabelPrint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberLabelPrint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberLabelPrint query()
 */
	final class TimberLabelPrint extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $purchase_record_id
 * @property string $lot_code
 * @property string $status_code
 * @property string $current_area_code
 * @property string $quantity_available
 * @property string $unit_of_measure
 * @property string $wood_species
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberDryingCycle> $dryingCycles
 * @property-read int|null $drying_cycles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberLabelPrint> $labelPrints
 * @property-read int|null $label_prints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberProcessingStep> $processingSteps
 * @property-read int|null $processing_steps_count
 * @property-read \Modules\Timber\Models\TimberPurchaseRecord|null $purchaseRecord
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read string $wood_species_name
 * @method static \Modules\Timber\Database\Factories\TimberLotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberLot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberLot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberLot query()
 */
	final class TimberLot extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string|null $from_area_code
 * @property string $to_area_code
 * @property int|null $moved_by_user_id
 * @property Carbon $moved_at
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $movable
 * @property-read \Modules\User\Models\User|null $movedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberMovementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberMovement query()
 */
	final class TimberMovement extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $derived_material_id
 * @property string $package_code
 * @property string $qr_code
 * @property string $package_type_code
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $status_code
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $article_code
 * @property string|null $traceable_code
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberDerivedMaterial|null $derivedMaterial
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberLabelPrint> $labelPrints
 * @property-read int|null $label_prints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberPackagedUnitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPackagedUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPackagedUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPackagedUnit query()
 */
	final class TimberPackagedUnit extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $customer_id
 * @property string $direction
 * @property Carbon $due_date
 * @property string $amount
 * @property string|null $payment_method_code
 * @property string $status_code
 * @property Carbon|null $paid_at
 * @property Carbon|null $reminded_at
 * @property string|null $notes
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberInvoice|null $invoice
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberPaymentInstallmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPaymentInstallment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPaymentInstallment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPaymentInstallment query()
 */
	final class TimberPaymentInstallment extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $code
 * @property string $name
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberPriceListItem> $items
 * @property-read int|null $items_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberPriceListVersion> $versions
 * @property-read int|null $versions_count
 * @method static \Modules\Timber\Database\Factories\TimberPriceListFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceList query()
 */
	final class TimberPriceList extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $price_list_id
 * @property int $product_id
 * @property string $net_price
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberPriceList|null $priceList
 * @property-read \Modules\Timber\Models\TimberProduct|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberPriceListItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceListItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceListItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceListItem query()
 */
	final class TimberPriceListItem extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $price_list_id
 * @property array<int, mixed> $items
 * @property string|null $note
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberPriceList|null $priceList
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberPriceListVersionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceListVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceListVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPriceListVersion query()
 */
	final class TimberPriceListVersion extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $work_sheet_code
 * @property string $step_type_code
 * @property string|null $input_quantity
 * @property string|null $output_quantity
 * @property string|null $waste_quantity
 * @property string|null $recoverable_quantity
 * @property string $unit_of_measure
 * @property int|null $executed_by_user_id
 * @property Carbon $executed_at
 * @property string|null $notes
 * @property array<string, mixed>|null $meta
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberDerivedMaterial> $derivedMaterials
 * @property-read int|null $derived_materials_count
 * @property-read \Modules\User\Models\User|null $executedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberDerivedMaterial> $inputDerivedMaterials
 * @property-read int|null $input_derived_materials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberLot> $inputLots
 * @property-read int|null $input_lots_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberProcessingStepFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProcessingStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProcessingStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProcessingStep query()
 */
	final class TimberProcessingStep extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * Pivot polimorfica: TimberProcessingStep ↔ TimberLot|TimberDerivedMaterial.
 *
 * @property int $processing_step_id
 * @property string $processable_type
 * @property int $processable_id
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $processable
 * @property-read \Modules\Timber\Models\TimberProcessingStep|null $processingStep
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberProcessingStepProcessableFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProcessingStepProcessable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProcessingStepProcessable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProcessingStepProcessable query()
 */
	final class TimberProcessingStepProcessable extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string|null $code
 * @property string $description
 * @property string $product_type_code
 * @property string|null $category
 * @property string|null $subcategory
 * @property string $unit_of_measure
 * @property array<string, mixed>|null $list_prices
 * @property string|null $notes
 * @property string|null $barcode
 * @property string|null $vat_rate_code
 * @property string|null $commission_class
 * @property string|null $product_url
 * @property string|null $manufacturer
 * @property bool $is_assembled
 * @property bool $publish_on_ecommerce
 * @property string|null $free_1
 * @property string|null $free_2
 * @property string|null $free_3
 * @property string|null $free_4
 * @property int|null $supplier_id
 * @property string|null $supplier_product_code
 * @property string|null $supplier_net_price
 * @property string|null $supply_notes
 * @property string|null $reorder_multiple
 * @property int|null $reorder_lead_days
 * @property string|null $minimum_stock
 * @property string|null $warehouse_location
 * @property string|null $average_cost
 * @property string|null $last_cost
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberSupplier|null $supplier
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProduct withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberProduct withoutTrashed()
 */
	final class TimberProduct extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property int $supplier_id
 * @property int|null $transporter_id
 * @property string|null $origin_forest
 * @property string|null $ddt_number
 * @property Carbon|null $ddt_date
 * @property string $wood_species
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string|null $gross_weight
 * @property string|null $net_weight
 * @property string|null $unit_purchase_price
 * @property string|null $certifications
 * @property string|null $photo_path
 * @property string|null $notes
 * @property string $status_code
 * @property int|null $created_by_user_id
 * @property Carbon|null $received_at
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $purchase_price_unit
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberLot|null $lot
 * @property-read \Modules\Timber\Models\TimberSupplier|null $supplier
 * @property-read \Modules\Timber\Models\TimberTransporter|null $transporter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read string $wood_species_name
 * @method static \Modules\Timber\Database\Factories\TimberPurchaseRecordFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPurchaseRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPurchaseRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberPurchaseRecord query()
 */
	final class TimberPurchaseRecord extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property Carbon $movement_date
 * @property int $product_id
 * @property string $direction_code
 * @property string $quantity
 * @property string $unit_of_measure
 * @property int|null $customer_id
 * @property string|null $document_type_code
 * @property string|null $document_reference
 * @property string|null $lot_code
 * @property string|null $category
 * @property string|null $unit_cost
 * @property string|null $notes
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Timber\Models\TimberCustomer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Timber\Models\TimberProduct|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberStockMovementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberStockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberStockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberStockMovement query()
 */
	final class TimberStockMovement extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $tax_code
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $province
 * @property string $country_code
 * @property string|null $certifications
 * @property string|null $notes
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberContactNote> $contactNotes
 * @property-read int|null $contact_notes_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberPurchaseRecord> $purchaseRecords
 * @property-read int|null $purchase_records_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberSupplierFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberSupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberSupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberSupplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberSupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberSupplier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberSupplier withoutTrashed()
 */
	final class TimberSupplier extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $vehicle_plate
 * @property string|null $notes
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Timber\Models\TimberPurchaseRecord> $purchaseRecords
 * @property-read int|null $purchase_records_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberTransporterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberTransporter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberTransporter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberTransporter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberTransporter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberTransporter withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberTransporter withoutTrashed()
 */
	final class TimberTransporter extends \Eloquent {}
}

namespace Modules\Timber\Models{
/**
 * @property string $code
 * @property string $name
 * @property int $sort_order
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Timber\Database\Factories\TimberWoodSpeciesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberWoodSpecies newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberWoodSpecies newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimberWoodSpecies query()
 */
	final class TimberWoodSpecies extends \Eloquent {}
}

namespace Modules\UI\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string $title
 * @property string $slug
 * @property int|null $parent_id
 * @property string|null $description
 * @property string|null $icon
 * @property bool $is_active
 * @property int $sort_order
 * @method static \Modules\UI\Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|Category newModelQuery()
 * @method static Builder<static>|Category newQuery()
 * @method static Builder<static>|Category query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 */
	class Category extends \Eloquent {}
}

namespace Modules\UI\Models{
/**
 * Collection model for UI module.
 *
 * FormBuilder module not available - extending from XotBaseModel instead.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property int|null $theme_id
 * @property bool $is_active
 * @property int|null $order
 * @method static \Modules\UI\Database\Factories\CollectionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Collection newModelQuery()
 * @method static Builder<static>|Collection newQuery()
 * @method static Builder<static>|Collection query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 */
	class Collection extends \Eloquent {}
}

namespace Modules\UI\Models{
/**
 * FieldOption model for UI module.
 *
 * FormBuilder module not available - extending from XotBaseModel instead.
 *
 * @method static \Modules\UI\Database\Factories\FieldOptionFactory factory($count = null, $state = [])
 * @method static Builder<static>|FieldOption newModelQuery()
 * @method static Builder<static>|FieldOption newQuery()
 * @method static Builder<static>|FieldOption query()
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 */
	class FieldOption extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Authentication Model.
 *
 * Tracks user authentication attempts and sessions.
 *
 * @property int         $id
 * @property string      $type                 Type of authentication (e.g., 'login', 'logout')
 * @property string|null $ip_address           IP address used for authentication
 * @property string|null $user_agent           User agent string from the request
 * @property string|null $location             Geographic location derived from IP
 * @property bool        $login_successful     Whether the login attempt was successful
 * @property Carbon|null $login_at             When the login attempt occurred
 * @property Carbon|null $logout_at            When the logout occurred
 * @property string      $authenticatable_type The class name of the authenticatable model
 * @property string      $authenticatable_id   The ID of the authenticatable model
 * @property Carbon|null $created_at           When the record was created
 * @property Carbon|null $updated_at           When the record was last updated
 * @method static Builder<static>|Authentication newModelQuery()
 * @method static Builder<static>|Authentication newQuery()
 * @method static Builder<static>|Authentication query()
 * @method static Builder<static>|Authentication whereCreatedAt($value)
 * @method static Builder<static>|Authentication whereId($value)
 * @method static Builder<static>|Authentication whereIpAddress($value)
 * @method static Builder<static>|Authentication whereLocation($value)
 * @method static Builder<static>|Authentication whereType($value)
 * @method static Builder<static>|Authentication whereUpdatedAt($value)
 * @method static Builder<static>|Authentication whereUserAgent($value)
 * @method static Builder<static>|Authentication whereLoginAt($value)
 * @method static Builder<static>|Authentication whereLogoutAt($value)
 * @method static Builder<static>|Authentication whereLoginSuccessful($value)
 * @method static Builder<static>|Authentication whereAuthenticatableType($value)
 * @method static Builder<static>|Authentication whereAuthenticatableId($value)
 * @property Model|\Eloquent      $authenticatable
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property string|null          $deleted_at
 * @property string|null          $deleted_by
 * @method static \Modules\User\Database\Factories\AuthenticationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Authentication                         whereCreatedBy($value)
 * @method static Builder<static>|Authentication                         whereDeletedAt($value)
 * @method static Builder<static>|Authentication                         whereDeletedBy($value)
 * @method static Builder<static>|Authentication                         whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class Authentication extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property int                       $id
 * @property string                    $authenticatable_type
 * @property int                       $authenticatable_id
 * @property string|null               $ip_address
 * @property string|null               $user_agent
 * @property Carbon|null               $login_at
 * @property bool                      $login_successful
 * @property Carbon|null               $logout_at
 * @property bool                      $cleared_by_user
 * @property array<string, mixed>|null $location
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $updated_by
 * @property string|null               $created_by
 * @property Model|\Eloquent           $authenticatable
 * @property ProfileContract|null      $creator
 * @property ProfileContract|null      $updater
 * @method static Builder|AuthenticationLog newModelQuery()
 * @method static Builder|AuthenticationLog newQuery()
 * @method static Builder|AuthenticationLog query()
 * @method static Builder|AuthenticationLog whereAuthenticatableId($value)
 * @method static Builder|AuthenticationLog whereAuthenticatableType($value)
 * @method static Builder|AuthenticationLog whereClearedByUser($value)
 * @method static Builder|AuthenticationLog whereCreatedAt($value)
 * @method static Builder|AuthenticationLog whereCreatedBy($value)
 * @method static Builder|AuthenticationLog whereId($value)
 * @method static Builder|AuthenticationLog whereIpAddress($value)
 * @method static Builder|AuthenticationLog whereLocation($value)
 * @method static Builder|AuthenticationLog whereLoginAt($value)
 * @method static Builder|AuthenticationLog whereLoginSuccessful($value)
 * @method static Builder|AuthenticationLog whereLogoutAt($value)
 * @method static Builder|AuthenticationLog whereUpdatedAt($value)
 * @method static Builder|AuthenticationLog whereUpdatedBy($value)
 * @method static Builder|AuthenticationLog whereUserAgent($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\AuthenticationLogFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class AuthenticationLog extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Device model representing a user's device in the system.
 *
 * @property EloquentCollection<int, Model&UserContract> $users
 * @property int|null                                    $users_count
 * @method static Builder|Device newModelQuery()
 * @method static Builder|Device newQuery()
 * @method static Builder|Device query()
 * @method static Builder|Device whereBrowser($value)
 * @method static Builder|Device whereCreatedAt($value)
 * @method static Builder|Device whereCreatedBy($value)
 * @method static Builder|Device whereDevice($value)
 * @method static Builder|Device whereId($value)
 * @method static Builder|Device whereIsDesktop($value)
 * @method static Builder|Device whereIsMobile($value)
 * @method static Builder|Device whereIsPhone($value)
 * @method static Builder|Device whereIsRobot($value)
 * @method static Builder|Device whereIsTablet($value)
 * @method static Builder|Device whereLanguages($value)
 * @method static Builder|Device whereMobileId($value)
 * @method static Builder|Device wherePlatform($value)
 * @method static Builder|Device whereRobot($value)
 * @method static Builder|Device whereUpdatedAt($value)
 * @method static Builder|Device whereUpdatedBy($value)
 * @method static Builder|Device whereVersion($value)
 * @property DeviceUser              $pivot
 * @property ProfileContract|null    $creator
 * @property ProfileContract|null    $updater
 * @property string                  $id
 * @property string|null             $mobile_id
 * @property array<int, string>|null $languages
 * @property string|null             $device
 * @property string|null             $platform
 * @property string|null             $browser
 * @property string|null             $version
 * @property bool|null               $is_robot
 * @property string|null             $robot
 * @property bool|null               $is_desktop
 * @property bool|null               $is_mobile
 * @property bool|null               $is_tablet
 * @property bool|null               $is_phone
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $updated_by
 * @property string|null             $created_by
 * @property string|null             $uuid
 * @method static Builder<static>|Device whereUuid($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\DeviceFactory factory($count = null, $state = [])
 * @property string|null $name
 * @property string|null $type
 * @method static Builder<static>|Device whereName($value)
 * @method static Builder<static>|Device whereType($value)
 * @mixin \Eloquent
 */
	class Device extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * DeviceProfile Model.
 *
 * Represents the relationship between a device and a user profile.
 * Extends the base DeviceUser model to add specific functionality.
 *
 * @property ProfileContract|null $creator
 * @property Device|null          $device
 * @property ProfileContract|null $profile
 * @property ProfileContract|null $updater
 * @property User|null            $user
 * @method static Builder<static>|DeviceProfile newModelQuery()
 * @method static Builder<static>|DeviceProfile newQuery()
 * @method static Builder<static>|DeviceProfile query()
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\DeviceProfileFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class DeviceProfile extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\DeviceUser.
 *
 * @property Device|null $device
 * @method static Builder|DeviceUser newModelQuery()
 * @method static Builder|DeviceUser newQuery()
 * @method static Builder|DeviceUser query()
 * @property string      $id
 * @property string      $device_id
 * @property string      $user_id
 * @property Carbon|null $login_at
 * @property Carbon|null $logout_at
 * @property string|null $push_notifications_token
 * @property bool|null   $push_notifications_enabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|DeviceUser whereCreatedAt($value)
 * @method static Builder|DeviceUser whereCreatedBy($value)
 * @method static Builder|DeviceUser whereDeviceId($value)
 * @method static Builder|DeviceUser whereId($value)
 * @method static Builder|DeviceUser whereLoginAt($value)
 * @method static Builder|DeviceUser whereLogoutAt($value)
 * @method static Builder|DeviceUser wherePushNotificationsEnabled($value)
 * @method static Builder|DeviceUser wherePushNotificationsToken($value)
 * @method static Builder|DeviceUser whereUpdatedAt($value)
 * @method static Builder|DeviceUser whereUpdatedBy($value)
 * @method static Builder|DeviceUser whereUserId($value)
 * @property ProfileContract|null $profile
 * @property UserContract|null    $user
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\DeviceUserFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class DeviceUser extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property SchemalessAttributes $extra_attributes
 * @method static Builder|Extra newModelQuery()
 * @method static Builder|Extra newQuery()
 * @method static Builder|Extra query()
 * @method static Builder|Extra withExtraAttributes()
 * @property int         $id
 * @property string      $model_type
 * @property string      $model_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|Extra whereCreatedAt($value)
 * @method static Builder|Extra whereCreatedBy($value)
 * @method static Builder|Extra whereDeletedAt($value)
 * @method static Builder|Extra whereDeletedBy($value)
 * @method static Builder|Extra whereExtraAttributes($value)
 * @method static Builder|Extra whereId($value)
 * @method static Builder|Extra whereModelId($value)
 * @method static Builder|Extra whereModelType($value)
 * @method static Builder|Extra whereUpdatedAt($value)
 * @method static Builder|Extra whereUpdatedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\ExtraFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	final class Extra extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder|Feature newModelQuery()
 * @method static Builder|Feature newQuery()
 * @method static Builder|Feature query()
 * @property string      $id
 * @property string      $name
 * @property string      $scope
 * @property string      $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|Feature whereCreatedAt($value)
 * @method static Builder|Feature whereCreatedBy($value)
 * @method static Builder|Feature whereDeletedAt($value)
 * @method static Builder|Feature whereDeletedBy($value)
 * @method static Builder|Feature whereId($value)
 * @method static Builder|Feature whereName($value)
 * @method static Builder|Feature whereScope($value)
 * @method static Builder|Feature whereUpdatedAt($value)
 * @method static Builder|Feature whereUpdatedBy($value)
 * @method static Builder|Feature whereValue($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\FeatureFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Feature extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\Membership.
 *
 * @property string $role
 * @method static Builder|Membership newModelQuery()
 * @method static Builder|Membership newQuery()
 * @method static Builder|Membership query()
 * @property int         $id
 * @property string|null $team_id
 * @property string|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $customer_id
 * @method static Builder|Membership whereCreatedAt($value)
 * @method static Builder|Membership whereCreatedBy($value)
 * @method static Builder|Membership whereCustomerId($value)
 * @method static Builder|Membership whereRole($value)
 * @method static Builder|Membership whereTeamId($value)
 * @method static Builder|Membership whereUpdatedAt($value)
 * @method static Builder|Membership whereUpdatedBy($value)
 * @method static Builder|Membership whereUserId($value)
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|Membership whereDeletedAt($value)
 * @method static Builder|Membership whereDeletedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @method static Builder<static>|Membership whereId($value)
 * @property array<array-key, mixed>|null $permissions
 * @property string|null                  $joined_at
 * @method static Builder<static>|Membership whereJoinedAt($value)
 * @method static Builder<static>|Membership wherePermissions($value)
 * @property string $uuid
 * @method static Builder<static>|Membership whereUuid($value)
 * @mixin \Eloquent
 */
	class Membership extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\ModelHasPermission.
 *
 * @property int    $id
 * @property int    $permission_id
 * @property string $model_type
 * @property string $model_id
 * @method static Builder|ModelHasPermission newModelQuery()
 * @method static Builder|ModelHasPermission newQuery()
 * @method static Builder|ModelHasPermission query()
 * @method static Builder|ModelHasPermission whereId($value)
 * @method static Builder|ModelHasPermission whereModelId($value)
 * @method static Builder|ModelHasPermission whereModelType($value)
 * @method static Builder|ModelHasPermission wherePermissionId($value)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|ModelHasPermission whereCreatedAt($value)
 * @method static Builder|ModelHasPermission whereCreatedBy($value)
 * @method static Builder|ModelHasPermission whereUpdatedAt($value)
 * @method static Builder|ModelHasPermission whereUpdatedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string|null          $team_id
 * @method static Builder|ModelHasPermission whereTeamId($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\ModelHasPermissionFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class ModelHasPermission extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\ModelHasRole.
 *
 * @property string      $id
 * @property string      $role_id
 * @property string      $model_type
 * @property string      $model_id
 * @property int|null    $team_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|ModelHasRole newModelQuery()
 * @method static Builder|ModelHasRole newQuery()
 * @method static Builder|ModelHasRole query()
 * @method static Builder|ModelHasRole whereCreatedAt($value)
 * @method static Builder|ModelHasRole whereCreatedBy($value)
 * @method static Builder|ModelHasRole whereId($value)
 * @method static Builder|ModelHasRole whereModelId($value)
 * @method static Builder|ModelHasRole whereModelType($value)
 * @method static Builder|ModelHasRole whereRoleId($value)
 * @method static Builder|ModelHasRole whereTeamId($value)
 * @method static Builder|ModelHasRole whereUpdatedAt($value)
 * @method static Builder|ModelHasRole whereUpdatedBy($value)
 * @property string $uuid (DC2Type:guid)
 * @method static Builder|ModelHasRole whereUuid($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\ModelHasRoleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class ModelHasRole extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\ModelHasRole.
 *
 * @property string      $id
 * @property string      $role_id
 * @property string      $model_type
 * @property string      $model_id
 * @property int|null    $team_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|ModelHasRole newModelQuery()
 * @method static Builder|ModelHasRole newQuery()
 * @method static Builder|ModelHasRole query()
 * @method static Builder|ModelHasRole whereCreatedAt($value)
 * @method static Builder|ModelHasRole whereCreatedBy($value)
 * @method static Builder|ModelHasRole whereId($value)
 * @method static Builder|ModelHasRole whereModelId($value)
 * @method static Builder|ModelHasRole whereModelType($value)
 * @method static Builder|ModelHasRole whereRoleId($value)
 * @method static Builder|ModelHasRole whereTeamId($value)
 * @method static Builder|ModelHasRole whereUpdatedAt($value)
 * @method static Builder|ModelHasRole whereUpdatedBy($value)
 * @property string $uuid (DC2Type:guid)
 * @method static Builder|ModelHasRole whereUuid($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\ModelRoleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class ModelRole extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property Model|\Eloquent $notifiable
 * @method static DatabaseNotificationCollection<int, static>          all($columns = ['*'])
 * @method static DatabaseNotificationCollection<int, static>          get($columns = ['*'])
 * @method static Builder|Notification                                 newModelQuery()
 * @method static Builder|Notification                                 newQuery()
 * @method static Builder|Notification                                 query()
 * @method static Builder|Notification                                 read()
 * @method static Builder|Notification                                 unread()
 * @method static DatabaseNotificationCollection<int, static>          all($columns = ['*'])
 * @method static DatabaseNotificationCollection<int, static>          get($columns = ['*'])
 * @method static DatabaseNotificationCollection<int, static>          all($columns = ['*'])
 * @method static DatabaseNotificationCollection<int, static>          get($columns = ['*'])
 * @method static \Modules\User\Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Notification extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\OauthAccessToken.
 *
 * @property string            $id
 * @property string|null       $user_id
 * @property string            $client_id
 * @property string|null       $name
 * @property list<string>|null $scopes
 * @property bool              $revoked
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property Carbon|null       $expires_at
 * @property OauthClient|null  $client
 * @property User|null         $user
 * @method static Builder|OauthAccessToken newModelQuery()
 * @method static Builder|OauthAccessToken newQuery()
 * @method static Builder|OauthAccessToken query()
 * @method static Builder|OauthAccessToken whereClientId($value)
 * @method static Builder|OauthAccessToken whereCreatedAt($value)
 * @method static Builder|OauthAccessToken whereExpiresAt($value)
 * @method static Builder|OauthAccessToken whereId($value)
 * @method static Builder|OauthAccessToken whereName($value)
 * @method static Builder|OauthAccessToken whereRevoked($value)
 * @method static Builder|OauthAccessToken whereScopes($value)
 * @method static Builder|OauthAccessToken whereUpdatedAt($value)
 * @method static Builder|OauthAccessToken whereUserId($value)
 * @property OauthRefreshToken|null $refreshToken
 * @property string|null            $updated_by
 * @property string|null            $created_by
 * @property string|null            $deleted_at
 * @property string|null            $deleted_by
 * @method static Builder<static>|OauthAccessToken                               whereCreatedBy($value)
 * @method static Builder<static>|OauthAccessToken                               whereDeletedAt($value)
 * @method static Builder<static>|OauthAccessToken                               whereDeletedBy($value)
 * @method static Builder<static>|OauthAccessToken                               whereUpdatedBy($value)
 * @method static static                                                         create(array<string, mixed> $attributes = [])
 * @method static static                                                         firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                         updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAccessToken existsIn(array<int, string> $haystack)
 * @mixin \Eloquent
 */
	class OauthAccessToken extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property string           $id
 * @property string           $user_id    (DC2Type:guid)
 * @property string           $client_id
 * @property string|null      $scopes
 * @property bool             $revoked
 * @property Carbon|null      $expires_at
 * @property OauthClient|null $client
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthAuthCode whereUserId($value)
 * @mixin \Eloquent
 */
	class OauthAuthCode extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property string                                $id
 * @property string|null                           $name
 * @property string|null                           $secret
 * @property string|null                           $provider
 * @property string|null                           $redirect
 * @property bool                                  $personal_access_client
 * @property bool                                  $password_client
 * @property bool                                  $revoked
 * @property string|null                           $user_id
 * @property User|null                             $user
 * @property Carbon|null                           $created_at
 * @property Carbon|null                           $updated_at
 * @property string|null                           $updated_by
 * @property string|null                           $created_by
 * @property string|null                           $owner_type
 * @property string|null                           $owner_id
 * @property array<int, string>                    $redirect_uris
 * @property array<string, bool>                   $grant_types
 * @property Collection<int, OauthAuthCode>        $authCodes
 * @property int|null                              $auth_codes_count
 * @property \Illuminate\Foundation\Auth\User|null $owner
 * @property string|null                           $plain_secret
 * @property Collection<int, OauthToken>           $tokens
 * @property int|null                              $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient existsIn(array<int, string> $haystack)
 * @method static \Laravel\Passport\Database\Factories\ClientFactory        factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereGrantTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient wherePasswordClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient wherePersonalAccessClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereRedirectUris($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthClient whereUserId($value)
 * @mixin \Eloquent
 */
	class OauthClient extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Class OauthDeviceCode.
 *
 * Wrapper for Laravel Passport DeviceCode model.
 *
 * @property bool $revoked
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthDeviceCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthDeviceCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthDeviceCode query()
 * @mixin \Eloquent
 */
	class OauthDeviceCode extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property string           $id
 * @property string           $client_id
 * @property OauthClient|null $client
 * @property Carbon|null      $created_at
 * @property Carbon|null      $updated_at
 * @property string|null      $updated_by
 * @property string|null      $created_by
 * @method static \Modules\User\Database\Factories\OauthPersonalAccessClientFactory       factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthPersonalAccessClient whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 */
	class OauthPersonalAccessClient extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property string                  $id
 * @property string                  $access_token_id
 * @property bool                    $revoked
 * @property \DateTimeInterface|null $expires_at
 * @property OauthToken|null         $accessToken
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken whereAccessTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthRefreshToken whereRevoked($value)
 * @mixin \Eloquent
 */
	class OauthRefreshToken extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property bool                         $revoked
 * @property int|string|null              $user_id
 * @property string                       $id
 * @property string                       $client_id
 * @property string|null                  $name
 * @property array<array-key, mixed>|null $scopes
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property Carbon|null                  $expires_at
 * @property string|null                  $updated_by
 * @property string|null                  $created_by
 * @property string|null                  $deleted_at
 * @property string|null                  $deleted_by
 * @property OauthClient|null             $client
 * @property OauthRefreshToken|null       $refreshToken
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken existsIn(array<int, string> $haystack)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Modules\User\Models\User|null $user
 */
	class OauthToken extends \Eloquent {}
}

namespace Modules\User\Models\Passport{
/**
 * Custom Passport Client model to fix compatibility issues with Laravel 12.
 *
 * @property Collection<int, OauthAuthCode> $authCodes
 * @property int|null                       $auth_codes_count
 * @property list<string>                   $grant_types
 * @property User                           $owner
 * @property string|null                    $plain_secret
 * @property list<string>                   $redirect_uris
 * @property string|null                    $secret
 * @property Collection<int, OauthToken>    $tokens
 * @property int|null                       $tokens_count
 * @property \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client existsIn(array<int, string> $haystack)
 * @method static \Laravel\Passport\Database\Factories\ClientFactory   factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @mixin \Eloquent
 */
	class Client extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\PasswordReset.
 *
 * @property int         $id
 * @property string      $email
 * @property string      $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $user_id
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|PasswordReset newModelQuery()
 * @method static Builder|PasswordReset newQuery()
 * @method static Builder|PasswordReset query()
 * @method static Builder|PasswordReset whereCreatedAt($value)
 * @method static Builder|PasswordReset whereCreatedBy($value)
 * @method static Builder|PasswordReset whereEmail($value)
 * @method static Builder|PasswordReset whereId($value)
 * @method static Builder|PasswordReset whereToken($value)
 * @method static Builder|PasswordReset whereUpdatedAt($value)
 * @method static Builder|PasswordReset whereUpdatedBy($value)
 * @method static Builder|PasswordReset whereUserId($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string|null          $uuid
 * @method static Builder<static>|PasswordReset whereUuid($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\PasswordResetFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class PasswordReset extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property int                         $id
 * @property string                      $name
 * @property string                      $guard_name
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property Collection<int, Permission> $permissions
 * @property int|null                    $permissions_count
 * @property Collection<int, Role>       $roles
 * @property int|null                    $roles_count
 * @property Collection<int, User>       $users
 * @property int|null                    $users_count
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission permission($permissions, $without = false)
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Permission whereCreatedAt($value)
 * @method static Builder<static>|Permission whereCreatedBy($value)
 * @method static Builder<static>|Permission whereGuardName($value)
 * @method static Builder<static>|Permission whereId($value)
 * @method static Builder<static>|Permission whereName($value)
 * @method static Builder<static>|Permission whereUpdatedAt($value)
 * @method static Builder<static>|Permission whereUpdatedBy($value)
 * @method static Builder<static>|Permission withoutPermission($permissions)
 * @method static Builder<static>|Permission withoutRole($roles, $guard = null)
 * @method static static                     firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                     updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static \Modules\User\Database\Factories\PermissionFactory factory($count = null, $state = [])
 * @property Collection<int, Team> $teams
 * @property int|null              $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTeam($teams)
 * @mixin \Eloquent
 */
	class Permission extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder|PermissionRole newModelQuery()
 * @method static Builder|PermissionRole newQuery()
 * @method static Builder|PermissionRole query()
 * @property string      $id
 * @property string|null $permission_id
 * @property string|null $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|PermissionRole whereCreatedAt($value)
 * @method static Builder|PermissionRole whereCreatedBy($value)
 * @method static Builder|PermissionRole whereId($value)
 * @method static Builder|PermissionRole wherePermissionId($value)
 * @method static Builder|PermissionRole whereRoleId($value)
 * @method static Builder|PermissionRole whereUpdatedAt($value)
 * @method static Builder|PermissionRole whereUpdatedBy($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class PermissionRole extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|PermissionUser newModelQuery()
 * @method static Builder<static>|PermissionUser newQuery()
 * @method static Builder<static>|PermissionUser query()
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\PermissionUserFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class PermissionUser extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\PersonalAccessToken.
 *
 * @property int         $id
 * @property string      $tokenable_type
 * @property int         $tokenable_id
 * @property string      $name
 * @property string      $token
 * @property string|null $abilities
 * @property Carbon|null $last_used_at
 * @property Carbon|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\User\Database\Factories\PersonalAccessTokenFactory       factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableType($value)
 * @mixin \Eloquent
 */
	class PersonalAccessToken extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * User Profile Model.
 *
 * Represents a user profile with relationships to devices, teams, and roles.
 *
 * @property int                                                       $id
 * @property string                                                    $first_name
 * @property string                                                    $last_name
 * @property string                                                    $user_name
 * @property string                                                    $email
 * @property string|null                                               $phone
 * @property string|null                                               $bio
 * @property string|null                                               $avatar
 * @property string|null                                               $timezone
 * @property string|null                                               $locale
 * @property array<string, mixed>                                      $preferences
 * @property string                                                    $status
 * @property SchemalessAttributes                                      $extra
 * @property string                                                    $avatar
 * @property ProfileContract|null                                      $creator
 * @property Collection<int, DeviceUser>                               $deviceUsers
 * @property int|null                                                  $device_users_count
 * @property ProfileTeam|DeviceProfile|null                            $pivot
 * @property Collection<int, Device>                                   $devices
 * @property int|null                                                  $devices_count
 * @property string|null                                               $first_name
 * @property string|null                                               $full_name
 * @property string|null                                               $last_name
 * @property MediaCollection<int, Media>                               $media
 * @property int|null                                                  $media_count
 * @property Collection<int, DeviceUser>                               $mobileDeviceUsers
 * @property int|null                                                  $mobile_device_users_count
 * @property Collection<int, Device>                                   $mobileDevices
 * @property int|null                                                  $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null                                                  $notifications_count
 * @property Collection<int, Permission>                               $permissions
 * @property int|null                                                  $permissions_count
 * @property Collection<int, Role>                                     $roles
 * @property int|null                                                  $roles_count
 * @property Collection<int, Team>                                     $teams
 * @property int|null                                                  $teams_count
 * @property ProfileContract|null                                      $updater
 * @property UserContract|null                                         $user
 * @property string|null                                               $user_name
 * @method static Builder<static>|Profile newModelQuery()
 * @method static Builder<static>|Profile newQuery()
 * @method static Builder<static>|Profile permission($permissions, $without = false)
 * @method static Builder<static>|Profile query()
 * @method static Builder<static>|Profile role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Profile withExtraAttributes()
 * @method static Builder<static>|Profile withoutPermission($permissions)
 * @method static Builder<static>|Profile withoutRole($roles, $guard = null)
 * @property string|null          $user_id
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property ProfileContract|null $deleter
 * @method static Builder<static>|Profile                         whereBio($value)
 * @method static Builder<static>|Profile                         whereCreatedAt($value)
 * @method static Builder<static>|Profile                         whereCreatedBy($value)
 * @method static Builder<static>|Profile                         whereDeletedAt($value)
 * @method static Builder<static>|Profile                         whereDeletedBy($value)
 * @method static Builder<static>|Profile                         whereEmail($value)
 * @method static Builder<static>|Profile                         whereFirstName($value)
 * @method static Builder<static>|Profile                         whereId($value)
 * @method static Builder<static>|Profile                         whereLastName($value)
 * @method static Builder<static>|Profile                         wherePhone($value)
 * @method static Builder<static>|Profile                         whereUpdatedAt($value)
 * @method static Builder<static>|Profile                         whereUpdatedBy($value)
 * @method static Builder<static>|Profile                         whereUserId($value)
 * @method static \Modules\User\Database\Factories\ProfileFactory factory($count = null, $state = [])
 * @property string|null $post_type
 * @property int|null    $ente
 * @property int|null    $matr
 * @property string|null $address
 * @property string|null $premise
 * @property string|null $premise_short
 * @property string|null $locality
 * @property string|null $locality_short
 * @property string|null $postal_town
 * @property string|null $postal_town_short
 * @property string|null $administrative_area_level_3
 * @property string|null $administrative_area_level_3_short
 * @property string|null $administrative_area_level_2
 * @property string|null $administrative_area_level_2_short
 * @property string|null $administrative_area_level_1
 * @property string|null $administrative_area_level_1_short
 * @property string|null $country
 * @property string|null $country_short
 * @property string|null $street_number
 * @property string|null $street_number_short
 * @property string|null $route
 * @property string|null $route_short
 * @property string|null $postal_code
 * @property string|null $postal_code_short
 * @property string|null $googleplace_url
 * @property string|null $googleplace_url_short
 * @property string|null $point_of_interest
 * @property string|null $point_of_interest_short
 * @property string|null $political
 * @property string|null $political_short
 * @property string|null $campground
 * @property string|null $campground_short
 * @method static Builder<static>|Profile byUuid(string $uuid)
 * @method static Builder<static>|Profile childrenWith(list<string> $relations)
 * @method static Builder<static>|Profile childrenWithCount(list<string> $relations)
 * @method static Builder<static>|Profile whereAddress($value)
 * @method static Builder<static>|Profile whereAdministrativeAreaLevel1($value)
 * @method static Builder<static>|Profile whereAdministrativeAreaLevel1Short($value)
 * @method static Builder<static>|Profile whereAdministrativeAreaLevel2($value)
 * @method static Builder<static>|Profile whereAdministrativeAreaLevel2Short($value)
 * @method static Builder<static>|Profile whereAdministrativeAreaLevel3($value)
 * @method static Builder<static>|Profile whereAdministrativeAreaLevel3Short($value)
 * @method static Builder<static>|Profile whereAvatar($value)
 * @method static Builder<static>|Profile whereCampground($value)
 * @method static Builder<static>|Profile whereCampgroundShort($value)
 * @method static Builder<static>|Profile whereCountry($value)
 * @method static Builder<static>|Profile whereCountryShort($value)
 * @method static Builder<static>|Profile whereEnte($value)
 * @method static Builder<static>|Profile whereGoogleplaceUrl($value)
 * @method static Builder<static>|Profile whereGoogleplaceUrlShort($value)
 * @method static Builder<static>|Profile whereLocale($value)
 * @method static Builder<static>|Profile whereLocality($value)
 * @method static Builder<static>|Profile whereLocalityShort($value)
 * @method static Builder<static>|Profile whereMatr($value)
 * @method static Builder<static>|Profile wherePointOfInterest($value)
 * @method static Builder<static>|Profile wherePointOfInterestShort($value)
 * @method static Builder<static>|Profile wherePolitical($value)
 * @method static Builder<static>|Profile wherePoliticalShort($value)
 * @method static Builder<static>|Profile wherePostType($value)
 * @method static Builder<static>|Profile wherePostalCode($value)
 * @method static Builder<static>|Profile wherePostalCodeShort($value)
 * @method static Builder<static>|Profile wherePostalTown($value)
 * @method static Builder<static>|Profile wherePostalTownShort($value)
 * @method static Builder<static>|Profile wherePreferences($value)
 * @method static Builder<static>|Profile wherePremise($value)
 * @method static Builder<static>|Profile wherePremiseShort($value)
 * @method static Builder<static>|Profile whereRoute($value)
 * @method static Builder<static>|Profile whereRouteShort($value)
 * @method static Builder<static>|Profile whereStatus($value)
 * @method static Builder<static>|Profile whereStreetNumber($value)
 * @method static Builder<static>|Profile whereStreetNumberShort($value)
 * @method static Builder<static>|Profile whereTimezone($value)
 * @property string|null $type
 * @property string|null $birth_date
 * @property string|null $gender
 * @property bool        $is_active
 * @method static Builder<static>|Profile whereBirthDate($value)
 * @method static Builder<static>|Profile whereExtra($value)
 * @method static Builder<static>|Profile whereGender($value)
 * @method static Builder<static>|Profile whereIsActive($value)
 * @method static Builder<static>|Profile whereType($value)
 * @method static Builder<static>|Profile whereUserName($value)
 * @method static Builder<static>|Profile team($teams, bool $without = false)
 * @method static Builder<static>|Profile withoutTeam($teams)
 * @mixin \Eloquent
 */
	class Profile extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * ProfileTeam Model.
 *
 * Represents the relationship between a profile and a team, including the user's role.
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string               $id
 * @property int                  $team_id
 * @property string|null          $user_id
 * @property string|null          $role
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @method static Builder<static>|ProfileTeam newModelQuery()
 * @method static Builder<static>|ProfileTeam newQuery()
 * @method static Builder<static>|ProfileTeam query()
 * @method static Builder<static>|ProfileTeam whereCreatedAt($value)
 * @method static Builder<static>|ProfileTeam whereCreatedBy($value)
 * @method static Builder<static>|ProfileTeam whereDeletedAt($value)
 * @method static Builder<static>|ProfileTeam whereDeletedBy($value)
 * @method static Builder<static>|ProfileTeam whereId($value)
 * @method static Builder<static>|ProfileTeam whereRole($value)
 * @method static Builder<static>|ProfileTeam whereTeamId($value)
 * @method static Builder<static>|ProfileTeam whereUpdatedAt($value)
 * @method static Builder<static>|ProfileTeam whereUpdatedBy($value)
 * @method static Builder<static>|ProfileTeam whereUserId($value)
 * @property ProfileContract|null         $deleter
 * @property Team|null                    $team
 * @property User|null                    $user
 * @property string|null                  $profile_id
 * @property array<array-key, mixed>|null $permissions
 * @method static Builder<static>|ProfileTeam                         childrenWith(array<int|string, mixed> $relations)
 * @method static Builder<static>|ProfileTeam                         childrenWithCount(array<int|string, mixed> $relations)
 * @method static \Modules\User\Database\Factories\ProfileTeamFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProfileTeam                         wherePermissions($value)
 * @method static Builder<static>|ProfileTeam                         whereProfileId($value)
 * @mixin \Eloquent
 */
	class ProfileTeam extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\Role.
 *
 * @property int                                 $id
 * @property string                              $uuid
 * @property string|null                         $team_id
 * @property string                              $name
 * @property string                              $guard_name
 * @property string|null                         $display_name
 * @property string|null                         $description
 * @property Carbon|null                         $created_at
 * @property Carbon|null                         $updated_at
 * @property string|null                         $updated_by
 * @property string|null                         $created_by
 * @property Collection<int, Permission>         $permissions
 * @property int|null                            $permissions_count
 * @property Team|null                           $team
 * @property Collection<int, Model&UserContract> $users
 * @property int|null                            $users_count
 * @property PermissionRole|null                 $pivot
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role permission($permissions)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereTeamId($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereCreatedBy($value)
 * @method static Builder|Role whereUpdatedBy($value)
 * @method static Builder|Role withoutPermission($permissions)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereDisplayName($value)
 * @method static static       firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static       updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static \Modules\User\Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Role                         whereUuid($value)
 * @mixin \Eloquent
 */
	class Role extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\RoleHasPermission.
 *
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 * @method static Builder|RoleHasPermission newModelQuery()
 * @method static Builder|RoleHasPermission newQuery()
 * @method static Builder|RoleHasPermission query()
 * @method static Builder|RoleHasPermission whereId($value)
 * @method static Builder|RoleHasPermission wherePermissionId($value)
 * @method static Builder|RoleHasPermission whereRoleId($value)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|RoleHasPermission whereCreatedAt($value)
 * @method static Builder|RoleHasPermission whereCreatedBy($value)
 * @method static Builder|RoleHasPermission whereUpdatedAt($value)
 * @method static Builder|RoleHasPermission whereUpdatedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class RoleHasPermission extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property int|null                  $id
 * @property string|null               $name
 * @property array<int, string>|null   $scopes
 * @property array<string, mixed>|null $parameters
 * @property bool|null                 $stateless
 * @property bool|null                 $active
 * @property bool|null                 $socialite
 * @property string|null               $svg
 * @property string|null               $client_id
 * @property string|null               $client_secret
 * @property ProfileContract|null      $creator
 * @property ProfileContract|null      $updater
 * @method static Builder|SocialProvider newModelQuery()
 * @method static Builder|SocialProvider newQuery()
 * @method static Builder|SocialProvider query()
 * @method static Builder|SocialProvider whereActive($value)
 * @method static Builder|SocialProvider whereClientId($value)
 * @method static Builder|SocialProvider whereClientSecret($value)
 * @method static Builder|SocialProvider whereId($value)
 * @method static Builder|SocialProvider whereName($value)
 * @method static Builder|SocialProvider whereParameters($value)
 * @method static Builder|SocialProvider whereScopes($value)
 * @method static Builder|SocialProvider whereSocialite($value)
 * @method static Builder|SocialProvider whereStateless($value)
 * @method static Builder|SocialProvider whereSvg($value)
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static Builder|SocialProvider whereCreatedAt($value)
 * @method static Builder|SocialProvider whereCreatedBy($value)
 * @method static Builder|SocialProvider whereUpdatedAt($value)
 * @method static Builder|SocialProvider whereUpdatedBy($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\SocialProviderFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class SocialProvider extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\SocialiteUser.
 *
 * @property int               $id
 * @property string            $user_id
 * @property string            $provider
 * @property string            $provider_id
 * @property string|null       $token
 * @property string|null       $name
 * @property string|null       $email
 * @property string|null       $avatar
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property string|null       $updated_by
 * @property string|null       $created_by
 * @property UserContract|null $user
 * @method static Builder|SocialiteUser newModelQuery()
 * @method static Builder|SocialiteUser newQuery()
 * @method static Builder|SocialiteUser query()
 * @method static Builder|SocialiteUser whereAvatar($value)
 * @method static Builder|SocialiteUser whereCreatedAt($value)
 * @method static Builder|SocialiteUser whereCreatedBy($value)
 * @method static Builder|SocialiteUser whereEmail($value)
 * @method static Builder|SocialiteUser whereId($value)
 * @method static Builder|SocialiteUser whereName($value)
 * @method static Builder|SocialiteUser whereProvider($value)
 * @method static Builder|SocialiteUser whereProviderId($value)
 * @method static Builder|SocialiteUser whereToken($value)
 * @method static Builder|SocialiteUser whereUpdatedAt($value)
 * @method static Builder|SocialiteUser whereUpdatedBy($value)
 * @method static Builder|SocialiteUser whereUserId($value)
 * @property string $uuid (DC2Type:guid)
 * @method static Builder|SocialiteUser whereUuid($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\SocialiteUserFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class SocialiteUser extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\SsoProvider.
 *
 * @property int                        $id
 * @property string                     $name
 * @property string                     $display_name
 * @property string                     $type
 * @property string|null                $entity_id
 * @property string|null                $client_id
 * @property string|null                $client_secret
 * @property string|null                $redirect_url
 * @property string|null                $metadata_url
 * @property string|null                $scopes
 * @property array<string, mixed>|null  $settings
 * @property array<int, string>|null    $domain_whitelist
 * @property array<string, string>|null $role_mapping
 * @property bool                       $is_active
 * @property Carbon|null                $created_at
 * @property Carbon|null                $updated_at
 * @property string|null                $created_by
 * @property string|null                $updated_by
 * @property Collection<int, User>      $users
 * @property int|null                   $users_count
 * @method static Builder<static>|SsoProvider newModelQuery()
 * @method static Builder<static>|SsoProvider newQuery()
 * @method static Builder<static>|SsoProvider query()
 * @method static Builder<static>|SsoProvider whereClientId($value)
 * @method static Builder<static>|SsoProvider whereClientSecret($value)
 * @method static Builder<static>|SsoProvider whereCreatedAt($value)
 * @method static Builder<static>|SsoProvider whereCreatedBy($value)
 * @method static Builder<static>|SsoProvider whereDisplayName($value)
 * @method static Builder<static>|SsoProvider whereDomainWhitelist($value)
 * @method static Builder<static>|SsoProvider whereEntityId($value)
 * @method static Builder<static>|SsoProvider whereId($value)
 * @method static Builder<static>|SsoProvider whereIsActive($value)
 * @method static Builder<static>|SsoProvider whereMetadataUrl($value)
 * @method static Builder<static>|SsoProvider whereName($value)
 * @method static Builder<static>|SsoProvider whereRedirectUrl($value)
 * @method static Builder<static>|SsoProvider whereRoleMapping($value)
 * @method static Builder<static>|SsoProvider whereScopes($value)
 * @method static Builder<static>|SsoProvider whereSettings($value)
 * @method static Builder<static>|SsoProvider whereType($value)
 * @method static Builder<static>|SsoProvider whereUpdatedAt($value)
 * @method static Builder<static>|SsoProvider whereUpdatedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static \Modules\User\Database\Factories\SsoProviderFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class SsoProvider extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Class Modules\User\Models\Team.
 *
 * @property string                          $id
 * @property string                          $user_id                (DC2Type:guid)
 * @property string                          $name
 * @property int                             $personal_team
 * @property Carbon|null                     $created_at
 * @property Carbon|null                     $updated_at
 * @property string|null                     $updated_by
 * @property string|null                     $created_by
 * @property Carbon|null                     $deleted_at
 * @property string|null                     $deleted_by
 * @property ProfileContract|null            $creator
 * @property TeamUser                        $pivot
 * @property Collection<int, User>           $members
 * @property int|null                        $members_count
 * @property User|null                       $owner
 * @property Collection<int, TeamInvitation> $teamInvitations
 * @property int|null                        $team_invitations_count
 * @property ProfileContract|null            $updater
 * @property Collection<int, User>           $users
 * @property int|null                        $users_count
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereCreatedBy($value)
 * @method static Builder|Team whereDeletedAt($value)
 * @method static Builder|Team whereDeletedBy($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team wherePersonalTeam($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @method static Builder|Team whereUpdatedBy($value)
 * @method static Builder|Team whereUserId($value)
 * @property string|null $code
 * @method static Builder|Team whereCode($value)
 * @property string|null $uuid
 * @method static Builder<static>|Team whereUuid($value)
 * @property string|null $owner_id
 * @method static Builder<static>|Team whereOwnerId($value)
 * @method static static               create(array<string, mixed> $attributes = [])
 * @method static static               firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static               updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\TeamFactory factory($count = null, $state = [])
 * @property string|null                     $slug
 * @property string|null                     $description
 * @property string|null                     $avatar_path
 * @property array<array-key, mixed>|null    $settings
 * @property Collection<int, TeamPermission> $permissions
 * @property int|null                        $permissions_count
 * @property Collection<int, TeamUser>       $teamUsers
 * @property int|null                        $team_users_count
 * @method static Builder<static>|Team whereAvatarPath($value)
 * @method static Builder<static>|Team whereDescription($value)
 * @method static Builder<static>|Team whereSettings($value)
 * @method static Builder<static>|Team whereSlug($value)
 * @mixin \Eloquent
 */
	class Team extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\TeamInvitation.
 *
 * @property int               $id
 * @property string|null       $team_id
 * @property string            $email
 * @property string|null       $role
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property Team|null         $team
 * @property TeamContract|null $team
 * @method static Builder|TeamInvitation newModelQuery()
 * @method static Builder|TeamInvitation newQuery()
 * @method static Builder|TeamInvitation query()
 * @method static Builder|TeamInvitation whereCreatedAt($value)
 * @method static Builder|TeamInvitation whereEmail($value)
 * @method static Builder|TeamInvitation whereId($value)
 * @method static Builder|TeamInvitation whereRole($value)
 * @method static Builder|TeamInvitation whereTeamId($value)
 * @method static Builder|TeamInvitation whereUpdatedAt($value)
 * @property string      $uuid
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|TeamInvitation whereCreatedBy($value)
 * @method static Builder|TeamInvitation whereDeletedAt($value)
 * @method static Builder|TeamInvitation whereDeletedBy($value)
 * @method static Builder|TeamInvitation whereUpdatedBy($value)
 * @method static Builder|TeamInvitation whereUuid($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 * @property Carbon|null          $accepted_at
 * @property Carbon|null          $declined_at
 * @property string|null          $user_id
 * @method static \Modules\User\Database\Factories\TeamInvitationFactory factory($count = null, $state = [])
 * @method static Builder<static>|TeamInvitation                         whereAcceptedAt($value)
 * @method static Builder<static>|TeamInvitation                         whereDeclinedAt($value)
 * @method static Builder<static>|TeamInvitation                         whereUserId($value)
 * @mixin \Eloquent
 */
	class TeamInvitation extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Team Permission Model.
 *
 * Represents a permission assigned to a user within a team context.
 *
 * @property string         $id
 * @property string         $team_id
 * @property string         $user_id
 * @property string         $permission
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property Team           $team
 * @property User           $user
 * @method static Builder<static>|TeamPermission newModelQuery()
 * @method static Builder<static>|TeamPermission newQuery()
 * @method static Builder<static>|TeamPermission query()
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @property string|null          $name
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @method static \Modules\User\Database\Factories\TeamPermissionFactory factory($count = null, $state = [])
 * @method static Builder<static>|TeamPermission                         whereCreatedAt($value)
 * @method static Builder<static>|TeamPermission                         whereCreatedBy($value)
 * @method static Builder<static>|TeamPermission                         whereDeletedAt($value)
 * @method static Builder<static>|TeamPermission                         whereDeletedBy($value)
 * @method static Builder<static>|TeamPermission                         whereId($value)
 * @method static Builder<static>|TeamPermission                         whereName($value)
 * @method static Builder<static>|TeamPermission                         wherePermission($value)
 * @method static Builder<static>|TeamPermission                         whereTeamId($value)
 * @method static Builder<static>|TeamPermission                         whereUpdatedAt($value)
 * @method static Builder<static>|TeamPermission                         whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class TeamPermission extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\TeamUser.
 *
 * @method static Builder|TeamUser newModelQuery()
 * @method static Builder|TeamUser newQuery()
 * @method static Builder|TeamUser query()
 * @property int         $id
 * @property string      $uuid
 * @property string|null $team_id
 * @property string|null $user_id
 * @property string|null $role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $customer_id
 * @method static Builder|TeamUser whereCreatedAt($value)
 * @method static Builder|TeamUser whereCreatedBy($value)
 * @method static Builder|TeamUser whereCustomerId($value)
 * @method static Builder|TeamUser whereId($value)
 * @method static Builder|TeamUser whereRole($value)
 * @method static Builder|TeamUser whereTeamId($value)
 * @method static Builder|TeamUser whereUpdatedAt($value)
 * @method static Builder|TeamUser whereUpdatedBy($value)
 * @method static Builder|TeamUser whereUserId($value)
 * @method static Builder|TeamUser whereUuid($value)
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|TeamUser whereDeletedAt($value)
 * @method static Builder|TeamUser whereDeletedBy($value)
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @property ProfileContract|null         $deleter
 * @property Team|null                    $team
 * @property User|null                    $user
 * @property array<array-key, mixed>|null $permissions
 * @property string|null                  $joined_at
 * @method static Builder<static>|TeamUser                         childrenWith(array<int|string, mixed> $relations)
 * @method static Builder<static>|TeamUser                         childrenWithCount(array<int|string, mixed> $relations)
 * @method static \Modules\User\Database\Factories\TeamUserFactory factory($count = null, $state = [])
 * @method static Builder<static>|TeamUser                         whereJoinedAt($value)
 * @method static Builder<static>|TeamUser                         wherePermissions($value)
 * @mixin \Eloquent
 */
	class TeamUser extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\Tenant.
 *
 * @method static Builder|Tenant newModelQuery()
 * @method static Builder|Tenant newQuery()
 * @method static Builder|Tenant query()
 * @property EloquentCollection<int, Model&UserContract> $members
 * @property int|null                                    $members_count
 * @property ProfileContract|null                        $creator
 * @property ProfileContract|null                        $updater
 * @property MediaCollection<int, Media>                 $media
 * @property int|null                                    $media_count
 * @property TenantUser                                  $pivot
 * @property EloquentCollection<int, User>               $users
 * @property int|null                                    $users_count
 * @property string                                      $id
 * @property string                                      $name
 * @property string|null                                 $slug
 * @property string|null                                 $domain
 * @property string|null                                 $database
 * @property int                                         $is_active
 * @property Carbon|null                                 $created_at
 * @property Carbon|null                                 $updated_at
 * @property Carbon|null                                 $deleted_at
 * @property ProfileContract|null                        $deleter
 * @method static Builder<static>|Tenant whereCreatedAt($value)
 * @method static Builder<static>|Tenant whereDatabase($value)
 * @method static Builder<static>|Tenant whereDeletedAt($value)
 * @method static Builder<static>|Tenant whereDomain($value)
 * @method static Builder<static>|Tenant whereId($value)
 * @method static Builder<static>|Tenant whereIsActive($value)
 * @method static Builder<static>|Tenant whereName($value)
 * @method static Builder<static>|Tenant whereSlug($value)
 * @method static Builder<static>|Tenant whereUpdatedAt($value)
 * @property string|null $email_address
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $address
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property string|null $settings
 * @method static \Modules\User\Database\Factories\TenantFactory factory($count = null, $state = [])
 * @method static Builder<static>|Tenant                         whereAddress($value)
 * @method static Builder<static>|Tenant                         whereCreatedBy($value)
 * @method static Builder<static>|Tenant                         whereDeletedBy($value)
 * @method static Builder<static>|Tenant                         whereEmailAddress($value)
 * @method static Builder<static>|Tenant                         whereMobile($value)
 * @method static Builder<static>|Tenant                         wherePhone($value)
 * @method static Builder<static>|Tenant                         wherePrimaryColor($value)
 * @method static Builder<static>|Tenant                         whereSecondaryColor($value)
 * @method static Builder<static>|Tenant                         whereSettings($value)
 * @method static Builder<static>|Tenant                         whereUpdatedBy($value)
 * @property string|null $trial_ends_at
 * @method static Builder<static>|Tenant whereTrialEndsAt($value)
 * @mixin \Eloquent
 */
	class Tenant extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Modules\User\Models\TenantUser.
 *
 * @method static Builder|TeamUser newModelQuery()
 * @method static Builder|TeamUser newQuery()
 * @method static Builder|TeamUser query()
 * @property int         $id
 * @property string|null $tenant_id
 * @property string|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static Builder|TeamUser whereCreatedAt($value)
 * @method static Builder|TeamUser whereCreatedBy($value)
 * @method static Builder|TeamUser whereCustomerId($value)
 * @method static Builder|TeamUser whereId($value)
 * @method static Builder|TeamUser whereRole($value)
 * @method static Builder|TeamUser whereTeamId($value)
 * @method static Builder|TeamUser whereUpdatedAt($value)
 * @method static Builder|TeamUser whereUpdatedBy($value)
 * @method static Builder|TeamUser whereUserId($value)
 * @method static Builder|TeamUser whereUuid($value)
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|TenantUser whereDeletedAt($value)
 * @method static Builder|TenantUser whereDeletedBy($value)
 * @method static Builder|TenantUser whereTenantId($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static \Modules\User\Database\Factories\TenantUserFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class TenantUser extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * Class Modules\User\Models\User.
 *
 * @property string                                            $id
 * @property string|null                                       $name
 * @property string|null                                       $first_name
 * @property string|null                                       $last_name
 * @property string                                            $email
 * @property Carbon|null                                       $email_verified_at
 * @property string                                            $password
 * @property string|null                                       $remember_token
 * @property int|null                                          $current_team_id
 * @property string|null                                       $profile_photo_path
 * @property Carbon|null                                       $created_at
 * @property Carbon|null                                       $updated_at
 * @property Carbon|null                                       $deleted_at
 * @property Carbon|null                                       $password_expires_at
 * @property string|null                                       $lang
 * @property bool                                              $is_active
 * @property bool                                              $is_otp
 * @property string|null                                       $updated_by
 * @property string|null                                       $created_by
 * @property string|null                                       $deleted_by
 * @property Collection<int, AuthenticationLog>                $authentications
 * @property int|null                                          $authentications_count
 * @property Collection<int, OauthClient>                      $clients
 * @property int|null                                          $clients_count
 * @property TenantUser                                        $pivot
 * @property Collection<int, Device>                           $devices
 * @property int|null                                          $devices_count
 * @property string|null                                       $full_name
 * @property AuthenticationLog|null                            $latestAuthentication
 * @property DatabaseNotificationCollection<int, Notification> $notifications
 * @property int|null                                          $notifications_count
 * @property Collection<int, Team>                             $ownedTeams
 * @property int|null                                          $owned_teams_count
 * @property Collection<int, Permission>                       $permissions
 * @property int|null                                          $permissions_count
 * @property ProfileContract|null                              $profile
 * @property Collection<int, Role>                             $roles
 * @property int|null                                          $roles_count
 * @property Membership                                        $membership
 * @property Collection<int, Team>                             $teams
 * @property int|null                                          $teams_count
 * @property Collection<int, Tenant>                           $tenants
 * @property int|null                                          $tenants_count
 * @property Collection<int, OauthToken>                       $tokens
 * @property int|null                                          $tokens_count
 * @method static Builder|User         newModelQuery()
 * @method static Builder|User         newQuery()
 * @method static Builder|User         permission($permissions, $without = false)
 * @method static Builder|User         query()
 * @method static Builder|User         role($roles, $guard = null, $without = false)
 * @method static Builder|User         whereCreatedAt($value)
 * @method static Builder|User         whereCreatedBy($value)
 * @method static Builder|User         whereCurrentTeamId($value)
 * @method static Builder|User         whereDeletedAt($value)
 * @method static Builder|User         whereDeletedBy($value)
 * @method static Builder|User         whereEmail($value)
 * @method static Builder|User         whereEmailVerifiedAt($value)
 * @method static Builder|User         whereFirstName($value)
 * @method static Builder|User         whereId($value)
 * @method static Builder|User         whereIsActive($value)
 * @method static Builder|User         whereLang($value)
 * @method static Builder|User         whereLastName($value)
 * @method static Builder|User         whereName($value)
 * @method static Builder<static>|User whereNotNull($column, $boolean = 'and')
 * @method static Builder|User         wherePassword($value)
 * @method static Builder|User         whereProfilePhotoPath($value)
 * @method static Builder|User         whereRememberToken($value)
 * @method static Builder|User         whereUpdatedAt($value)
 * @method static Builder|User         whereUpdatedBy($value)
 * @method static Builder|User         withoutPermission($permissions)
 * @method static Builder|User         withoutRole($roles, $guard = null)
 * @property string                         $last_name
 * @property Team|null                      $currentTeam
 * @property MediaCollection<int, Media>    $media
 * @property int|null                       $media_count
 * @property Collection<int, SocialiteUser> $socialiteUsers
 * @property int|null                       $socialite_users_count
 * @property Collection<int, Membership>    $teamUsers
 * @property int|null                       $team_users_count
 * @property Collection<int, User>          $all_team_users
 * @property string|null                    $phone
 * @property string|null                    $address
 * @property string|null                    $city
 * @property string|null                    $registration_number
 * @property string|null                    $status
 * @property string|null                    $state
 * @property string|null                    $moderation_data
 * @property string|null                    $certifications
 * @property string|null                    $type
 * @method static Builder<static>|User whereAddress($value)
 * @method static Builder<static>|User whereCertifications($value)
 * @method static Builder<static>|User whereCity($value)
 * @method static Builder<static>|User whereIsOtp($value)
 * @method static Builder<static>|User whereModerationData($value)
 * @method static Builder<static>|User wherePasswordExpiresAt($value)
 * @method static Builder<static>|User wherePhone($value)
 * @method static Builder<static>|User whereRegistrationNumber($value)
 * @method static Builder<static>|User whereState($value)
 * @method static Builder<static>|User whereStatus($value)
 * @method static Builder<static>|User whereType($value)
 * @property string|null $facebook_id
 * @method static Builder<static>|User whereFacebookId($value)
 * @property User|null $creator
 * @property User|null $updater
 * @property User|null $user
 * @method static \Modules\User\Database\Factories\UserFactory factory($count = null, $state = [])
 * @property string|null                  $uuid
 * @property string                       $surname
 * @property string|null                  $two_factor_secret
 * @property string|null                  $two_factor_recovery_codes
 * @property string|null                  $two_factor_confirmed_at
 * @property Collection<int, OauthClient> $oauthApps
 * @property int|null                     $oauth_apps_count
 * @method static Builder<static>|User childrenWith(array<int|string, mixed> $relations)
 * @method static Builder<static>|User childrenWithCount(array<int|string, mixed> $relations)
 * @method static Builder<static>|User whereSurname($value)
 * @method static Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder<static>|User whereTwoFactorSecret($value)
 * @method static Builder<static>|User whereUuid($value)
 * @property Collection<int, Team> $membershipTeams
 * @property int|null              $membership_teams_count
 * @method static Builder<static>|User team($teams, bool $without = false)
 * @method static Builder<static>|User withoutTeam($teams)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace Modules\Vehicle\Models{
/**
 * @property int $id
 * @property string|null $uuid
 * @property string $plate_number
 * @property string|null $make
 * @property string|null $model
 * @property int|null $year
 * @property int|null $odometer_km
 * @property Carbon|null $last_service_on
 * @property Carbon|null $insurance_expires_on
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $deleted_at
 * @method static VehicleFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vehicle\Models\VehicleDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle withoutTrashed()
 */
	final class Vehicle extends \Eloquent {}
}

namespace Modules\Vehicle\Models{
/**
 * @property int $id
 * @property string|null $uuid
 * @property int $vehicle_id
 * @property int $media_id
 * @property string|null $title
 * @method static VehicleDocumentFactory factory($count = null, $state = [])
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Document\Models\Media|null $media
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\Vehicle\Models\Vehicle|null $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument query()
 */
	final class VehicleDocument extends \Eloquent {}
}

namespace Modules\WhatsApp\Models{
/**
 * @property int $id
 * @property string $phone_e164
 * @property string|null $wa_id
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\WhatsApp\Database\Factories\WhatsAppContactIdentityFactory factory($count = null, $state = [])
 * @property-read \Modules\Customer\Models\Contact|null $contact
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppContactIdentity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppContactIdentity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppContactIdentity query()
 */
	final class WhatsAppContactIdentity extends \Eloquent {}
}

namespace Modules\WhatsApp\Models{
/**
 * @property int $id
 * @property string $phone_e164
 * @property string|null $wa_id
 * @property string|null $display_name
 * @property int|null $identity_id
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property string $status
 * @property int|null $active_proposal_id
 * @property int|null $approved_up_to_message_id
 * @property Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\WhatsApp\Database\Factories\WhatsAppConversationFactory factory($count = null, $state = [])
 * @property-read \Modules\Customer\Models\Contact|null $contact
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WhatsApp\Models\WhatsAppContactIdentity|null $identity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\WhatsApp\Models\WhatsAppMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppConversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppConversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppConversation query()
 */
	final class WhatsAppConversation extends \Eloquent {}
}

namespace Modules\WhatsApp\Models{
/**
 * @property int $id
 * @property int $conversation_id
 * @property int|null $work_order_id
 * @property int|null $intervention_id
 * @property int|null $ai_action_proposal_id
 * @property string|null $meta_message_id
 * @property string $direction
 * @property string|null $from_number
 * @property string|null $to_number
 * @property string $message_type
 * @property string|null $body
 * @property string $status
 * @property string $processing_status
 * @property array<string, mixed>|null $payload
 * @property array<string, mixed>|null $ai_result
 * @property string|null $error
 * @property Carbon|null $received_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $processed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Modules\WhatsApp\Database\Factories\WhatsAppMessageFactory factory($count = null, $state = [])
 * @property-read \Modules\WhatsApp\Models\WhatsAppConversation|null $conversation
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Intervention\Models\Intervention|null $intervention
 * @property-read \Modules\AiAssistant\Models\AiActionProposal|null $proposal
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsAppMessage query()
 */
	final class WhatsAppMessage extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * Profilo dominio WorkOrder — owner schema: create_profiles_table in questo modulo.
 *
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra
 * @property-read string $avatar
 * @property-read Profile|null $creator
 * @property-read Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\DeviceUser> $deviceUsers
 * @property-read int|null $device_users_count
 * @property-read \Modules\User\Models\DeviceProfile|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read string|null $first_name
 * @property-read string|null $full_name
 * @property-read string|null $last_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\DeviceUser> $mobileDeviceUsers
 * @property-read int|null $mobile_device_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Device> $mobileDevices
 * @property-read int|null $mobile_devices_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @property-read string|null $user_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile byUuid(string $uuid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile childrenWith(array $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile childrenWithCount(array $relations)
 * @method static \Modules\WorkOrder\Database\Factories\ProfileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutRole($roles, ?string $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutTeam($teams)
 */
	class Profile extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property int|null $primary_contact_id
 * @property int|null $status_id
 * @property string $title
 * @property string|null $description
 * @property string|null $notes
 * @property Carbon|null $opened_at
 * @property Carbon|null $scheduled_at
 * @property int|null $planned_minutes
 * @property int $progress_percent
 * @property Carbon|null $delivery_due_at
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, WorkOrderMaterial> $materials
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\WorkOrder\Models\WorkOrderActivity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\WorkOrder\Models\WorkOrderAssignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\WorkOrderChangeOrder> $changeOrders
 * @property-read int|null $change_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\ComplianceDocument> $complianceDocuments
 * @property-read int|null $compliance_documents_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Intervention\Models\Intervention> $interventions
 * @property-read int|null $interventions_count
 * @property-read int|null $materials_count
 * @property-read \Modules\Customer\Models\Contact|null $primaryContact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\PublicProcurement\Models\PublicProcurementContract> $publicProcurementContracts
 * @property-read int|null $public_procurement_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Compliance\Models\WorkOrderReadinessItem> $readinessItems
 * @property-read int|null $readiness_items_count
 * @property-read \Modules\Customer\Models\CustomerSite|null $site
 * @property-read \Modules\WorkOrder\Models\WorkOrderStatus|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\WorkOrder\Models\WorkOrderStatusEvent> $statusEvents
 * @property-read int|null $status_events_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\WorkOrder\Database\Factories\WorkOrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder withoutTrashed()
 */
	final class WorkOrder extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * @property int $id
 * @property int $work_order_id
 * @property string|null $created_by_user_id
 * @property string $type
 * @property string $body
 * @property Carbon|null $occurred_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\WorkOrder\Database\Factories\WorkOrderActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderActivity withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderActivity withoutTrashed()
 */
	final class WorkOrderActivity extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * @property int $id
 * @property int $work_order_id
 * @property int $user_id
 * @property string|null $role
 * @property string|null $assigned_by_user_id
 * @property Carbon|null $assigned_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $assignedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\User\Models\User|null $user
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\WorkOrder\Database\Factories\WorkOrderAssignmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderAssignment query()
 */
	final class WorkOrderAssignment extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * Una riga della distinta materiali di una commessa.
 *
 * Testo libero + link opzionale al prodotto: metà di ciò che un'officina mette
 * su una commessa non esiste a catalogo, e forzarlo riporterebbe la gente ai
 * fogli di calcolo.
 *
 * @property int $id
 * @property int $work_order_id
 * @property int|null $product_id
 * @property string $description
 * @property string|null $unit_code
 * @property numeric-string $quantity
 * @property numeric-string|null $unit_cost
 * @property numeric-string|null $line_total
 * @property string $source_code
 * @property string|null $notes
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read WorkOrder|null $workOrder
 * @property-read Product|null $product
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\WorkOrder\Database\Factories\WorkOrderMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderMaterial query()
 */
	final class WorkOrderMaterial extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $color
 * @property bool $is_terminal
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\WorkOrder\Models\WorkOrder> $workOrders
 * @property-read int|null $work_orders_count
 * @method static \Modules\WorkOrder\Database\Factories\WorkOrderStatusFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderStatus query()
 */
	final class WorkOrderStatus extends \Eloquent {}
}

namespace Modules\WorkOrder\Models{
/**
 * @property int $id
 * @property int $work_order_id
 * @property int|null $from_status_id
 * @property int $to_status_id
 * @property string|null $changed_by_user_id
 * @property string|null $note
 * @property Carbon|null $changed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $changedBy
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\WorkOrderStatus|null $fromStatus
 * @property-read \Modules\WorkOrder\Models\WorkOrderStatus|null $toStatus
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @property-read \Modules\WorkOrder\Models\WorkOrder|null $workOrder
 * @method static \Modules\WorkOrder\Database\Factories\WorkOrderStatusEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderStatusEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderStatusEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderStatusEvent query()
 */
	final class WorkOrderStatusEvent extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property string $auditable_type
 * @property int $auditable_id
 * @property string $action
 * @property array<string, mixed>|null $old_values
 * @property array<string, mixed>|null $new_values
 * @property string|null $performed_by_user_id
 * @property string|null $performed_by_name
 * @property \Carbon\CarbonInterface $performed_at
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property-read \Illuminate\Database\Eloquent\Model $auditable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsAuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsAuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsAuditLog query()
 */
	final class WtsAuditLog extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property int $processing_step_id
 * @property int|null $technical_sheet_snapshot_id
 * @property string $output_code
 * @property string $output_type_code
 * @property string $container_type_code
 * @property int $quantity
 * @property string|null $weight
 * @property bool $is_dirty
 * @property array<string, mixed>|null $meta
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsLabelPrint> $labelPrints
 * @property-read int|null $label_prints_count
 * @property-read \Modules\Wts\Models\WtsProcessingStep|null $processingStep
 * @property-read \Modules\Wts\Models\WtsTechnicalSheetSnapshot|null $technicalSheetSnapshot
 * @method static \Modules\Wts\Database\Factories\WtsDerivedMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsDerivedMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsDerivedMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsDerivedMaterial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsDerivedMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsDerivedMaterial withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsDerivedMaterial withoutTrashed()
 */
	final class WtsDerivedMaterial extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property int $inbound_record_id
 * @property string $lot_code
 * @property string $status_code
 * @property string $current_area_code
 * @property int $initial_units_count
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Modules\Wts\Models\WtsInboundRecord|null $inboundRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsNonConformityReport> $nonConformityReports
 * @property-read int|null $non_conformity_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsUnit> $units
 * @property-read int|null $units_count
 * @method static \Modules\Wts\Database\Factories\WtsInboundLotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundLot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundLot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundLot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundLot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundLot withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundLot withoutTrashed()
 */
	final class WtsInboundLot extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property string|null $legacy_reference
 * @property string|null $form_number
 * @property string $source_company_name
 * @property string|null $vehicle_reference
 * @property string $gross_quantity
 * @property string|null $entry_weight
 * @property string $status_code
 * @property int|null $technical_sheet_snapshot_id
 * @property \Carbon\CarbonInterface|null $received_at
 * @property string|null $created_by_user_id
 * @property string|null $notes
 * @property string|null $tare_weight
 * @property string|null $net_weight
 * @property string|null $acceptance_status
 * @property \Carbon\CarbonInterface|null $taken_charge_at
 * @property string|null $commercial_company_name
 * @property string|null $batch_number
 * @property int|null $ecos_units_count
 * @property bool|null $is_weight_closed
 * @property string|null $treatment_line_code
 * @property string|null $production_cycle_code
 * @property array<string, mixed>|null $legacy_payload
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Modules\User\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsInboundLot> $lots
 * @property-read int|null $lots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsNonConformityReport> $nonConformityReports
 * @property-read int|null $non_conformity_reports_count
 * @property-read \Modules\Wts\Models\WtsTechnicalSheetSnapshot|null $technicalSheetSnapshot
 * @method static \Modules\Wts\Database\Factories\WtsInboundRecordFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundRecord withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsInboundRecord withoutTrashed()
 */
	final class WtsInboundRecord extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property string $labelable_type
 * @property int $labelable_id
 * @property string $label_code
 * @property string $qr_payload
 * @property string $print_reason_code
 * @property string|null $printed_by_user_id
 * @property \Carbon\CarbonInterface $printed_at
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Model $labelable
 * @property-read \Modules\User\Models\User|null $printedBy
 * @method static \Modules\Wts\Database\Factories\WtsLabelPrintFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsLabelPrint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsLabelPrint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsLabelPrint onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsLabelPrint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsLabelPrint withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsLabelPrint withoutTrashed()
 */
	final class WtsLabelPrint extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property int|null $wts_inbound_lot_id
 * @property int|null $wts_inbound_record_id
 * @property string|null $form_number
 * @property string $note
 * @property string|null $recipient_email
 * @property string|null $reported_by_user_id
 * @property \Carbon\CarbonInterface $reported_at
 * @property \Carbon\CarbonInterface|null $email_sent_at
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Modules\Wts\Models\WtsInboundRecord|null $inboundRecord
 * @property-read \Modules\Wts\Models\WtsInboundLot|null $lot
 * @property-read \Modules\User\Models\User|null $reportedBy
 * @method static \Modules\Wts\Database\Factories\WtsNonConformityReportFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsNonConformityReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsNonConformityReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsNonConformityReport onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsNonConformityReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsNonConformityReport withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsNonConformityReport withoutTrashed()
 */
	final class WtsNonConformityReport extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $label
 * @property string $value
 * @property string|null $unit
 * @property string|null $description
 * @property string|null $updated_by_user_id
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Modules\User\Models\User|null $updatedBy
 * @method static \Modules\Wts\Database\Factories\WtsOperationalParameterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsOperationalParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsOperationalParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsOperationalParameter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsOperationalParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsOperationalParameter withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsOperationalParameter withoutTrashed()
 */
	final class WtsOperationalParameter extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property int|null $wts_unit_id
 * @property string|null $label_code
 * @property string $zpl
 * @property int $copies
 * @property string $status_code
 * @property int $attempts
 * @property string|null $error
 * @property \Carbon\CarbonInterface|null $printed_at
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Modules\Wts\Models\WtsUnit|null $unit
 * @method static \Modules\Wts\Database\Factories\WtsPrintJobFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsPrintJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsPrintJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsPrintJob onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsPrintJob query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsPrintJob withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsPrintJob withoutTrashed()
 */
	final class WtsPrintJob extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property string $work_sheet_code
 * @property string $step_type_code
 * @property string $quantity_mode
 * @property int $selected_units_count
 * @property string|null $input_weight
 * @property string|null $output_weight
 * @property string|null $remainder_weight
 * @property string|null $remainder_waste_code
 * @property int|null $technical_sheet_snapshot_id
 * @property string|null $executed_by_user_id
 * @property \Carbon\CarbonInterface $executed_at
 * @property string|null $notes
 * @property array<string, mixed>|null $meta
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsDerivedMaterial> $derivedMaterials
 * @property-read int|null $derived_materials_count
 * @property-read \Modules\User\Models\User|null $executedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsUnit> $selectedUnits
 * @property-read \Modules\Wts\Models\WtsTechnicalSheetSnapshot|null $technicalSheetSnapshot
 * @method static \Modules\Wts\Database\Factories\WtsProcessingStepFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStep onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStep query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStep withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStep withoutTrashed()
 */
	final class WtsProcessingStep extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property int $wts_processing_step_id
 * @property int $wts_unit_id
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStepUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStepUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsProcessingStepUnit query()
 */
	final class WtsProcessingStepUnit extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property string $legacy_system
 * @property string $sheet_code
 * @property string $producer_name
 * @property string $waste_code
 * @property string|null $hp_codes
 * @property string|null $packaging_description
 * @property string|null $production_description
 * @property string|null $note_1
 * @property string|null $note_2
 * @property array<string, mixed>|null $legacy_payload
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsInboundRecord> $inboundRecords
 * @property-read int|null $inbound_records_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsUnit> $units
 * @property-read int|null $units_count
 * @method static \Modules\Wts\Database\Factories\WtsTechnicalSheetSnapshotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsTechnicalSheetSnapshot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsTechnicalSheetSnapshot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsTechnicalSheetSnapshot query()
 */
	final class WtsTechnicalSheetSnapshot extends \Eloquent {}
}

namespace Modules\Wts\Models{
/**
 * @property int $id
 * @property int $inbound_lot_id
 * @property int|null $technical_sheet_snapshot_id
 * @property string $unit_code
 * @property string $qr_code
 * @property string $unit_type_code
 * @property string $status_code
 * @property string $current_area_code
 * @property string|null $location_label
 * @property string|null $gross_weight
 * @property bool $is_banned
 * @property int $generated_sequence
 * @property array<string, mixed>|null $meta
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 * @property-read \Modules\Wts\Models\WtsInboundLot|null $inboundLot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsLabelPrint> $labelPrints
 * @property-read int|null $label_prints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsProcessingStep> $processingSteps
 * @property-read int|null $processing_steps_count
 * @property-read \Modules\Wts\Models\WtsTechnicalSheetSnapshot|null $technicalSheetSnapshot
 * @method static \Modules\Wts\Database\Factories\WtsUnitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsUnit withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WtsUnit withoutTrashed()
 */
	final class WtsUnit extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Modules\Xot\Models\Cache.
 *
 * @property string $key
 * @property string $value
 * @property int    $expiration
 * @method static CacheFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Cache newModelQuery()
 * @method static Builder<static>|Cache newQuery()
 * @method static Builder<static>|Cache query()
 * @method static Builder<static>|Cache whereExpiration($value)
 * @method static Builder<static>|Cache whereKey($value)
 * @method static Builder<static>|Cache whereValue($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	class Cache extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Modules\Xot\Models\CacheLock.
 *
 * @property string $key
 * @property string $owner
 * @property int    $expiration
 * @method static CacheLockFactory          factory($count = null, $state = [])
 * @method static Builder<static>|CacheLock newModelQuery()
 * @method static Builder<static>|CacheLock newQuery()
 * @method static Builder<static>|CacheLock query()
 * @method static Builder<static>|CacheLock whereExpiration($value)
 * @method static Builder<static>|CacheLock whereKey($value)
 * @method static Builder<static>|CacheLock whereOwner($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	class CacheLock extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Model Extra.
 *
 * @property string                    $id
 * @property string                    $model_type
 * @property string                    $model_id
 * @property SchemalessAttributes|null $extra_attributes
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $updated_by
 * @property string|null               $created_by
 * @property Carbon|null               $deleted_at
 * @property string|null               $deleted_by
 * @method static ExtraFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Extra newModelQuery()
 * @method static Builder<static>|Extra newQuery()
 * @method static Builder<static>|Extra query()
 * @method static Builder<static>|Extra whereCreatedAt($value)
 * @method static Builder<static>|Extra whereCreatedBy($value)
 * @method static Builder<static>|Extra whereDeletedAt($value)
 * @method static Builder<static>|Extra whereDeletedBy($value)
 * @method static Builder<static>|Extra whereExtraAttributes($value)
 * @method static Builder<static>|Extra whereId($value)
 * @method static Builder<static>|Extra whereModelId($value)
 * @method static Builder<static>|Extra whereModelType($value)
 * @method static Builder<static>|Extra whereUpdatedAt($value)
 * @method static Builder<static>|Extra whereUpdatedBy($value)
 * @method static Builder<static>|Extra withExtraAttributes()
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	final class Extra extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Modules\Xot\Models\Feed.
 *
 * @property string      $id
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static FeedFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Feed newModelQuery()
 * @method static Builder<static>|Feed newQuery()
 * @method static Builder<static>|Feed query()
 * @method static Builder<static>|Feed whereCreatedAt($value)
 * @method static Builder<static>|Feed whereCreatedBy($value)
 * @method static Builder<static>|Feed whereId($value)
 * @method static Builder<static>|Feed whereUpdatedAt($value)
 * @method static Builder<static>|Feed whereUpdatedBy($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	class Feed extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property int                     $id
 * @property string                  $check_name
 * @property string                  $check_label
 * @property string                  $status
 * @property string|null             $notification_message
 * @property string|null             $short_summary
 * @property array<array-key, mixed> $meta
 * @property string                  $ended_at
 * @property string                  $batch
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $updated_by
 * @property string|null             $created_by
 * @method static Builder<static>|HealthCheckResultHistoryItem newModelQuery()
 * @method static Builder<static>|HealthCheckResultHistoryItem newQuery()
 * @method static Builder<static>|HealthCheckResultHistoryItem query()
 * @method static Builder<static>|HealthCheckResultHistoryItem whereBatch($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereCheckLabel($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereCheckName($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereCreatedAt($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereCreatedBy($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereEndedAt($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereId($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereMeta($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereNotificationMessage($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereShortSummary($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereStatus($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereUpdatedAt($value)
 * @method static Builder<static>|HealthCheckResultHistoryItem whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class HealthCheckResultHistoryItem extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property int|null             $table_rows
 * @property string               $table_schema
 * @property string               $table_name
 * @property string|null          $model_class
 * @property Carbon|null          $created_at
 * @property string|null          $created_by
 * @property int                  $id
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static InformationSchemaTableFactory          factory($count = null, $state = [])
 * @method static Builder<static>|InformationSchemaTable newModelQuery()
 * @method static Builder<static>|InformationSchemaTable newQuery()
 * @method static Builder<static>|InformationSchemaTable query()
 * @method static Builder<static>|InformationSchemaTable whereCreatedAt($value)
 * @method static Builder<static>|InformationSchemaTable whereCreatedBy($value)
 * @method static Builder<static>|InformationSchemaTable whereId($value)
 * @method static Builder<static>|InformationSchemaTable whereModelClass($value)
 * @method static Builder<static>|InformationSchemaTable whereTableName($value)
 * @method static Builder<static>|InformationSchemaTable whereTableRows($value)
 * @method static Builder<static>|InformationSchemaTable whereTableSchema($value)
 * @method static Builder<static>|InformationSchemaTable whereUpdatedAt($value)
 * @method static Builder<static>|InformationSchemaTable whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class InformationSchemaTable extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Modules\Xot\Models\Feed.
 *
 * @property string|null $id
 * @property string|null $name
 * @property int|null    $size
 * @method static LogFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Log newModelQuery()
 * @method static Builder<static>|Log newQuery()
 * @method static Builder<static>|Log query()
 * @method static Builder<static>|Log whereId($value)
 * @method static Builder<static>|Log whereName($value)
 * @method static Builder<static>|Log whereSize($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property string|null          $file_content
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	class Log extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property int                             $id
 * @property string|null                     $name
 * @property string|null                     $slug
 * @property string|null                     $version
 * @property string|null                     $description
 * @property bool|null                       $status
 * @property bool|null                       $enabled
 * @property bool|null                       $is_active
 * @property int|null                        $priority
 * @property string|null                     $path
 * @property string|null                     $icon
 * @property array<array-key, mixed>|null    $colors
 * @property array<array-key, mixed>|null    $dependencies
 * @property array<array-key, mixed>|null    $config
 * @property array<array-key, mixed>|null    $metadata
 * @property \Illuminate\Support\Carbon|null $activation_date
 * @property \Illuminate\Support\Carbon|null $deactivation_date
 * @property \Illuminate\Support\Carbon|null $installation_date
 * @property array<array-key, mixed>|null    $update_history
 * @method static Builder<static>|Module newModelQuery()
 * @method static Builder<static>|Module newQuery()
 * @method static Builder<static>|Module query()
 * @method static Builder<static>|Module whereColors($value)
 * @method static Builder<static>|Module whereDescription($value)
 * @method static Builder<static>|Module whereIcon($value)
 * @method static Builder<static>|Module whereId($value)
 * @method static Builder<static>|Module whereName($value)
 * @method static Builder<static>|Module wherePath($value)
 * @method static Builder<static>|Module wherePriority($value)
 * @method static Builder<static>|Module whereStatus($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static ModuleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	final class Module extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property string      $id
 * @property int         $bucket
 * @property int         $period
 * @property string      $type
 * @property string      $key
 * @property string|null $key_hash
 * @property string      $aggregate
 * @property string      $value
 * @property int|null    $count
 * @method static PulseAggregateFactory          factory($count = null, $state = [])
 * @method static Builder<static>|PulseAggregate newModelQuery()
 * @method static Builder<static>|PulseAggregate newQuery()
 * @method static Builder<static>|PulseAggregate query()
 * @method static Builder<static>|PulseAggregate whereAggregate($value)
 * @method static Builder<static>|PulseAggregate whereBucket($value)
 * @method static Builder<static>|PulseAggregate whereCount($value)
 * @method static Builder<static>|PulseAggregate whereId($value)
 * @method static Builder<static>|PulseAggregate whereKey($value)
 * @method static Builder<static>|PulseAggregate whereKeyHash($value)
 * @method static Builder<static>|PulseAggregate wherePeriod($value)
 * @method static Builder<static>|PulseAggregate whereType($value)
 * @method static Builder<static>|PulseAggregate whereValue($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	class PulseAggregate extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property string               $id
 * @property int                  $timestamp
 * @property string               $type
 * @property string               $key
 * @property string|null          $key_hash
 * @property int|null             $value
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static PulseEntryFactory          factory($count = null, $state = [])
 * @method static Builder<static>|PulseEntry newModelQuery()
 * @method static Builder<static>|PulseEntry newQuery()
 * @method static Builder<static>|PulseEntry query()
 * @method static Builder<static>|PulseEntry whereId($value)
 * @method static Builder<static>|PulseEntry whereKey($value)
 * @method static Builder<static>|PulseEntry whereKeyHash($value)
 * @method static Builder<static>|PulseEntry whereTimestamp($value)
 * @method static Builder<static>|PulseEntry whereType($value)
 * @method static Builder<static>|PulseEntry whereValue($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class PulseEntry extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property string               $id
 * @property int                  $timestamp
 * @property string               $type
 * @property string               $key
 * @property string|null          $key_hash
 * @property string               $value
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static PulseValueFactory          factory($count = null, $state = [])
 * @method static Builder<static>|PulseValue newModelQuery()
 * @method static Builder<static>|PulseValue newQuery()
 * @method static Builder<static>|PulseValue query()
 * @method static Builder<static>|PulseValue whereId($value)
 * @method static Builder<static>|PulseValue whereKey($value)
 * @method static Builder<static>|PulseValue whereKeyHash($value)
 * @method static Builder<static>|PulseValue whereTimestamp($value)
 * @method static Builder<static>|PulseValue whereType($value)
 * @method static Builder<static>|PulseValue whereValue($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class PulseValue extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Modules\Xot\Models\Session.
 *
 * @property string               $id
 * @property string|null          $user_id
 * @property string|null          $ip_address
 * @property string|null          $user_agent
 * @property string               $payload
 * @property int                  $last_activity
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property string|null          $created_by
 * @property Carbon|null          $deleted_at
 * @property string|null          $deleted_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static SessionFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Session newModelQuery()
 * @method static Builder<static>|Session newQuery()
 * @method static Builder<static>|Session query()
 * @method static Builder<static>|Session whereCreatedAt($value)
 * @method static Builder<static>|Session whereCreatedBy($value)
 * @method static Builder<static>|Session whereDeletedAt($value)
 * @method static Builder<static>|Session whereDeletedBy($value)
 * @method static Builder<static>|Session whereId($value)
 * @method static Builder<static>|Session whereIpAddress($value)
 * @method static Builder<static>|Session whereLastActivity($value)
 * @method static Builder<static>|Session wherePayload($value)
 * @method static Builder<static>|Session whereUpdatedAt($value)
 * @method static Builder<static>|Session whereUpdatedBy($value)
 * @method static Builder<static>|Session whereUserAgent($value)
 * @method static Builder<static>|Session whereUserId($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class Session extends \Eloquent {}
}

