<?php

namespace App\Proxy\DataCNES;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;
use App\Proxy\DataCNES\DataCNESHeaders;

class DataCNESProxy
{
	/**
	 * Comando para gerar um novo certificado para ser utilizado nas requests para o datacnes
	 * openssl s_client -showcerts -connect cnes.datasus.gov.br:443 </dev/null 2>/dev/null|openssl x509 -outform PEM >datacnes.pem
	 */

	private $dataCNESHeaders;

	public function __construct(DataCNESHeaders $dataCNESHeaders)
	{
		$this->dataCNESHeaders = $dataCNESHeaders;
	}

	public function fetch(string $typeObject, string $data = null)
	{
		$fetchList = [
			'descs' => fn() => $this->fetchDescs(),
			'cities' => fn() => $this->fetchCities(),
			'establishments' => fn() => $this->fetchEstablishments(),
			'cbo' => fn() => $this->fetchCBO(),
			'user' => fn() => $this->fetchUser($data)
		];

		return call_user_func($fetchList[$typeObject]);
	}

	private function fetchDescs()
	{
		ini_set('memory_limit', '-1');

		$response = Http::withHeaders($this->dataCNESHeaders->getDescsHeader())->retry(3, 15000)->get(env('DTACNES_DESCS_URL'));

		if ($response->ok()) {
			$data = $response->json();
			$descsList = [];

			foreach ($data as $descBireme) {
				$descsList[$descBireme['codigo']] = ['name' => $descBireme['nome'], 'description' => $descBireme['descricao']];
			}

			return $descsList;
		}

		$response->throw()->json();
	}

	private function fetchCities()
	{
		try {
			$response = Http::withOptions(['verify' => base_path('datacnes.pem')])
				->withHeaders($this->dataCNESHeaders->getEstablishmentHeader())
				->get(env('DTACNES_STATE_URL'));
		} catch (\Exception $e) {
			$response = null;

			ApiException::handleException($e, func_get_args());
		}

		if ($response->ok()) {
			$state = app('App\Models\State');
			$stateList = $state::all();
			$cityList = [];
			$dataCNESStateList = $response->json();

			foreach ($dataCNESStateList as $key => $state) {
				try {
					$response = Http::withOptions(['verify' => base_path('datacnes.pem')])
						->withHeaders($this->dataCNESHeaders->getEstablishmentHeader())
						->get(env('DTACNES_CITY_URL') . $key);
				} catch (\Exception $e) {
					$response = null;

					ApiException::handleException($e, func_get_args());
				}

				if ($response->ok()) {
					$acronym = $stateList->first(function ($state) use ($dataCNESStateList, $key) {
						return $state->name === $dataCNESStateList[$key];
					})->acronym;

					$cityList[$acronym] = $response->json();

					continue;
				}

				$response->throw()->json();
			}

			return $cityList;
		}
	}

	private function fetchEstablishments()
	{
		$city = app('App\Models\City');
		$cityList = $city::all();
		$establishmentList = [];

		ini_set('memory_limit', '-1');

		foreach ($cityList as $key => $city) {
			try {
				$response = Http::withOptions(['verify' => base_path('datacnes.pem')])
					->withHeaders($this->dataCNESHeaders->getEstablishmentHeader())
					->retry(3, 15000)
					->get(env('DTACNES_ESTABLISHMENT_URL') . $city->datacnes_id);
			} catch (\Exception $e) {
				$response = null;

				ApiException::handleException($e, func_get_args());
			}

			if ($response->ok()) {
				$dataCNESEstablishmentList = $response->json();

				$establishmentList += collect($dataCNESEstablishmentList)->reduce(function ($acc, $establishment) use ($city) {
					$acc[$establishment['id']] = [
						'cnes' => $establishment['cnes'],
						'name' => $establishment['noFantasia'],
						'management' => $establishment['gestao'],
						'legal_nature' => $establishment['natJuridica'],
						'sus' => $establishment['atendeSus'],
						'city_id' => $city->id
					];

					return $acc;
				}, []);

				$establishmentList[$city->datacnes_id] = [
					'cnes' => '9999999',
					'name' => 'OUTROS',
					'management' => 'E',
					'legal_nature' => 1,
					'sus' => 'S',
					'city_id' => $city->id
				];

				continue;
			}

			$response->throw()->json();
		}

		return $establishmentList;
	}

	private function fetchCBO()
	{
		$response = Http::withHeaders($this->dataCNESHeaders->getCBOHeader())->get(env('DTACNES_CBO_URL'));

		if ($response->ok()) {
			$data = $response->json();
			$cboList = [];

			foreach ($data as $cbo) {
				$cboList[$cbo['codigo']] = ['name' => $cbo['nome']];
			}

			return $cboList;
		}

		$response->throw()->json();
	}

	private function fetchUser($data)
	{
		// 011.736.351-06
		try {
			$response = Http::withOptions(['verify' => base_path('datacnes.pem')])
				->withHeaders($this->dataCNESHeaders->getProfessionalsHeader())
				->get(env('DTACNES_USER_CPF_URL') . $data);
		} catch (\Exception $e) {
			$response = null;

			ApiException::handleException($e, func_get_args());
		}

		if ($response?->ok()) {
			$user = optional($response->json())[0];

			if ($user) {
				try {
					$response = Http::withOptions(['verify' => base_path('datacnes.pem')])
						->withHeaders($this->dataCNESHeaders->getProfessionalsHeader())
						->get(env('DTACNES_USER_CNS_URL') . $user['id']);
				} catch (\Exception $e) {
					$response = null;

					ApiException::handleException($e, func_get_args());
				}

				if ($response->ok()) {
					return $response->json();
				}

				return [$user];
			}
		}

		return null;
	}
}
