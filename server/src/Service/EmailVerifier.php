<?php
declare(strict_types=1);

namespace App\Service;

//use App\Exception\Resource\TokenInvalid;
use App\Model\Entity\User;
use App\Service\Authentication\EmailVerificationTokenEncoder;
use App\Service\UrlGenerator\VerificationUrlGenerator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class EmailVerifier {
	function __construct(
		private MailerInterface $mailer,
		private string $fromEmail,
		private EmailVerificationTokenEncoder $tokenEncoder,
		private VerificationUrlGenerator $verificationUrlGenerator,
	)
	{

	}

	public function sendEmail(User $user): void {
        //TODO fix email message
        /*$token = $this->tokenEncoder->create($user);
		$url = $this->verificationUrlGenerator->generate()->expand(['userId' => $user->getId(), 'token' => $token]);
		$email = (new Email())
			->from($this->fromEmail)
			->to($user->getEmail())
			->subject('Confirm Your Email')
            ->html("
<html>
    <body style='font-family: Arial, sans-serif'>
        <h1><b>Please confirm your email address</b></h1>
        <p>{$user->getFirstName()}, please take a moment to confirm your email for your account.
			To proceed with email confirmation, please use the following link within the next 15 minutes.</p>
        <p><a href='{$url}' target='blank'>Click here to confirm your email</a></p>
    </body>
</html>");
		$this->mailer->send($email);*/
	}

	public function verify(User $user, string $token): void {
		$tokenUser = $token;//$this->tokenEncoder->verify($token);

        if ($tokenUser->getId() !== $user->getId())
            //throw new TokenInvalid();

        $tokenUser->setEmailVerified(true);
	}
}
