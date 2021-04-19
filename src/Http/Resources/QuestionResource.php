<?php

namespace R64\Webforms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use R64\Webforms\Helpers\Options;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $currentUserAnswer = $this->current_user_answer;

        return [
            'id' => $this->id,
            'form_step' => new FormStepResource($this->whenLoaded('formStep')),
            'sort' => $this->sort,
            'depends_on' => $this->depends_on,
            'shown_when' => $this->shown_when,
            'required' => $this->required,
            'slug' => $this->slug,
            'group_by' => $this->group_by,
            'group_by_description' => $this->group_by_description,
            'label_position' => $this->label_position,
            'help_title' => $this->help_title,
            'help_body' => $this->help_body,
            'type' => $this->type,
            'post_input_text' => $this->post_input_text,
            'title' => $this->title,
            'description' => $this->description,
            'error_message' => $this->error_message,
            'default_value' => $this->castNullValueToFront($this->default_value),
            'min' => $this->castNullValueToFront($this->min),
            'max' => $this->castNullValueToFront($this->max),
            'options' => $this->options ? Options::transform($this->options) : null,
            'answer' => $currentUserAnswer
                ? new AnswerResource($currentUserAnswer)
                : null,
        ];
    }
}
