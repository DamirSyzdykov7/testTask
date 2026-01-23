<?php
namespace App\Models\LogicModels\EntityLogic\CustomerTicketLogic;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\LogicModels\EntityLogic\EntityLogicInterface;
use App\Models\LogicModels\Core\CoreEngine;
use Illuminate\Support\Facades\Log;
use App\Models\DBModels\Ticket;
use Illuminate\Http\UploadedFile;

class CustomerTicketLogic extends CoreEngine implements EntityLogicInterface{

    public $status = [
        'new' => 0,
        'in_progress' => 1,
        'resolved' => 2,
        'closed' => 3,
    ];

    public function __construct(Ticket $customer) {
        $this->engine = $customer;
        return parent::__construct();
    }

    public function saveEntity(array $entity , $key = null) {
        try {
            return $this->save($entity);
        } catch (\Throwable $exception) {
            Log::error('CustomerTicketSaveException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function getEntity(array $param = null) {
        try {
            return $this->get($param);
        } catch (\Throwable $exception) {
            Log::error('CustomerTicketGetException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function deleteEntity(array $param) {
        try {
            return $this->delete($param);
        } catch (\Throwable $exception) {
            Log::error('CustomerTicketDeleteException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function saveWithAttachments(array $data, array $files = [])
    {
        DB::beginTransaction();
        try {
            $ticketData = $this->saveEntity($data);

            if (!$ticketData) {
                throw new \Exception('не полуичлось сохранить заявку');
            }
            $ticket = Ticket::find($ticketData['id']);

            if (!$ticket) {
                throw new \Exception('заявка не найдена после сохранения');
            }
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $ticket->addMedia($file)
                        ->usingFileName(time() . '_' . $file->getClientOriginalName())
                        ->toMediaCollection('attachments');
                }
            }
            DB::commit();
            return $ticket;

        } catch (\Throwable $exception) {
            dump($exception->getMessage());
            DB::rollBack();
            Log::error('SaveTicketWithAttachmentsException', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile(), 'trace' => $exception->getTraceAsString()]);
            return false;
        }
    }

    public function getWithAttachments($id)
    {
        try {
            $ticket = $this->getEntity(['id' => $id]);

            if (!$ticket) {
                return null;
            }

            if (is_array($ticket)) {
                $ticket = Ticket::find($id);
            }

            if (!$ticket) {
                return null;
            }
            $ticket->load('media');
            return $ticket;
        } catch (\Throwable $exception) {
            dump($exception->getMessage());
            Log::error('GetTicketWithAttachmentsException', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);
            return null;
        }
    }

    public function getStatusTypes() {
        return $this->status;
    }
}
