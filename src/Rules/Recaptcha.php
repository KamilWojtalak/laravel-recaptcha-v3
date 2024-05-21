<?php

namespace Wojtalak\LaravelRecaptchaV3\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = $this->makeRequestToGoogle($value);

            $this->handleLogic($response);
        } catch (\Throwable $th) {
            $this->handleException($th, $fail);
        }
    }

    private function getErroMappings(): array
    {
        return [
            'missing-input-secret' => __('You have not attached your google secret key.'),
            'invalid-input-secret' => __('You have put invalid google secret key.'),
            'missing-input-response' => __('You have not attached \'response\' key.'),
            'invalid-input-response' => __('You have put invalid \'response\' key.'),
            'bad-request' => __('Bad request.'),
            'timeout-or-duplicate' => __('The token has timed out or is a duplicate.'),
        ];
    }

    private function getErrorMessage(array $response): string
    {
        $key = $response['error-codes'][0];

        return $this->getErroMappings()[$key] ?? __('Something went wrong.');
    }

    private function getThreshold(): float
    {
        return (float) config('recaptcha.threshold', 0.5);
    }

    private function handleException(\Throwable $th, Closure $fail): void
    {
        \Log::error('ERROR | ' . __CLASS__);
        \Log::error($th->getMessage());

        $fail(__('Try submitting the form once more.'));
    }

    private function handleLogic(array $response): void
    {
        if ($response['success'] === false) {
            $errorMessage = $this->getErrorMessage($response);

            throw new \Exception($errorMessage);
        }

        $threshold = $this->getThreshold();

        if ($response['score'] < $threshold) {
            throw new \Exception(__('Try submitting the form once more.'));
        }
    }

    private function makeRequestToGoogle(mixed $value): array
    {
        $body = $this->prepareBody($value);

        $url = config('recaptcha.url');

        $response = Http::asForm()->post($url, $body);

        $json = $response->json();

        return $json;
    }

    private function prepareBody(mixed $value): array
    {
        return [
            'secret' => config('recaptcha.api_secret_key'),
            'response' => $value,
        ];
    }
}
