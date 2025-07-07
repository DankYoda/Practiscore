<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistration
{
	#[Groups([])]
	public User $user;

	#[Groups([])]
	#[Assert\Length(
		min: 14,
		minMessage: 'Your password must be at least 14 characters long'
	)]
	public string $plainPassword;
}
