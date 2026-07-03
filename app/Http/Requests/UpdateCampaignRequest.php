<?php

namespace App\Http\Requests;

use App\Enums\CampaignType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $campaignId = $this->route('campaign')?->id ?? $this->route('campaign');

        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:newsletter_campaigns,slug,' . $campaignId,
            'subject' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:255',
            'campaign_type' => ['required', new Enum(CampaignType::class)],
            'template_id' => 'nullable|exists:newsletter_campaign_templates,id',
            'html_content' => 'required|string',
            'plain_text' => 'nullable|string',
            'scheduled_at' => 'nullable|date|after:now',
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'analytics_utm_source' => 'nullable|string|max:100',
            'analytics_utm_medium' => 'nullable|string|max:100',
            'analytics_utm_campaign' => 'nullable|string|max:100',
        ];
    }
}
