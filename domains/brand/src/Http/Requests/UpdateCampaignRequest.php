<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name'            => ['sometimes', 'string', 'max:255'],
            'type'            => ['sometimes', 'string', 'in:win_back,milestone,nurture,seasonal'],
            'channel'         => ['sometimes', 'string', 'in:email,push,sms,all'],
            'status'          => ['sometimes', 'string', 'in:draft,active,paused,completed'],
            'subject'         => ['nullable', 'string', 'max:500'],
            'body'            => ['nullable', 'string'],
            'trigger_rule'    => ['nullable', 'array'],
            'audience_filter' => ['nullable', 'array'],
            'scheduled_at'    => ['nullable', 'date'],
        ];
    }
}
