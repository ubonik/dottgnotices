<?php

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Service;

class DotBank
{
    public function Authorization(): string {
		//$authToken = $this->getAuthToken();
		//return $this->getRedirectForAuthorization($authToken);

        return 'auth';
	}

}