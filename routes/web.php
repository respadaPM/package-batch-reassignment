<?php

Route::group(['middleware' => ['auth']], function () {
    //Route::get('admin/package-batch-reassignment', [PackageBatchReassignmentController::class, 'index'])->name('package.batch.reassignment.index');
    Route::get('requests/package-batch-reassignment', 'PackageBatchReassignmentController@index')->name('package.batch.reassignment.index');
    //Route::get('package-batch-reassignment', [PackageBatchReassignmentController::class, 'index'])->name('package.batch.reassignment.tab.index');
    //Route::get('package-batch-reassignment', 'PackageBatchReassignmentController@index')->name('package.batch.reassignment.tab.index');
});
