<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Exceptions\CustomErrorHandler;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\InventoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InventoryController extends Controller
{
    public function getAllInventories()
    {
        try {
            $inventories = Inventory::all();
            // Ambil data inventory, urutkan berdasarkan created_at secara menurun
            $inventories = Inventory::orderBy('created_at', 'desc')->get();
            if ($inventories->isEmpty()) {
                return ApiResponse::error('No inventories found.', 404);
            }
            return ApiResponse::success(InventoryResource::collection($inventories), 'Inventories retrieved successfully.', 200);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while retrieving the inventories.', 500);
        }
    }

    public function createInventory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100|unique:inventories,name',
                'quantity' => 'required|integer|min:1',
                'unit' => 'required|string|max:50',
                'item_status' => 'nullable|string|max:50',
                'entry_date' => 'nullable|date',
                'category' => 'required|string|max:100',
            ]);

            $item_status = 'Masuk';

            $inventory = Inventory::create(array_merge($request->only([
                'name',
                'quantity',
                'unit',
                'entry_date',
                'category',
            ]), [
                'item_status' => $item_status,
            ]));

            return ApiResponse::success(new InventoryResource($inventory), 'Inventory created successfully.', 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return CustomErrorHandler::handleException($e, 'Database error occurred while creating inventory.', 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return CustomErrorHandler::handleException($e, 'Validation error occurred.', 422);
        } catch (\Exception $e) {
            return CustomErrorHandler::handleException($e, 'An unexpected error occurred.', 500);
        }
    }

    public function getInventoryById($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            return ApiResponse::success(new InventoryResource($inventory), 'Inventory retrieved successfully.', 200);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Inventory not found.', 404);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while retrieving the inventory.', 500);
        }
    }

    public function getDeletedInventories()
    {
        try {
            $deletedInventories = Inventory::onlyTrashed()->get();
            if ($deletedInventories->isEmpty()) {
                return ApiResponse::error('No deleted inventories found.', 404);
            }
            return ApiResponse::success(InventoryResource::collection($deletedInventories), 'Deleted inventories retrieved successfully.', 200);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while retrieving deleted inventories.', 500);
        }
    }


    public function updateInventory(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'sometimes|required|string|max:100',
                'quantity' => 'sometimes|required|integer',
                'unit' => 'sometimes|required|string|max:50',
                'item_status' => 'sometimes|nullable|string|max:50',
                'entry_date' => 'sometimes|nullable|date',
                'category' => 'sometimes|required|string|max:100',
            ]);
            $inventory = Inventory::findOrFail($id);

            $updateData = $request->only([
                'name',
                'quantity',
                'unit',
                'entry_date',
                'category',
            ]);

            $inventory->fill($updateData);
            $inventory->save();

            Log::info('Inventory updated successfully', ['inventory' => $inventory]);

            return ApiResponse::success(new InventoryResource($inventory), 'Inventory updated successfully.', 200);
        } catch (ModelNotFoundException $exception) {
            Log::error('Inventory not found', ['id' => $id, 'exception' => $exception]);
            return ApiResponse::error('Inventory not found.', 404);
        } catch (\Exception $exception) {
            Log::error('An error occurred while updating the inventory', [
                'id' => $id,
                'exception' => $exception,
                'request_data' => $request->all()
            ]);
            return CustomErrorHandler::handleException($exception, 'An error occurred while updating the inventory.', 500);
        }
    }

    public function deleteInventory($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->delete();

            return ApiResponse::success(null, 'Inventory deleted successfully.', 200);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Inventory not found.', 404);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while deleting the inventory.', 500);
        }
    }
}
