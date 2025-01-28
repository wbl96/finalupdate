<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "question_ar" => $this->question_ar,
            "question_en" => $this->question_en,
            "answer_ar" => $this->answer_ar,
            "answer_en" => $this->answer_en,
            "created_by" => new AdminResource($this->createdBy),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
