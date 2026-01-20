<?php
namespace App\Models\LogicModels\EntityLogic;

class EntityLogicFactory {
    const USER = 1;
    const ADMIN = 2;
    const TICKET = 3;
    private $typeLogic = null;
    private $logic = null;
    public function __construct(int $typeLogic) {
        $this->typeLogic = $typeLogic;
        $this->logic = null;
    }

    public function switchLogic() {
        switch ($this->logic) {
            case self::USER:
                $this->logic = '';
            break;
            case self::ADMIN:
                $this->logic = '';
            break;

            case self::TICKET:
            $this->logic = '';
            break;
        }
    }

    public function getLogic() {
        return $this->logic;
    }
}
