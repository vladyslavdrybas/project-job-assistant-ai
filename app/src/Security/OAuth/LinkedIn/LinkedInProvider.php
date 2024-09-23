<?php
declare(strict_types=1);

namespace App\Security\OAuth\LinkedIn;

use League\OAuth2\Client\Provider\Exception\LinkedInAccessDeniedException;
use League\OAuth2\Client\Provider\LinkedInResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

class LinkedInProvider extends \League\OAuth2\Client\Provider\LinkedIn
{
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://api.linkedin.com/v2/userinfo';
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return LinkedInResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token): LinkedInResourceOwner
    {
        // If current accessToken is not authorized with r_emailaddress scope,
        // getResourceOwnerEmail will throw LinkedInAccessDeniedException, it will be caught here,
        // and then the email will be set to null
        // When email is not available due to chosen scopes, other providers simply set it to null, let's do the same.
        try {
            $email = $this->getResourceOwnerEmail($token);
        } catch (LinkedInAccessDeniedException $exception) {
            $email = null;
        }

        $response['email'] = $email;

        return new LinkedInResourceOwner($response);
    }
}
