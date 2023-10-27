<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\View;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class UserController
{

    #[Get('/user/create')]
    public function create(): View
    {
        return View::make('users/register');
    }

    #[Post('/users')]
    public function register()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];

        $firstname = explode(' ', $name)[0];

        $text =<<<Body
Hello $firstname,

Thank you for signing up!
Body;

        // Should be in a view file and rendered to string here
        $html = <<<HTMLBody
<h1 style="text-align: center; color: blue">Welcome</h1>
Hello $firstname,
<br/>
Thank you for signing up!
HTMLBody;

        $email = (new Email())
            ->from('support@example.com')
            ->to($email)
            ->subject('Welcome!')
            ->attach('Hello World', 'welcome.txt')
            ->html($html)
            ->text($text);


        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);

        $mailer = new Mailer($transport);

        $mailer->send($email);

    }
}