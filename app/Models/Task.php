<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task_description', 'date', 'hourly_rate', 'additional_charges'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class)
            ->withPivot('hours_spent')
            ->withTimestamps();
    }

    public function calculateRemuneration()
    {
        $total = [];
        foreach ($this->employees as $employee) {
            $total[] = [
                'id'           => $employee->id,
                'name'         => $employee->name,
                'hours_spent'  => (int) $employee->pivot->hours_spent,
                'remuneration' => ($employee->pivot->hours_spent * $this->hourly_rate) - ($this->additional_charges / count($this->employees)),
            ];
        }

        return collect($total);
    }
}
