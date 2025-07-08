<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\User;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class PasswordSetter {
	function __construct(
		private MailerInterface $mailer,
		private string $fromEmail,
		/*private ResetPasswordTokenEncoder $tokenEncoder,
		private ResetUrlGenerator $resetUrlGenerator,
        private UserPasswordHasherInterface $passwordHasher,
        private ClockInterface $clock*/
	)
	{

	}
	public function sendEmail(User $user): void {
        //TODO fix email message
/*        $token = $this->tokenEncoder->create($user);
		$url = $this->resetUrlGenerator->generate()->expand(['userId' => $user->getId(), 'token' => $token]);
		$email = (new Email())
			->from($this->fromEmail)
			->to($user->getEmail())
			->subject('Password Reset')
            ->html("
<html>  
    <body style='font-family: Arial, sans-serif'>
        <h1><b>Reset your UND Work Well password</b></h1>
        <p>{$user->getFirstName()}, A password reset has been requested for your UND Work Well Account.
			To proceed with the password reset, please use the following link within the next 15 minutes.</p>
        <p><a href='{$url}' target='blank'>Click here to reset your password</a></p>
        <p>If you didn't request a reset, you can safely ignore this email.</p>
    </body>
</html>");
		$this->mailer->send($email);*/
	}

	public function reset(User $user, string $token, string $newPassword): void {
        /*$tokenUser = $this->tokenEncoder->verify($token);

        if ($tokenUser->getId() !== $user->getId())
            throw new TokenInvalid();

        if($this->passwordHasher->isPasswordValid($user, $newPassword))
            throw new InvalidValueException('New Password cannot be old password');

        $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
        $user->setPasswordChanged($this->clock->now());*/
	}
}
