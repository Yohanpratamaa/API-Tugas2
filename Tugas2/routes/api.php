<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\InventoryOutController;

// Inventory In
Route::get('/inventories', [InventoryController::class, 'getAllInventories']);
Route::post('/inventory', [InventoryController::class, 'createInventory']);
Route::patch('/inventory/{id}', [InventoryController::class, 'updateInventory']);
Route::delete('/inventory/{id}', [InventoryController::class, 'deleteInventory']);
Route::get('/inventory/{id}', [InventoryController::class, 'getInventoryById']);

// Inventory Out
Route::post('/inventory-outs', [InventoryOutController::class, 'storeOut']);
Route::get('/inventory-outs-all', [InventoryOutController::class, 'getAllInventoryOuts']);
Route::get('/inventory-out/{inventory_id}', [InventoryOutController::class, 'getInventoryOutById']);
Route::get('/inventories/deleted', [InventoryController::class, 'getDeletedInventories']);

