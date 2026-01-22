<?php
namespace App\Http\Controllers\MainStay\TicketController;

use App\Http\Requests\StoreTicketRequest;
use App\Models\DBModels\Customer;
use App\Models\LogicModels\EntityLogic\CustomerLogic\CustomerLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LogicModels\EntityLogic\CustomerTicketLogic\CustomerTicketLogic;
use App\Models\LogicModels\FileSystemLogic\FileSystemLogic;
use App\Models\DBModels\Ticket;

class TicketController {

    protected $customerTicketLogic;
    protected $fileSystemLogic;
    protected $customerLogic;

    public function __construct(
        CustomerLogic $customerLogic,
        CustomerTicketLogic $customerTicketLogic,
        FileSystemLogic $fileSystemLogic
    ) {
        $this->customerLogic = $customerLogic;
        $this->customerTicketLogic = $customerTicketLogic;
        $this->fileSystemLogic = $fileSystemLogic;
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $customer = $this->customerLogic->saveEntity($request->all() , 'phone_number');

            if (!$customer) {
                return response()->json(['message' => 'Ошибка при сохранении клиента'], 500);
            }
            $ticketData = [
                'description' => $request->input('description'),
                'status' => 0,
                'topic' => 'Обратная связь',
                'client_id' => $customer['id'],
                'response_date' =>  date('Y-m-d H:i:s'),
            ];

            $files = $request->hasFile('files') ? $request->file('files') : [];

            $ticket = $this->customerTicketLogic->saveWithAttachments($ticketData, $files);

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при сохранении обращения'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Сообщение успешно отправлено',
                'data' => [
                    'ticket_id' => $ticket->id,
                    'description' => $ticket->description,
                    'status' => $ticket->status,
                ]
            ], 201);

        } catch (\Throwable $exception) {
            dump($exception->getMessage() , $exception->getFile() , $exception->getLine());
            return response()->json(['message' => 'Ошибка сервера',], 500);
        }
    }

    public function show($id)
    {
        try {
            $ticket = $this->customerTicketLogic->getWithAttachments($id);
            if (!$ticket) {
                return response()->json(['message' => 'Заявка не найдена'], 404);
            }
            return response()->json(['data' => $ticket]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Ошибка сервера'], 500);
        }
    }
}
