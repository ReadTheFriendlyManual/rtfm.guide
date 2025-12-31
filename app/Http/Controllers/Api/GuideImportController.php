<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportGuidesRequest;
use App\Jobs\ProcessGuideImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GuideImportController extends Controller
{
    public function upload(ImportGuidesRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $fileName = sprintf('%s_%s', time(), $file->getClientOriginalName());

        $path = Storage::disk('guide-imports')->putFileAs(
            'imports',
            $file,
            $fileName
        );

        if (! $path) {
            return response()->json([
                'message' => 'Failed to upload guide import file.',
            ], 500);
        }

        ProcessGuideImport::dispatch($path, $request->user()->id);

        return response()->json([
            'message' => 'Guide import file uploaded successfully. Processing will begin shortly.',
            'file' => $path,
        ], 202);
    }
}
