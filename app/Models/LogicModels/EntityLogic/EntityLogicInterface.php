<?php
namespace app\Models\LogicModels\EntityLogic;

interface EntityLogicInterface {
    public function getEntity(array $param = null);
    public function saveEntity(array $entity);
    public function deleteEntity(array $param);
}
