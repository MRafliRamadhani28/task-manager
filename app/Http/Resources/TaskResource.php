<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'task_description'      => $this->task_description,
            'date'                  => date('d M Y', strtotime($this->date)),
            'hourly_rate'           => (int) $this->hourly_rate,
            'total_hours_spent'     => $this->calculateRemuneration()->sum('hours_spent'),
            'additional_charges'    => (int) $this->additional_charges,
            'employees'             => $this->calculateRemuneration(),
            'total_remuneration'    => $this->calculateRemuneration()->sum('remuneration'),
        ];
    }
}
