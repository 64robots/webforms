<?php

namespace R64\Webforms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $answer;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param $answer
     * @param $user
     */
    public function __construct($answer, $user)
    {
        $this->answer = $answer;
        $this->user = $user;
    }
}
