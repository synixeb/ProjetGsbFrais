<?php
namespace App\DAO;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MonException;
use Illuminate\Support\Facades\Session;


class ServiceFrais
{
    public function getFrais($id_visiteur){
        try{
            $lesfrais = DB::table('frais')
                ->Select()
                ->where('frais.id_visiteur','=',$id_visiteur)
                ->get();
            return $lesfrais;
        }catch (QueryException $e){
            throw new MonException($e->getMessage(),5);
        }
    }

    public function updateFrais($id_frais,$anneemois,$nbjustification){
        try{
            $datejour = date("Y-m-d");
            DB::table('frais')
                ->where('id_frais',$id_frais)
                ->update(['anneemois'=>$anneemois,'nbjustificatifs'=>$nbjustification,
                    'datemodification'=>$datejour]);
        }catch (QueryException $e){
            throw new MonException($e->getMessage(),5);
        }finally{
            return redirect('/getListeFrais');
        }
    }

    public function getById($id_frais){
        try{
            $lesfrais = DB::table('frais')
                ->Select()
                ->where('frais.id_frais','=',$id_frais)
                ->first();
            return $lesfrais;

        }catch (MonException $e){
            throw new MonException($e->getMessage(),5);
        }
    }

    public function deleteFrais($id_frais){
        try {
            DB::table('fraishorsforfait')->where('id_frais','=',$id_frais)->delete();
            DB::table('frais')->where('id_frais','=',$id_frais)->delete();
        }catch (QueryException $e){
            throw new MonException($e->getMessage(),5);
        }finally{
            return redirect('/getListeFrais');
        }
    }


    public function insertFrais($anneemois,$nbjustificatifs,$id_visiteur){
        try {
            DB::table('frais')->insert(
                ['anneemois'=>$anneemois,
                 'nbjustificatifs'=>$nbjustificatifs,
                 'id_etat'=>2,
                 'id_visiteur'=>$id_visiteur,
                 'montantvalide'=>0
                    ]
            );
        }catch (QueryException $e){
            throw new MonException($e->getMessage(),5);
        }
    }
}

