<?php

namespace App\Http\Controllers;

use App\Exceptions\MonException;
use Hamcrest\BaseDescription;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Metier\Frais;
use App\DAO\ServiceVisiteur;
use App\DAO\ServiceFrais;

class FraisController extends Controller
{
    public function getFraisVisiteur(){
        try{
            $monErreur = Session::get('monErreur');
            $unServiceFrais = new ServiceFrais();
            $id_visiteur =  Session::get('id');
            $mesFrais = $unServiceFrais->getFrais($id_visiteur);
            return view('vues\listeFrais',compact('mesFrais','monErreur'));
        } catch (MonException $e) {
            $monErreur = $e->getMessage();
            return view('vues\error', compact('monErreur'));
        }
    }

    public function validateFrais(){
        try {
            $id_frais = Request::input('id_frais');
            $anneemois =Request::input('anneemois');
            $nbjustificatifs =Request::input('nbjustificatifs');
            $unServiceFrais = new ServiceFrais();
            if($id_frais>0){
                $unServiceFrais->updateFrais($id_frais,$anneemois,$nbjustificatifs);
            }else{
                $montant = Request::input('montant');
                $id_visiteur = Session::get('id');
                $unServiceFrais->insertFrais($anneemois,$nbjustificatifs,$id_visiteur,$montant);
            }
            return redirect('/getListeFrais');
        } catch (MonException $e) {
            $monErreur = $e->getMessage();
            return view('vues\error', compact('monErreur'));
        } catch (Exception $e) {
            $Erreur = $e->getMessage();
            return view('vues\error', compact('Erreur'));
        }
    }


    public function updateFrais($id_frais){
        try{
            $monErreur = "";
            $unsServiceFrais =new ServiceFrais();
            $unFrais = $unsServiceFrais->getById($id_frais);
            return view('vues/formFrais', compact('unFrais','monErreur'));
        }catch (QueryException $e){
            throw new MonException($e->getMessage(),5);
        }
    }

    public function supprimeFrais($id_frais){
        $unServiceFrais = new ServiceFrais();
        try {
            $unServiceFrais->deleteFrais($id_frais);
        }catch (MonException $e){
            $monErreur = $e->getMessage();
            return view('vues/error',compact('monErreur'));
        }catch (Exception $e){
            Session::put('monErreur',$e->getMessage());
        }finally{
            return redirect('/getListeFrais');
        }
    }

    public function addFrais(){
        try{
            $monErreur ="";
            $titreVue="Ajout d'une fiche de frais";
            $unFrais = new Frais();
            return view('vues/formFrais',compact('unFrais','titreVue','monErreur'));
        }catch (MonException $e){
            $monErreur = $e->getMessage();
            return view('vues\error', compact('monErreur'));
        }catch (Exception $e){
            $Erreur = $e->getMessage();
            return view('vues\error', compact('Erreur'));
        }
    }
}
