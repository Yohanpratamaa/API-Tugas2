<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use App\Models\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\InventoryImport;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\CustomErrorHandler;
use App\Http\Requests\InventoryRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\InventoryResource;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
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
                'location' => 'required|string|max:100',
                'unit_price' => 'required|numeric',
                'quantity' => 'required|integer|min:1',
                'unit' => 'required|string|max:50',
                'minimum' => 'required|integer|min:1',
                'stock_status' => 'nullable|string|max:50',
                'item_status' => 'nullable|string|max:50',
                'total_price' => 'nullable|numeric|min:0',
                'entry_date' => 'nullable|date',
                'document_date' => 'nullable|date',
                'date_of_manufacture' => 'nullable|date',
                'date_of_expired' => 'nullable|date',
                'source' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'condition' => 'required|string|max:50',
                // 'part_number' => 'nullable|json',
                'part_number' => 'nullable|array',
                'part_number.*' => 'string',
                'document' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:10240',
                'image' => 'nullable|file|mimes:jpg,png|max:10240',
            ]);
            $documentPath = null;
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('documents', 'public'); // Menyimpan dokumen di storage/app/public/documents
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public'); // Menyimpan gambar di storage/app/public/images
            }

            $totalPrice = $request->unit_price * $request->quantity;

            $item_status = 'Masuk';
            $stock_status = 'Aman';
            if ($request->quantity <= $request->minimum) {
                $stock_status = 'Tidak Aman';
            } elseif ($request->quantity == 0) {
                $stock_status = 'Habis';
            }

            $inventory = Inventory::create(array_merge($request->only([
                'name',
                'location',
                'unit_price',
                'quantity',
                'unit',
                'minimum',
                'entry_date',
                'document_date',
                'date_of_expired',
                'date_of_manufacture',
                'source',
                'category',
                'condition',
                'part_number',
            ]), [
                'total_price' => $totalPrice,
                'stock_status' => $stock_status,
                'item_status' => $item_status,
                'document' => $documentPath,
                'image' => $imagePath,
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
                'location' => 'sometimes|required|string|max:100',
                'unit_price' => 'sometimes|required|numeric',
                'quantity' => 'sometimes|required|integer',
                'unit' => 'sometimes|required|string|max:50',
                'minimum' => 'sometimes|required|integer|min:1',
                'stock_status' => 'sometimes|nullable|string|max:50',
                'item_status' => 'sometimes|nullable|string|max:50',
                'total_price' => 'sometimes|nullable|numeric|min:0',
                'entry_date' => 'sometimes|nullable|date',
                'document_date' => 'sometimes|nullable|date',
                'date_of_manufacture' => 'sometimes|nullable|date',
                'date_of_expired' => 'sometimes|nullable|date',
                'source' => 'sometimes|required|string|max:100',
                'category' => 'sometimes|required|string|max:100',
                'condition' => 'sometimes|required|string|max:50',
                // 'part_number' => 'sometimes|nullable|json',
                'part_number' => 'sometimes|nullable|array',
                'part_number.*' => 'string',
                'document' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:10240',
                'image' => 'nullable|file|mimes:jpg,png|max:10240',
            ]);
            $inventory = Inventory::findOrFail($id);

            $oldDocumentPath = $inventory->document;
            $oldImagePath = $inventory->image;

            $updateData = $request->only([
                'name',
                'location',
                'unit_price',
                'quantity',
                'unit',
                'minimum',
                'entry_date',
                'document_date',
                'date_of_manufacture',
                'date_of_expired',
                'source',
                'category',
                'condition',
                'part_number',
            ]);

            if ($request->hasFile('document')) {
                // Hapus file lama jika ada
                if ($oldDocumentPath && Storage::disk('public')->exists($oldDocumentPath)) {
                    Storage::disk('public')->delete($oldDocumentPath);
                    Log::info('Deleted old document: ' . $inventory->document);
                }
                // Simpan file baru
                $updateData['document'] = $request->file('document')->store('documents', 'public');
                Log::info('Uploaded new document: ' . $updateData['document']);
            } elseif ($request->input('delete_document')) {
                // Hapus dokumen lama jika ada dan set ke null
                if ($oldDocumentPath && Storage::disk('public')->exists($oldDocumentPath)) {
                    Storage::disk('public')->delete($oldDocumentPath);
                    Log::info('Deleted old document due to cancel: ' . $oldDocumentPath);
                }
                $updateData['document'] = null; // Set field document ke null
                Log::info('Document field set to null');
            } else {
                // Jika tidak ada file baru, tetap gunakan path lama
                $updateData['document'] = $oldDocumentPath;
            }

            // Proses file image jika ada
            if ($request->hasFile('image')) {
                // Hapus file lama jika ada
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                    Log::info('Deleted old image: ' . $inventory->image);
                }
                // Simpan file baru
                $updateData['image'] = $request->file('image')->store('images', 'public');
                Log::info('Uploaded new image: ' . $updateData['image']);
            } elseif ($request->input('delete_image')) {
                // Hapus dokumen lama jika ada dan set ke null
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                    Log::info('Deleted old image due to cancel: ' . $oldImagePath);
                }
                $updateData['image'] = null; // Set field image ke null
                Log::info('Image field set to null');
            } else {
                // Jika tidak ada file baru, tetap gunakan path lama
                $updateData['image'] = $oldImagePath;
            }

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

    // public function updateInventory(Request $request, $id)
    // {
    //     try {
    //         // Validasi input
    //         $request->validate([
    //             'name' => 'sometimes|required|string|max:100',
    //             'location' => 'sometimes|required|string|max:100',
    //             'unit_price' => 'sometimes|required|numeric',
    //             'quantity' => 'sometimes|required|integer',
    //             'unit' => 'sometimes|required|string|max:50',
    //             'minimum' => 'sometimes|required|integer',
    //             'entry_date' => 'sometimes|required|date',
    //             'expired_at' => 'sometimes|required|date',
    //             'document_date' => 'sometimes|required|date',
    //             'source' => 'sometimes|required|string|max:100',
    //             'category' => 'sometimes|required|string|max:100',
    //             'document' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:10240',
    //             'image' => 'nullable|file|mimes:jpg,png|max:10240',
    //         ]);

    //         // Temukan inventory berdasarkan ID
    //         $inventory = Inventory::findOrFail($id);

    //         // Ambil path dokumen dan gambar lama
    //         $oldDocumentPath = $inventory->document;
    //         $oldImagePath = $inventory->image;

    //         // Ambil data yang akan diperbarui
    //         $updateData = $request->only([
    //             'name',
    //             'location',
    //             'unit_price',
    //             'quantity',
    //             'unit',
    //             'minimum',
    //             'entry_date',
    //             'expired_at',
    //             'document_date',
    //             'source',
    //             'category'
    //         ]);

    //         if ($request->hasFile('document')) {
    //             // Hapus file lama jika ada
    //             if ($oldDocumentPath && Storage::disk('public')->exists($oldDocumentPath)) {
    //                 Storage::disk('public')->delete($oldDocumentPath);
    //             }
    //             // Simpan file baru
    //             $documentPath = $request->file('document')->store('documents', 'public');
    //             Log::info('New document uploaded: ' . $documentPath);
    //             $updateData['document'] = $documentPath;
    //         }

    //         if ($request->hasFile('image')) {
    //             // Hapus file lama jika ada
    //             if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
    //                 Storage::disk('public')->delete($oldImagePath);
    //             }
    //             // Simpan file baru
    //             $imagePath = $request->file('image')->store('images', 'public');
    //             Log::info('New image uploaded: ' . $imagePath);
    //             $updateData['image'] = $imagePath;
    //         }


    //         // Perbarui inventory dengan data baru
    //         $inventory->fill($updateData);
    //         $inventory->save();
    //         // Cek apakah file diterima oleh Laravel
    //         Log::info('Request received', [
    //             'document' => $request->hasFile('document'),
    //             'image' => $request->hasFile('image')
    //         ]);
    //         // dd($request->all(), $request->hasFile('document'), $request->hasFile('image'));
    //         Log::info('Inventory updated successfully', ['inventory' => $inventory]);

    //         return ApiResponse::success(new InventoryResource($inventory), 'Inventory updated successfully.', 200);
    //     } catch (ModelNotFoundException $exception) {
    //         Log::error('Inventory not found', ['id' => $id, 'exception' => $exception]);
    //         return ApiResponse::error('Inventory not found.', 404);
    //     } catch (\Exception $exception) {
    //         Log::error('An error occurred while updating the inventory', [
    //             'id' => $id,
    //             'exception' => $exception,
    //             'request_data' => $request->all()
    //         ]);
    //         return CustomErrorHandler::handleException($exception, 'An error occurred while updating the inventory.', 500);
    //     }
    // }

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

    public function restoreInventory($id)
    {
        try {
            $inventory = Inventory::onlyTrashed()->findOrFail($id);
            $inventory->restore();

            return ApiResponse::success(new InventoryResource($inventory), 'Inventory restored successfully.', 200);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Deleted inventory not found.', 404);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while restoring the inventory.', 500);
        }
    }

    public function forceDeleteInventory($id)
    {
        try {
            $inventory = Inventory::onlyTrashed()->findOrFail($id);
            $inventory->forceDelete();

            return ApiResponse::success(null, 'Inventory permanently deleted.', 200);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Deleted inventory not found.', 404);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while permanently deleting the inventory.', 500);
        }
    }

    private function createNotification($inventory, $type, $message)
    {
        Notification::create([
            'inventories_id' => $inventory->id,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    public function indexPDFDaftarBarang(){
        $inventory = Inventory::all();
        return view('features.inventory.show', compact('inventory'));
    }
    public function exportPDFDaftarBarang(){
        $inventory = Inventory::all();
        $pdf = Pdf::loadView('features.inventory.PdfDaftarBarang', compact('inventory'));

        return $pdf-> download('daftar_barang.pdf');
    }
    public function exportPDFDetailBarang($id)
 {
        try {
            $inventory = Inventory::findOrFail($id);
            $pdf = Pdf::loadView('features.inventory.PdfDetailBarang', compact('inventory'));
            return $pdf->download('detail_barang_' . $inventory->name . '.pdf');
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Inventory not found.', 404);
        } catch (\Exception $exception) {
            return CustomErrorHandler::handleException($exception, 'An error occurred while generating the PDF.', 500);
        }
}
public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:10240' // Maks 10MB
    ]);

    try {
        Excel::import(new InventoryImport, $request->file('file'));
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diimport'
        ]);
    } catch (ExcelValidationException $e) {
        $failures = $e->failures();
        $errorMessage = 'Validasi gagal: ';
        foreach ($failures as $failure) {
            $errorMessage .= "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . '; ';
        }
        return response()->json([
            'status' => 'error',
            'message' => $errorMessage
        ], 422);
    } catch (\Exception $e) {
        Log::error('Import Error: ' . $e->getMessage());
        $errorMessage = 'Gagal mengimport data: ' . $e->getMessage();
        if (str_contains($errorMessage, 'Undefined array key')) {
            $errorMessage = 'Format file Excel tidak sesuai. Pastikan header sesuai dengan template: Nama_Barang, Serial_Number_Part_Number, Lokasi_Barang, dll.';
        }
        return response()->json([
            'status' => 'error',
            'message' => $errorMessage
        ], 500);
    }
}
    public function downloadTemplate()
    {
        $file = public_path('templates/inventory_template.xlsx');
        return response()->download($file, 'template_inventori.xlsx');
    }
}
