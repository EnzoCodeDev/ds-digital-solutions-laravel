<?php

namespace App\Http\Controllers\Datos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DocumentHeadDeligenciado;
use App\Models\DataMasterParametrizationHead;
use App\Models\DataMasterParametrizationBody;
use App\Models\ProcesoModel;
use App\Models\SubProcesos;
use App\Models\DocumentMaster;
use App\Models\DocumenMasterTable;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
//recursos para traer recursos de los datos de la peticiones personalizados
use App\Http\Resources\DataMasterTablaDeliResource;

class DatosController extends Controller
{
    /**
     *
     *
     *
     ********************************************
     * MAESTRO DE INFORMACION
     ********************************************
     *
     *
     *
     * */
    //Traer los documentos para la paginacion
    public function index()
    {
        return DataMasterTablaDeliResource::collection(DocumentHeadDeligenciado::latest()->paginate(13));
    }
    //Traer solo un documento
    public function view($DocumentMaster)
    {
        //Cabeza del documeneto deligenciado
        $DocumentMasterHead = DocumentHeadDeligenciado::where('uuid', '=', $DocumentMaster)->first();
        //Targetas de los documentos deligenciados en su version actual
        $DocumentMasterBody = DataMasterParametrizationBody::where('id_header', '=', $DocumentMasterHead->id_header_original)->where('version', '=', $DocumentMasterHead->version)->get();
        //Informacion deligenciada relacionada con la cabea del documento
        $DocumentMasterInfo = $DocumentMasterHead->documentMasterCardInfoRelaciones;
        //Traer las imagenes o archivos guardados en el servidor
        for ($i = 0; $i < count($DocumentMasterBody); $i++) {
            if ($DocumentMasterInfo[$i]->type_card === 'Imagen') {
                $DocumentMasterInfo[$i]->img = $DocumentMasterInfo[$i]->format_img . ',' . base64_encode(Storage::disk("image")->get($DocumentMasterInfo[$i]->img));
            };
        };
        //Informacion deligenciada de las tablas relacionada con la cabeza del documento
        $DocumentMasterInfoTable = $DocumentMasterHead->documentMasterCardInfoTableRelaciones;
        //Traer las imagenes o archivos guardados en el servidor de las tablas 
        for ($i = 0; $i < count($DocumentMasterInfoTable); $i++) {
            if ($DocumentMasterInfoTable[$i]->type_celda === 'Imagen' || $DocumentMasterInfoTable[$i]->type_celda === 'Imagen titulo') {
                $DocumentMasterInfoTable[$i]->img = $DocumentMasterInfoTable[$i]->img_extension . ',' .  base64_encode(Storage::disk("image")->get($DocumentMasterInfoTable[$i]->img));
            };
        };
        return response()->json([
            'res' => 'success_view',
            'proceso' => $DocumentMasterHead->procesosRelacionados,
            'Sub_proceso' => $DocumentMasterHead->subProcesosRelacionados,
            'DocumentMasterHead' => $DocumentMasterHead,
            'DocumentMasterBody' => $DocumentMasterBody,
            'DocumentMasterInfo' => $DocumentMasterInfo,
            'DocumentMasterInfoTable' => $DocumentMasterInfoTable,
        ], 200);
    }
    //Buscar un documento para deligenciar
    public function search($consulta)
    {
        $document  = DataMasterParametrizationHead::where('format', 'LIKE', "%{$consulta}%")
            ->orderBy('created_at', 'DESC')
            ->get();
        return response()->json([
            'res' => true,
            'documentMaster' => $document
        ], 200);
    }
    //Guardar la informacion el documento ya deligenciado
    public function store(Request $request)
    {
        //Guardar documento deligenciado en su version actual
        $uuid = Uuid::uuid1();
        $uuid2 = Uuid::uuid4();
        $NewDocumentMasterDeligenciadoHeader = DocumentHeadDeligenciado::create([
            'user_id' => \Auth::user()->id,
            'uuid' => $uuid . $uuid2,
            'id_header_original' => $request->all()['documentMasterHead']['id'],
            'version' => $request->all()['documentMasterHead']['version'],
            'code' => $request->all()['documentMasterHead']['code'],
            'format' => $request->all()['documentMasterHead']['format'],
            'template' => $request->all()['documentMasterHead']['template'],
            'description' => $request->all()['documentMasterHead']['description'],
            'logo_header' => $request->all()['documentMasterHead']['logo_header'],
            'id_process' => $request->all()['proceso'],
            'id_sub_proccess' => $request->all()['subProceso'],
            'name_user_deligenciar' => $request->all()['name'],
            'identify_user_deligenciar' => $request->all()['identity'],
            'position' => $request->all()['documentMasterHead']['position'],
            'position_data_basic' =>  json_encode($request->all()['dataBasicCount']),
            'data_basic' =>  json_encode($request->all()['dataBasic']),
        ]);
        //Guardar los datos de la tarjeta
        for ($i = 1; $i < count($request->all()['option']); $i++) {
            if ($request->all()['option'][$i][0]['card'] !== 'inhabilidado') {
                //Guardar una imagen
                if ($request->all()['option'][$i][0]['optionValue'] === 'Imagen') {
                    $uuidImg = Uuid::uuid1();
                    $img = $request->all()['option'][$i][0]['img'];
                    $archivo = base64_decode(explode(",", $img)[1]);
                    $archivo_format_img = explode(",", $img)[0];
                    $nomImg = $request->all()['option'][$i][0]['img_extesion'] . '/' . $uuidImg . $request->all()['option'][$i][0]['img_extesion'];
                    Storage::disk('image')->put($nomImg, $archivo);
                };
                //Guardar un archivo
                if ($request->all()['option'][$i][0]['optionValue'] === 'Archivo') {
                    $uuidArchivo = Uuid::uuid1();
                    $archivos = $request->all()['option'][$i][0]['archivo'];
                    $archivo = base64_decode(explode(",", $archivos)[1]);
                    $archivoFormat = explode(",", $archivos)[0];
                    $nomarchivo = $request->all()['option'][$i][0]['archivo_extesion'] . '/' . $uuidArchivo . $request->all()['option'][$i][0]['archivo_extesion'];
                    Storage::disk('file')->put($nomarchivo, $archivo);
                };
                $NewDocumentMasterDeligenciado = DocumentMaster::create([
                    'id_card' => $request->all()['option'][$i][0]['card_id'],
                    'id_header' => $NewDocumentMasterDeligenciadoHeader->id,
                    'num_version' => $request->all()['documentMasterHead']['version'],
                    'title_card' => $request->all()['option'][$i][0]['titleCard'],
                    'type_card' => $request->all()['option'][$i][0]['optionValue'],
                    'text_description' => $request->all()['option'][$i][0]['text'],
                    'text_description_item' => $request->all()['option'][$i][0]['optionValue'] === 'Texto' ? $request->all()['option'][$i][0]['textDescription'] : null,
                    'link' => $request->all()['option'][$i][0]['optionValue'] === 'Link' ? $request->all()['option'][$i][0]['link'] : null,
                    'link_description' => $request->all()['option'][$i][0]['optionValue'] === 'Link' ? $request->all()['option'][$i][0]['linkDescription'] : null,
                    'file' => $request->all()['option'][$i][0]['optionValue'] === 'Archivo' ? $nomarchivo : null,
                    'format_archivo' => $request->all()['option'][$i][0]['optionValue'] === 'Archivo' ? $archivoFormat : null,
                    'img' => $request->all()['option'][$i][0]['optionValue'] === 'Imagen' ? $nomImg  : null,
                    'format_img' => $request->all()['option'][$i][0]['optionValue'] === 'Imagen' ? $archivo_format_img  : null,
                    'date' => $request->all()['option'][$i][0]['optionValue'] === 'Fecha' ? $request->all()['option'][$i][0]['date'] : null,
                ]);
                $NewDocumentMasterTableDeligenciado = null;
                //Guardar la informacion si viene una tabla
                if ($request->all()['option'][$i][0]['optionValue'] === 'Tabla') {
                    for ($t = 0; $t < count($request->all()['option'][$i][0]['tablasValue']); $t++) {
                        //Convertir imagen a base 64 para guardar en la base de datos
                        if ($request->all()['option'][$i][0]['tablasValue'][$t]['type'] === "Imagen titulo" || $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === "Imagen") {
                            $uuidImgTable = Uuid::uuid1();
                            $imgTable = $request->all()['option'][$i][0]['tablasValue'][$t]['img'];
                            $archivo_table = base64_decode(explode(",", $imgTable)[1]);
                            $archivo_format_img_table = explode(",", $imgTable)[0];
                            $nomImgtable = $request->all()['option'][$i][0]['tablasValue'][$t]['img_extesion'] . '/' . $uuidImgTable . $request->all()['option'][$i][0]['tablasValue'][$t]['img_extesion'];
                            Storage::disk('image')->put($nomImgtable, $archivo_table);
                        }
                        $NewDocumentMasterTableDeligenciado = DocumenMasterTable::create([
                            'id_header' => $NewDocumentMasterDeligenciadoHeader->id,
                            'id_card' => $request->all()['option'][$i][0]['card_id'],
                            'type_celda' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'],
                            'index_celda' => $request->all()['option'][$i][0]['tablasValue'][$t]['index'],
                            'title_celda' => isset($request->all()['option'][$i][0]['tablasValue'][$t]['titleCelda']) ? $request->all()['option'][$i][0]['tablasValue'][$t]['titleCelda'] : null,
                            'text_description' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === 'Título texto' ? $request->all()['option'][$i][0]['tablasValue'][$t]['textDescription'] : null,
                            'img' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === 'Imagen' || $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === 'Imagen titulo' ? $nomImgtable : null,
                            'img_extension' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === 'Imagen' || $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === 'Imagen titulo' ? $archivo_format_img_table : null,
                            'link' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === "link" ? $request->all()['option'][$i][0]['tablasValue'][$t]['link'] : null,
                            'link_description' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === "link" ? $request->all()['option'][$i][0]['tablasValue'][$t]['linkDescription'] : null,
                            'fecha' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === "fecha" ? $request->all()['option'][$i][0]['tablasValue'][$t]['fecha'] : null,
                            'lista' => $request->all()['option'][$i][0]['tablasValue'][$t]['type'] === "lista" ? json_encode($request->all()['option'][$i][0]['tablasValue'][$t]['lista']) : null
                        ]);
                    };
                };
            }
        }
        return response()->json([
            'res' => true,
            'documentMaster' => $NewDocumentMasterDeligenciadoHeader,
            'DocumentMasterInfo' => $NewDocumentMasterDeligenciado,
            'DocumentMasterInfoTable' => $NewDocumentMasterTableDeligenciado,
        ], 200);
    }
    public function downloadFile(Request $request)
    {
        $file = public_path() . "/storage/file/" . $request->all()['archive'];
        return Response::download($file);
    }
    public function indexProceso()
    {
        return response()->json([
            'res' => true,
            'procesos' => ProcesoModel::get(),
        ], 200);
    }
    public function indexSubProceso($id)
    {
        return response()->json([
            'res' => true,
            'sub_procesos' => SubProcesos::where('id_process', '=', $id)->get(),
        ], 200);
    }
    //Actualiza un documento
    public function update(Request $request, $DocumentMaster)
    {
        //
    }
    //Eliminar un documento
    public function destroy($DocumentAdmin)
    {
        //
    }
}
