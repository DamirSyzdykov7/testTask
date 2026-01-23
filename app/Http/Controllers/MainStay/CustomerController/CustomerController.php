<?php
namespace App\Http\Controllers\MainStay\CustomerController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LogicModels\EntityLogic\CustomerTicketLogic\CustomerTicketLogic;
use App\Models\LogicModels\FileSystemLogic\FileSystemLogic;
use App\Models\DBModels\Ticket;
use App\Models\LogicModels\EntityLogic\CustomerLogic\CustomerLogic;


class CustomerController {

    protected $customerTicketLogic;
    protected $fileSystemLogic;

    public function __construct(
        CustomerLogic $customerLogic,
        FileSystemLogic $fileSystemLogic
    ) {
        $this->customerLogic = $customerLogic;
        $this->fileSystemLogic = $fileSystemLogic;
    }
    public function customerAdmin() {
        $customers = $this->customerLogic->getEntity()->get()->toArray();
        return view('admin.customers.CustomersPage' , compact('customers'));
    }

}
