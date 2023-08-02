<?php

Route::group(['middleware' => ['auth:api', 'bindings']], function () {
    //Route::get('admin/package-batch-reassignment/fetch', [PackageBatchReassignmentController::class, 'fetch'])->name('package.skeleton.fetch');
    //Route::get('request/package-batch-reassignment', 'PackageBatchReassignmentController')->name('package.batch.reassignment');
    //Route::apiResource('admin/package-batch-reassignment', PackageBatchReassignmentController::class);
    Route::apiResource('requests/package-batch-reassignment', 'PackageBatchReassignmentController');
});
