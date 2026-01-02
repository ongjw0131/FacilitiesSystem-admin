<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Services\OutputEncodingService;

/**
 * Security Service Provider
 * 
 * Registers Blade directives for contextual output encoding
 */
class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        $this->app->singleton(OutputEncodingService::class);
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives for secure output encoding
     */
    protected function registerBladeDirectives(): void
    {
        $encoder = app(OutputEncodingService::class);

        /**
         * @js directive - Encode for JavaScript context
         * Usage: @js($variable)
         * Example: <script>const name = '@js($user->name)';</script>
         */
        Blade::directive('js', function ($expression) use ($encoder) {
            return "<?php echo app(\\App\\Services\\OutputEncodingService::class)->encodeJavaScript({$expression}); ?>";
        });

        /**
         * @jsString directive - Encode for JavaScript string literal
         * Usage: @jsString($variable)
         * Example: <script>const data = @jsString($data);</script>
         */
        Blade::directive('jsString', function ($expression) use ($encoder) {
            return "<?php echo app(\\App\\Services\\OutputEncodingService::class)->encodeJavaScriptString({$expression}); ?>";
        });

        /**
         * @url directive - Encode for URL parameter
         * Usage: @url($parameter)
         * Example: <a href="/search?q=@url($query)">Search</a>
         */
        Blade::directive('url', function ($expression) use ($encoder) {
            return "<?php echo app(\\App\\Services\\OutputEncodingService::class)->encodeUrl({$expression}); ?>";
        });

        /**
         * @css directive - Encode for CSS context
         * Usage: @css($value)
         * Example: <style>.user-color { color: @css($userColor); }</style>
         */
        Blade::directive('css', function ($expression) use ($encoder) {
            return "<?php echo app(\\App\\Services\\OutputEncodingService::class)->encodeCss({$expression}); ?>";
        });

        /**
         * @safeJson directive - Safe JSON encoding for inline scripts
         * Usage: @safeJson($data)
         * Example: <script>const config = @safeJson($config);</script>
         */
        Blade::directive('safeJson', function ($expression) use ($encoder) {
            return "<?php echo app(\\App\\Services\\OutputEncodingService::class)->safeJsonForScript({$expression}); ?>";
        });

        /**
         * @attr directive - Encode for HTML attribute
         * Usage: @attr($value)
         * Example: <input data-value="@attr($userInput)">
         */
        Blade::directive('attr', function ($expression) use ($encoder) {
            return "<?php echo app(\\App\\Services\\OutputEncodingService::class)->encodeHtmlAttribute({$expression}); ?>";
        });
    }
}