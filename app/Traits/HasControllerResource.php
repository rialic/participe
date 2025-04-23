<?php

namespace App\Traits;

use App\Exceptions\ApiException;
use DateTime;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait HasControllerResource
{
  /**
   * How to use it in controllers, just copy and paste the code bellow in your controller files
   * use HasControllerResource; (This file should be in Controller files)
   */

  protected int $limit = -1;
  protected ?DateTime $deletedAt = null;
  protected array $filterFields = [];
  protected array $params = ['limit', 'order_by', 'direction', 'page', 'deleted_at'];

  protected $showValidatorRequest = null;
  protected $indexValidatorRequest = null;
  protected $storeValidatorRequest = null;
  protected $updateValidatorRequest = null;
  protected $deleteValidatorRequest = null;

  /**
   *
   * @return JsonResource|JsonResponse
   * @throws BindingResolutionException
   */
  public function show(): JsonResource|JsonResponse
  {
    $request = $this->showValidatorRequest();

    try {
      $uuid = $request->route()->parameters[array_key_first($request->route()->parameters())] ?? null;
      $data = (get_class($request) === 'Illuminate\Http\Request') ? $uuid : $request->validated();
      $data = $data ?? $data['uuid'];
      $data = $this->service->show($data);

      return new $this->resourceCollection($data);
    } catch (\Exception $e) {
      return ApiException::handleException($e, func_get_args());
    }
  }

  /**
   *
   * @return JsonResource|JsonResponse
   * @throws BindingResolutionException
   */
  public function index(): JsonResource|JsonResponse
  {
    $request = $this->indexValidatorRequest();

    try {
      $data = (get_class($request) ==='Illuminate\Http\Request') ? $this->getParams() : array_merge($request->validated(), $this->getParams());
      $data = $this->service->index($data);

      return $this->resourceCollection::collection($data);
    } catch (\Exception $e) {
      return ApiException::handleException($e, func_get_args());
    }
  }

  /**
   *
   * @return JsonResource|JsonResponse
   * @throws BindingResolutionException
   */
  public function store(): JsonResource|JsonResponse
  {
    $validator = $this->storeValidatorRequest();
    try {
      $data = $validator->validated();
      $model = $this->service->store($data);

      return (new $this->resourceCollection($model))->response()->setStatusCode(201);
    } catch (\Exception $e) {
      return ApiException::handleException($e, func_get_args());
    }
  }

  /**
   *
   * @param string $uuid
   * @param mixed $data
   * @return JsonResource|JsonResponse
   * @throws BindingResolutionException
   */
  public function update(): JsonResource|JsonResponse
  {
    $validator = $this->updateValidatorRequest();

    try {
      $data = $validator->validated();
      $model = $this->service->update($data['uuid'], $data);

      return (new $this->resourceCollection($model))->response()->setStatusCode(200);
    } catch (\Exception $e) {
      return ApiException::handleException($e, func_get_args());
    }
  }

  /**
   *
   * @return JsonResource|JsonResponse
   * @throws BindingResolutionException
   */
  public function delete(): JsonResource|JsonResponse
  {
    $validator = $this->deleteValidatorRequest();

    try {
      $data['uuid'] = (get_class($validator) ==='Illuminate\Http\Request') ? request()->route()->parameters()['uuid'] : $validator->validated()['uuid'];
      $response = $this->service->delete($data['uuid']);

      return (new $this->resourceCollection([$response]))->response()->setStatusCode(200);
    } catch (\Exception $e) {
      return ApiException::handleException($e, func_get_args());
    }
  }

  /**
   *
   * @return array
   * @throws BindingResolutionException
   * @throws BadRequestException
   */
  private function getParams()
  {
    $params = [];
    $params['deleted_at'] = $this->deletedAt;

    if (!is_null($this->limit)) {
      $params['limit'] = (int) $this->limit;
    }

    foreach ($this->params as $arg) {
      if (request()->get($arg)) {
        $params[$arg] = ($arg === 'limit' || $arg === 'page') ? (int) request()->get($arg) : request()->get($arg);
      }
    }

    foreach (request()->only($this->filterFields) as $field => $value) {
      $params[$field] = $value;
    }

    return $params;
  }

  /**
   *
   * @param mixed $request
   * @param mixed $data
   * @return array
   * @throws BindingResolutionException
   * @throws ValidationException
   */
  protected function validator($request, $data): array
  {
    $validator = Validator($data, $request->rules(), $request->messages());

    return $validator->validated();
  }

  /**
   *
   * @return mixed
   * @throws BindingResolutionException
   */
  protected function showValidatorRequest(): mixed
  {
    return !is_null($this->showValidatorRequest ?? null) ? app($this->showValidatorRequest) : request();
  }

  /**
   *
   * @return mixed
   * @throws BindingResolutionException
   */
  protected function indexValidatorRequest(): mixed
  {
    return !is_null($this->indexValidatorRequest ?? null) ? app($this->indexValidatorRequest) : request();
  }

  /**
   *
   * @return mixed
   * @throws BindingResolutionException
   */
  protected function storeValidatorRequest(): mixed
  {
    return !is_null($this->storeValidatorRequest ?? null) ? app($this->storeValidatorRequest) : request();
  }

  /**
   *
   * @return mixed
   * @throws BindingResolutionException
   */
  protected function updateValidatorRequest(): mixed
  {
    return !is_null($this->updateValidatorRequest ?? null) ? app($this->updateValidatorRequest) : request();
  }

  /**
   *
   * @return mixed
   * @throws BindingResolutionException
   */
  protected function deleteValidatorRequest(): mixed
  {
    return !is_null($this->deleteValidatorRequest ?? null) ? app($this->deleteValidatorRequest) : request();
  }
}
