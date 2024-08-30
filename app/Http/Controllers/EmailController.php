<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller {

            public function enviar(){
                        return '<h1>Informações para Contato</h1>
                                   <b>Nome:</b> Luciano Alexandre <br />
                                   <b>Telefone:</b> (xx) xxxxx-xxxx <br />
                                   <b>e-mail:</b> luciano.cnrn@gmail.com <br />';
            }
}