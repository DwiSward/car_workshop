<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepairService extends Model
{
    use SoftDeletes;
    use HasFactory;
    use UuidTrait;

    use LogsActivity;

    protected $appends = ['status_text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function repairServiceMechanics()
    {
        return $this->hasMany(RepairServiceMechanic::class);
    }

        public function service()
        {
            return $this->belongsTo(Service::class);
        }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'In Progress';
                break;

            case 2 :
                return 'Done';
                break;
            
            default:
                return 'New';
                break;
        }
    }
}
