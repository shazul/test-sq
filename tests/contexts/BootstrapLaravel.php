<?php
trait BootstrapLaravel
{
    /**
     * @static
     * @beforeSuite
     */
    public static function bootstrapLaravel()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }
}
