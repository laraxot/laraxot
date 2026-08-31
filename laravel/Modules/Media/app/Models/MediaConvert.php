<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\TechPlanner\Models\Profile;

/**
 * @property-read Profile|null $creator
 * @property-read string|null $converted_file
 * @property-read string|null $disk
 * @property-read string|null $file
 * @property-read Media|null $media
 * @property-read Profile|null $updater
 *
 * @method static Builder<static>|MediaConvert newModelQuery()
 * @method static Builder<static>|MediaConvert newQuery()
 * @method static Builder<static>|MediaConvert query()
 *
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
 *
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
 *
 * @mixin \Eloquent
 */
class MediaConvert extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'media_id',
        'format',
        'codec_video',
        'codec_audio',
        'preset',
        'bitrate',
        'width',
        'height',
        'threads',
        'speed',
        'percentage',
        'remaining',
        'rate',
        'execution_time',
    ];

    /**
     * @return BelongsTo<Media, $this>
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function getDiskAttribute(?string $value): ?string
    {
        if ($this->media === null) {
            return null;
        }

        return $this->media->disk;
    }

    public function getFileAttribute(?string $value): ?string
    {
        if ($this->media === null) {
            return null;
        }

        return $this->media->path.'/'.$this->media->file_name;
    }

    public function getConvertedFileAttribute(?string $value): ?string
    {
        if ($this->media === null) {
            return null;
        }
        $info = pathinfo($this->media->file_name);
        // "dirname" => "."
        // "basename" => "20600550-uhd_3840_2160_30fps.mp4"
        // "extension" => "mp4"
        // "filename" => "20600550-uhd_3840_2160_30fps"

        return $this->media->path.'/conversions/'.$info['filename'].'_'.$this->id.'.'.$this->format;
    }
}
