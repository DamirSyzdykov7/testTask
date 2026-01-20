<?php
namespace App\Models\LogicModels\Core;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CoreEngine {
    protected $engine;

    const UPDATE_OR_INSERT = 1;
    const BUTCH_UPDATE_OR_INSERT = 0;

    public function __construct() {
    }

    protected function save(array $data, array|string $key = null, $type = self::UPDATE_OR_INSERT) {
        try {
            switch ($type) {
                case self::UPDATE_OR_INSERT:
                    $keySave = isset($key) ? $key : $data['id'];
                    return DB::table($this->engine->getName())->updateOrInsert(
                        [is_array($key) ? $key : $keySave => $data[$keySave]],
                        $data
                    );
                    break;
                case self::BUTCH_UPDATE_OR_INSERT:
                    if (!is_array($key)) {
                        return 'должен быть массив условий';
                    }
                    return DB::table($this->engine->getName())->upsert(
                        $data,
                        $key
                    );
                    break;
            }
        } catch (\Throwable $exception) {
            Log::error('CoreEngineSaveException', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    protected function delete($param) {
        try {
            if (empty($param)) {return 'пустые параметры для удаления';}
            return DB::table($this->engine->getName())->where($param)->delete();
        } catch (\Throwable $exception) {
            Log::error('CoreEngineDeleteException', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

    protected function get($param = '') {
        try {
            return DB::table($this->engine->getName())->where($param)->get()->toArray();
        } catch (\Throwable $exception) {
            Log::error('CoreEngineGetException', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile()]);
            return false;
        }
    }

}
