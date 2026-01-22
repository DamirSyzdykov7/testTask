<?php
namespace App\Models\LogicModels\FileSystemLogic;

use App\Models\LogicModels\Core\CoreEngine;
use App\Models\DBModels\Ticket;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileSystemLogic extends CoreEngine {
    public function __construct(Ticket $ticket) {
        $this->engine = $ticket;
        parent::__construct();
    }


    public function attachFileToTicket(int $ticketId, UploadedFile $file, string $collection = 'attachments')
    {
        try {
            $ticket = Ticket::find($ticketId);

            if (!$ticket) {
                return ['success' => false, 'message' => 'Ticket not found'];
            }

            $media = $ticket->addMedia($file)
                ->usingFileName($this->generateFileName($file))
                ->toMediaCollection($collection);

            return [
                'success' => true,
                'media' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'size' => $media->size,
                    'mime_type' => $media->mime_type,
                    'url' => $media->getUrl(),
                    'created_at' => $media->created_at->format('Y-m-d H:i:s')
                ]
            ];

        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function getTicketFiles(int $ticketId, string $collection = 'attachments')
    {
        try {
            $ticket = Ticket::with('media')->find($ticketId);

            if (!$ticket) {
                return ['success' => false, 'message' => 'Ticket not found'];
            }

            $files = $ticket->getMedia($collection)->map(function (Media $media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'size' => $media->size,
                    'human_size' => $this->formatBytes($media->size),
                    'mime_type' => $media->mime_type,
                    'url' => $media->getUrl(),
                    'download_url' => $media->getUrl('download'),
                    'created_at' => $media->created_at->format('d.m.Y H:i'),
                    'extension' => $media->extension,
                    'collection_name' => $media->collection_name
                ];
            });

            return [
                'success' => true,
                'files' => $files,
                'count' => $files->count()
            ];

        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function deleteFile(int $mediaId)
    {
        try {
            $media = Media::find($mediaId);

            if (!$media) {
                return ['success' => false, 'message' => 'File not found'];
            }

            $ticket = $media->model;

            $media->delete();

            return ['success' => true, 'message' => 'File deleted successfully'];

        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function validateFile(UploadedFile $file)
    {
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['valid' => false, 'message' => 'Invalid file type'];
        }

        $maxSize = 10 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            return ['valid' => false, 'message' => 'File too large (max 10MB)'];
        }

        return ['valid' => true, 'message' => 'File is valid'];
    }

    private function generateFileName(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();

        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);

        return "{$cleanName}_{$timestamp}.{$extension}";
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function getStorageInfo()
    {
        try {
            $disk = config('media-library.disk_name', 'public');
            $total = disk_total_space(storage_path('app/public'));
            $free = disk_free_space(storage_path('app/public'));
            $used = $total - $free;

            return [
                'success' => true,
                'disk' => $disk,
                'total' => $this->formatBytes($total),
                'free' => $this->formatBytes($free),
                'used' => $this->formatBytes($used),
                'used_percent' => round(($used / $total) * 100, 2)
            ];

        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
