<?php


namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Usuario;
use App\Models\SistemaEmpresaLicencia;
use App\Utils\Encrypt;
use App\Utils\GenerateRandom;
use App\Core\{Email , Path};

use App\Core\JWTManager as JWT;
use App\Validations\UserValidation as Request;
use App\Validations\VerifyCodeValidation as VC_Request;

use Illuminate\Database\Capsule\Manager as Capsule;



class UsuarioController extends BaseController
{
    use Encrypt;
    use GenerateRandom;


    public function create()
    {
        $request = new Request();

        $message = $request->get('message');

        return $this->render('register', [
            'enlace_web' => $this->get_env('ENLACE_WEB'),
            'message' => $message
        ]);
    }

    public function verification()
    {
        try {

            $request = new Request();
            $jwtManage = new JWT();

            $jwt = $request->get('id');
            $jwtManage->decode($jwt);

            return $this->render('step_verification', [
                'jwt' => $jwt,
                'code' => $request->get('code')
            ]);
        } catch (\Exception $e) {

            $message = $e->getMessage();
            header("Location: /?message={$message}");
            exit;
        }
    }

    public function term_and_conditions()
    {
        return $this->render('terms_condition');
    }

    public function store()
    {

        try {

            $response = [];

            $request = new Request();
            $jwt = new JWT();

            $this->validate_csrf_token($request->get('csrf'));

            if ($this->validate_new_email($request->get('correo')))
                throw new \Exception("[El correo ya existe]", 422);

            $request->run_validations();

            $code_email = $this->random_code_email();
            $link = $jwt->encode([
                'nombre' => $request->get('nombre'),
                'apellido' => $request->get('apellido'),
                'clave' => $request->get('clave'),
                'correo' => $request->get('correo'),
                'code' => $this->sha256_encrypt($code_email)
            ]);



            $response['url'] = "/verificacion?id={$link}";
            $url_aux = "/verificacion?id={$link}&code={$code_email}";

            $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            $protocol = $is_https ? 'https://' : 'http://';

            $this->send_email(
                $request->get('correo'),
                [
                    'code_number' => strval($code_email),
                    'url' => $protocol . $_SERVER['HTTP_HOST'] . $url_aux,
                ]
            );
        } catch (\Exception $e) {

            $r = $this->handle_errors($e);
            extract($r);
        }



        return $this->response($response, $code ?? 200);
    }

    public function verification_code()
    {
        try {

            set_time_limit(3000);

            $response = [];

            $request = new VC_Request();
            $jwtManage = new JWT();

            $this->validate_csrf_token($request->get('csrf'));
            $request->run_validations();

            $jwt = $request->get('token');
            $res = $jwtManage->decode($jwt);

            $code = $request->get('code_1') . $request->get('code_2') . $request->get('code_3') . $request->get('code_4');


            if ($res->code == $this->sha256_encrypt($code)) {
                throw new \Exception("[El codigo es incorrecto]", 422);
            }

            $name_db = $this->restore_db();

            if(!$name_db)  throw new \Exception("[Error al cargar base de datos]", 500);


            $usuario = Usuario::updateOrCreate([
                'nombre' => $res->data->nombre,
                'apellidos' => $res->data->apellido,
                'acceso_usuario' => $res->data->correo,
                'acceso_clave' => $res->data->clave,
                'acceso_correo_estado' => 'validado',
                'acceso_correo_actualizado' => null,
                'acceso_correo_codigo' => $code,
                'correo_temporal' => $res->data->correo,
                'fk_estado' => 1,
                'fk_idioma' => 1 

            ]);


            if($usuario){
                $new_token = $jwtManage->encode([
                    'usuario_id' => $usuario->rowid,
                    'name_db' => $name_db

                ]);

                $url = "/configuracion?id={$new_token}";

                $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
                $protocol = $is_https ? 'https://' : 'http://';

                $url = $protocol . $_SERVER['HTTP_HOST'] . $url;


                $response = [
                    'message' => 'Se creo el usuario',
                    'token' => $new_token,
                    'url' => $url
                ];

                $code = 200;
            }
        } catch (\Exception $e) {

            $r = $this->handle_errors($e);
            extract($r);
        }

        return $this->response($response, $code ?? 200);
    }

    public function get_company_config()
    {
        return $this->render('conf_company');
    }

    protected function validate_new_email($new_email)
    {

        $control = false;

        $query = Usuario::query();

        $query->where('acceso_usuario', $new_email);
        $user = $query->get();


        if (!$user) $control = true;

        return $control;
    }

    protected function send_email($email_value, $params = [])
    {

        $email = new Email();
        $email->addAddress($email_value);
        $control = $email->send(
            "Codigo de Verificacion",
            $this->render('emails/send_code', $params)
        );
        return $control;
    }

    protected function restore_db() {

        $name = 'facturas_' . $this->generateRandomCode();

   
        $sistema_empresa_licencia = SistemaEmpresaLicencia::where('bd', $name)->first();

      

        if($sistema_empresa_licencia) $this->restore_db();


        try {
         
            //Capsule::connection('licencias')->beginTransaction();
         
            $sistema_empresa_licencia =  SistemaEmpresaLicencia::create([
                'bd' => $name , 
                'server' => $this->get_env('DB_HOST_PLATAFORMA'),
                'user' => $this->get_env('DB_USER_PLATAFORMA'),
                'pass' => $this->get_env('DB_PASS_PLATAFORMA'),
            ]);

  
            $sistema_empresa_licencia->create_database( Path::$path_base_sql );



            //Capsule::connection('licencias')->commit();


            return $name ;

        } catch (\Exception $e) {
        
            //Capsule::connection('licencias')->rollBack();
            echo "Error: " . $e->getMessage() . "\n";

            return false;
        }
  
    }
}
