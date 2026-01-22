<?php
namespace App\Models\DBModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\File;

class Ticket extends Model implements HasMedia {
    use HasFactory, InteractsWithMedia;

    protected $table = 'ticket';
    protected $fillable = [
        'client_id',
        'topic',
        'description',
        'status',
        'response_date',
    ];

    public function getFillable() {return $this->fillable;}
    public function getName() {return $this->table;}

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsFile(function (File $file) {
                return true;
            })
            ->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->nonQueued();
    }

    public function attachments()
    {
        return $this->getMedia('attachments');
    }
}
