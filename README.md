# Laravel reCaptcha v3

## Intallation

```
composer require kamilwojtalak/laravel-recaptcha-v3:dev-master
```

## Get your key first!

First of all you have to create your own API keys [here](https://www.google.com/recaptcha/admin/site/446028211) 

Follow the instructions and at the end of the process you will find Site key and Secret key. Keep them close... you will need soon!  

## How to use

Add those environment variables to your .env file:  

```
RECAPTCHA_SITE_KEY='xxxxxxxxxxxxxxxxx'
RECAPTCHA_SECRET_KEY='xxxxxxxxxxxxxxxxx'
```

Then publish config files:  

```
php artisan vendor:publish --provider="Wojtalak\LaravelRecaptchaV3\Providers\LaravelRecaptchaV3ServiceProvider"
```

Ok, and now... When you want to use recaptcha in .blade.php where you have your form, you have to do following:  

```
@push('head_scripts')
    @reCaptchaHead()
@endpush

@push('footer_scripts')
    @reCaptchaFooter('demo-form')
@endpush
```

Keep that in mind, you have to define your @stacks accordingly.  

Then, when you define your form, you have to: 

```
<form action="<your url>" method="<your method>" id="demo-form">
    ... <your code> ...

    <button class="g-recaptcha" data-sitekey="{{ config('recaptcha.api_site_key') }}"
        data-callback="onSubmitRecaptcha" data-action="submit">
        {{ __('Submit') }}
    </button>
</form
```

Notice that you have provide ID for your form in two places, 1) @reCaptchaFooter('demo-form'), 2) id="demo-form"  


Inside yuur validation request do:  

```
use Wojtalak\LaravelRecaptchaV3\Rules;

...

public function rules(): array
{
    return [
        ... <the rest of the request rules> ...
        'g-recaptcha-response' => ['required' , new Rules\Recaptcha]
    ];
}
```

Have a nice day! 😎  
