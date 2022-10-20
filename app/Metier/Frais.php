<?php
namespace App\Metier;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use DB;

class Frais extends Model {
    protected $table = 'frais';
    public $timestamps = false;
    private $id_frais;
    protected $fillable = [
        'id_frais',
        'id_etat',
        'annemois',
        'id_visiteur',
        'nbjustificatifs',
        'datemodification',
        'montantvalide',
    ];

    public function __construct()
    {
        $this->id_frais = 0;
    }
}
?>
