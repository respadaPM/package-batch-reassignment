<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Artisan::command('package-batch-reassignment:install', function () {
   /* if (!Schema::hasTable('package_csi_settings')) {
        Schema::create('package_csi_settings', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->json('config');
            $table->timestamps();
        });

        DB::table('package_csi_settings')->insert(
            [
                ["name" => "Partner", "description" => "CSI Partner Name", "config" => json_encode(
                    [
                        "variable" => "partner",
                        "type" => "text",
                        "label" => "",
                        "value" => "ProcessMaker",
                        "options" => []
                    ]
                )],
                ["name" => "Product", "description" => "Lending or deposit (used in various API calls)", "config" => json_encode(
                    [
                        "variable" => "Product",
                        "type" => "text",
                        "label" => "",
                        "value" => "Deposit",
                        "options" => []
                    ]
                )],
                ["name" => "TDT Translator Code", "description" => "This is the translator code", "config" => json_encode(
                    [
                        "variable" => "tdtTranslatorCode",
                        "type" => "select",
                        "label" => "",
                        "value" => "json",
                        "options" => [
                            ["text" => "JSON", "value"=>"json"], 
                            ["text" => "XML", "value"=>"xml"],
                            ["text" => "CSV", "value"=>"csv"],
                        ]
                    ]
                )],
                ["name" => "TDT Mapping File", "description" => "This is the mapping schema file", "config" => json_encode(
                    [
                        "variable" => "tdtMappingFile",
                        "type" => "text",
                        "label" => "",
                        "value" => "Simplicity.Deposit.Schema.v1.0.json",
                        "options" => []
                    ]
                )],
                ["name" => "TDT API Key", "description" => "API key to be sent in the request header", "config" => json_encode(
                    [
                        "variable" => "apiKey",
                        "type" => "text",
                        "label" => "",
                        "value" => "",
                        "options" => []
                    ]
                )],
                ["name" => "License Key", "description" => "This is the license key", "config" => json_encode(
                    [
                        "variable" => "licenseKey",
                        "type" => "longtext",
                        "label" => "",
                        "value" => "",
                        "options" => []
                    ]
                )],
                ["name" => "Document Library", "description" => "Compliance Systems releases document libraries often. Specify which document linrary to use for document generation.", "config" =>  json_encode(
                    [
                        "variable" => "documentLibrary",
                        "type" => "text",
                        "label" => "",
                        "value" => "",
                        "options" => []
                    ]
                )],
                ["name" => "Configuration Id", "description" => "This is an ID used in the Runtime StartSession API call.", "config" => json_encode(
                    [
                        "variable" => "configurationId",
                        "type" => "text",
                        "label" => "",
                        "value" => "CSi-Default",
                        "options" => []
                    ]
                )],
                ["name" => "Environment", "description" => "Indicates the environment calls will be made to (dev, test, demo, prod)", "config" => json_encode(
                    [
                        "variable" => "env",
                        "type" => "select",
                        "label" => "",
                        "value" => "",
                        "options" => [
                            ["text" => "Development Environment", "value"=>"dev"], 
                            ["text" => "Test Environment", "value"=>"test"],
                            ["text" => "Demo Environment", "value"=>"demo"],
                            ["text" => "Production Environment", "value"=>"prod"],
                        ]
                    ]
                )],
                ["name" => "Notification URL", "description" => "This url will tell Runtime UI where to post notifications that partners can use for state management.", "config" => json_encode(
                    [
                        "variable" => "notificationUrl",
                        "type" => "url",
                        "label" => "",
                        "value" => "",
                        "options" => []
                    ]
                )],
                ["name" => "Redirect URL", "description" => "This url will tell Runtime UI where to redirect if/when Save is pressed.", "config" => json_encode(
                    [
                        "variable" => "redirectUrl",
                        "type" => "url",
                        "label" => "",
                        "value" => "",
                        "options" => []
                    ]
                )],
                ["name" => "PDT Url", "description" => "CSI PDT Url", "config" => json_encode(
                    [
                        "variable" => "pdtUrl",
                        "type" => "text",
                        "label" => "",
                        "value" => "https://pdt.{{env}}.compliancesystems.cloud",
                        "options" => []
                    ]
                )],
                ["name" => "Runtime Url", "description" => "CSI Runtime Url", "config" => json_encode(
                    [
                        "variable" => "runtimeUrl",
                        "type" => "text",
                        "label" => "",
                        "value" => "https://runtime.{{env}}.compliancesystems.cloud",
                        "options" => []
                    ]
                )],
                ["name" => "Intelledoc Url", "description" => "CSI Intelledoc Url", "config" => json_encode(
                    [
                        "variable" => "intelledocUrl",
                        "type" => "text",
                        "label" => "",
                        "value" => "https://intelledoc.{{env}}.compliancesystems.cloud/2.0/ComplianceService.asmx",
                        "options" => []
                    ]
                )],
                ["name" => "Translate Endpoint", "description" => "CSI Translate Endpoint", "config" => json_encode(
                    [
                        "variable" => "translateEndpoint",
                        "type" => "text",
                        "label" => "",
                        "value" => "/api/translate",
                        "options" => []
                    ]
                )],
                ["name" => "Start Session Endpoint", "description" => "CSI Start Session Endpoint", "config" => json_encode(
                    [
                        "variable" => "startSessionEndpoint",
                        "type" => "text",
                        "label" => "",
                        "value" => "/2.0/api/startsession/deposit",
                        "options" => []
                    ]
                )],
                ["name" => "Retrieve Txl Data Endpoint", "description" => "CSI Retrieve Txl Data Endpoint", "config" => json_encode(
                    [
                        "variable" => "retrieveTxlDataEndpoint",
                        "type" => "text",
                        "label" => "",
                        "value" => "/2.0/api/session",
                        "options" => []
                    ]
                )],
            ]
        );
    } 

    if (!Schema::hasTable('package_csi_logs')) {
        Schema::create('package_csi_logs', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('identifier')->nullable();
            $table->uuid('log_tracking_id')->nullable();
            $table->string('status');
            $table->json('request');
            $table->json('response');
            $table->timestamps();
        });
    }*/
    Artisan::call('vendor:publish', [
        '--tag' => 'package-batch-reassignment',
        '--force' => true
    ]);

    $this->info('Package Batch Reassignment has been installed');
})->describe('Installs the required js files and table in DB for Batch Reassignement Package');

Artisan::command('package-batch-reassignment:uninstall', function () {
    //Schema::dropIfExists('package_batch_reassignment_settings');
    //Schema::dropIfExists('package_batch_reassignment_logs');
    Illuminate\Support\Facades\File::deleteDirectory(public_path('vendor/processmaker/packages/package-batch-reassignment'));
    $this->info('Package Batch Reassignment has been Uninstalled');
})->describe('Uninstalls the Batch Reassignment Package');
