<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserGoogleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserGoogleRepository::class, readOnly: false)]
#[ORM\Table(name: "user_google")]
class UserGoogle extends AbstractUserOAuth
{
}
