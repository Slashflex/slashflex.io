<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-Fr');
        // Setup an admin
        $admin = new User();
        // Create an admin role
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        // // Create an user role
        // $roleUser = new Role();
        // $roleUser->setName('ROLE_USER');

        // $manager->persist($roleUser);

        $password = $_ENV['DB_PASSWORD'];
        $email = $_ENV['DB_EMAIL'];
        $firstname = $_ENV['DB_FIRSTNAME'];
        $lastname = $_ENV['DB_LASTNAME'];
        // $admin->setAvatar('avatar.png');
        $admin
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setConfirmationToken(null)
            ->setTokenEnabled(true)
            ->setEmail($email)
            ->setPassword($this->passwordEncoder->encodePassword($admin, $password))
            ->setDescription('Creator of https://slashflex.io')
            ->setLogin('Slashflex')
            ->addRoleUser($roleAdmin)
            // ->addRoleUser($roleUser)
            ->initializeSlug();

        $path = 'public/uploads/avatars/' . strtolower($admin->getFirstname()) . '-' . strtolower($admin->getLastname());
        $path = 'public/uploads/avatars/' . $admin->getSlug();
        mkdir($path);
        $admin->setAvatar('avatar.png');
        copy('public/uploads/avatars/avatar.png', $path . '/avatar.png');
        $manager->persist($admin);
        $article1 = new Article();
        $article2 = new Article();
        $article3 = new Article();
        $article4 = new Article();
        $article5 = new Article();
        $article6 = new Article();
        $article7 = new Article();
        // $article8 = new Article();
        $article9 = new Article();
        $article10 = new Article();
        $user = "$" . "USER";
//        $zshPath = "$" . "ZSH_CUSTOM/plugins";
//        $zshCustom = "$" . "{ZSH_CUSTOM:-~/.oh-my-zsh/custom}";
//        $shell = "$" . "SHELL";
        // How To Secure Apache with Let’s Encrypt on Ubuntu 20.04
        $content1 = <<<EOT
            <div class="article__content">
                <h2 class="language__title">How To Secure Apache with Let&apos;s Encrypt on Ubuntu 20.04</h2>
                <h3 class="language__subtitle">Introduction</h3>
                <p class="language-html">Let&apos;s Encrypt is a Certificate Authority (CA) that facilitates obtaining and installing free <a class="in-bl" href="https://www.digitalocean.com/community/tutorials/openssl-essentials-working-with-ssl-certificates-private-keys-and-csrs">TLS/SSL certificates</a>, thereby enabling encrypted HTTPS on web servers. It simplifies the  process by providing a software client, Certbot, that attempts to automate most (if not all) of the required steps. Currently, the entire process of obtaining and installing a certificate is fully automated on both Apache and Nginx.</p>
                <p class="language-html">In this guide, we’ll use <a class="in-bl" href="https://certbot.eff.org/">Certbot</a> to obtain a free SSL certificate for Apache on Ubuntu 20.04, and make sure this certificate is set up to renew automatically.</p>
                <p class="language-html">This tutorial uses a separate virtual host file instead of Apache’s default configuration file for setting up the website that will be secured by Let’s Encrypt. <a class="in-bl" href="https://slashflex.io/blog/post/how-to-set-up-apache-virtual-hosts-on-ubuntu-20-04">I recommend</a> creating new Apache virtual host files for each domain hosted in a  server, because it helps to avoid common mistakes and maintains the default configuration files as a fallback setup.</p> 
                <h3 class="language__subtitle">Prerequisites</h3>
                <p class="language-html">To follow this tutorial, you will need:</p>
                <p class="language-html"><span class="invisible">tab</span>- One Ubuntu 20.04 server set up by following this <a class="in-bl" href="https://slashflex.io/blog/post/initial-server-setup-with-ubuntu-20-04">initial server setup for Ubuntu 20.04</a> tutorial, including a sudo non-root user and a firewall.</p>
                <p class="language-html"><span class="invisible">tab</span>- A fully registered domain name. This tutorial will use <strong>your_domain</strong> as an example throughout. You can purchase a domain name on <a class="in-bl" href="https://namecheap.com">Namecheap</a>, get one for free on <a class="in-bl" href="http://www.freenom.com/en/index.html">Freenom</a>, or use the domain registrar of your choice.</p>
                <p class="language-html"><span class="invisible">tab</span>- Both of the following DNS records set up for your server. You can follow <a class="in-bl" href="https://www.digitalocean.com/community/tutorials/an-introduction-to-digitalocean-dns">this introduction to DigitalOcean DNS</a> for details on how to add them.</p>
                <p class="language-html"><span class="invisible">tab</span> <span class="invisible">tab</span>- An <span class="highlight">A</span> record with <strong>your_domain</strong> pointing to your server’s public IP address.</p>
                <p class="language-html"><span class="invisible">tab</span> <span class="invisible">tab</span>- An <span class="highlight">A</span> record with <strong>www.your_domain</strong> pointing to your server’s public IP address.</p>
                <p class="language-html"><span class="invisible">tab</span>- Apache installed by following <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-the-apache-web-server-on-ubuntu-20-04">How To Install Apache on Ubuntu 20.04</a>. Be sure that you have a <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-the-apache-web-server-on-ubuntu-20-04#step-5-setting-up-virtual-hosts-recommended">virtual host file</a> for your domain. This tutorial will use <strong>/etc/apache2/sites-available/your_domain.conf</strong> as an example.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 1 – Installing Certbot</h3>
                <p class="language-html">In order to obtain an SSL certificate with Let’s Encrypt, we’ll first need to install the Certbot software on your server. We’ll use the default Ubuntu package repositories for that.</p>
                <p class="language-html">We need two packages: <strong>certbot</strong>, and <strong>python3-certbot-apache</strong>. The latter is a plugin that integrates Certbot with Apache, making it possible to automate obtaining a certificate and configuring HTTPS within your web server with a single command.</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt install certbot python3-certbot-apache
                </code>
                <p class="language-html">You will be prompted to confirm the installation by pressing <span class="highlight">Y</span>, then <span class="highlight">ENTER</span>.</p>
                <p class="language-html">Certbot is now installed on your server. In the next step, we’ll verify Apache’s configuration to make sure your virtual host is set  appropriately. This will ensure that the <strong>certbot</strong> client  script will be able to detect your domains and reconfigure your web  server to use your newly generated SSL certificate automatically.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 2 - Checking your Apache Virtual Host Configuration</h3>
                <p class="language-html">In order to be able to automatically obtain and configure SSL for your web server, Certbot needs to find the correct virtual host within your Apache configuration files. Your server domain name(s) will be retrieved from the <strong>ServerName</strong> and <strong>ServerAlias</strong> directives defined within your <strong>VirtualHost</strong> configuration block.</p>
                <p class="language-html">If you followed the <a class="in-bl" href="https://slashflex.io/blog/post/how-to-set-up-apache-virtual-hosts-on-ubuntu-20-04">virtual host setup tutorial</a>, you should have a VirtualHost block set up for your domain at <strong>/etc/apache2/sites-available/your_domain.conf</strong> with the <strong>ServerName</strong> and also the <strong>ServerAlias</strong> directives already set appropriately.</p>
                <p class="language-html">To check this up, open the virtual host file for your domain using <strong>nano</strong> or your preferred text editor:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nano /etc/apache2/sites-available/your_domain.conf
                </code>
                <p class="language-html">Find the existing <strong>ServerName</strong> and <strong>ServerAlias</strong> lines. They should look like this:</p>
                <code class="language-shell">
                ...<br>
                ServerName your_domain<br>
                ServerAlias www.your_domain<br>
                ...<br>
                </code>
                <p class="language-html">If you already have your <strong>ServerName</strong> and <strong>ServerAlias</strong> set up like this, you can exit your text editor and move on to the next step. If you’re using <strong>nano</strong>, you can exit by typing <span class="highlight">CTRL+X</span>, then <span class="highlight">Y</span> and <span class="highlight">ENTER</span> to confirm.</p>
                <p class="language-html">If your current virtual host configuration doesn’t match the example, update it accordingly. When you’re done, save the file and quit the  editor. Then, run the following command to validate your changes:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apache2ctl configtest
                </code>
                <p class="language-html">You should get a <strong>Syntax OK</strong> as a response. If you get an error, reopen the virtual host file and check for any typos or missing  characters. Once your configuration file’s syntax is correct, reload Apache so that the changes take effect:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl reload apache2
                </code>
                <p class="language-html">With these changes, Certbot will be able to find the correct VirtualHost block and update it.</p>
                <p class="language-html">Next, we’ll update the firewall to allow HTTPS traffic.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 3 – Allowing HTTPS Through the Firewall</h3>
                <p class="language-html">If you have the UFW firewall enabled, as recommended by the prerequisite guides, you’ll need to adjust the settings to allow HTTPS traffic. Upon installation, Apache registers a few different UFW application profiles. We can leverage the <strong>Apache Full</strong> profile to allow both HTTP and HTTPS traffic on your server.</p>
                <p class="language-html">To verify what kind of traffic is currently allowed on your server, you can use:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw status
                </code>
                <p class="language-html">If you have followed my Apache installation guide, your output should look something like this, meaning that only HTTP traffic on port <strong>80</strong> is currently allowed:</p>
                <code class="language-shell">
                Status: active<br><br>
                To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>Action <span class="invisible">tab</span> <span class="invisible">tab</span> From<br>
                --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ------ <span class="invisible">tab</span><span class="invisible">tab</span><span class="invisible">tab</span>----<br>
                OpenSSH<span class="invisible">tab</span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere<br>
                Apache<span class="invisible">ta</span> <span class="invisible">tab</span><span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere<br>
                OpenSSH (v6)<span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere (v6)<br>
                Apache (v6)<span class="invisible">tab</span> <span class="invisible">t</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere (v6)<br>
                </code>
                <p class="language-html">To additionally let in HTTPS traffic, allow the <strong>Apache Full</strong> profile and delete the redundant <strong>Apache</strong> profile:
                <code class="language-shell">
                <span class="sudo">sudo</span> allow "Apache Full"<br>
                <span class="sudo">sudo</span> delete allow "Apache"
                </code>
                <p class="language-html">Your status will now look like this:</p>
                <code class="language-shell">
                Status: active<br><br>
                To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>Action <span class="invisible">tab</span> <span class="invisible">tab</span> From<br>
                --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ------ <span class="invisible">tab</span><span class="invisible">tab</span><span class="invisible">tab</span>----<br>
                OpenSSH<span class="invisible">tab</span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere<br>
                Apache Full <span class="invisible">tab</span> <span class="invisible">t</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere<br>
                OpenSSH (v6)<span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere (v6)<br>
                Apache Full (v6) <span class="invisible">t</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere (v6)<br>
                </code>
                <p class="language-html">You are now ready to run Certbot and obtain your certificates.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 4 - Obtain an SSL Certificate</h3>
                <p class="language-html">Certbot provides a variety of ways to obtain SSL certificates through plugins. The Apache plugin will take care of reconfiguring Apache and reloading the configuration whenever necessary. To use this plugin, type the following:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> certbot <span class="flag">--apache</span>
                </code>
                <p class="language-html">This script will prompt you to answer a series of questions in order to configure your SSL certificate. First, it will ask you for a valid email address. This email will be used for renewal notifications and security advisories:</p>
                <code class="language-shell">
                Saving debug log to /var/log/letsencrypt/letsencrypt.log<br>
                Plugins selected: Authenticator apache, Installer apache<br>
                Enter email address (used for urgent renewal and security notices) (Enter "c" to
                cancel): your@your_domain
                </code>
                <p class="language-html">After providing a valid email address, press <span class="highlight">ENTER</span> to go to the next step. You will then be asked to confirm whether you agree to the Let's Encrypt terms of service. You can confirm by pressing <span class="highlight">A</span> then <span class="highlight">ENTER</span></p>
                <code class="language-shell">
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br>
                Please read the Terms of Service at<br>
                https://letsencrypt.org/documents/LE-SA-v1.2-November-15-2017.pdf. You must<br>
                agree in order to register with the ACME server at<br>
                https://acme-v02.api.letsencrypt.org/directory<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br>
                (A)gree/(C)ancel: A
                </code>
                <p class="language-html">Next, you will be asked if you would like to share your email with the Electronic Frontier Foundation to receive news and other information. If you don't want to subscribe to their content, type <span class="highlight">N</span>. Otherwise, enter <span class="highlight">Y</span>. Then, press <span class="highlight">ENTER</span> to go to the next step.</p>
                <code class="language-shell">
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                Would you be willing to share your email address with the Electronic Frontier<br>
                Foundation, a founding partner of the Let's Encrypt project and the non-profit<br>
                organization that develops Certbot? We'd like to send you email about our work<br>
                encrypting the web, EFF news, campaigns, and ways to support digital freedom.<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                (Y)es/(N)o: N
                </code>
                <p class="language-html">The next step will prompt you to inform Certbot of the domains for which you want to enable HTTPS. The domain names listed are automatically obtained from your Apache virtual host configuration, which is why it is important to ensure that you have the <strong>ServerName</strong> and <strong>ServerAlias​​</strong> directives configured in your virtual host. If you want to enable HTTPS for all listed domain names (recommended), you can leave the prompt blank and press <span class="highlight">ENTER</span>. Otherwise, select the domains for which you want to enable HTTPS by listing each appropriate number, separated by commas and / or spaces, then press <span class="highlight">ENTER</span>.</p>
                <code class="language-shell">
                Which names would you like to activate HTTPS for?<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                1: your_domain<br>
                2: www.your_domain<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                Select the appropriate numbers separated by commas and/or spaces, or leave input<br>
                blank to select all options shown (Enter "c" to cancel): 
                </code>
                <p class="language-html">You’ll see output like this:</p>
                <code class="language-shell">
                Obtaining a new certificate<br>
                Performing the following challenges:<br>
                http-01 challenge for your_domain<br>
                http-01 challenge for www.your_domain<br>
                Enabled Apache rewrite module<br>
                Waiting for verification...<br>
                Cleaning up challenges<br>
                Created an SSL vhost at /etc/apache2/sites-available/your_domain-le-ssl.conf<br>
                Enabled Apache socache_shmcb module<br>
                Enabled Apache ssl module<br>
                Deploying Certificate to VirtualHost /etc/apache2/sites-available/your_domain-le-ssl.conf<br>
                Enabling available site: /etc/apache2/sites-available/your_domain-le-ssl.conf<br>
                Deploying Certificate to VirtualHost /etc/apache2/sites-available/your_domain-le-ssl.conf
                </code>
                <p class="language-html">Then you will be prompted to indicate whether or not you want HTTP traffic to be redirected to HTTPS. In practice, this means that if someone visits your website through unencrypted channels (HTTP), they will automatically be redirected to your website's HTTPS address. Choose <span class="highlight">2</span> to enable redirect, or <span class="highlight">1</span> if you want to keep HTTP and HTTPS as separate methods of accessing your website.</p>
                <code class="language-shell">
                Please choose whether or not to redirect HTTP traffic to HTTPS, removing HTTP access.<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                1: No redirect - Make no further changes to the webserver configuration.<br>
                2: Redirect - Make all requests redirect to secure HTTPS access. Choose this for<br>
                new sites, or if you're confident your site works on HTTPS. You can undo this<br>
                change by editing your web server's configuration.<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                Select the appropriate number [1-2] then [enter] (press "c" to cancel): 2
                </code>
                <p class="language-html">After this step, the configuration of Certbot is complete and you will be presented with the last remarks on your new certificate, where to locate the generated files and how to test your configuration using an external tool that analyzes the authenticity of your certificate.:</p>
                <code class="language-shell">
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                Congratulations! You have successfully enabled https://your_domain and<br>
                https://www.your_domain<br><br>
                You should test your configuration at:<br>
                https://www.ssllabs.com/ssltest/analyze.html?d=your_domain<br>
                https://www.ssllabs.com/ssltest/analyze.html?d=www.your_domain<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br><br>
                IMPORTANT NOTES:<br>
                    - Congratulations! Your certificate and chain have been saved at:<br>
                    /etc/letsencrypt/live/your_domain/fullchain.pem<br>
                    Your key file has been saved at:<br>
                    /etc/letsencrypt/live/your_domain/privkey.pem<br>
                    Your cert will expire on 2020-07-27. To obtain a new or tweaked<br>
                    version of this certificate in the future, simply run certbot again<br>
                    with the "certonly" option. To non-interactively renew *all* of<br>
                    your certificates, run "certbot renew"<br>
                    - Your account credentials have been saved in your Certbot<br>
                    configuration directory at /etc/letsencrypt. You should make a<br>
                    secure backup of this folder now. This configuration directory will<br>
                    also contain certificates and private keys obtained by Certbot so<br>
                    making regular backups of this folder is ideal.<br>
                    - If you like Certbot, please consider supporting our work by:<br><br>
                    Donating to ISRG / Let's Encrypt:   https://letsencrypt.org/donate<br>
                    Donating to EFF:                     https://eff.org/donate-le
                </code>
                <p class="language-html">Your certificate is now installed and loaded into the Apache configuration. Try reloading your website using <strong>https://</strong> and notice your browser's security flag. It should tell you that your site is properly secured, usually by including a lock icon in the address bar.</p>
                <p class="language-html">You can use <a class="in-bl" href="https://www.ssllabs.com/ssltest/"> SSL Labs Server Test </a> to check your certificate score and get detailed information about it, from the point of view an external service.</p>
                <p class="language-html">In the next and final step, we will test Certbot's auto-renew feature, which ensures that your certificate is automatically renewed before the expiration date.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 5 - Verifying the automatic renewal of Certbot</h3>
                <p class="language-html">Let's Encrypt certificates are only valid for ninety days. This is to encourage users to automate their certificate renewal process, as well as to ensure that misused certificates or stolen keys expire as soon as possible.</p>
                <p class="language-html">The <strong> certbot </strong> package we installed supports renewals by including a renewal script for <strong>/etc/cron.d</strong>, which is managed by <strong>systemctl</strong> and the service called <strong>certbot.timer</strong>. This script runs twice a day and will automatically renew any certificate within thirty days of expiration.</p>
                <p class="language-html">To check the status of this service and make sure it is active and running, you can use:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl status certbot.timer
                </code>
                <p class="language-html">You will get output similar to this:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl status nginx<br>
                </code>
                <code class="language-shell">
                <span class="sudo">●</span> certbot.timer - Run certbot twice daily<br>
                <span class="invisible">tab</span>Loaded: loaded (/lib/systemd/system/certbot.timer; enabled; vendor preset: enabled)<br>
                <span class="invisible">tab</span>Active: <span class="sudo">active (waiting)</span> since Tue 2020-04-28 17:57:48 UTC; 17h ago<br>
                <span class="invisible">tab</span>Trigger: <span class="invisible">tab</span>Wed 2020-04-29 23:50:31 UTC; 12h left<br>
                <span class="invisible">tab</span>Triggers: ● certbot.service<br><br>
                Apr 28 17:57:48 fine-turtle systemd[1]: Started Run certbot twice daily.
                </code>
                <p class="language-html">To test the renewal process, you can give it a try with <strong> certbot </strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> certbot renew <span class="flag">--dry-run</span>
                </code>
                <p class="language-html">If you don't see any errors, you're good to go. If necessary, Certbot will renew your certificates and reload Apache to pick up the changes. If the automated renewal process fails, Let's Encrypt will send a message to the email you specified, notifying you when your certificate is about to expire.</p>
            </div>
        EOT;

        // How To Secure Nginx with Let’s Encrypt on Ubuntu 20.04
        $content2 = <<<EOT
            <div class="article__content">
                <h2 class="language__title">How To Secure Nginx with Let’s Encrypt on Ubuntu 20.04</h2>
                <h3 class="language__subtitle">Introduction</h3>
                <p class="language-html">Let’s Encrypt is a Certificate Authority (CA) that provides an easy way to obtain and install free TLS/SSL certificates, thereby enabling encrypted HTTPS on web servers. It simplifies the process by providing a software client, Certbot, that attempts to  automate most (if not all) of the required steps. Currently, the entire  process of obtaining and installing a certificate is fully automated on both Apache and Nginx.</p>
                <p class="language-html">In this tutorial, you will use Certbot to obtain a free SSL certificate for Nginx on Ubuntu 20.04 and set up your certificate to  renew automatically.</p>
                <p class="language-html">This tutorial will use a separate Nginx server configuration file instead of the default file. <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-nginx-on-ubuntu-20-04#step-5-setting-up-server-blocks-(recommended)">I recommend</a> creating new Nginx server block files for each domain because it helps to avoid common mistakes and maintains the default files as a fallback  configuration.</p> 
                <h3 class="language__subtitle">Prerequisites</h3>
                <p class="language-html">To follow this tutorial, you will need:</p>
                <p class="language-html"><span class="invisible">tab</span>- One Ubuntu 20.04 server set up by following this <a class="in-bl" href="https://slashflex.io/blog/post/initial-server-setup-with-ubuntu-20-04">initial server setup for Ubuntu 20.04</a> tutorial, including a sudo non-root user and a firewall.</p>
                <p class="language-html"><span class="invisible">tab</span>- A fully registered domain name. This tutorial will use <strong>your_domain</strong> as an example throughout. You can purchase a domain name on <a class="in-bl" href="https://namecheap.com">Namecheap</a>, get one for free on <a class="in-bl" href="http://www.freenom.com/en/index.html">Freenom</a>, or use the domain registrar of your choice.</p>
                <p class="language-html"><span class="invisible">tab</span>- Both of the following DNS records set up for your server. You can follow <a class="in-bl" href="https://www.digitalocean.com/community/tutorials/an-introduction-to-digitalocean-dns">this introduction to DigitalOcean DNS</a> for details on how to add them.</p>
                <p class="language-html"><span class="invisible">tab</span> <span class="invisible">tab</span>- An <span class="highlight">A</span> record with <strong>your_domain</strong> pointing to your server’s public IP address.</p>
                <p class="language-html"><span class="invisible">tab</span> <span class="invisible">tab</span>- An <span class="highlight">A</span> record with <strong>www.your_domain</strong> pointing to your server’s public IP address.</p>
                <p class="language-html"><span class="invisible">tab</span>- Nginx installed by following <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-nginx-on-ubuntu-20-04">How To Install Nginx on Ubuntu 20.04</a>. Be sure that you have a <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-nginx-on-ubuntu-20-04#step-5-setting-up-server-blocks-(recommended)">server block</a> for your domain. This tutorial will use <strong>/etc/nginx/sites-available/example.com</strong> as an example.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 1 – Installing Certbot</h3>
                <p class="language-html">The first step to using Let’s Encrypt to obtain an SSL certificate is to install the Certbot software on your server.</p>
                <p class="language-html">Install Certbot and it’s Nginx plugin with <strong>apt</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt install certbot python3-certbot-nginx
                </code>
                <p class="language-html">Certbot is now ready to use, but in order for it to automatically configure SSL for Nginx, we need to verify some of Nginx’s configuration.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 2 - Confirming Nginx’s Configuration</h3>
                <p class="language-html">Certbot needs to be able to find the correct <strong>server</strong> block in your Nginx configuration for it to be able to automatically configure SSL. Specifically, it does this by looking for a <strong>server_name</strong> directive that matches the domain you request a certificate for.</p>
                <p class="language-html">If you followed the <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-nginx-on-ubuntu-20-04#step-5-setting-up-server-blocks-(recommended)">server block set up step in the Nginx installation tutorial</a>, you should have a server block for your domain at <strong>/etc/nginx/sites-available/example.com</strong> with the <strong>server_name</strong> a directive already set appropriately.</p>
                <p class="language-html">To check, open the configuration file for your domain using <strong>nano</strong> or your preferred text editor:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nano /etc/nginx/sites-available/example.com
                </code>
                <p class="language-html">Find the existing <strong>server_name</strong>. It should look like this:</p>
                <code class="language-shell">
                ...<br>
                server_name example.com www.example.com <br>
                ...
                </code>
                <p class="language-html">If it does, exit your editor and move on to the next step.</p>
                <p class="language-html">If it doesn’t, update it to match. Then save the file, quit your editor, and verify the syntax of your configuration edits:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nginx <span class="flag">-t</span>
                </code>
                <p class="language-html">If you get an error, reopen the server block file and check for any typos or missing characters. Once your configuration file’s syntax is  correct, reload Nginx to load the new configuration:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl reload nginx
                </code>
                <p class="language-html">Certbot can now find the correct <strong>server</strong> block and update it automatically.</p>
                <p class="language-html">Next, we’ll update the firewall to allow HTTPS traffic.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 3 – Allowing HTTPS Through the Firewall</h3>
                <p class="language-html">If you have the UFW firewall enabled, as recommended by the prerequisite guides, you’ll need to adjust the settings to allow HTTPS traffic. Upon installation, Apache registers a few different UFW application profiles. We can leverage the <strong>Apache Full</strong> profile to allow both HTTP and HTTPS traffic on your server.</p>
                <p class="language-html">To verify what kind of traffic is currently allowed on your server, you can use:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw status
                </code>
                <p class="language-html">If you have followed my Apache installation guide, your output should look something like this, meaning that only HTTP traffic on port <strong>80</strong> is currently allowed:</p>
                <code class="language-shell">
                Status: active<br><br>
                To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> Action <span class="invisible">tab</span><span class="invisible">tab</span>From<br>
                --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ------ <span class="invisible">tab</span><span class="invisible">tab</span>----<br>
                OpenSSH<span class="invisible">tab</span> <span class="invisible">tab</span><span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                Nginx <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                OpenSSH (v6)<span class="invisible">tab</span><span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                Nginx (v6) <span class="invisible">tab</span> <span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                </code>
                <p class="language-html">To additionally let in HTTPS traffic, allow the <strong>Apache Full</strong> profile and delete the redundant <strong>Apache</strong> profile:
                <code class="language-shell">
                <span class="sudo">sudo</span> allow "Nginx Full"<br>
                <span class="sudo">sudo</span> delete allow "Nginx HTTP"
                </code>
                <p class="language-html">Your status will now look like this:</p>
                <code class="language-shell">
                Status: active<br><br>
                To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> Action <span class="invisible">tab</span><span class="invisible">tab</span>From<br>
                --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ------ <span class="invisible">tab</span><span class="invisible">tab</span>----<br>
                OpenSSH<span class="invisible">tab</span> <span class="invisible">tab</span><span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                Nginx Full<span class="invisible">tab</span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                OpenSSH (v6)<span class="invisible">tab</span><span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                Nginx Full (v6)<span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                </code>
                <p class="language-html">Next, let’s run Certbot and fetch our certificates.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 4 – Obtaining an SSL Certificate</h3>
                <p class="language-html">Certbot provides a variety of ways to obtain SSL certificates through plugins. The Nginx plugin will take care of reconfiguring Nginx and reloading the configuration whenever necessary. To use this plugin, type the following:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> certbot <span class="flag">--nginx -d</span> example.com <span class="flag">-d</span> www.example.com
                </code>
                <p class="language-html">This runs <strong>certbot</strong> with the <strong>--nginx</strong> plugin, using <strong>-d</strong> to specify the domain names we’d like the certificate to be valid for.</p>
                <p class="language-html">If this is your first time running <strong>certbot</strong>, you will be prompted to enter an email address and agree to the terms of service. After doing so, <strong>certbot</strong> will communicate with the Let’s Encrypt server, then run a challenge to verify that you control the domain you’re requesting a certificate for.</p>
                <p class="language-html">If that’s successful, <strong>certbot</strong> will ask how you’d like to configure your HTTPS settings.</p>
                <code class="language-shell">
                Please choose whether or not to redirect HTTP traffic to HTTPS, removing HTTP access.
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                1: No redirect - Make no further changes to the webserver configuration.<br>
                2: Redirect - Make all requests redirect to secure HTTPS access. Choose this for<br>
                new sites, or if you’re confident your site works on HTTPS. You can undo this<br>
                change by editing your web server’s configuration.<br>
                - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br>
                Select the appropriate number [1-2] then [enter] (press "c" to cancel):
                </code>
                <p class="language-html">Select your choice then hit <span class="highlight">ENTER</span>. The configuration will be updated, and Nginx will reload to pick up the new settings. <strong>certbot</strong> will wrap up with a message telling you the process was successful and where your certificates are stored:</p>
                <code class="language-shell">
                IMPORTANT NOTES:<br>
                - Congratulations! Your certificate and chain have been saved at:<br>
                /etc/letsencrypt/live/example.com/fullchain.pem<br>
                Your key file has been saved at:<br>
                /etc/letsencrypt/live/example.com/privkey.pem<br>
                Your cert will expire on 2020-08-20. To obtain a new or tweaked<br>
                version of this certificate in the future, simply run certbot again<br>
                with the "certonly" option. To non-interactively renew *all* of<br>
                your certificates, run "certbot renew"<br>
                - If you like Certbot, please consider supporting our work by:<br><br>
                Donating to ISRG / Let’s Encrypt:   https://letsencrypt.org/donate
                Donating to EFF:                    https://eff.org/donate-le
                </code>
                <p class="language-html">Your certificate is now installed and loaded. Try reloading your website using <strong>https://</strong> and notice your browser’s security indicator. It should indicate that  the site is properly secured, usually with a lock icon. If you test your server using the <a class="in-bl" href="https://www.ssllabs.com/ssltest/">SSL Labs Server Test</a>, it will get an <span class="highlight">A</span> grade.</p>
                <p class="language-html">Let’s finish by testing the renewal process.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 5 – Verifying Certbot Auto-Renewal</h3>
                <p class="language-html">Let’s Encrypt’s certificates are only valid for ninety days. This is to encourage users to automate their certificate renewal process. The <strong>certbot</strong> package we installed takes care of this for us by adding a systemd timer that will run twice a day and automatically renew any certificate that’s within thirty days of expiration.</p>
                <p class="language-html">You can query the status of the timer with <strong>systemctl</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl status certbot.timer
                </code><br>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl status nginx<br>
                <span class="sudo">●</span> certbot.timer - Run certbot twice daily<br>
                <span class="invisible">tab</span>Loaded: loaded (/lib/systemd/system/certbot.timer; enabled; vendor preset: enabled)<br>
                <span class="invisible">tab</span>Active: <span class="sudo">active (waiting)</span> since Tue 2020-04-28 17:57:48 UTC; 17h ago<br>
                <span class="invisible">tab</span>Trigger: <span class="invisible">tab</span>Wed 2020-04-29 23:50:31 UTC; 12h left<br>
                <span class="invisible">tab</span>Triggers: <span class="invisible">tab</span>●<span class="invisible">tab</span>certbot.service<br><br>
                </code>
                <p class="language-html">To test the renewal process, you can do a dry run with <strong>certbot</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> certbot renew <span class="flag">--dry-run</span>
                </code>
                <p class="language-html">If you see no errors, you’re all set. When necessary, Certbot will renew your certificates and reload Nginx to pick up the changes. If the  automated renewal process ever fails, Let’s Encrypt will send a message  to the email you specified, warning you when your certificate is about to expire.</p>
            </div>
        EOT;

        // How To Install Nginx on Ubuntu 20.04
        $content3 = <<<EOT
            <div class="article__content">
                <h2 class="language__title">How To Install Nginx on Ubuntu 20.04 </h2>
                <h3 class="language__subtitle">Introduction</h3>
                <p class="language-html"><a class="in-bl" href="https://www.nginx.com/ ">Nginx</a> is one of the most popular web servers in the world and is responsible for hosting some of the  largest and highest-traffic sites on the internet. It is a lightweight  choice that can be used as either a web server or reverse proxy.
                In this guide, we’ll discuss how to install Nginx on your Ubuntu 20.04 server, adjust the firewall, manage the Nginx process, and set up  server blocks for hosting more than one domain from a single server. </p>
                <h3 class="language__subtitle">Prerequisites</h3>
                <p class="language-html">Before you begin this guide, you should have a regular, non-root user with sudo privileges configured on your server.<br>
                When you have an account available, log in as your non-root user to begin.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 1 – Installing Nginx</h3>
                <p class="language-html">Because Nginx is available in Ubuntu’s default repositories, it is possible to install it from these repositories using the <strong>apt</strong> packaging system.<br>
                Since this is our first interaction with the <strong>apt</strong> packaging system in this session, we will update our local package index so that we have access to the most recent package listings.<br>
                Afterwards, we can install <strong>nginx</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt update<br>
                <span class="sudo">sudo</span> apt install nginx
                </code>
                <p class="language-html">After accepting the procedure, <strong>apt</strong> will install Nginx and any required dependencies to your server.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 2 – Adjusting the Firewall</h3>
                <p class="language-html">Before testing Nginx, the firewall software needs to be adjusted to allow access to the service.  Nginx registers itself as a service with <strong>ufw</strong> upon installation, making it straightforward to allow Nginx access.</p>
                <p class="language-html">List the application configurations that <strong>ufw</strong> knows how to work with by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw app list
                </code>
                <p class="language-html">You should get a listing of the application profiles:</p>
                <code class="language-shell">
                Available applications:<br>
                <span class="invisible">tab</span>Nginx Full<br>
                <span class="invisible">tab</span>Nginx HTTP<br>
                <span class="invisible">tab</span>Nginx HTTPS<br>
                <span class="invisible">tab</span>OpenSSH
                </code>
                <p class="language-html">As demonstrated by the output, there are three profiles available for Nginx:</p>
                <ul>
                <li>
                <p class="language-html"><span class="invisible">tab</span><strong>- Nginx Full</strong>: This profile opens both port 80 (normal, unencrypted web traffic) and port 443 (TLS/SSL encrypted traffic)</p>
                </li>
                <li>
                <p class="language-html"><span class="invisible">tab</span><strong>- Nginx HTTP</strong>: This profile opens only port 80 (normal, unencrypted web traffic)</p>
                </li>
                <li>
                <p class="language-html"><span class="invisible">tab</span><strong>- Nginx HTTPS</strong>: This profile opens only port 443 (TLS/SSL encrypted traffic)</p>
                </li>
                </ul>
                <p class="language-html">It is recommended that you enable the most restrictive profile that will still allow the traffic you’ve configured. Right now, we will only need to allow traffic on port 80.</p>
                <p class="language-html">You can enable this by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw allow "Nginx HTTP"
                </code>
                <p class="language-html">You can verify the change by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw status
                </code>
                <p class="language-html">The output will indicated which HTTP traffic is allowed:</p>
                <code class="language-shell">
                Status: active<br><br>
                To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> Action <span class="invisible">tab</span><span class="invisible">tab</span>From<br>
                --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ------ <span class="invisible">tab</span><span class="invisible">tab</span>----<br>
                OpenSSH<span class="invisible">tab</span> <span class="invisible">tab</span><span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                Nginx HTTP<span class="invisible">tab</span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                OpenSSH (v6)<span class="invisible">tab</span><span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                Nginx HTTP (v6)<span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                </code>
                <hr class="separator">
                <h3 class="language__subtitle">Step 3 – Checking your Web Server</h3>
                <p class="language-html">At the end of the installation process, Ubuntu 20.04 starts Nginx. The web server should already be up and running.</p>
                <p class="language-html">We can check with the <strong>systemd</strong> init system to make sure the service is running by typing:<p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl status nginx<br>
                <span class="sudo">●</span> nginx.service - A high performance web server and a reverse proxy server<br>
                <span class="invisible">tab</span>Loaded: loaded (/lib/systemd/system/nginx.service; enabled; vendor preset: enabled)<br>
                <span class="invisible">tab</span>Active: <span class="sudo">active (running)</span> since Fri 2020-04-20 16:08:19 UTC; 3 days ago<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>Docs: man:nginx(8)<br>
                <span class="invisible">tab</span>Main PID: 2369 (nginx)<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>Tasks: 2 (limit: 1153)<br>
                <span class="invisible">tab</span>Memory: 3.5M<br>
                <span class="invisible">tab</span>CGroup: /system.slice/nginx.service<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>├─2369 nginx: master process /usr/sbin/nginx -g daemon on;<br> 
                <span class="invisible">tab</span>master_process on;<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>└─2380 nginx: worker process 
                </code>
                <p class="language-html">As confirmed by this out, the service has started successfully. However, the best way to test this is to actually request a page from Nginx.</p>
                <p class="language-html">You can access the default Nginx landing page to confirm that the software is running properly by navigating to your server’s IP address.  If you do not know your server’s IP address, you can find it by using  the icanhazip.com tool, which will give you your public IP address as  received from another location on the internet:</p>
                <code class="language-shell">
                <span class="sudo">curl</span> <span class="flag">-4</span> icanhazip.com
                </code>
                <p class="language-html">When you have your server’s IP address, enter it into your browser’s address bar:</p>
                <code class="language-shell">
                http://your_server_ip
                </code>
                <p class="language-html">You should receive the default Nginx landing page:</p>
                <img class="img-fluid" src="https://assets.digitalocean.com/articles/nginx_1604/default_page.png" alt="nginx welcome page">
                <p class="language-html">If you are on this page, your server is running correctly and is ready to be managed.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 4 – Managing the Nginx Process</h3>
                <p class="language-html">Now that you have your web server up and running, let’s review some basic management commands.</p>
                <p class="language-html">To stop your web server, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl stop nginx
                </code>
                <p class="language-html">To start the web server when it is stopped, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl start nginx
                </code>
                <p class="language-html">To stop and then start the service again, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl restart nginx
                </code>
                <p class="language-html">If you are only making configuration changes, Nginx can often reload without dropping connections. To do this, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl reload nginx
                </code>
                <p class="language-html">By default, Nginx is configured to start automatically when the server boots. If this is not what you want, you can disable this behavior by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl disable nginx
                </code>
                <p class="language-html">To re-enable the service to start up at boot, you can type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl enable nginx
                </code>
                <p class="language-html">You have now learned basic management commands and should be ready to configure the site to host more than one domain.</p> 
                <hr class="separator">
                <h3 class="language__subtitle" id="step-5-setting-up-server-blocks-(recommended)">Step 5 – Setting Up Server Blocks (Recommended)</h3>
                <p class="language-html">When using the Nginx web server, <em>server blocks</em> (similar to virtual hosts in Apache) can be used to encapsulate configuration details and host more than one domain from a single server. We will set up a domain called <strong>your_domain</strong>, but you should <strong>replace this with your own domain name</strong>.</p>
                <p class="language-html">Nginx on Ubuntu 20.04 has one server block enabled by default that is configured to serve documents out of a directory at <strong>/var/www/html</strong>. While this works well for a single site, it can become unwieldy if you are hosting multiple sites. Instead of modifying <strong>/var/www/html</strong>, let’s create a directory structure within <strong>/var/www</strong> for our <strong>your_domain</strong> site, leaving <strong>/var/www/html</strong> in place as the default directory to be served if a client request doesn’t match any other sites.</p>
                <p class="language-html">Create the directory for <strong>your_domain</strong> as follows, using the <span class="highlight">-p</span> flag to create any necessary parent directories:
                <code class="language-shell">
                <span class="sudo">sudo</span> mkdir <span class="flag">-p</span> /var/www/your_domain/html
                </code>
                <p class="language-html">Next, assign ownership of the directory with the <strong>$user</strong> environment variable:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> chown <span class="flag">-R</span> $user:$user /var/www/your_domain/html
                </code>
                <p class="language-html">The permissions of your web roots should be correct if you haven’t modified your <strong>umask</strong> value, which sets default file permissions. To ensure that your permissions are correct and allow the owner to read, write, and execute the files while granting only read and execute permissions to groups and others, you can input the following command:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> chmod <span class="flag">-R</span> 755 /var/www/your_domain
                </code>
                <p class="language-html">Next, create a sample <strong>index.html</strong> page using <strong>nano</strong> or your favorite editor:</p>
                <code class="language-shell">
                nano /var/www/your_domain/html/index.html
                </code>
                <p class="language-html">Inside, add the following sample HTML:</p>
                <img class="img-fluid" src="https://i.imgur.com/o2I3MoN.png">
                <p class="language-html">Save and close the file by typing <span class="highlight">CTRL</span> and <span class="highlight">X</span> then <span class="highlight">Y</span> and <span class="highlight">ENTER</span> when you are finished.</p>
                <p class="language-html">In order for Nginx to serve this content, it’s necessary to create a server block with the correct directives. Instead of modifying the  default configuration file directly, let’s make a new one at <strong>/etc/nginx/sites-available/your_domain</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nano /etc/nginx/sites-available/your_domain
                </code>
                <p class="language-html">Paste in the following configuration block, which is similar to the default, but updated for our new directory and domain name:</p>
                <code class="language-shell">
                server {<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>listen80;<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>listen [::]:80;<br><br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>root /var/www/your_domain/html;<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>index index.html index.htm index.nginx-debian.html;<br><br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>server_name your_domain www.your_domain;<br><br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>location / {<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>try_files $ uri $ uri/ =404;<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>}<br>
                }
                </code>
                <p class="language-html">Notice that we’ve updated the <span class="highlight">root</span> configuration to our new directory, and the <strong>server_name</strong> to our domain name.</p>
                <p class="language-html">Next, let’s enable the file by creating a link from it to the <strong>sites_enabled</strong> directory, which Nginx reads from during startup:
                <code class="language-shell">
                <span class="sudo">sudo</span> ln <span class="flag">-s</span> /etc/nginx/sites-available/your_domain /etc/nginx/sites-enabled/
                </code>
                <p class="language-html">Two server blocks are now enabled and configured to respond to requests based on their <strong>listen</strong> and <strong>server_name</strong> directives:</p>
                <ul>
                <li>
                <p class="language-html"><span class="invisible">tab</span>- <strong>your_domain</strong>: Will respond to requests for <strong>your_domain</strong> and <strong>www.your_domain</strong>.</p>
                </li>
                <li>
                <p class="language-html"><span class="invisible">tab</span>- <strong>default</strong>: Will respond to any requests on port 80 that do not match the other two blocks.</p>
                </li>
                </ul>
                <p class="language-html">To avoid a possible hash bucket memory problem that can arise from adding additional server names, it is necessary to adjust a single value in the <strong>/etc/nginx/nginx.conf</strong> file. Open the file:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nano /etc/nginx/nginx.conf
                </code>
                <p class="language-html">Find the <strong>server_names_hash_bucket_size</strong> directive and remove the <span class="highlight">#</span> symbol to uncomment the line. If you are using nano, you can quickly search for words in the file by pressing <span class="highlight">CTRL</span> and <span class="highlight">w</span>.</p>
                <code class="language-shell">
                ...<br>
                http {<br>
                <span class="invisible">tab</span>...<br>
                <span class="invisible">tab</span>server_name_hash_bucket_size 64:<br>
                <span class="invisible">tab</span>...<br>
                }<br>
                ...
                </code>
                <p class="language-html">Save and close the file when you are finished.</p>
                <p class="language-html">Next, test to make sure that there are no syntax errors in any of your Nginx files:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nginx <span class="flag">-t</span>
                </code>
                <p class="language-html">If there aren’t any problems, restart Nginx to enable your changes:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl restart nginx
                </code>
                <p class="language-html">Nginx should now be serving your domain name. You can test this by navigating to <strong>http://your_domain</strong>, where you should see something like this:</p>
                <img class="img-fluid" src="https://assets.digitalocean.com/articles/nginx_server_block_1404/first_block.png">
                <hr class="separator">
                <h3 class="language__subtitle">Step 6 – Getting Familiar with Important Nginx Files and Directories</h3>
                <p class="language-html">Now that you know how to manage the Nginx service itself, you should take a few minutes to familiarize yourself with a few important directories and files.</p>
                <h5 class="language__heading5">Content</h5>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/var/www/html</strong>: The actual web content, which by default only consists of the default Nginx page you saw earlier, is served out of the <strong>/var/www/html</strong> directory. This can be changed by altering Nginx configuration files.</p>
                <h5 class="language__heading5">Server Configuration</h5>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/nginx</strong>: The Nginx configuration directory. All of the Nginx configuration files reside here.</p>  
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/nginx/nginx.conf</strong>: The main Nginx configuration file. This can be modified to make changes to the Nginx global configuration.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/nginx/sites-available/</strong>: The directory where per-site server blocks can be stored. Nginx will not use the configuration files found in this directory unless they are linked to the <strong>sites-enabled</strong> directory. Typically, all server block configuration is done in this directory, and then enabled by linking to the other directory.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/nginx/sites-enabled/</strong>: The directory where enabled per-site server blocks are stored. Typically, these are created by linking to configuration files found in the <strong>sites-available</strong> directory.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/nginx/snippets</strong>: This directory contains configuration fragments that can be included elsewhere in the Nginx configuration. Potentially repeatable configuration segments are good  candidates for refactoring into snippets.</p>
                <h5 class="language__heading5">Server Logs</h5>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/var/log/nginx/access.log</strong>: Every request to your web server is recorded in this log file unless Nginx is configured to do otherwise.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/var/log/nginx/error.log</strong>: Any Nginx errors will be recorded in this log.</p>
            </div>
        EOT;

        // How to Install Latest Node.js and NPM on Ubuntu with PPA
        $content4 = <<<EOT
            <div class="article__content">
                <h2 class="language__title">How to Install Latest Node.js and NPM on Ubuntu with PPA</h2>
                <h3 class="language__subtitle">Step 1 – Add Node.js PPA</h3>
                <p class="language-html">Node.js package is available in the LTS release and the current release. It’s your choice to select which version you want to install on the system as per your requirements. Let’s add the PPA to your system to install Nodejs on Ubuntu.<br><br>
                <strong>Use Current Release:</strong> At the last update of this tutorial, Node.js 14 is the current Node.js release available.</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt-get install <span class="sudo">curl</span><br>
                <span class="sudo">curl</span> <span class="flag">-sL</span> https://deb.nodesource.com/setup_14.x | <span class="sudo">sudo</span> <span class="flag">-E</span> <span class="sudo">bash</span> <span class="flag">-</span>
                </code>
                <p class="language-html"><strong>Use LTS Release :</strong> At the last update of this tutorial, Node.js 12.x is the LTS release available.</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt-get install <span class="sudo">curl</span><br>
                <span class="sudo">curl</span> <span class="flag">-sL</span> https://deb.nodesource.com/setup_12.x | <span class="sudo">sudo</span> <span class="flag">-E</span> <span class="sudo">bash</span> <span class="flag">-</span>
                </code>
                <p class="language-html">For this tutorial, <strong>I am using the latest current release</strong> and added their PPA to my system.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 2 – Install Node.js on Ubuntu</h3>
                <p class="language-html">You can successfully add Node.js PPA to the Ubuntu system. Now execute the below command will install Node on Ubuntu using apt-get. This will also install NPM with node.js. This command also installs many other dependent packages on your system.</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt-get install nodejs<br>
                <span class="sudo">curl</span> <span class="flag">-sL</span> https://deb.nodesource.com/setup_14.x | <span class="sudo">sudo</span> <span class="flag">-E</span> <span class="sudo">bash</span> <span class="flag">-</span>
                </code>
                <hr class="separator">
                <h3 class="language__subtitle">Step 3 – Check Node.js and NPM Version</h3>
                <p class="language-html">After installing node.js verify and check the installed version. You can find more details about current version on node.js <a class="in-bl" href="https://nodejs.org/download/">Official website</a>.</p>
                <code class="language-shell">
                <span class="sudo">node</span> <span class="flag">-v</span><br>
                v14.13.1
                </code>
                <p class="language-html">Also, check the npm version</p>
                <code class="language-shell">
                <span class="sudo">npm</span> <span class="flag">-v</span><br>
                6.14.8
                </code>
                <hr class="separator">
                <h3 class="language__subtitle">Step 4 – Create Demo Web Server (Optional)</h3>
                <p class="language-html">This is an optional step. If you want to test your node.js install. Let’s create a web server with “Hello World!” text. Create a file <strong>server.js</strong></p>
                <code class="language-shell">
                <span class="sudo">vim</span> server.js
                </code>
                <p class="language-html">and add the following content to the file server.js</p>
                <code class="language-shell" lang="javascript">
                <span class="flag">var</span> http = <span class="var">require</span>(<span class="var__string">"http"</span>);<br>
                http.<span class="var">createServer</span>((<span class="var__param">req, res</span>) <span class="var__function">=></span> {<br>
                <span class="invisible">tab</span> res.<span class="var">writeHead</span>(<span class="var__param">200</span>, {<span class="var__string">"Content-Type": "text/plain"</span>});<br>
                <span class="invisible">tab</span> res.<span class="var">end</span>(<span class="var__string">"Hello World\n"</span>);<br>
                }).<span class="var">listen</span>(<span class="var__param">3000</span>, <span class="var__string">"127.0.0.1"</span>);<br>
                <span class="flag">console</span>.<span class="var">log</span>(<span class="var__string">"Server running at http://127.0.0.1:3000/"</span>);
                </code>
                <p class="language-html">Now start the Node application using the command.</p>
                <code class="language-shell">
                <span class="sudo">node</span> server.js<br><br>
                debugger listening on port 5858<br>
                Server running at http://127.0.0.1:3000/
                </code>
                <p class="language-html">You can also start the application with debugging enabled with the following commands.</p>
                <code class="language-shell">
                <span class="sudo">node</span> <span class="flag">--inspect</span><br><br>
                Debugger listening on ws://127.0.0.1:9229/938cf97a-a9e6-4672-922d-a22479ce4e29<br>
                For help, see: https://nodejs.org/en/docs/inspector<br>
                Server running at http://127.0.0.1:3000/
                </code>
                <p class="language-html">The web server has been started on port 3000. Now access <strong>http://127.0.0.1:3000/</strong> URL in your browser. Now you will need to configure a front-end server for your app.</p>
            </div>
        EOT;

        // How to install PgAdmin4 on Ubuntu 20.04 Foca Fossa
        $content5 = <<<EOT
                <div class="article__content">
                    <h2 class="language__title">How to install PgAdmin4 on Ubuntu 20.04 Foca Fossa</h2>
                    <h3 class="language__subtitle">Update the system</h3>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt-get update<br>
                    </code>
                    <h3 class="language__subtitle">Install required packages</h3>
                    <p class="language-html">Three packages require to install before downloading pgAdmin which are <strong>python, pip and virtualenv</strong>. Run the following command to install these packages.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt-get install build-essential libssl-dev libffi-dev libgmp3-dev<br>
                    <span class="sudo">sudo</span> apt-get install python3-virtualenv libpq-dev python3-dev
                    </code>
                    <h3 class="language__subtitle">Create virtual environment</h3>
                    <p class="language-html">Run the following commands to create a new folder named <strong>pgAdmin4</strong> in the current location, go to the newly created folder and create the virtual environment.</p>
                    <code class="language-shell">
                    <span class="sudo">mkdir</span> pgAdmin4<br>
                    <span class="sudo">cd</span> pgAdmin4<br>
                    virtualenv pgAdmin4
                    </code>
                    <h3 class="language__subtitle">Activate virtual environment</h3>
                    <p class="language-html">Go to <strong>pgAdmin4</strong> folder under pgAdmin4 and run the following commands to activate the virtual environment.</p>
                    <code class="language-shell">
                    <span class="sudo">cd</span> pgAdmin4<br>
                    source bin/activate
                    </code>
                    <h3 class="language__subtitle">Download pgAdmin 4</h3>
                    <p class="language-html">Run the following command to download the latest version of pgAdmin 4.</p>
                    <code class="language-shell">
                    <span class="sudo">wget</span> https://ftp.postgresql.org/pub/pgadmin/pgadmin4/v4.20/pip/pgadmin4-4.20-py2.py3-none-any.whl
                    </code>
                    <h3 class="language__subtitle">Install pgAdmin 4</h3>
                    <p class="language-html">Run the following command to complete the installation process of pgAdmin 4.</p>
                    <code class="language-shell">
                    <span class="sudo">pip</span> install pgadmin4-4.20-py2.py3-none-any.whl
                    </code>
                    <h3 class="language__subtitle">Configure and run pgAdmin 4</h3>
                    <p class="language-html">After completing the installation steps, you have to create a configuration file to run this software. Create a new file named <strong>config_local.py</strong> in <strong>lib/python3.8/site-packages/pgadmin4/</strong> folder using nano editor.
                    <code class="language-shell">
                    <span class="sudo">nano</span> lib/python3.8/site-packages/pgadmin4/config_local.py
                    </code>
                    <p class="language-html">Add the following content in <strong>config_local.py</strong>.</p>
                    <code class="language-shell">
                    import os<br>
                    DATA_DIR = os.path.realpath(os.path.expanduser(u<span class="var__string">"~/.pgadmin/"</span>))<br>
                    LOG_FILE = os.path.join(DATA_DIR, <span class="var__string">"pgadmin4.log"</span>)<br>
                    SQLITE_PATH = os.path.join(DATA_DIR, <span class="var__string">"pgadmin4.db"</span>)<br>
                    SESSION_DB_PATH = os.path.join(DATA_DIR, <span class="var__string">"sessions"</span>)<br>
                    STORAGE_DIR = os.path.join(DATA_DIR, <span class="var__string">"storage"</span>)<br>
                    SERVER_MODE = False
                    </code>
                    <p class="language-html">Now, use the following command to run pgAdmin.</p>
                    <code class="language-shell">
                    <span class="sudo">python</span> lib/python3.8/site-packages/pgadmin4/pgAdmin4.py
                    </code>
                    <p class="language-html">Now, access http://localhost:5050/ from any browser. If all the steps are completed properly then the browser will display the administration and development platform for the PostgreSQL database.</p>
                </div>
            EOT;

        // How to install PostgreSQL on ubuntu 20.04 LTS
        $content6 = <<<EOT
                <div class="article__content">
                    <h2 class="language__title">How to install PostgreSQL on ubuntu 20.04 LTS</h2>
                    <h3 class="language__subtitle">Step 1 – Enable PostgreSQL Apt Repository</h3>
                    <p class="language-html">PostgreSQL packages are also available in default Ubuntu repository. So you need to add PostgreSQL apt repository to your system suggested on official <a class="in-bl" href="https://www.postgresql.org/download/linux/ubuntu/">PostgreSQL website</a> using following command.</p>
                    <p class="language-html">Start with the import of the GPG key for PostgreSQL packages.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt-get install <span class="sudo">wget</span> ca-certificates<br>
                    <span class="sudo">wget</span> <span class="flag">--quiet -O - </span>https://www.postgresql.org/media/keys/ACCC4CF8.asc | <span class="sudo">sudo</span> apt-key add <span class="flag">-</span>
                    </code>
                    <p class="language-html">Now add the repository to your system.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo sh</span> <span class="flag">-c</span> <span class="var__string">"echo "deb http://apt.postgresql.org/pub/repos/apt/ "lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list"</span><br>
                    </code>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step 2 – Install PostgreSQL on Ubuntu</h3>
                    <p class="language-html">Now as we have added PostgreSQL official repository in our system, First we need to update the repository list. After that install Latest PostgreSQL Server in our Ubuntu system using the following commands.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt-get update<br>
                    <span class="sudo">sudo</span> apt-get install postgresql postgresql-contrib
                    </code>
                    <p class="language-html">Multiple other dependencies will also be installed. PostgreSQL 12 is the latest available version during the last update of this tutorial.</p>
                    <h3 class="language__subtitle">Creating PostgreSQL users</h3>
                    <p class="language-html">A default PostgresSQL installation always includes the <strong>postgres</strong> superuser. Initially, you must connect to PostgreSQL as the <strong>user</strong> until you create other users (which are also referred to as <em>roles</em>).</p>
                    <p class="language-html">To create a PostgreSQL user, follow these steps:</p>
                    <p class="language-html"><span class="invisible">tab</span>1 - At the command line, type the following command as the server’s root user:</p>
                    <code class="language-shell ml-2-rem">
                    <span class="sudo">su</span> <span class="flag">- </span>postgres
                    </code>
                    <p class="language-html"><span class="invisible">tab</span>2 - You can now run commands as the PostgreSQL superuser. To create a user, type the following command:</p>
                    <code class="language-shell ml-2-rem">
                    createuser <span class="flag">--interactive --pwprompt</span>
                    </code>
                    <p class="language-html"><span class="invisible">tab</span>3 - At the Enter name of role to add: prompt, type the user’s name.</p>
                    <p class="language-html"><span class="invisible">tab</span>4 - At the Enter password for new role: prompt, type a password for the user.</p>
                    <p class="language-html"><span class="invisible">tab</span>5 - At the Enter it again: prompt, retype the password.</p>
                    <p class="language-html"><span class="invisible">tab</span>6 - At the Shall the new role be a superuser? prompt, type <span class="highlight">y</span> if you want to grant superuser access. Otherwise, type <span class="highlight">n</span>.</p>
                    <p class="language-html"><span class="invisible">tab</span>7 - At the Shall the new role be allowed to create databases? prompt, type <span class="highlight">y</span> if you want to allow the user to create new databases. Otherwise, type <span class="highlight">n</span>.</p>
                    <p class="language-html"><span class="invisible">tab</span>8 - At the Shall the new role be allowed to create more new roles? prompt, type <span class="highlight">y</span> if you want to allow the user to create new users. Otherwise, type <span class="highlight">n</span>.</p>
                    <p class="language-html"><span class="invisible">tab</span>9 - PostgreSQL creates the user with the settings you specified.</p>
                    <h3 class="language__subtitle">Creating PostgreSQL databases</h3>
                    <p class="language-html">To create a PostgreSQL database, follow these steps:</p>
                    <p class="language-html"><span class="invisible">tab</span>1 - At the command line, type the following command as the server’s root user:</p>
                    <code class="language-shell ml-2-rem">
                    <span class="sudo">su</span> <span class="flag">- </span>postgres
                    </code>
                    <p class="language-html"><span class="invisible">tab</span>2 - You can now run commands as the PostgreSQL superuser. To create a database, type the following command. Replace <strong>user</strong> with the name of the user that you want to own the database, and replace <strong>dbname</strong> with the name of the database that you want to create:</p>
                    <code class="language-shell ml-2-rem">
                    createdb <span class="flag">-O</span> user dbname
                    </code>
                    <p class="language-html">PostgreSQL users that have permission to create databases can do so from their own accounts by typing the following command, where <strong>dbname</strong> is the name of the database to create:</p>
                    <code class="language-shell">
                    createdb dbname
                    </code>
                    <h3 class="language__subtitle">Adding an existing user to a database</h3>
                    <p class="language-html">To grant an existing user privileges to a database, follow these steps:</p>
                    <p class="language-html"><span class="invisible">tab</span>1 - Run the <strong>psql</strong> program as the database’s owner, or as the <strong>postgres</strong> superuser.</p>
                    <p class="language-html"><span class="invisible">tab</span>2 - Type the following command. Replace <strong>permissions</strong> with the permissions you want to grant, <strong>dbname</strong> with the name of the database, and <strong>username</strong> with the user:</p>
                    <code class="language-shell ml-2-rem">
                    GRANT permissions ON DATABASE dbname TO username;
                    </code>
                    <p class="language-html"><span class="invisible">tab</span>For detailed information about the access privileges that are available in PostgreSQL, please visit <a class="in-bl" href="https://www.postgresql.org/docs/9.1/static/sql-grant.html">PostgreSQL website</a>.</p>
                    <p class="language-html"><span class="invisible">tab</span>3 - The user can now access the database with the specified permissions.</p>
                    <h3 class="language__subtitle">Deleting PostgreSQL databases</h3>
                    <p class="language-html">Similar to the <strong>createdb</strong> command for creating databases, there is the <strong>dropdb</strong> command for deleting databases. To delete a database, you must be the owner or have superuser privileges.</p>
                    <p class="language-html">Type the following command, replacing <strong>dbname</strong> with the name of the database that you want to delete:</p>
                    <code class="language-shell">
                    dropdb dbname
                    </code>
                </div>
            EOT;


        // How To Set Up Apache Virtual Hosts on Ubuntu 20.04
        $content7 = <<<EOT
                <div class="article__content">
                    <h3 class="language__subtitle">Introduction</h3>
                    <p class="language-html">The Apache web server is a popular method for serving websites on the internet. As of 2019, it is estimated to serve 29% of all active  websites and it offers robustness and flexibility for developers. Using  Apache, an administrator can set up one server to host multiple domains  or sites off of a single interface or IP by using a matching system.</p>
                    <p class="language-html">Each domain or individual site — known as a <strong>virtual host</strong> — that is configured using Apache will direct the visitor to a specific directory  holding that site’s information. This is done without indicating that  the same server is also responsible for other sites. This scheme is  expandable without any software limit as long as your server can handle  the load. The basic unit that describes an individual site or domain is  called a virtual hostIn this guide, we will walk you through how to set up Apache virtual  hosts on an Ubuntu 20.04 server. During this process, you’ll learn how  to serve different content to different visitors depending on which  domains they are requesting.</p>
                    <h3 class="language__subtitle">Prerequisites</h3>
                    <p class="language-html">Before you begin this tutorial, you should <a class="in-bl" href="https://slashflex.io/blog/post/initial-server-setup-with-ubuntu-20-04">create a non-root user</a>.</p>
                    <p class="language-html">
                    You will also need to have Apache installed in order to work through these steps. If you haven’t already done so, you can get Apache installed on your server through the <strong>apt</strong> package manager:</p> 
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt update<br>
                    <span class="sudo">sudo</span> apt install apache2
                    </code>
                    <p class="language-html">If you would like more detailed instructions as well as firewall setup, please refer to our guide <a class="in-bl" href="https://slashflex.io/blog/post/how-to-install-the-apache-web-server-on-ubuntu-20-04">How To Install the Apache Web Server on Ubuntu 20.04</a>.</p>
                    <p class="language-html">For the purposes of this guide, our configuration will make a virtual host for <strong>example.com</strong>. These will be referenced throughout the guide, but you should substitute your own domains or values while following along.</p>
                    <p class="language-html">We will show how to edit your local hosts file later on to test the configuration if you are using test values. This will allow you to  validate your configuration from your home computer, even though your  content won’t be available through the domain name to other visitors.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step One — Create the Directory Structure</h3>
                    <p class="language-html">The first step that we are going to take is to make a directory structure that will hold the site data that we will be serving to  visitors.</p>
                    <p class="language-html">Our <strong>document root</strong> (the top-level directory that Apache looks at to find content to serve) will be set to individual directories under the <strong>/var/www</strong> directory.  We will create a directory here for the virtual host we plan on making.</p>
                    <p class="language-html">Within this directory, we will create a <strong>public_html</strong> folder that will hold our actual files.  This gives us some flexibility in our hosting.</p>
                    <p class="language-html">For instance, for our site, we’re going to make our directory as follows. If you are using actual domain or alternate values, swap out the highlighted text for this.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> mkdir <span class="flag">-p</span> /var/www/example.com/public_html
                    </code>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step Two — Grant Permissions</h3>
                    <p class="language-html">Now we have the directory structure for our files, but they are owned by our root user.  If we want our regular user to be able to modify  files in our web directories, we can change the ownership by doing this:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> chown <span class="flag">-R</span> $user:$user /var/www/example.com/public_html
                    </code>
                    <p class="language-html">The <strong>$user</strong> variable will take the value of the user you are currently logged in as when you press <span class="highlight">ENTER</span>. By doing this, our regular user now owns the <strong>public_html</strong> subdirectories where we will be storing our content.</p>
                    <p class="language-html">We should also modify our permissions to ensure that read access is permitted to the general web directory and all of the files and folders  it contains so that pages can be served correctly:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> chmod <span class="flag">-R</span> 755 /var/www
                    </code>
                    <p class="language-html">Your web server should now have the permissions it needs to serve content, and your user should be able to create content within the  necessary folders.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step Three — Create a Demo Page for the Virtual Host</h3>
                    <p class="language-html">We now have our directory structure in place. Let’s create some content to serve.</p>
                    <p class="language-html">For demonstration purposes, we’ll make an <strong>index.html</strong> page this site.</p>
                    <p class="language-html">Let’s begin with <strong>example.com</strong>. We can open up an <strong>index.html</strong> file in a text editor, in this case we’ll use nano:</p>
                    <code class="language-shell">
                    <span class="sudo">nano</span> /var/www/example.com/public_html/index.html
                    </code>
                    <p class="language-html">Within this file, create an HTML document that indicates the site it is connected to, like the following:</p>
                    <img class="img-fluid" src="https://slashflex.io/build/images/index-html.webp" alt="html page">
                    <p class="language-html">Save and close the file (in nano, press <span class="highlight">CTRL</span> + <span class="highlight">X</span> then <span class="highlight">Y</span> then <span class="highlight">ENTER</span>) when you are finished.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step Four — Create New Virtual Host Files</h3>
                    <p class="language-html">Virtual host files are the files that specify the actual configuration of our virtual hosts and dictate how the Apache web server will respond to various domain requests.</p>
                    <p class="language-html">Apache comes with a default virtual host file called <strong>000-default.conf</strong> that we can use as a jumping off point. We are going to copy it over to create a virtual host file for our domain.</p>
                    <p class="language-html">We will start with one domain, configure it and then make the few further adjustments needed. The default Ubuntu configuration requires that each virtual host file end in <strong>.conf</strong>.</p>
                    <p class="language-html">Start by copying the default file for the first domain:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/example.com.conf
                    </code>
                    <p class="language-html">Open the new file in your editor with root privileges:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> nano /etc/apache2/sites-available/example.com.conf
                    </code>
                    <p class="language-html">With comments removed, the file will look similar to this:</p>
                    <img class="img-fluid" src="https://slashflex.io/build/images/v-host.webp" alt="basic virtual host configuration">
                    <p class="language-html">Within this file, we will customize the items for our first domain and add some additional directives. This virtual host section matches <em>any</em> requests that are made on port 80, the default HTTP port.</p>
                    <p class="language-html">First, we need to change the <strong>ServerAdmin</strong> directive to an email that the site administrator can receive emails through.</p>
                    <code class="language-shell">
                    ServerAdmin admin@example.com
                    </code>
                    <p class="language-html">After this, we need to <em>add</em> two directives. The first, called <strong>ServerName</strong>, establishes the base domain that should match for this virtual host definition. This will most likely be your domain. The second, called <strong>ServerAlias</strong>, defines further names that should match as if they were the base name. This is useful for matching hosts you defined, like <strong>www</strong>:</p>
                    <code class="language-shell">
                    ServerName example.com<br>
                    ServerAlias www.example.com
                    </code>
                    <p class="language-html">The only other thing we need to change for our virtual host file is the location of the document root for this domain. We already created the directory we need, so we just need to alter the <strong>DocumentRoot</strong> directive to reflect the directory we created:</p>
                    <code class="language-shell">
                    DocumentRoot /var/www/example.com/public_html
                    </code>
                    <p class="language-html">At this point, save and close the file.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step Five — Enable the New Virtual Host Files</h3>
                    <p class="language-html">Now that we have created our virtual host files, we must enable them. Apache includes some tools that allow us to do this.</p>
                    <p class="language-html">We’ll be using the <strong>a2ensite</strong> tool to enable each of our sites. If you would like to read more about this script, you can refer to the <a class="in-bl" href="https://manpages.debian.org/jessie/apache2/a2ensite.8.en.html"><strong>a2ensite</strong> documentation</a>.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> a2ensite example.com.conf<br>
                    <span class="sudo">sudo</span> a2ensite test.com.conf
                    </code>
                    <p class="language-html">Next, disable the default site defined in <strong>000-default.conf</strong>:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> a2dissite 000-default.conf
                    </code>
                    <p class="language-html">When you are finished, you need to restart Apache to make these changes take effect and use <strong>systemctl status</strong> to verify the success of the restart.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> systemctl restart apache2<br>
                    <span class="sudo">sudo</span> systemctl status apache2
                    </code>
                    <p class="language-html">Your server should now be set up to serve your website.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step Six — Set Up Local Hosts File (Optional)</h3>
                    <p class="language-html">If you haven’t been using actual domain names that you own to test this procedure and have been using some example domains instead, you can at least test the functionality of this process by temporarily modifying the <strong>hosts</strong> file on your local computer.</p>
                    <p class="language-html">This will intercept any requests for the domains that you configured and point them to your VPS server, just as the DNS system would do if you were using registered domains. This will only work from your local  computer though, and only for testing purposes.</p>
                    <p class="language-html">Make sure you are operating on your local computer for these steps and not your VPS server. You will need to know the computer’s administrative password or otherwise be a member of the administrative group.</p>
                    <p class="language-html">If you are on a Mac or Linux computer, edit your local file with administrative privileges by typing:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> nano /etc/hosts
                    </code>
                    <p class="language-html">The details that you need to add are the public IP address of your server followed by the domain you want to use to reach that server.</p>
                    <p class="language-html">Using the domains used in this guide, and replacing your server IP for the <strong>your_server_IP</strong> text, your file should look like this:</p>
                    <code class="language-shell">
                    127.0.0.1 localhost<br>
                    127.0.1.1 guest-desktop<br>
                    your_server_IP example.com
                    </code>
                    <p class="language-html">Save and close the file.</p>
                    <p class="language-html">This will direct any requests for <strong>example.com</strong> on our computer and send them to our server. This is what we want if we are not actually the owners of this domain in order to test our  virtual host.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step Seven — Test your Results</h3>
                    <p class="language-html">Now that you have your virtual host configured, you can test your setup by going to the domain that you configured in your web browser:</p>
                    <code class="language-shell">
                    http://example.com
                    </code>
                    <p class="language-html">You should see a page that looks like this:</p>
                    <img class="img-fluid" src="https://slashflex.io/build/images/success-v-host.webp" alt="success page">
                    <p class="language-html">If this site work as expected, you’ve successfully configured a virtual host on a server.</p>
                    <p class="language-html">If you adjusted your home computer’s hosts file, you may want to delete the lines you added now that you verified that your configuration works. This will prevent your hosts file from being filled with entries that are no longer necessary.</p>
                    <p class="language-html">If you need to access this long term, consider adding a domain name for each site you need and <a class="in-bl" href="https://www.digitalocean.com/docs/networking/dns/">setting it up to point to your server</a>.</p>
                </div>
            EOT;

        // $introduction8 = "ZSH, also called the Z shell, is an extended version of the Bourne Shell (sh), with plenty of new features, and support for plugins and themes. Since it’s based on the same shell as Bash, ZSH has many of the same features, and switching over is a breeze.";

        // How to setup ZSH and Oh-my-zsh on Linux
        /*$content8 = <<<EOT
                <div class="article__content">
                    <h2 class="language__title">How to setup ZSH and Oh-my-zsh on Linux</h2>
                    <h3 class="language__subtitle">Introduction</h3>
                    <p class="language-html">$introduction8</p>
                    <h3 class="language__subtitle">Prerequisites</h3>
                    <ul>
                    <li><p class="language-html"><span class="invisible">tab</span>Linux - Ubuntu >= 16.04</p></li>
                    <li><p class="language-html"><span class="invisible">tab</span>Root privileges</p></li>
                    </ul>
                    <h3 class="language__subtitle">What we will do</h3>
                    <ul>
                    <li><p class="language-html"><span class="invisible">tab</span>1 - Install and configure <strong>ZSH</strong></p></li>
                    <li><p class="language-html"><span class="invisible">tab</span>2 - Install and configure <strong>Oh-my-zsh</strong> framework</p></li>
                    <li><p class="language-html"><span class="invisible">tab</span>3 - Change default theme</p></li>
                    <li><p class="language-html"><span class="invisible">tab</span>4 - Enable <strong>Oh-my-zsh</strong> plugins</p></li>
                    </ul>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step 1 - Install and configure ZSH</h3>
                    <p class="language-html">In this step, we will install the Z shell from the repository, and  then configure a user to use the Z shell as the default theme.  Basically, the default shell on Ubuntu and CentOS is bash, so we will  configure a root user to use zsh as the default shell.</p>
                    <p class="language-html">To install zsh from the repository, use the following commands.</p>
                    <p class="language-html">Linux - Ubuntu >= 16.04</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt install <span class="sudo">zsh</span>
                    </code>
                    <p class="language-html">After the installation is complete, change the default shell of the root user to zsh with the chsh command below.</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> chsh <span class="flag">-s</span> /usr/bin/zsh root
                    </code>
                    <p class="language-html">Now logout from the root user, log in again, and you will get the zsh shell.</p>
                    <p class="language-html">Check the current shell used with the command below.</p>
                    <code class="language-shell">
                    <span class="sudo">echo</span><span class="flag"> $shell</span>
                    </code>
                    <img src="https://slashflex.io/build/images/echo-shell.webp" class="img-fluid" alt="check current shell used">
                    <p class="language-html">The Z shell <strong>zsh</strong> has been installed.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step 2 - Install and configure Oh-my-zsh framework</h3>
                    <p class="language-html">So the Z shell is now installed on the system. Next, we want to  install the <strong>oh-my-zsh</strong> framework for managing the Z shell <strong>zsh</strong>. Oh-my-zsh provides an installer script for installing the framework, and we need to install some other required packages, including wget for downloading the installer script and Git for downloading <strong>oh-my-zsh</strong> shell from <strong>GitHub</strong>.</p>
                    <p class="language-html">So the first step is to install wget and git on the system. Here are the commands you need to run:</p>
                    <code class="language-shell">
                    <span class="sudo">sudo</span> apt install <span class="sudo">wget</span> <span class="sudo">git</span><br>
                    <span class="sudo">sudo</span> <span class="sudo">wget</span> https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh <span class="flag">-O -</span> | <span class="sudo">zsh</span>
                    </code>
                    <p class="language-html">The result/output should be similar to the one shown below.</p>
                    <img src="https://slashflex.io/build/images/oh-my-zsh.webp" class="img-fluid" alt="oh my zsh">
                    <p class="language-html">So, oh-my-zsh is installed in the home directory <strong>~/.oh-my-zsh</strong>.</p>
                    <p class="language-html">Next, we need to create a new configuration for zsh. As with the Bash shell, which has a configuration named <strong>.bashrc</strong>, for zsh, we need a <strong>.zshrc</strong> configuration file. It’s available in the oh-my-zsh templates directory.</p>
                    <p class="language-html">Copy the template <strong>.zshrc.zsh-template</strong> configuration file to the home directory <strong>.zshrc</strong> and apply the configuration by running the source command, as shown below.</p>
                    <code class="language-shell">
                    <span class="sudo">cp</span> ~/.oh-my-zsh/templates/zshrc.zsh-template ~/.zshrc source ~/.zshrc
                    </code>
                    <p class="language-html">Oh-my-zsh is now installed on the system, and the Z shell has been configured for using the oh-my-zsh framework with default configuration.</p>
                    <p class="language-html">The following result is on Ubuntu on my machine with a zsh theme (agnoster).</p>
                    <img src="https://slashflex.io/build/images/my-terminal.webp" class="img-fluid" alt="my oh my zsh terminal">
                    <hr class="separator">
                    <h3 class="language__subtitle">Step 3 - Change default themes</h3>
                    <p class="language-html">The default .zshrc configuration that’s provided by oh-my-zsh is using <strong>robbyrusell</strong> theme. In this step, we will edit the configuration and change the default theme.</p>
                    <p class="language-html">The Oh-my-zsh framework provides many themes for your zsh shell, head to the link below to take a look at the available options.</p>
                    <a class="in-bl" href="https://github.com/robbyrussell/oh-my-zsh/wiki/Themes" style="font-size: 1.4rem;">Oh my zsh themes</a>
                    <p class="language-html">Alternatively, you can go to the 'themes' directory and see the list of available themes.</p>
                    <code class="language-shell">
                    <span class="sudo">ls</span> <span class="flag">-a</span> ~/.oh-my-zsh/themes/
                    </code>
                    <img src="https://slashflex.io/build/images/oh-my-zsh-themes.webp" class="img-fluid" alt="oh my zsh themes list">
                    <p class="language-html">In order to change the default theme, we need to edit the .zshrc configuration file. Edit the configuration with the <a class="in-bl" href="https://www.howtoforge.com/vim-basics">vim</a> editor.</p>
                    <code class="language-shell">
                    <span class="sudo">vim</span> ~/.zshrc
                    </code>
                    <p class="language-html">Pick one zsh theme - let’s say <strong>agnoster</strong> theme (which i'm using).</p>
                    <p class="language-html">Then change the <strong>ZSH_THEME</strong> line 10 with <strong>agnoster</strong> theme as below.</p>
                    <code class="language-shell">
                    <span class="flag">ZSH_THEME</span>=<span class="string">'agnoster'</span>
                    </code>
                    <p class="language-html">Save and exit.</p>
                    <p class="language-html">Now, reload the configuration .zshrc and you will see that <strong>agnoster</strong> theme is currently used as your shell theme.</p>
                    <code class="language-shell">
                    <span class="sudo">source</span> ~/.zshrc
                    </code>
                    <p class="language-html">So this way, you can apply a new oh-my-zsh theme.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step 4 - Enable Oh-my-zsh plugins</h3>
                    <p class="language-html">Oh-my-zsh offers awesome plugins. There are a lot of plugins for our  environment, aimed at developers, system admins, and everyone else.</p>
                    <p class="language-html">Default plugins are in the <strong>plugins</strong> directory.</p>
                    <code class="language-shell">
                    <span class="sudo">ls</span> <span class="flag">-a</span> ~/.oh-my-zsh/plugins/
                    </code>
                    <img src="https://slashflex.io/build/images/oh-my-zsh-plugins.webp" class="img-fluid" alt="oh my zsh plugins list">
                    <p class="language-html">In this step, we will tweak zsh using the <strong>oh-my-zsh</strong> framework by enabling some plugins. In order to enable the plugins, we need to edit the .zshrc configuration file.</p>
                    <p class="language-html">Edit .zshrc configuration file.</p>
                    <code class="language-shell">
                    <span class="sudo">vim</span> ~/.zshrc<br>
                    </code>
                    <p class="language-html">Go to the <strong>plugins</strong> line and add some plugins that you want to enable inside the bracket ().</p>
                    <code class="language-shell">
                    <span class="flag">plugins</span>=(<br>
                        <span class="sudo"><span class="invisible">tab</span>git</span><br>
                        <span class="comment"><span class="invisible">tab</span># add other plugins here e.g: vscode, ruby etc..</span><br>
                    )
                    </code> 
                    <p class="language-html">To conclude, the Z shell, as well as the oh-my-zsh framework, have been installed. In addition, oh-my-zsh default theme has been changed with some plugins enabled.</p>
                    <hr class="separator">
                    <h3 class="language__subtitle">Step 5 - Enable Oh-my-zsh plugins</h3>  
                    <p class="language-html">To install <strong>zsh-autosuggestions</strong>:</p>
                    <ul>
                    <li>
                    <p class="language-html"><span class="invisible">tab</span>1 - Clone this repository into <strong>$zshPath</strong> (by default <strong>~/.oh-my-zsh/custom/plugins</strong>)</p>
                    <code class="language-shell">
                    <span class="sudo">git</span> clone https://github.com/zsh-users/zsh-autosuggestions <span class="flag">$zshCustom</span>/plugins/zsh-autosuggestions
                    </code>
                    </li>
                    <li>
                    <p class="language-html"><span class="invisible">tab</span>2 - Add the plugin to the list of plugins for Oh My Zsh to load (inside <strong>~/.zshrc</strong>):</p>
                    <code class="language-shell">
                    <span class="flag">plugins</span>=(zsh-autosuggestions)
                    </code>
                    </li>
                    <li>
                    <p class="language-html"><span class="invisible">tab</span>3 - Start a new terminal session.</p>
                    </li>
                    </ul>
                    <p class="language-html">To install <strong>zsh-autosuggestions</strong>:</p>
                    <ul>
                    <li>
                    <p class="language-html"><span class="invisible">tab</span>1 - Clone this repository in oh-my-zsh’s plugins directory:</p>
                    <code class="language-shell">
                    <span class="sudo">git</span> clone https://github.com/zsh-users/zsh-syntax-highlighting.git <span class="flag">$zshCustom</span>/plugins/zsh-syntax-highlighting
                    </code>
                    </li>
                    <li>
                    <p class="language-html"><span class="invisible">tab</span>2 - Activate the plugin in <strong>~/.zshrc</strong>:</p>
                    <span class="invisible">tab</span><code class="language-shell">
                    <span class="flag">plugins</span>=( [plugins...] zsh-syntax-highlighting)
                    </code>
                    </li>
                    <li>
                    <p class="language-html"><span class="invisible">tab</span>3 - Restart zsh (such as by opening a new instance of your terminal emulator).</p>
                    </li>
                    </ul>
                    <hr class="separator">
                    <h3 class="language__subtitle">References</h3>
                    <ul>
                    <li style="font-size: 1.4rem"><a class="in-bl" href="https://github.com/robbyrussell/oh-my-zsh/wiki" >https://github.com/robbyrussell/oh-my-zsh/wiki</a>
                    </li>
                    <li style="font-size: 1.4rem"><a class="in-bl" href="https://github.com/robbyrussell/oh-my-zsh/wiki/Themes" >https://github.com/robbyrussell/oh-my-zsh/wiki/Themes</a>
                    </li>
                    <li style="font-size: 1.4rem"><a class="in-bl" href="https://github.com/robbyrussell/oh-my-zsh/wiki" >https://github.com/robbyrussell/oh-my-zsh/wiki/Plugins</a>
                    </li>
                    <li style="font-size: 1.4rem"><a class="in-bl" href="https://github.com/zsh-users/zsh-autosuggestions" >https://github.com/zsh-users/zsh-autosuggestions</a> - <a class="in-bl" href="https://github.com/zsh-users/zsh-autosuggestions/blob/master/INSTALL.md" >install.md</a>
                    </li>
                    <li style="font-size: 1.4rem"><a class="in-bl" href="https://github.com/zsh-users/zsh-syntax-highlighting" >https://github.com/zsh-users/zsh-syntax-highlighting</a> - <a class="in-bl" href="https://github.com/zsh-users/zsh-syntax-highlighting/blob/master/INSTALL.md" >install.md</a>
                    </li>
                    </ul> 
                </div>
            EOT;*/
        // Initial Server Setup with Ubuntu 20.04
        $content9 = <<<EOT
            <div class="article__content">
                <h2 class="language__title">Initial Server Setup with Ubuntu 20.04</h2>
                <p class="language-html">When you first create a new Ubuntu 20.04 server, there are a few configuration steps that you should take early on as part of the basic setup. This will increase the security and usability of your server and will give you a solid foundation for subsequent actions.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 1 - Logging in as Root</h3>
                <p class="language-html">To log into your server, you will need to know your server’s public IP address. You will also need the password or, if you installed an SSH key for authentication, the private key for the root user’s account.</p>
                <p class="language-html">If you are not already connected to your server, go ahead and log in as the root user using the following command (substitute the highlighted portion of the command with your server’s public IP address):</p>
                <code class="language-shell">
                <span class="sudo">ssh</span> root@your_server_ip
                </code>
                <p class="language-html">Accept the warning about host authenticity if it appears. If you are using password authentication, provide your <strong>root</strong> password to log in. If you are using an SSH key that is passphrase protected, you may be prompted to enter the passphrase the first time you use the key each session. If this is your first time logging into the server with a password, you may also be prompted to change the <strong>root</strong> password.</p>
                <h3 class="language__subtitle">About Root</h3>
                <p class="language-html">The <strong>root</strong> user is the administrative user in a Linux environment that has very broad privileges. Because of the heightened privileges of the <strong>root</strong> account, you are discouraged from using it on a regular basis. This is because part of the power inherent with the <strong>root</strong> account is the ability to make very destructive changes, even by accident.</p>
                <p class="language-html">The next step is to set up an alternative user account with a reduced scope of influence for day-to-day work. We’ll teach you how to gain increased privileges during the times when you need them.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 2 - Creating a New User</h3>
                <p class="language-html">Once you are logged in as <strong>root</strong>, we’re prepared to add the new user account that we will use to log in from now on.</p>
                <p class="language-html">This example creates a new user called david, but you should replace it with a username that you like:</p>
                <code class="language-shell">
                <span class="sudo">adduser</span> david
                </code>
                <p class="language-html">You will be asked a few questions, starting with the account password.</p>
                <p class="language-html">Enter a strong password and, optionally, fill in any of the additional information if you would like. This is not required and you can just hit <span class="highlight">ENTER</span> in any field you wish to skip.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 3 - Granting Administrative Privileges</h3>
                <p class="language-html">Now, we have a new user account with regular account privileges. However, we may sometimes need to do administrative tasks.</p>
                <p class="language-html">To avoid having to log out of our normal user and log back in as the <strong>root</strong> account, we can set up what is known as <strong>superuser</strong> or <strong>root</strong> privileges for our normal account. This will allow our normal user to run commands with administrative privileges by putting the word sudo before each command.</p>
                <p class="language-html">To add these privileges to our new user, we need to add the new user to the <strong>sudo</strong> group. By default, on Ubuntu 20.04, users who belong to the <strong>sudo</strong> group are allowed to use the <span class="highlight">sudo</span> command.</p>
                <p class="language-html">As root, run this command to add your new user to the sudo group:</p>
                <code class="language-shell">
                <span class="sudo">usermod</span> <span class="flag">-aG</span> <span class="sudo">sudo </span> david
                </code>
                <p class="language-html">Now, when logged in as your regular user, you can type <span class="highlight">sudo</span> before commands to perform actions with superuser privileges.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 4 - Setting Up a Basic Firewall</h3>
                <p class="language-html">Ubuntu 20.04 servers can use the UFW firewall to make sure only connections to certain services are allowed. We can set up a basic firewall very easily using this application.</p>
                <p class="language-html">Different applications can register their profiles with UFW upon installation. These profiles allow UFW to manage these applications by name. OpenSSH, the service allowing us to connect to our server now, has a profile registered with UFW.</p>
                <p class="language-html">You can see this by typing:</p>
                <code class="language-shell">
                <span class="sudo">ufw</span> app list
                </code>
                <p class="language-html">The output should look like this:</p>
                <code class="language-shell">
                Available applications</br>
                <span class="invisible">tab</span>OpenSSH
                </code>
                <p class="language-html">Afterwards, we can enable the firewall by typing:</p>
                <code class="language-shell">
                <span class="sudo">ufw</span> enable
                </code>
                <p class="language-html">Type <span class="hightlight">y</span> and press <span class="hightlight">ENTER</span> to proceed. You can see that SSH connections are still allowed by typing:</p>
                <code class="language-shell">
                <span class="sudo">ufw</span> status
                </code>
                <code class="language-shell">
                    Status: active<br><br>
                    To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span><span class="invisible">tab</span>Action <span class="invisible">tab</span> <span class="invisible">tab</span>From<br>
                    --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>------<span class="invisible">tab</span><span class="invisible">tab</span> <span class="invisible">tab</span>----<br>
                    OpenSSH<span class="invisible">tab</span> <span class="invisible">tab</span><span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere<br>
                    OpenSSH (v6)<span class="invisible">tab</span><span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span>Anywhere (v6)<br>
                    </code>
                <p class="language-html">As <strong>the firewall is currently blocking all connections except for SSH</strong>, if you install and configure additional services, you will need to adjust the firewall settings to allow acceptable traffic in.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 5 - Enabling External Access for Your Regular User</h3>
                <p class="language-html">Now that we have a regular user for daily use, we need to make sure we can SSH into the account directly.</p>
                <p class="language-html">The process for configuring SSH access for your new user depends on whether your server’s <strong>root</strong> account uses a password or SSH keys for authentication.</p>
                <h4 class="language__subtitle">If the Root Account Uses Password Authentication</h4>
                <p class="language-html">If you logged in to your <strong>root</strong> account using a password, then password authentication is enabled for SSH. You can SSH to your new user account by opening up a new terminal session and using SSH with your new username:</p>
                <code class="language-shell">
                <span class="sudo">ssh</span> david@your_server_ip
                </code>
                <p class="language-html">After entering your regular user’s password, you will be logged in. Remember, if you need to run a command with administrative privileges, type <span class="hightlight">sudo</span> before it like this:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> command_to_run
                </code>
                <p class="language-html">You will be prompted for your regular user password when using <strong>sudo</strong> for the first time each session (and periodically afterwards).</p>
                <h4 class="language__subtitle">If the Root Account Uses SSH Key Authentication</h4>
                <p class="language-html">If you logged in to your <strong>root</strong> account using SSH keys, then password authentication is <i>disabled</i> for SSH. You will need to add a copy of your local public key to the new user’s <strong>~/.ssh/authorized_keys</strong> file to log in successfully.</p>
                <p class="language-html">Since your public key is already in the <strong>root</strong> account’s <strong>~/.ssh/authorized_keys</strong> file on the server, we can copy that file and directory structure to our new user account in our existing session.</p>
                <p class="language-html">The simplest way to copy the files with the correct ownership and permissions is with the <strong>rsync</strong> command. This will copy the <strong>root</strong> user’s <strong>.ssh</strong> directory, preserve the permissions, and modify the file owners, all in a single command. Make sure to change the highlighted portions of the command below to match your regular user’s name:</p>
                <code class="language-shell">
                <span class="sudo">rsync</span> <span class="flag">--archive --chown</span>=david:david ~/.ssh /home/david
                </code>
                <p class="language-html">Now, open up a new terminal session and using SSH with your new username:</p>
                <code class="language-shell">
                <span class="sudo">ssh</span> david@your_server_ip
                </code>
                <p class="language-html">You should be logged in to the new user account without using a password. Remember, if you need to run a command with administrative privileges, type <strong>sudo</strong> before it like this:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> command_to_run
                </code>
                <p class="language-html">You will be prompted for your regular user password when using <strong>sudo</strong> for the first time each session (and periodically afterwards).</p>
            </div>
        EOT;

        // How To Install the Apache Web Server on Ubuntu 20.04
        $content10 = <<<EOT
            <div class="article__content">
                <h2 class="language__title">How To Install the Apache Web Server on Ubuntu 20.04</h2>
                <h3 class="language__subtitle">Introduction</h3>
                <p class="language-html">The Apache HTTP server is the most widely-used web server in the world. It provides many powerful features including dynamically loadable modules, robust media support, and extensive integration with other popular software.</p>
                <p class="language-html">In this guide, i’ll explain how to install an Apache web server on your Ubuntu 20.04 server.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Prerequisites</h3>
                <p class="language-html">Before you begin this guide, you should have a regular, non-root user with sudo privileges configured on your server. Additionally, you will need to enable a basic firewall to block non-essential ports. You can learn how to configure a regular user account and set up a firewall for your server by following <a class="in-bl" href="https://slashflex.io/blog/post/initial-server-setup-with-ubuntu-20-04">Initial server setup guide for Ubuntu 20.04</a>.</p>
                <p class="language-html">When you have an account available, log in as your non-root user to begin.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 1 – Installing Apache</h3>
                <p class="language-html">Apache is available within Ubuntu’s default software repositories, making it possible to install it using conventional package management tools.</p>
                <p class="language-html">Let’s begin by updating the local package index to reflect the latest upstream changes:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt update
                </code>
                <p class="language-html">Then, install the <strong>apache2</strong> package:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apt install apache2
                </code>
                <p class="language-html">After confirming the installation, <strong>apt</strong> will install Apache and all required dependencies.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 2 - Adjusting the Firewall</h3>
                <p class="language-html">Before testing Apache, it’s necessary to modify the firewall settings to allow outside access to the default web ports. Assuming that you followed the instructions in the prerequisites, you should have a UFW firewall configured to restrict access to your server.</p>
                <p class="language-html">During installation, Apache registers itself with UFW to provide a few application profiles that can be used to enable or disable access to Apache through the firewall.</p>
                <p class="language-html">List the <strong>ufw</strong> application profiles by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw app list
                </code>
                <p class="language-html">You will receive a list of the application profiles:</p>
                <code class="language-shell">
                Available applications:<br>
                <span class="invisible">tab</span>Apache<br>
                <span class="invisible">tab</span>Apache Full<br>
                <span class="invisible">tab</span>Apache Secure<br>
                <span class="invisible">tab</span>OpenSSH
                </code>
                <p class="language-html">As indicated by the output, there are three profiles available for Apache:</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>Apache</strong>: This profile opens only port 80 (normal, unencrypted web traffic)</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>Apache Full</strong>: This profile opens both port 80 (normal, unencrypted web traffic) and port 443 (TLS/SSL encrypted traffic)</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>Apache Secure</strong>: This profile opens only port 443 (TLS/SSL encrypted traffic)</p>
                <p class="language-html">It is recommended that you enable the most restrictive profile that will still allow the traffic you’ve configured. Since we haven’t configured SSL for our server yet in this guide, we will only need to allow traffic on port 80:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> ufw status
                </code>
                <code class="language-shell">
                Status: active<br><br>
                To<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> Action <span class="invisible">tab</span><span class="invisible">tab</span>From<br>
                --<span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> ------ <span class="invisible">tab</span><span class="invisible">tab</span>----<br>
                OpenSSH<span class="invisible">tab</span><span class="invisible">tab</span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                Apache<span class="invisible">tab</span> <span class="invisible"> tab </span> <span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere<br>
                OpenSSH (v6)<span class="invisible">tab</span><span class="invisible">tab</span>ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                Apache (v6)<span class="invisible">tab</span><span class="invisible">tab</span> ALLOW<span class="invisible">tab</span> <span class="invisible">tab</span> Anywhere (v6)<br>
                </code>
                <p class="language-html">As indicated by the output, the profile has been activated to allow access to the Apache web server.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 3 – Checking your Web Server</h3>
                <p class="language-html">Check with the systemd init system to make sure the service is running by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl status apache2
                </code>
                <code class="language-shell">
                <span class="sudo">●</span> apache2.service - The Apache HTTP Server<br>
                <span class="invisible">tab</span>Loaded: loaded (/lib/systemd/system/apache2.service; enabled; vendor preset: enabled)<br>
                <span class="invisible">tab</span>Active: <span class="sudo">active (running)</span> since Fri 2020-04-20 16:08:19 UTC; 3 days ago<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>Docs: https://httpd.apache.org/docs/2.4/<br>
                <span class="invisible">tab</span>Main PID: 29435 (apache2)<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span>Tasks: 55 (limit: 1137)<br>
                <span class="invisible">tab</span>Memory: 8.0M<br>
                <span class="invisible">tab</span>CGroup: /system.slice/apache2.service<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>├─29435 /usr/sbin/apache2 -k start<br> 
                <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>└─29437 /usr/sbin/apache2 -k start<br>
                <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span> <span class="invisible">tab</span>└─29438 /usr/sbin/apache2 -k start  
                </code>
                <p class="language-html">As confirmed by this output, the service has started successfully. However, the best way to test this is to request a page from Apache.</p>
                <p class="language-html">You can access the default Apache landing page to confirm that the software is running properly through your IP address. If you do not know your server’s IP address, you can get it a few different ways from the command line.</p>
                <p class="language-html">Try typing this at your server’s command prompt:</p>
                <code class="language-shell">
                <span class="sudo">hostname</span> <span class="flag">-I</span>
                </code>
                <p class="language-html">You will get back a few addresses separated by spaces. You can try each in your web browser to determine if they work.</p>
                <p class="language-html">Another option is to use the Icanhazip tool, which should give you your public IP address as read from another location on the internet:</p>
                <code class="language-shell">
                <span class="sudo">curl</span> <span class="flag">-4</span> icanhazip.com
                </code>
                <p class="language-html">When you have your server’s IP address, enter it into your browser’s address bar:</p>
                <code class="language-shell">
                http://your_server_ip
                </code>
                <p class="language-html">You should see the default Ubuntu 20.04 Apache web page:</p>
                <img class="w-50 img-fluid" src="https://slashflex.io/build/images/apache_default.webp" alt="apache default age">
                <p class="language-html">This page indicates that Apache is working correctly. It also includes some basic information about important Apache files and directory locations.</p>
                <hr class="separator">
                <h3 class="language__subtitle">Step 4 - Managing the Apache Process</h3>
                <p class="language-html">Now that you have your web server up and running, let’s go over some basic management commands using <strong>systemctl</strong>.</p>
                <p class="language-html">To stop your web server, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl stop apache2
                </code>
                <p class="language-html">To start the web server when it is stopped, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl start apache2
                </code>
                <p class="language-html">To stop and then start the service again, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl restart apache2
                </code>
                <p class="language-html">If you are simply making configuration changes, Apache can often reload without dropping connections. To do this, use this command:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl reload apache2
                </code>
                <p class="language-html">By default, Apache is configured to start automatically when the server boots. If this is not what you want, disable this behavior by typing:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl disable apache2
                </code>
                <p class="language-html">To re-enable the service to start up at boot, type:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl enable apache2
                </code>
                <p class="language-html">Apache should now start automatically when the server boots again.</p>
                <hr class="separator">
                <h3 class="language__subtitle" id="step-5-setting-up-virtual-hosts-recommended">Step 5 - Setting Up Virtual Hosts (Recommended)</h3>
                <p class="language-html">When using the Apache web server, you can use virtual hosts (similar to server blocks in Nginx) to encapsulate configuration details and host more than one domain from a single server. We will set up a domain called <strong>your_domain</strong>, but you should <strong>replace this with your own domain name</strong>.</p>
                <p class="language-html">Apache on Ubuntu 20.04 has one server block enabled by default that is configured to serve documents from the <strong>/var/www/html</strong> directory. While this works well for a single site, it can become unwieldy if you are hosting multiple sites. Instead of modifying <strong>/var/www/html</strong>, let’s create a directory structure within <strong>/var/www</strong> for a <strong>your_domain</strong> site, leaving <strong>/var/www/html</strong> in place as the default directory to be served if a client request doesn’t match any other sites.</p>
                <p class="language-html">Create the directory for <strong>your_domain</strong> as follows:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> mkdir /var/www/your_domain
                </code>
                <p class="language-html">Next, assign ownership of the directory with the <strong>$user</strong> environment variable:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> chmod <span class="flag">-R</span> 755 /var/www/your_domain
                </code>
                <p class="language-html">Next, create a sample <strong>index.html</strong> page using nano or your favorite editor:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nano /var/www/your_domain/index.html
                </code>
                <p class="language-html">Inside, add the following sample HTML:</p>
                <img class="img-fluid" src="https://slashflex.io/build/images/index-html.webp" alt="basic html page">
                <p class="language-html">Save and close the file when you are finished.</p>
                <p class="language-html">In order for Apache to serve this content, it’s necessary to create a virtual host file with the correct directives. Instead of modifying the default configuration file located at <strong>/etc/apache2/sites-available/000-default.conf</strong> directly, let’s make a new one at <strong>/etc/apache2/sites-available/your_domain.conf</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> nano /etc/apache2/sites-available/your_domain.conf
                </code>
                <p class="language-html">Paste in the following configuration block, which is similar to the default, but updated for our new directory and domain name:</p>
                <img class="img-fluid" src="https://slashflex.io/build/images/v-host.webp" alt="basic virtual host">
                <p class="language-html">Notice that we’ve updated the <strong>DocumentRoot</strong> to our new directory and <strong>ServerAdmin</strong> to an email that the <strong>your_domain</strong> site administrator can access. We’ve also added two directives: <strong>ServerName</strong>, which establishes the base domain that should match for this virtual host definition, and <strong>ServerAlias</strong>, which defines further names that should match as if they were the base name.</p>
                <p class="language-html">Save and close the file when you are finished.</p>
                <p class="language-html">Let’s enable the file with the <strong>a2ensite</strong> tool:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> a2ensite your_domain.conf
                </code>
                <p class="language-html">Disable the default site defined in <strong>000-default.conf</strong>:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> a2dissite 000-default.conf
                </code>
                <p class="language-html">Next, let’s test for configuration errors:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> apache2ctl configtest
                </code>
                <p class="language-html">You should receive the following output:</p>
                <code class="language-shell">
                Syntax OK
                </code>
                <p class="language-html">Restart Apache to implement your changes:</p>
                <code class="language-shell">
                <span class="sudo">sudo</span> systemctl restart apache2
                </code>
                <p class="language-html">Apache should now be serving your domain name. You can test this by navigating to <strong>http://your_domain</strong>, where you should see something like this:</p>
                <img class="img-fluid" src="https://slashflex.io/build/images/success-v-host.webp" alt="virtual host successfully setup">
                <hr class="separator">
                <h3 class="language__subtitle">Step 6 - Getting Familiar with Important Apache Files and Directories</h3>
                <p class="language-html">Now that you know how to manage the Apache service itself, you should take a few minutes to familiarize yourself with a few important directories and files.</p>
                <h5 class="language__subtitle">Content</h5>
                <p class="language-html"><strong>/var/www/html</strong>: The actual web content, which by default only consists of the default Apache page you saw earlier, is served out of the <strong>/var/www/html</strong> directory. This can be changed by altering Apache configuration files.</p>
                <h5 class="language__subtitle">Server Configuration</h5>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/apache2.conf</strong>: </br>The main Apache configuration file. This can be modified to make changes to the Apache global configuration. This file is responsible for loading many of the other files in the configuration directory.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/ports.conf</strong>: </br>This file specifies the ports that Apache will listen on. By default, Apache listens on port 80 and additionally listens on port 443 when a module providing SSL capabilities is enabled.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/sites-available/</strong>: </br>The directory where per-site virtual hosts can be stored. Apache will not use the configuration files found in this directory unless they are linked to the <strong>sites-enabled</strong> directory. Typically, all server block configuration is done in this directory, and then enabled by linking to the other directory with the <strong>a2ensite</strong> command.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/sites-enabled/</strong>: </br>The directory where enabled per-site virtual hosts are stored. Typically, these are created by linking to configuration files found in the <strong>sites-available</strong> directory with the <strong>a2ensite</strong>. Apache reads the configuration files and links found in this directory when it starts or reloads to compile a complete configuration.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/conf-available/</strong>, <strong>/etc/apache2/conf-enabled/</strong>: </br>These directories have the same relationship as the <strong>sites-available</strong> and <strong>sites-enabled</strong> directories, but are used to store configuration fragments that do not belong in a virtual host. Files in the <strong>conf-available</strong> directory can be enabled with the <strong>a2enconf</strong> command and disabled with the <strong>a2disconf</strong> command.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/mods-available/</strong>, <strong>/etc/apache2/mods-enabled/</strong>: </br>These directories contain the available and enabled modules, respectively. Files ending in <strong>.load</strong> contain fragments to load specific modules, while files ending in <strong>.conf</strong> contain the configuration for those modules. Modules can be enabled and disabled using the <strong>a2enmod</strong> and <strong>a2dismod</strong> command.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/conf-available/</strong>, </br> <span class="invisible"> tab </span> <strong>/etc/apache2/conf-enabled/</strong>: </br>These directories have the same relationship as the <strong>sites-available</strong> and <strong>sites-enabled</strong> directories, but are used to store configuration fragments that do not belong in a virtual host. Files in the <strong>conf-available</strong> directory can be enabled with the <strong>a2enconf</strong> command and disabled with the <strong>a2disconf</strong> command.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/etc/apache2/mods-available/</strong>, </br> <span class="invisible"> tab </span> <strong>/etc/apache2/mods-enabled/</strong>: </br>These directories contain the available and enabled modules, respectively. Files ending in <strong>.load</strong> contain fragments to load specific modules, while files ending in <strong>.conf</strong> contain the configuration for those modules. Modules can be enabled and disabled using the <strong>a2enmod</strong> and <strong>a2dismod</strong> command.</p>
                <h5 class="language__subtitle">Server Logs</h5>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/var/log/apache2/access.log</strong>: </br>By default, every request to your web server is recorded in this log file unless Apache is configured to do otherwise.</p>
                <p class="language-html"><span class="invisible">tab</span>- <strong>/var/log/apache2/error/log</strong>: </br>By default, all errors are recorded in this file. The <strong>LogLevel</strong> directive in the Apache configuration specifies how much detail the error logs will contain.</p>
                <p class="language-html">Now that you have your web server installed, you have many options for the type of content you can serve and the technologies you can use to create a richer experience.</p>
            </div>
        EOT;

        $introduction1 = "Let’s Encrypt is a non-profit certificate authority run by Internet Security Research Group that provides X.509 certificates for Transport Layer Security encryption at no charge. The certificate is valid for 90 days, during which renewal can take place at any time.";
        $introduction2 = "Let’s Encrypt is a Certificate Authority (CA) that provides an easy way to obtain and install free TLS/SSL certificates, thereby enabling encrypted HTTPS on web servers. It simplifies the process by providing a software client, Certbot, that attempts to  automate most (if not all) of the required steps. Currently, the entire  process of obtaining and installing a certificate is fully automated on both Apache and Nginx.";
        $introduction3 = "Nginx is a web server which can also be used as a reverse proxy, load balancer, mail proxy and HTTP cache. The software was created by Igor Sysoev and first publicly released in 2004. A company of the same name was founded in 2011 to provide support and Nginx plus paid software.";
        $introduction4 = "Node.js® is a JavaScript runtime built on Chrome’s V8 JavaScript engine.";
        $introduction5 = "PgAdmin is the most popular and feature rich Open Source administration and development platform for PostgreSQL, the most advanced Open Source database in the world.";
        $introduction6 = "PostgreSQL, also known as Postgres, is a free and open-source relational database management system emphasizing extensibility and SQL compliance. It was originally named POSTGRES, referring to its origins as a successor to the Ingres database developed at the University of California, Berkeley.";
        $introduction7 = "The Apache HTTP Server Project is an effort to develop and maintain an open-source HTTP server for modern operating systems including UNIX and Windows. The goal of this project is to provide a secure, efficient and extensible server that provides HTTP services in sync with the current HTTP standards.";
        $introduction9 = "When you first create a new Ubuntu 20.04 server, there are a few configuration steps that you should take early on as part of the basic setup. This will increase the security and usability of your server and will give you a solid foundation for subsequent actions.";
        $introduction10 = "The Apache HTTP server is the most widely-used web server in the world. It provides many powerful features including dynamically loadable modules, robust media support, and extensive integration with other popular software.</br>In this guide, i’ll explain how to install an Apache web server on your Ubuntu 20.04 server.";
        $title1 = "How To Secure Apache with Let’s Encrypt on Ubuntu 20.04";
        $title2 = "How To Secure Nginx with Let’s Encrypt on Ubuntu 20.04";
        $title3 = "How To Install Nginx on Ubuntu 20.04";
        $title4 = "How to Install Latest Node.js and NPM on Ubuntu with PPA";
        $title5 = "How to install PgAdmin4 on Ubuntu 20.04 Foca Fossa";
        $title6 = "How to install PostgreSQL on ubuntu 20.04 LTS";
        $title7 = "How To Set Up Apache Virtual Hosts on Ubuntu 20.04";
        // $title8 = "How to setup ZSH and Oh-my-zsh on Linux";
        $title9 = "Initial Server Setup with Ubuntu 20.04";
        $title10 = "How To Install the Apache Web Server on Ubuntu 20.04";
        $article1->setImageName('certbot-apache.webp');
        $article1
            ->setTitle($title1)
            ->setIntroduction($introduction1)
            ->setContent($content1)
            ->initializeSlug($title1);
        $article2->setImageName('certbot-nginx.webp');
        $article2
            ->setTitle($title2)
            ->setIntroduction($introduction2)
            ->setContent($content2)
            ->initializeSlug($title2);
        $article3->setImageName('nginx-ubuntu.webp');
        $article3
            ->setTitle($title3)
            ->setIntroduction($introduction3)
            ->setContent($content3)
            ->initializeSlug($title3);
        $article4->setImageName('node-ubuntu.webp');
        $article4
            ->setTitle($title4)
            ->setIntroduction($introduction4)
            ->setContent($content4)
            ->initializeSlug($title4);
        $article5->setImageName('pgadmin-4.webp');
        $article5
            ->setTitle($title5)
            ->setIntroduction($introduction5)
            ->setContent($content5)
            ->initializeSlug($title5);
        $article6->setImageName('postgresql.webp');
        $article6
            ->setTitle($title6)
            ->setIntroduction($introduction6)
            ->setContent($content6)
            ->initializeSlug($title6);
        $article7->setImageName('apachevirtual-hosts.webp');
        $article7
            ->setTitle($title7)
            ->setIntroduction($introduction7)
            ->setContent($content7)
            ->initializeSlug($title7);
        // $article8->setImageName('avatar.png');
        // $article8
        //     ->setTitle($title8)
        //     ->setIntroduction($introduction8)
        //     ->setContent($content8)
        //     ->initializeSlug($title8);
        $article9->setImageName('initial-setup-ubuntu.webp');
        $article9
            ->setTitle($title9)
            ->setIntroduction($introduction9)
            ->setContent($content9)
            ->initializeSlug($title9);
        $article10->setImageName('apache-ubuntu.webp');
        $article10
            ->setTitle($title10)
            ->setIntroduction($introduction10)
            ->setContent($content10)
            ->initializeSlug($title10);
        $admin->addArticle($article1);
        $admin->addArticle($article2);
        $admin->addArticle($article3);
        $admin->addArticle($article4);
        $admin->addArticle($article5);
        $admin->addArticle($article6);
        $admin->addArticle($article7);
        // $admin->addArticle($article8);
        $admin->addArticle($article9);
        $admin->addArticle($article10);
        $manager->persist($admin);
        $manager->persist($article1);
        $manager->persist($article2);
        $manager->persist($article3);
        $manager->persist($article4);
        $manager->persist($article5);
        $manager->persist($article6);
        $manager->persist($article7);
        // $manager->persist($article8);
        $manager->persist($article9);
        $manager->persist($article10);

        // Populate the database with fake project, fake images and fake paragraphs
        // for ($i = 1; $i <= 6; $i++) {
        //     $project = new Project();
        for ($i = 1; $i <= 6; $i++) {
            $project = new Project();

            //     $title = $faker->word(3);
            $title = $faker->word(3);

            //     $src = 'public/uploads/images/error_404.gif';
            $src = 'public/uploads/images/error_404.gif';

            //     $file = new UploadedFile(
            //         $src,
            //         'error_404.gif',
            //         'image/gif',
            //         false,
            //         true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
            //     );
            //     $project->setImageName($file);
            //     $file2 = new File('public/uploads/images/error_404.gif');
            //     $project->setImageFile($file2);
            //     $project
            //         ->setTitle($title)
            //         ->setIntroduction($faker->sentence(6))
            //         ->setContent($faker->sentence(20))
            //         ->setUsers($admin)
            //         ->initializeSlug($title);
            $file = new UploadedFile(
                $src,
                'error_404.gif',
                'image/gif',
                false,
                true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
            );
            $project->setImageName($file);
            $file2 = new File('public/uploads/images/error_404.gif');
            $project->setImageFile($file2);
            $project
                ->setTitle($title)
                ->setIntroduction($faker->sentence(6))
                ->setContent($faker->sentence(20))
                ->setUsers($admin)
                ->initializeSlug($title);

            //     $manager->persist($project);
            // }
            $manager->persist($project);
        }

        // for ($j = 1; $j <= 4; $j++) {
        //     $article = new Article();
        //     $title = $faker->word(3);
        //     $article->setImageName($file);
        //     $article->setImageFile($file2);
        //     $article
        //         ->setTitle($title)
        //         ->setIntroduction($faker->sentence(9))
        //         ->setContent($faker->sentence(20))
        //         ->setUsers($admin)
        //         ->initializeSlug($title);
        //     $manager->persist($article);
        // }

        // Create a user role
        // $roleUser = new Role();
        // $roleUser->setName('ROLE_USER');

        // $manager->persist($roleUser);

        // for ($k = 1; $k < mt_rand(1, 12); $k++) {
        //     $user = new User();

        //     $user
        //         ->setFirstname($faker->firstName())
        //         ->setLastname($faker->lastname())
        //         ->setDescription($faker->sentence())
        //         ->setEmail($faker->email())
        //         ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
        //         ->setLogin($faker->userName())
        //         ->addRoleUser($roleUser)
        //         ->initializeSlug();
        //     $path = 'public/uploads/avatars/' . strtolower($user->getFirstname()) . '-' . strtolower($user->getLastname());
        //     mkdir($path);
        //     $user->setAvatar('avatar.png');
        //     copy('public/uploads/avatars/avatar.png', $path . '/avatar.png');
        //     // for ($l = 0; $l < 1; $l++) {
        //     //     $comment = new Comment();
        //     //     $comment
        //     //         // ->addReply($reply)
        //     //         ->setUsers($user)
        //     //         ->setContent($faker->sentence(8))
        //     //         ->setArticle($article);
        //     //     $user
        //     //         ->addComment($comment);
        //     //     // ->addReply($reply);
        //     //     $manager->persist($comment);
        //     // }
        //     $manager->persist($user);
        // }
        $manager->flush();
    }
}