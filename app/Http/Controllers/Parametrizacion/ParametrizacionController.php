<?php

namespace App\Http\Controllers\Parametrizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataMasterParametrizationHead;
use App\Models\DataMasterParametrizationBody;
use App\Models\DataMasterParametrizationTable;
use App\Models\ProcesoModel;
use App\Models\SubProcesos;
use App\Models\DocumentStatusFlow;
use App\Models\TableStateDocument;
use App\Http\Resources\DataMasterTablaResource;
use App\Http\Resources\ProcesosResource;
use App\Http\Resources\SubProcesosResource;
use Ramsey\Uuid\Uuid;

class ParametrizacionController extends Controller
{
    /**
     *
     *
     *
     ********************************************
     * PARAMETRIZACION
     ********************************************
     *
     *
     *
     * */
    //Traer todos los documentos
    public function index()
    //Los recursos se estan trayendo con relaciones para no malgastar recursos
    {
        return DataMasterTablaResource::collection(DataMasterParametrizationHead::latest()->paginate(13));
    }
    //Traer solo un documento
    public function view($DocumentMaster)
    {
        $DocumentMasterHead = DataMasterParametrizationHead::where('uuid', '=', $DocumentMaster)->first();
        $DocumentMasterBody = DataMasterParametrizationBody::where('id_header', '=', $DocumentMasterHead->id)->where('version', '=', $DocumentMasterHead->version)->get();
        $DocumentMasterBodyTable = DataMasterParametrizationTable::where('id_header', '=', $DocumentMasterHead->id)->where('id_header_version', '=', $DocumentMasterHead->version)->get();
        return response()->json([
            'res' => 'success_view',
            'DocumentMasterHead' => $DocumentMasterHead,
            'DocumentMasterBody' => $DocumentMasterBody,
            'DocumentMasterBodyTable' => $DocumentMasterBodyTable,
        ], 200);
    }
    //Crear un nuevo documento
    public function store(Request $request)
    {
        //Validacion de que vengan estos datos obligatorios
        $request->validate([
            'code' => ['required', 'string'],
            'format' => ['required', 'string'],
            'template' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);
        if (DataMasterParametrizationHead::where('code', $request->code)->exists()) {
            return response()->json([
                'res' => 'failed code',
            ], 201);
        }
        $uuid = Uuid::uuid1();
        $uuid2 = Uuid::uuid4();
        //Guardar los datos que estan en el header de la aplicacion
        $NewDocumentMaster  = DataMasterParametrizationHead::create([
            'uuid' => $uuid . $uuid2,
            'user_id' => \Auth::user()->id,
            'version' => 1,
            'state_document' => 1,
            'code' => $request->all()['code'],
            'format' => $request->all()['format'],
            'template' => $request->all()['template'],
            'description' => $request->all()['description'],
            'process_select' => $request->all()['process_option'],
            'sub_process_select' => $request->all()['sub_process_option'],
            'position_data_basic' => $request->all()['dataBasicCount'] === [] ? json_encode([]) : json_encode($request->all()['dataBasicCount']),
            'data_basic' =>  $request->all()['data_basic'] === [] ? json_encode([])  : json_encode($request->all()['data_basic']),
            'position' => json_encode($request->all()['position']),
        ]);
        //Guardar los datos de la tarjeta
        for ($i = 1; $i < count($request->all()['optionTarget']); $i++) {
            if ($request->all()['optionTarget'][$i]['card'] !== 'inhabilidado') {
                $NewDocumentMasterBody = DataMasterParametrizationBody::create([
                    'id_header' => $NewDocumentMaster->id,
                    'version' => $NewDocumentMaster->version,
                    'number_card' => $request->all()['optionTarget'][$i]['card'],
                    'title_card' => $request->all()['optionTarget'][$i]['titleCard'],
                    'select_value' => $request->all()['optionTarget'][$i]['optionValue'],
                    'text_description' => $request->all()['optionTarget'][$i]['text'] !== 'Tabla' ?$request->all()['optionTarget'][$i]['text']  : null,
                    'columns' => $request->all()['optionTarget'][$i]['optionValue'] === 'Tabla' ? json_encode($request->all()['optionTarget'][$i]['tabla']['column']) : null,
                    'row' => $request->all()['optionTarget'][$i]['optionValue'] === 'Tabla' ?  json_encode($request->all()['optionTarget'][$i]['tabla']['row']) : null,
                    'title_columns' => $request->all()['optionTarget'][$i]['optionValue'] === 'Tabla' ?  $request->all()['optionTarget'][$i]['arrayTitleColumns'] : null,
                ]);
                //Guardar los datos de la tablas de las tarjetas
                if ($request->all()['optionTarget'][$i]['optionValue'] === 'Tabla') {
                    for ($t = 0; $t < count($request->all()['optionTarget'][$i]['arrayTable']); $t++) {
                        DataMasterParametrizationTable::create([
                            'id_header' => $NewDocumentMaster->id,
                            'id_header_version' => $NewDocumentMaster->version,
                            'id_card' => $NewDocumentMasterBody->id,
                            'type_celda' => $request->all()['optionTarget'][$i]['arrayTable'][$t]['typeCelda'],
                            'type_lista' => json_encode($request->all()['optionTarget'][$i]['arrayTable'][$t]['lista']),
                            'index_table' => $request->all()['optionTarget'][$i]['arrayTable'][$t]['index'],
                        ]);
                    }
                }
            }
        }
        $StateDocument  = DocumentStatusFlow::create([
            'id_header_document' => $NewDocumentMaster->id,
            'id_state_document' => 1,
            'observacion' => null,
        ]);
        return response()->json([
            'res' => 'success_new',
            'DocumentMasterHead' => $NewDocumentMaster,
            'New_state'=> $StateDocument,
        ], 201);
    }
    //Actualiza un documento
    public function update(Request $request, $DocumentMaster)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'format' => ['required', 'string'],
            'template' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);
        $DocumentMasterHead = DataMasterParametrizationHead::where('uuid', '=', $DocumentMaster)->first();
        if ($DocumentMasterHead->code === $request->code) {
        } else {
            if (DataMasterParametrizationHead::where('code', $request->code)->exists()) {
                return response()->json([
                    'res' => 'failed code',
                ], 201);
            }
        };
        $DocumentMasterHead->update([
            'user_id' => \Auth::user()->id,
            'version' => $DocumentMasterHead->version + 1,
            'state_document' => 1,
            'code' => $request->all()['code'],
            'format' => $request->all()['format'],
            'template' => $request->all()['template'],
            'description' => $request->all()['description'],
            'process_select' => $request->all()['process_option'],
            'sub_process_select' => $request->all()['sub_process_option'],
            'position_data_basic' => $request->all()['dataBasicCount'] === [] ? json_encode([]) : json_encode($request->all()['dataBasicCount']),
            'data_basic' =>  $request->all()['data_basic'] === [] ? json_encode([])  : json_encode($request->all()['data_basic']),
            'position' => json_encode($request->all()['position']),
        ]);
        for ($i = 1; $i < count($request->all()['optionTarget']); $i++) {
            if ($request->all()['optionTarget'][$i]['card'] !== 'inhabilidado') {
                $DocumentMasterBody = DataMasterParametrizationBody::create([
                    'id_header' => $DocumentMasterHead->id,
                    'version' => $DocumentMasterHead->version,
                    'number_card' => $request->all()['optionTarget'][$i]['card'],
                    'title_card' => $request->all()['optionTarget'][$i]['titleCard'],
                    'select_value' => $request->all()['optionTarget'][$i]['optionValue'],
                    'text_description' => $request->all()['optionTarget'][$i]['text'] !== 'Tabla' ?$request->all()['optionTarget'][$i]['text']  : null,
                    'columns' => $request->all()['optionTarget'][$i]['optionValue'] === 'Tabla' ? json_encode($request->all()['optionTarget'][$i]['tabla']['column']) : null,
                    'row' => $request->all()['optionTarget'][$i]['optionValue'] === 'Tabla' ?  json_encode($request->all()['optionTarget'][$i]['tabla']['row']) : null,
                    'title_columns' => $request->all()['optionTarget'][$i]['optionValue'] === 'Tabla' ?  $request->all()['optionTarget'][$i]['arrayTitleColumns'] : null,
                ]);
                //Guardar los datos de la tablas de las tarjetas
                if ($request->all()['optionTarget'][$i]['optionValue'] === 'Tabla') {
                    for ($t = 0; $t < count($request->all()['optionTarget'][$i]['arrayTable']); $t++) {
                        DataMasterParametrizationTable::create([
                            'id_header' => $DocumentMasterHead->id,
                            'id_header_version' => $DocumentMasterHead->version,
                            'id_card' => $DocumentMasterBody->id,
                            'type_celda' => $request->all()['optionTarget'][$i]['arrayTable'][$t]['typeCelda'],
                            'type_lista' => json_encode($request->all()['optionTarget'][$i]['arrayTable'][$t]['lista']),
                            'index_table' => $request->all()['optionTarget'][$i]['arrayTable'][$t]['index'],
                        ]);
                    };
                };
            };
        }
        return response()->json([
            'res' => 'success_update',
            'DocumentMasterHead' => $DocumentMasterHead,
        ], 201);
    }
    /**
     *
     *
     *
     ********************************************
     * PROCESOS
     ********************************************
     *
     *
     *
     * */
    //Traer en tabla los procesos
    public function indexProcess()
    //Los recursos se estan trayendo con relaciones para no malgastar recursos
    {
        return ProcesosResource::collection(ProcesoModel::latest()->paginate(13));
    }
    //Traer solo un procesos
    public function indexProcessView($uuid)
    //Los recursos se estan trayendo con relaciones para no malgastar recursos
    {
        $proceso = ProcesoModel::where('uuid', '=', $uuid)->first();
        return response()->json([
            'res' => 'process_view',
            'Proceso' => $proceso,
        ], 201);
    }
    //Guardar proceso
    public function storeProceso(Request $request)
    {
        //Validacion de que venga el proceso
        if ($request->all()['proceso'] === null) {
            return response()->json([
                'res' => 'not_found_process',
            ], 200);
        };
        //Validacion del largor del proceso
        if (strlen($request->all()['proceso']) < 2) {
            return response()->json([
                'res' => 'not_large',
            ], 200);
        };
        if (ProcesoModel::where('process', $request->all()['proceso'])->exists()) {
            return response()->json([
                'res' => 'exists_process',
                'response' => 'El proceso ya existe',
            ], 201);
        }
        $uuid = Uuid::uuid1();
        $uuid2 = Uuid::uuid4();
        $proceso = ProcesoModel::create([
            'uuid' => $uuid . $uuid2,
            'user_id' => \Auth::user()->id,
            'process' => $request->all()['proceso'],
        ]);
        return response()->json([
            'res' => 'new_process',
            'Proceso' => $proceso,
        ], 201);
    }
    public function updateProceso(Request $request, $uuid)
    {
        $proceso = ProcesoModel::where('uuid', '=', $uuid)->first();
        if ($proceso->process === $request->all()['proceso']) {
        } else {
            if (ProcesoModel::where('process', $request->all()['proceso'])->exists()) {
                return response()->json([
                    'res' => 'exists_process',
                ], 201);
            }
        };
        $proceso->update([
            'process' => $request->all()['proceso'],
        ]);
        return response()->json([
            'res' => 'update_process',
            'Proceso' => $proceso,
        ], 201);
    }
    /**
     *
     *
     *
     ********************************************
     * SUB_PROCESOS
     ********************************************
     *
     *
     *
     * */
    //Traer los procesos paginados
    public function indexSubProcess()
    //Los recursos se estan trayendo con relaciones para no malgastar recursos
    {
        return SubProcesosResource::collection(SubProcesos::latest()->paginate(13));
    }
    public function indexSubProcessView($uuid)
    {
        $subproceso = SubProcesos::where('uuid', '=', $uuid)->first();
        $proceso = ProcesoModel::where('id', '=', $subproceso->id_process)->first();
        return response()->json([
            'res' => 'sub_process_view',
            'Sub_Proceso' => $subproceso,
            'proceso' => $proceso,
        ], 201);
    }
    //Buscar proceso
    public function searchSubproceso($consulta)
    {
        $proceso  = ProcesoModel::where('process', 'LIKE', "%{$consulta}%")
            ->orderBy('created_at', 'DESC')
            ->get();
        return response()->json([
            'res' => true,
            'proceso' => $proceso
        ], 200);
    }
    //Guardar subProceso
    public function storeSubProceso(Request $request)
    {
        //Validacion de que venga el subproceso
        if ($request->all()['subProcesos'] === null) {
            return response()->json([
                'res' => 'not_found_sub_process',
            ], 200);
        };
        //Validacion del largor del subproceso
        if (strlen($request->all()['subProcesos']) < 2) {
            return response()->json([
                'res' => 'not_large',
            ], 200);
        };
        $uuid = Uuid::uuid1();
        $uuid2 = Uuid::uuid4();
        $sub_proceso = SubProcesos::create([
            'uuid' => $uuid . $uuid2,
            'user_id' => \Auth::user()->id,
            'id_process' => $request->all()['procesId'],
            'subProceso' => $request->all()['subProcesos'],
        ]);

        return response()->json([
            'res' => 'new_sub_process',
            'sub_proceso' => $sub_proceso,
        ], 201);
    }
    public function updateSubProceso(Request $request, $uuid)
    {
        $subProceso = SubProcesos::where('uuid', '=', $uuid)->first();
        $subProceso->update([
            'id_process' => $request->all()['procesId'],
            'subProceso' => $request->all()['subProcesos'],
        ]);
        return response()->json([
            'res' => 'update_sub_process',
            'Proceso' => $subProceso,
        ], 201);
    }
}
