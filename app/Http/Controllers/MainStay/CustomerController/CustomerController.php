<?php
namespace App\Http\Controllers\MainStay\TicketController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LogicModels\EntityLogic\CustomerTicketLogic\CustomerTicketLogic;
use App\Models\LogicModels\FileSystemLogic\FileSystemLogic;
use App\Models\DBModels\Ticket;

class CustomerController {

    protected $customerTicketLogic;
    protected $fileSystemLogic;

    public function __construct(
        CustomerTicketLogic $customerTicketLogic,
        FileSystemLogic $fileSystemLogic
    ) {
        $this->customerTicketLogic = $customerTicketLogic;
        $this->fileSystemLogic = $fileSystemLogic;
    }

}
