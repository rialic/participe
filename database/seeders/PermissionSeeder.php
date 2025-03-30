<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'ADMIN']);

        Permission::firstOrCreate(['name' => 'HOME'], ['description' => 'Permite acesso à página inicial na qual é possível ver o dashboard']);

        Permission::firstOrCreate(['name' => 'MENU.DASHBOARD'], ['description' => 'Permite visualizar o menu lateral Dashboard']);
        Permission::firstOrCreate(['name' => 'MENU.EVENT'], ['description' => 'Permite visualizar o menu lateral de Tele-educação']);
        Permission::firstOrCreate(['name' => 'MENU.EVENT-WEBCLASS'], ['description' => 'Permite visualizar o menu lateral Web-aulas']);
        Permission::firstOrCreate(['name' => 'MENU.EVENT-COURSE'], ['description' => 'Permite visualizar o menu lateral Cursos']);
        Permission::firstOrCreate(['name' => 'MENU.SMART'], ['description' => 'Permite visualizar o menu lateral Smart']);
        Permission::firstOrCreate(['name' => 'MENU.SETTINGS'], ['description' => 'Permite visualizar o menu lateral Configurações']);
        Permission::firstOrCreate(['name' => 'MENU.SETTINGS-USER'], ['description' => 'Permite visualizar o menu lateral Usuários']);
        Permission::firstOrCreate(['name' => 'MENU.SETTINGS-ROLES'], ['description' => 'Permite visualizar o menu lateral Papéis']);

        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de web-aulas e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-CREATE'], ['description' => 'Permite o usuário acessar à página para criar web-aulas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-EDIT'], ['description' => 'Permite o usuário acessar à página para editar web-aulas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-CERTIFICATE'], ['description' => 'Permite o usuário gerar certificados de web-aulas']);

        Permission::firstOrCreate(['name' => 'EVENT.COURSE-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de cursos e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'EVENT.COURSE-CREATE'], ['description' => 'Permite o usuário acessar à página para criar cursos']);
        Permission::firstOrCreate(['name' => 'EVENT.COURSE-EDIT'], ['description' => 'Permite o usuário acessar à página para editar cursos']);
        Permission::firstOrCreate(['name' => 'EVENT.COURSE-CERTIFICATE'], ['description' => 'Permite o usuário gerar certificados de cursos']);

        Permission::firstOrCreate(['name' => 'SMART.ESTABLISHMENT'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de estabelecimentos ao smart']);
        Permission::firstOrCreate(['name' => 'SMART.USERS'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de usuários ao smart']);
        Permission::firstOrCreate(['name' => 'SMART.WEBLCASS'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de webaula ao smart']);
        Permission::firstOrCreate(['name' => 'SMART.WEBCONSULTING'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de teleconsultoria ao smart']);

        Permission::firstOrCreate(['name' => 'SETTINGS.USER-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de usuários e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'SETTINGS.USER-CREATE'], ['description' => 'Permite o usuário acessar à página para criar usuários']);
        Permission::firstOrCreate(['name' => 'SETTINGS.USER-EDIT'], ['description' => 'Permite o usuário acessar à página para editar usuários']);
        Permission::firstOrCreate(['name' => 'SETTINGS.USER-PROFILE'], ['description' => 'Permite o usuário acessar o seu perfil']);

        Permission::firstOrCreate(['name' => 'SETTINGS.ROLES-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de papéis e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'SETTINGS.ROLES-CREATE'], ['description' => 'Permite o usuário acessar à página para criar papéis']);
        Permission::firstOrCreate(['name' => 'SETTINGS.ROLES-EDIT'], ['description' => 'Permite o usuário acessar à página para editar papéis']);

        Permission::firstOrCreate(['name' => 'API.MODULES'], ['description' => 'Permite o usuário fazer request na api de módulos do sistema']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-SAVE'], ['description' => 'Permite o usuário fazer request na api para inserir novas web-aulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar web-aulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir web-aulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.COURSE-SAVE'], ['description' => 'Permite o usuário fazer request na api para criar novos cursos']);
        Permission::firstOrCreate(['name' => 'API.EVENT.COURSE-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar cursos']);
        Permission::firstOrCreate(['name' => 'API.EVENT.COURSE-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir os cursos']);

        Permission::firstOrCreate(['name' => 'API.USER-SAVE'], ['description' => 'Permite o usuário fazer request na api para salvar novos usuários']);
        Permission::firstOrCreate(['name' => 'API.USER-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar usuários']);
        Permission::firstOrCreate(['name' => 'API.USER-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir usuários']);
        Permission::firstOrCreate(['name' => 'API.USER-ME'], ['description' => 'Permite o usuário fazer request na api para consultar seus próprios dados']);

        Permission::firstOrCreate(['name' => 'API.ROLES-SAVE'], ['description' => 'Permite o usuário fazer request na api para salvar novos papéis']);
        Permission::firstOrCreate(['name' => 'API.ROLES-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar papéis']);
        Permission::firstOrCreate(['name' => 'API.ROLES-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir usuários']);
    }
}
