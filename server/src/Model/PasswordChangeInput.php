<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordChangeInput
{
	#[Assert\NotBlank]
	public string $oldPassword;
	#[Assert\NotBlank]
	#[Assert\Length(
		min: 14,
		minMessage: 'Your password must be at least 14 characters long'
	)]
	public string $newPassword;
}
