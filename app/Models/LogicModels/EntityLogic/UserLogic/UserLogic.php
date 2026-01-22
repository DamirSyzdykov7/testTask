<?php
namespace  App\Models\LogicModels\EntityLogic\UserLogic;

use Illuminate\View\Engines\Engine;
use App\Models\DBModels\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\LogicModels\EntityLogic\EntityLogicInterface;
use App\Models\LogicModels\Core\CoreEngine;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class UserLogic extends CoreEngine implements EntityLogicInterface{

    public function __construct(User $user){
        $this->engine = $user;
        return parent::__construct();
    }

    public function saveEntity(array $entity , $key) {
        try {
            return $this->save($entity);
        } catch (\Throwable $exception) {
            Log::error('UserSaveException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function getEntity(array $param = null) {
        try {
            return $this->get($param);
        } catch (\Throwable $exception) {
            Log::error('UserGetException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    public function deleteEntity(array $param) {
        try {
            return $this->delete($param);
        } catch (\Throwable $exception) {
            Log::error('UserDeleteException' , ['message' => $exception->getMessage() , 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }
}
