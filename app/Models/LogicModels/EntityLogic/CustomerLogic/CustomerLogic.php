<?php
namespace  App\Models\LogicModels\EntityLogic\CustomerLogic;

use Illuminate\View\Engines\Engine;
use App\Models\DBModels\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\LogicModels\EntityLogic\EntityLogicInterface;
use App\Models\LogicModels\Core\CoreEngine;
use Illuminate\Support\Facades\Log;
class CustomerLogic extends CoreEngine implements EntityLogicInterface{
    public function __construct(Customer $customer) {
        $this->engine = $customer;
        return parent::__construct();
    }

    public function saveEntity(array $entity , $key = null) {
        try {
            return $this->save($entity , $key);
        } catch (\Throwable $exception) {
            dump($exception->getMessage() , $exception->getFile() , $exception->getLine());
            Log::error('CustomerSaveException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function getEntity(array $param = null) {
        try {
            return $this->get($param);
        } catch (\Throwable $exception) {
            Log::error('CustomerGetException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function deleteEntity(array $param) {
        try {
            return $this->delete($param);
        } catch (\Throwable $exception) {
            Log::error('CustomerDeleteException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }
}
