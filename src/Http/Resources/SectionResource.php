<?php

namespace R64\Webforms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'sort' => $this->sort,
            'slug' => $this->slug,
            'menu_title' => $this->menu_title,
            'title' => $this->title,
            'description' => $this->description,
            // Todo
            // 'completed' => $this->is_completed_by_current_user,
        ];
    }
}
