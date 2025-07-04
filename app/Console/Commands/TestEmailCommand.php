<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriberMail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email configuration...");
        $this->info("Sending test email to: " . $email);
        
        try {
            // Display mail configuration
            $this->info("Mail Configuration:");
            $this->info("MAIL_MAILER: " . config('mail.default'));
            $this->info("MAIL_HOST: " . config('mail.mailers.smtp.host'));
            $this->info("MAIL_PORT: " . config('mail.mailers.smtp.port'));
            $this->info("MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption'));
            $this->info("MAIL_USERNAME: " . config('mail.mailers.smtp.username'));
            $this->info("MAIL_FROM_ADDRESS: " . config('mail.from.address'));
            
            // Send test email
            Mail::to($email)->send(new SubscriberMail('Test Email', 'This is a test email to verify the email configuration.'));
            
            $this->info("Test email sent successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error sending email: " . $e->getMessage());
            return 1;
        }
    }
} 