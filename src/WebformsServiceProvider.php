<?php

namespace R64\Webforms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use R64\Webforms\Http\Controllers\AdminFormSectionController;
use R64\Webforms\Http\Controllers\AdminFormStepController;
use R64\Webforms\Http\Controllers\AdminQuestionController;
use R64\Webforms\Http\Controllers\AnswerController;
use R64\Webforms\Http\Controllers\FormSectionController;
use R64\Webforms\Http\Controllers\FormStepController;
use R64\Webforms\Http\Controllers\QuestionController;

class WebformsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/webforms.php' => config_path('webforms.php'),
            ], 'config');

            if (! class_exists('CreateWebformsTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_webforms_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_webforms_tables.php'),
                ], 'migrations');
            }
        }

        Route::macro('webforms', function (string $prefix) {
            Route::prefix($prefix)
                ->middleware(['throttle:60,1', 'bindings'])
                ->group(function () {
                    Route::get('/form-sections', [FormSectionController::class, 'index']);
                    Route::get('/form-steps', [FormStepController::class, 'index']);
                    Route::put('/form-steps/{formStep}', [FormStepController::class, 'update']);
                    Route::get('/questions', [QuestionController::class, 'index']);
                    Route::post('/answers', [AnswerController::class, 'store']);
                });
        });

        Route::macro('webformsAdmin', function (string $prefix) {
            Route::prefix($prefix)
                ->middleware(['throttle:60,1', 'bindings'])
                ->group(function () {
                    Route::post('/form-sections', [AdminFormSectionController::class, 'store']);
                    Route::post('/form-steps', [AdminFormStepController::class, 'store']);
                    Route::post('/questions', [AdminQuestionController::class, 'store']);
                    // Todo: Implement this
                    Route::put('/form-sections/{formSection}', [AdminFormSectionController::class, 'update']);
                    // Todo: Implement this
                    Route::put('/form-steps/{formStep}', [AdminFormStepController::class, 'update']);
                    // Todo: Implement this
                    Route::put('/questions/{question}', [AdminQuestionController::class, 'update']);
                    // Todo: Implement this
                    Route::delete('/form-sections/{formSection}', [AdminFormSectionController::class, 'destroy']);
                    // Todo: Implement this
                    Route::delete('/form-steps/{formStep}', [AdminFormStepController::class, 'destroy']);
                    // Todo: Implement this
                    Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy']);
                });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/webforms.php', 'webforms');
    }
}
