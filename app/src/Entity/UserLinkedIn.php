<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserLinkedInRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLinkedInRepository::class, readOnly: false)]
#[ORM\Table(name: "user_linkedin")]
class UserLinkedIn extends AbstractUserOAuth
{
}
