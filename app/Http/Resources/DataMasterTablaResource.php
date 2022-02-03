<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DataMasterTablaResource extends JsonResource
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
                'user_id' => $this->user_id,
                'uuid' => $this->uuid,
                'version' => $this->version,
                'code' => $this->code,
                'format' => $this->format,
                'template' => $this->template,
                'description' => $this->description,
                'process_type' => $this->process_type,
                'process_description' => $this->process_description,
                'logo_header' => $this->logo_header,
                'position' => $this->position,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'id_user' => $this->user->id,
                'state_document' => $this->stateDocument->state_document,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'photo' => $this->user->profile_photo_path,
        ];   
    }
}
