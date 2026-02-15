<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaV3 implements ValidationRule
{
    public function __construct(
        protected string $action = 'feedback',
        protected float $threshold = 0.5
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (empty($secretKey)) {
            return; // Skip validation if not configured
        }

        if (empty($value)) {
            $fail('reCAPTCHA verification failed. Please try again.');
            return;
        }

        try {
            $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $value,
            ]);
        } catch (\Throwable) {
            $fail('reCAPTCHA verification failed. Please try again.');
            return;
        }

        $result = $response->json();

        if (!is_array($result)
            || !($result['success'] ?? false)
            || ($result['action'] ?? '') !== $this->action
            || ($result['score'] ?? 0) < $this->threshold
        ) {
            $fail('reCAPTCHA verification failed. Please try again.');
        }
    }
}
