<?php

namespace App\Repositories\Api\V1;

class BaseRepository
{
    /**
     * Set response
     *
     * @param  bool  $status
     * @param  string  $message
     * @param  array|object  $data
     * @param  string  $note
     */
    protected function setResponse($status = false, $message = 'Failed', $data = [], $note = ''): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'note' => $note,
        ];
    }

    /**
     * Set response Success
     *
     * @param  string  $message
     * @param  array|object  $data
     * @param  string  $note
     */
    protected function setResponseSuccess($message = 'Success', $data = [], $note = ''): array
    {
        return $this->setResponse(true, $message, $data, $note);
    }

    /**
     * Set response Error
     *
     * @param  string  $message
     * @param  array|object  $data
     * @param  string  $note
     */
    protected function setResponseError($message = 'Failed', $errors = 'Error', $note = ''): array
    {
        return $this->setResponse(false, $message, ['errors' => $errors], $note);
    }
}
