<?php

namespace R64\Webforms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'question_id' => $this->question_id,
            'text' => $this->front_text,
            'confirmed' => $this->confirmed,
        ];
    }
}
