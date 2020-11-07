<?php

namespace App\Traits;

use Flugg\Responder\Http\MakesResponses;
use Flugg\Responder\Http\Responses\SuccessResponseBuilder;
use Flugg\Responder\Serializers\SuccessSerializer;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Http\JsonResponse;

trait HasTransformer
{
    use MakesResponses;

    /**
     * @var mixed
     */
    protected $serializer = SuccessSerializer::class;

    /**
     * Build a HTTP_OK response.
     *
     * @param mixed $data
     * @param callable|string|Transformer|null $transformer
     * @param string|null $resourceKey
     * @param bool $isCMS
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function httpOK($data = null, $transformer = null, string $resourceKey = null, bool $isCMS = true)
    {
        $respond = $this->success($data, $transformer, $resourceKey)
                        ->serializer($this->getSerializer())
                        ->respond(JsonResponse::HTTP_OK);
        if (! $isCMS) {
            $respondData = [
                'code' => JsonResponse::HTTP_OK,
                'data' => $respond->getData()->data,
            ];
            if (isset($respond->getData()->pagination)) {
                $respondData = $respondData + ['pagination' => $respond->getData()->pagination];
            }
            $respond->setData($respondData);
        }

        return $respond;
    }

    protected function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param mixed $serializer
     * @return $this
     */
    protected function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Build a HTTP_CREATED response.
     *
     * @param mixed $data
     * @param callable|string|Transformer|null $transformer
     * @param string|null $resourceKey
     * @param bool $returnCode
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function httpCreated($data = null, $transformer = null, string $resourceKey = null, $returnCode = false)
    {
        $respond = $this->success($data, $transformer, $resourceKey)
                        ->serializer($this->getSerializer())
                        ->respond(JsonResponse::HTTP_CREATED);

        if ($returnCode) {
            $respondData = [
                'code' => JsonResponse::HTTP_CREATED,
                'data' => $respond->getData()->data,
            ];
            $respond->setData($respondData);
        }

        return $respond;
    }

    /**
     * Build a HTTP_NO_CONTENT response.
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function httpNoContent()
    {
        return $this->success()
                    ->serializer($this->getSerializer())
                    ->respond(JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Build a HTTP_BAD_REQUEST response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function httpBadRequest(array $errors = [])
    {
        return $this->error()
                    ->data($errors)
                    ->respond(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * Build a HTTP_NOT_FOUND response
     *
     * @param array $errors
     * @param null $errorCode
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function httpNotFound(array $errors = [], $errorCode = null, string $message = null)
    {
        return $this->error($errorCode, $message)
                    ->data($errors)
                    ->respond(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Build a custom response
     *
     * @param $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function httpResponse($data, $statusCode = JsonResponse::HTTP_OK)
    {
        return response()->json($data)->setStatusCode($statusCode);
    }
}
