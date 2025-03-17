<?php

namespace App\ServiceLayer;

use App\Models\User;
use App\Repository\CboRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\ParticipantRepository;
use App\ServiceLayer\Base\ServiceResource;
use Illuminate\Support\Facades\Hash;

class ParticipantServiceLayer extends ServiceResource {
    public function __construct(
        private readonly ParticipantRepository $participantRepository,
        private CboRepository $cboRepository,
        private EstablishmentRepository $establishmentRepository,
        private User $user,
    )
    {
        $this->repository = $participantRepository;
    }

    public function show(string|array $data): ?object
    {
        $dataCNESProxy = app('App\Proxy\DataCNES\DataCNESProxy');
        $this->user = $this->repository->getFirstDataOrNew($data);

        if (!$this->user->exists) {
            $cpf = str_replace(['.', '-'], [''], $data['cpf']);
            $userDataCNES = $dataCNESProxy->fetch('user', $cpf);

            if(is_array($userDataCNES)) {
                $this->storeParticipant($userDataCNES, $data['cpf']);
            }

            return $this->user->load(['establishments' => fn($establishment) => [
                $establishment->with('city', fn($city) => [
                    $city->with('state')
                ])
                ->where('primary_bond', true)
            ]]);
        }

        return $this->user;
    }

    public function store($data): object
    {
        $establishment = $this->establishmentRepository->getUuidToId($data['establishment']);
        $cbo = $this->cboRepository->getUuidToId($data['cbo']);

        $this->user->fill(['name' => $data['name'], 'cpf' => $data['cpf'], 'email' => $data['email'], 'sex' => $data['sex'], 'password' => Hash::make('password')]);
        $this->user->save();
        $this->user->establishments()->syncWithPivotValues([$establishment->id], ['cbo_id' => $cbo->id, 'primary_bond' => true]);

        return $this->user;
    }

    public function update(string $uuid, array $data): object
    {
        $user = parent::update($uuid, $data);
        $establishment = $this->establishmentRepository->getUuidToId($data['establishment']);
        $cbo = $this->cboRepository->getUuidToId($data['cbo']);

        $user->establishments()->syncWithPivotValues([$establishment->id], ['cbo_id' => $cbo->id, 'primary_bond' => true]);

        return $user;
    }

    private function storeParticipant($user, $cpf)
    {
        $userBonds = $user['vinculos'] ?? $user[0]['vinculos'] ?? null;

        $this->user->fill(['name' => $user['nome'] ?? $user[0]['nome'], 'cpf' => $cpf, 'email' => uniqid() . '@email.com', 'sexo' => 'Outro', 'password' => Hash::make('password')]);
        $this->user->save();

        if (is_array($userBonds)) {
            $cbo = $this->cboRepository->getFirstDataOrNew(['code' => $userBonds[0]['cbo']]);
            $establishment = $this->establishmentRepository->getFirstDataOrNew(['cnes' => $userBonds[0]['cnes']]);

            $this->user->establishments()->syncWithPivotValues([$establishment->id], ['cbo_id' => $cbo->id, 'primary_bond' => true]);
        }
    }
}