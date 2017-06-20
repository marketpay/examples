<?php

// CREAR USUARIO NUEVO
try
{
    $usersApi = new Swagger\Client\Api\UsersApi($apiClient);
    $user = new Swagger\Client\Model\UserNaturalPost;

    $response = $usersApi->usersPostNatural($user);

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}