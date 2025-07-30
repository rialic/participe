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

        Permission::firstOrCreate(['name' => 'HOME'], ['description' => 'Permite acesso à página inicial padrão']);
        Permission::firstOrCreate(['name' => 'DASHBOARD'], ['description' => 'Permite acesso à página inicial na qual é possível ver o dashboard']);

        Permission::firstOrCreate(['name' => 'MENU.DASHBOARD'], ['description' => 'Permite visualizar o menu lateral Dashboard']);
        Permission::firstOrCreate(['name' => 'MENU.EVENT'], ['description' => 'Permite visualizar o menu lateral de Tele-educação']);
        Permission::firstOrCreate(['name' => 'MENU.EVENT-WEBCLASS'], ['description' => 'Permite visualizar o menu lateral Webaulas']);
        Permission::firstOrCreate(['name' => 'MENU.EVENT-COURSE'], ['description' => 'Permite visualizar o menu lateral Cursos']);
        Permission::firstOrCreate(['name' => 'MENU.SMART'], ['description' => 'Permite visualizar o menu lateral Smart']);
        Permission::firstOrCreate(['name' => 'MENU.SETTINGS'], ['description' => 'Permite visualizar o menu lateral Configurações']);
        Permission::firstOrCreate(['name' => 'MENU.SETTINGS-USER'], ['description' => 'Permite visualizar o menu lateral Usuários']);
        Permission::firstOrCreate(['name' => 'MENU.SETTINGS-ROLES'], ['description' => 'Permite visualizar o menu lateral Papéis']);

        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de webaulas e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-CREATE'], ['description' => 'Permite o usuário acessar à página para criar webaulas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-EDIT'], ['description' => 'Permite o usuário acessar à página para editar webaulas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-REPORT'], ['description' => 'Permite o usuário acessar à página de relatórios de webaulas e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'EVENT.WEBCLASS-CERTIFICATE'], ['description' => 'Permite o usuário gerar certificados de webaulas']);

        Permission::firstOrCreate(['name' => 'SMART.ESTABLISHMENT'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de estabelecimentos ao smart']);
        Permission::firstOrCreate(['name' => 'SMART.PROFESSIONALS'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de usuários ao smart']);
        Permission::firstOrCreate(['name' => 'SMART.WEBCLASS'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de webaula ao smart']);
        Permission::firstOrCreate(['name' => 'SMART.WEBCONSULTING'], ['description' => 'Permite o usuário ter acesso as funções gerais para enviar dados de teleconsultoria ao smart']);

        Permission::firstOrCreate(['name' => 'SETTINGS.USER-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de usuários e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'SETTINGS.USER-CREATE'], ['description' => 'Permite o usuário acessar à página para criar usuários']);
        Permission::firstOrCreate(['name' => 'SETTINGS.USER-EDIT'], ['description' => 'Permite o usuário acessar à página para editar usuários']);
        Permission::firstOrCreate(['name' => 'SETTINGS.USER-PROFILE'], ['description' => 'Permite o usuário acessar o seu perfil']);

        Permission::firstOrCreate(['name' => 'SETTINGS.ROLES-VIEW'], ['description' => 'Permite o usuário acessar à página de listagem de papéis e realizar pesquisas']);
        Permission::firstOrCreate(['name' => 'SETTINGS.ROLES-CREATE'], ['description' => 'Permite o usuário acessar à página para criar papéis']);
        Permission::firstOrCreate(['name' => 'SETTINGS.ROLES-EDIT'], ['description' => 'Permite o usuário acessar à página para editar papéis']);

        Permission::firstOrCreate(['name' => 'API.MODULES'], ['description' => 'Permite o usuário fazer request na api de módulos do sistema']);
        Permission::firstOrCreate(['name' => 'API.DASHBOARD'], ['description' => 'Permite o usuário fazer request na api de dashboard do sistema']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-SAVE'], ['description' => 'Permite o usuário fazer request na api para inserir novas webaulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar webaulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir webaulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-REPORT'], ['description' => 'Permite o usuário fazer request na api para imprimir webaulas']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-REPORT-PDF'], ['description' => 'Permite o usuário imprimir relatórios de webaulas em PDF']);
        Permission::firstOrCreate(['name' => 'API.EVENT.WEBCLASS-REPORT-EXCEL'], ['description' => 'Permite o usuário imprimir relatórios de webaulas em Excel']);
        Permission::firstOrCreate(['name' => 'API.EVENT.COURSE-SAVE'], ['description' => 'Permite o usuário fazer request na api para criar novos cursos']);
        Permission::firstOrCreate(['name' => 'API.EVENT.COURSE-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar cursos']);
        Permission::firstOrCreate(['name' => 'API.EVENT.COURSE-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir os cursos']);
        Permission::firstOrCreate(['name' => 'API.DESCS'], ['description' => 'Permite o usuário fazer request na api de módulos do sistema']);

        Permission::firstOrCreate(['name' => 'API.USER-SAVE'], ['description' => 'Permite o usuário fazer request na api para salvar novos usuários']);
        Permission::firstOrCreate(['name' => 'API.USER-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar usuários']);
        Permission::firstOrCreate(['name' => 'API.USER-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir usuários']);
        Permission::firstOrCreate(['name' => 'API.USER-ME'], ['description' => 'Permite o usuário fazer request na api para consultar seus próprios dados']);

        Permission::firstOrCreate(['name' => 'API.ROLES-SAVE'], ['description' => 'Permite o usuário fazer request na api para salvar novos papéis']);
        Permission::firstOrCreate(['name' => 'API.ROLES-UPDATE'], ['description' => 'Permite o usuário fazer request na api para alterar papéis']);
        Permission::firstOrCreate(['name' => 'API.ROLES-DELETE'], ['description' => 'Permite o usuário fazer request na api para excluir usuários']);

        Permission::firstOrCreate(['name' => 'API.MACRO-ZONE'], ['description' => 'Permite o usuário a realizar consulta as macro regiões']);
        Permission::firstOrCreate(['name' => 'API.MICRO-ZONE'], ['description' => 'Permite o usuário a realizar consulta as micro regiões']);

        Permission::firstOrCreate(['name' => 'API.SMART.ESTABLISHMENT-GET'], ['description' => 'Permite o usuário fazer request na api para obter estabelecimentos a serem enviados ao smart']);
        Permission::firstOrCreate(['name' => 'API.SMART.ESTABLISHMENT-SEND'], ['description' => 'Permite o usuário fazer request na api para enviar estabelecimentos ao smart']);
        Permission::firstOrCreate(['name' => 'API.SMART.PROFESSIONALS-GET'], ['description' => 'Permite o usuário fazer request na api para obter profissionais da saúde a serem enviados ao smart']);
        Permission::firstOrCreate(['name' => 'API.SMART.PROFESSIONALS-SEND'], ['description' => 'Permite o usuário fazer request na api para enviar profissionais da saúde ao smart']);
        Permission::firstOrCreate(['name' => 'API.SMART.WEBCLASS-GET'], ['description' => 'Permite o usuário fazer request na api para obter tele-educações a serem enviados ao smart']);
        Permission::firstOrCreate(['name' => 'API.SMART.WEBCLASS-SEND'], ['description' => 'Permite o usuário fazer request na api para enviar tele-educações ao smart']);
        Permission::firstOrCreate(['name' => 'API.SMART.WEBCONSULTING'], ['description' => 'Permite o usuário fazer request na api para manipular dados do smart em teleconsultoria']);
    }
}
