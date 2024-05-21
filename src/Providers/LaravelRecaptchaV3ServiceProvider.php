<?php

namespace Wojtalak\LaravelRecaptchaV3\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class LaravelRecaptchaV3ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishFiles();

        $this->registerBladeDirectives();
    }

    private function publishFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/recaptcha.php' => config_path('recaptcha.php'),
        ]);
    }

    private function registerBladeDirectives(): void
    {
        Blade::directive('reCaptchaHead', function () {
            return '<script src="https://www.google.com/recaptcha/api.js"></script>';
        });

        Blade::directive('reCaptchaFooter', function (string $formId) {
            $return = $this->getHtmlForRecaptchaFooter($formId);

            return $return;
        });
    }

    private function getHtmlForRecaptchaFooter(string $formId): string
    {
        return <<<HTML
        <script>
        function onSubmitRecaptcha(token) {
            document.getElementById($formId).submit();
        }
        </script>
        HTML;
    }
}
