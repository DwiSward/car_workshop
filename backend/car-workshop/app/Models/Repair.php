<?php

namespace App\Models;

use App\Models\Car;
use App\Traits\UuidTrait;
use App\Models\RepairService;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair extends Model
{
    use SoftDeletes;
    use HasFactory;
    use UuidTrait;

    use LogsActivity;

    protected $appends = ['created_at_text', 'status_of_services', 'status_text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }

    public function repairServices()
    {
        return $this->hasMany(RepairService::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function getCreatedAtTextAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getStatusOfServicesAttibute()
    {
        $text = '';
        foreach ($this->repairServices as $key => $repairService) {
            $text .= $repairService->service->name.' '.$repairService->status_text;
        }
        return $text;
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'Approved Owner';
                break;

            case 2 :
                return 'Progress';
                break;

            case 3 :
                return 'Service Done';
                break;
            
            case 4 :
                return 'Done';
                break;

            case 5 :
                return 'Canceled';
                break;

            default:
                return 'Proposal';
                break;
        }
    }
}
