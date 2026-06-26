<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class EmailChangeInput {
    #[Assert\NotBlank]
    public string $email;
}
