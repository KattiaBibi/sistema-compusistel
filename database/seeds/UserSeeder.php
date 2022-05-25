<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // JM DESARROLLADOR
    User::create([
      'name' => 'JOHON',
      'email' => 'gerencia@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '1',
      'estado' => '1',
    ])->assignRole('Admin');

    User::create([
      'name' => 'MARCO',
      'email' => 'mgordillo@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '2',
      'estado' => '1',
    ])->assignRole('AdminGerente');

    User::create([
      'name' => 'JOHNY',
      'email' => 'jfacho@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '3',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ELIANA',
      'email' => 'echapilliquen@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '4',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'KAREN',
      'email' => 'knunez@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '5',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'HARUMI DE MARIA',
      'email' => 'hyampufe@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '6',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ESWIN',
      'email' => 'eperez@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '7',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'LAURA',
      'email' => 'Lyacila@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '8',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'XIOMY',
      'email' => 'xvillegas@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '9',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'LUIS',
      'email' => 'luchofen@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '10',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ABEL',
      'email' => 'asantistebam@jmdesarrollador.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '11',
      'estado' => '1',
    ])->assignRole('Trabajador');
    // JM DESARROLLADOR

    // NLEON
    User::create([
      'name' => 'EDWIN',
      'email' => 'erodriguez@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '12',
      'estado' => '1',
    ])->assignRole('AdminGerente');

    User::create([
      'name' => 'JUAN',
      'email' => 'jrodriguez@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '13',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'FRANK',
      'email' => 'frodriguez@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '14',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ANGEL',
      'email' => 'arodriguez@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '15',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'VICTOR',
      'email' => 'Emera@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '16',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'BRAYAN',
      'email' => 'bpisfil@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '17',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'DARWIN',
      'email' => 'dvillanueva@nleonsac.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '18',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'MARY',
      'email' => 'nleon@jmholding.org',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '19',
      'estado' => '1',
    ])->assignRole('Trabajador');
    // NLEON


    // GENEXIDU
    User::create([
      'name' => 'JORGE',
      'email' => 'jcoello@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '20',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'VANNESA',
      'email' => 'vburga@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '21',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'OLGA',
      'email' => 'lpaz@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '22',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'LUIS',
      'email' => 'llopez@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '23',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'HECTOR',
      'email' => 'hvasquez@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '24',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ALEX',
      'email' => 'aodar@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '25',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'JOSE',
      'email' => 'jpaico@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '26',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ANGIELY',
      'email' => 'aflores@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '27',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'JUNIOR',
      'email' => 'jpasache@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '28',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'MARCO',
      'email' => 'mcabanillas@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '29',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'GUSTAVO',
      'email' => 'gcaicedo@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '30',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'BRAYN',
      'email' => 'xlinares@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '31',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'MARCO',
      'email' => 'mseminario@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '32',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'CESAR',
      'email' => 'ctello@genexidu.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '33',
      'estado' => '1',
    ])->assignRole('Trabajador');
    // GENEXIDU

    // JM INMOBILIARIA
    User::create([
      'name' => 'LISBETH',
      'email' => 'lrivera@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '34',
      'estado' => '1',
    ])->assignRole('AdminGerente');

    User::create([
      'name' => 'KENNY',
      'email' => 'kaguirre@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '35',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'FIORELLA',
      'email' => 'fhuaman@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '36',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'RAMIRO',
      'email' => 'rticeran@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '37',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'PIERRE',
      'email' => 'pfigueroa@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '38',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'VANESSA',
      'email' => 'vchumacero@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '39',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ANTHONY',
      'email' => 'asandoval@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '40',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'KELLIE',
      'email' => 'test@test.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '41',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'NANLU',
      'email' => 'npalacios@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '42',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ISABEL DEL MILAGRO',
      'email' => 'ichavez@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '43',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'SANDRO',
      'email' => 'schavez@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '44',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'DANTE',
      'email' => 'dporras@jminmobiliarias.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '45',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'ALBERTO',
      'email' => 'aerc.rodriguez20@gmail.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '52',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'CRISTIAN',
      'email' => 'cristhian_vera_129@hotmail.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '53',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'JUNIOR',
      'email' => 'jujocahu019@gmail.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '54',
      'estado' => '1',
    ])->assignRole('Trabajador');
    // JM INMOBILIARIA


    // COMPUSISTEL
    User::create([
      'name' => 'OSCAR',
      'email' => 'osalazar@compusistel.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '46',
      'estado' => '1',
    ])->assignRole('AdminGerente');

    User::create([
      'name' => 'JANINA',
      'email' => 'jrivas@compusistel.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '47',
      'estado' => '1',
    ])->assignRole('AdminGerente');

    User::create([
      'name' => 'MARYOEI',
      'email' => 'mtejada@compusistel.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '48',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'DAVID',
      'email' => 'dmanayalle@compusistel.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '49',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'KATHIA',
      'email' => 'bibianacruzado@compusistel.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '50',
      'estado' => '1',
    ])->assignRole('Trabajador');

    User::create([
      'name' => 'JUAN',
      'email' => 'jdias@compusistel.com',
      'password' => bcrypt('12345678'),
      'colaborador_id' => '51',
      'estado' => '1',
    ])->assignRole('Trabajador');
    // COMPUSISTEL
  }
}
