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

    public function store(StoreTicketRequest $request) {
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
                'message' => 'Сообщение успешно отправлено',
                'data' => [
                    'ticket_id' => $ticket->id,
                    'description' => $ticket->description,
                    'status' => $ticket->status,
                ]
            ], 201);
    }

    public function updateTicket(Request $request) {
        return $this->customerTicketLogic->saveEntity($request->all());
    }

    public function adminTicket(Request $request) {
        $tickets = ($this->customerTicketLogic
            ->getEntity(!empty($request->all()) ? $request->all() :null)
            ->join('customer', 'customer.id', '=', 'ticket.client_id')
            ->select('ticket.*', 'customer.name as customer_name', 'customer.email')
            ->get()->toArray());
        $stats = $this->customerTicketLogic->getStatusTypes();
        return view('admin.tickets.TicketsPage', compact('tickets', 'stats'));
    }

    public function adminTicketsDetalis(Request $request) {
        $data = $request->all();
        $ticket = Ticket::with(['media'])->findOrFail($data['id']);
        return view('admin.tickets.chank.Ticketeitailpopup', compact('ticket' , 'data'));
    }
}
