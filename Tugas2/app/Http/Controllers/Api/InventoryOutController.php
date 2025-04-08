<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use App\Models\InventoryOut;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Exceptions\CustomErrorHandler;
use App\Http\Resources\InventoryOutResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use carbon\Carbon;

class InventoryOutController extends Controller
{
    public function getAllInventoryOuts()
    {
        try {
            $inventoryouts = InventoryOut::all();
            if ($inventoryouts->isEmpty()) {
                return ApiResponse::error('No inventories found.', 404);
            }
            return ApiResponse::success(InventoryOutResource::collection($inventoryouts), 'Inventories retrieved successfully.', 200);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while retrieving the inventories.', 500);
        }
    }
    public function storeOut(Request $request)
    {
        try {
            Log::info('Request data:', $request->all()); // Log data permintaan

            // Validasi manual
            $request->validate([
                'inventory_id' => 'required|exists:inventories,id',
                'destination' => 'required|string|max:100',
                'unit_price' => 'required|numeric|min:0',
                'drop_out_date' => 'required|date',
                'quantity' => 'required|numeric|min:1',
                'document' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:10240',
                'image' => 'nullable|file|mimes:jpg,png|max:10240',
            ]);

            DB::beginTransaction(); // Mulai transaksi database

            // Cari inventory berdasarkan nama barang
            $inventory = Inventory::findOrFail($request->inventory_id);

            // Cek apakah stok cukup
            if ($inventory->quantity <= 0) {
                return ApiResponse::error('Stock is not available.', 400);
            }

            // Validasi tambahan
            if (Carbon::parse($request->drop_out_date)->lt(Carbon::parse($inventory->entry_date))) {
                return ApiResponse::error('Drop out date must be the same or after entry date.', 400);
            }

            if ($request->unit_price != $inventory->unit_price) {
                return ApiResponse::error('Unit price does not match the inventory price.', 400);
            }

            if ($inventory->quantity <= $inventory->minimum) {
                return ApiResponse::error('Stock is too low to be removed.', 400);
            }

            $inventory->quantity -= $request->quantity;

            // Hapus inventory jika stok habis
            if ($inventory->quantity <= 0) {
                $inventory->item_status = 'Keluar';
                $inventory->save();
                $inventory->delete(); // Soft delete
            } else {
                $inventory->save();
            }

            // Simpan dokumen & gambar jika ada
            $documentPath = $request->hasFile('document')
                ? $request->file('document')->store('documents', 'public')
                : null;

            $imagePath = $request->hasFile('image')
                ? $request->file('image')->store('images', 'public')
                : null;

            // Simpan data barang keluar
            $inventoryOut = InventoryOut::create([
                'inventory_id' => $inventory->id,
                'destination' => $request->destination,
                'unit_price' => $request->unit_price,
                'drop_out_date' => $request->drop_out_date,
                'quantity' => $request->quantity,
                'item_status' => 'Keluar',
                'category' => $inventory->category ?? 'Uncategorized',
                'document' => $documentPath,
                'image' => $imagePath,
            ]);

            DB::commit(); // Simpan perubahan

            Log::info('Saving InventoryOut:', [
                'inventory_id' => $inventory->id,
                'destination' => $request->destination,
                'unit_price' => $request->unit_price,
                'drop_out_date' => $request->drop_out_date,
                'quantity' => $request->quantity,
                'item_status' => 'Keluar',
                'category' => $inventory->category,
                'document' => $documentPath,
                'image' => $imagePath,
            ]);

            return ApiResponse::success(new InventoryOutResource($inventoryOut), 'Inventory successfully removed.', 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return ApiResponse::error('Inventory not found.', 404);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error processing inventory out: ' . $exception->getMessage());
            return ApiResponse::error('An error occurred while processing inventory out.', 500);
        }
    }
}
