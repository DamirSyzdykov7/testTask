<?php
namespace  App\Models\LogicModels\EntityLogic\UserLogic;

use Illuminate\View\Engines\Engine;
use App\Models\DBModels\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\LogicModels\EntityLogic\EntityLogicInterface;
use App\Models\LogicModels\Core\CoreEngine;
use Illuminate\Support\Facades\Log;
class UserLogic extends CoreEngine implements EntityLogicInterface{

    public function __construct(){
        parent::__construct();
    }

    public function getEntity(array $param = null) {

    }

    public function saveEntity(array $entity) {
        
    }
    public function deleteEntity(array $param) {
        // TODO: Implement deleteEntity() method.
    }
}
