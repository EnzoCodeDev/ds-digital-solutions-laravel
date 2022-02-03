<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubProcesosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'id_proceso' => $this->id_process,
            'sub_proceso' => $this->subProceso,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'proceso_id' => $this->proceso->id,
            'proceso' => $this->proceso->process,
            'created_at_proceso' => $this->proceso->created_at,
            'updated_at_proceso' => $this->proceso->updated_at,
            'id_user' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ];
    }
}
