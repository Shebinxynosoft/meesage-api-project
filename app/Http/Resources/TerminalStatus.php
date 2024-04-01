<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerminalStatus extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
           'branch_id' => $this->branch_id,
            'branch_id' => $this->branch->name,
            'terminal_name' => $this->terminal_name,
            'terminal_code' => $this->terminal_code,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at'=> $this->deleted_at,
        ];
    }
}
