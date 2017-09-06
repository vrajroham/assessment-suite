<?php

/**
 * Give credits where credits are due.
 * ---------------------------------
 * This file was the part of CachetHQ
 * We used some part of their install command.
 * Thanks to Assessment Suite Team and original author.
 *
 * @author James Brooks <james@alt-three.com>
 */

namespace App\Console\Commands;

use Dotenv\Dotenv;
use Illuminate\Console\Command;
use App\User;

class InstallAssessmentSuite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assessment:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will install assessment suite';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::where('usertype', 'superadmin')->first();
        if ($user) {
            $this->error('It seems admin account is already created');
            if (!$this->confirm('Do you still want to continue?')) {
                $this->line('Flight Aborted. Bye!');

                return;
            }
        }
        if (!$this->confirm('Do you want to install Assessment Suite?')) {
            $this->line('Flight Aborted. Bye!');

            return;
        }

        $this->configureMail();
        $this->configureEnvironmentFile();
        $this->configureKey();
        $this->configureDatabase();
        $this->configureDrivers();
        $this->makeMigrations();
        $this->seedDatabase();
        $this->createSuperAdmin();
        $this->info('Assessment Suite is installed ⚡');
    }

    /**
     * Copy the environment file.
     */
    protected function configureEnvironmentFile()
    {
        $dir = app()->environmentPath();
        $file = app()->environmentFile();
        $path = "{$dir}/{$file}";

        if (file_exists($path)) {
            $this->line('Environment file already exists. Moving on.');

            return;
        }

        copy("$path.example", $path);
    }

    /**
     * Generate the app key.
     */
    protected function configureKey()
    {
        $this->call('key:generate');
    }

    /**
     * Configure the database.
     *
     * @param array $default
     */
    protected function configureDatabase(array $default = [])
    {
        $config = array_merge([
            'DB_DRIVER' => null,
            'DB_HOST' => null,
            'DB_DATABASE' => null,
            'DB_USERNAME' => null,
            'DB_PASSWORD' => null,
            'DB_PORT' => null,
            'DB_PREFIX' => null,
        ], $default);

        $config['DB_DRIVER'] = $this->choice('Which database driver do you want to use?', [
            'mysql' => 'MySQL',
            'postgresql' => 'PostgreSQL',
            'sqlite' => 'SQLite',
        ], $config['DB_DRIVER']);

        if ($config['DB_DRIVER'] === 'sqlite') {
            $config['DB_DATABASE'] = $this->ask('Please provide the full path to your SQLite file.', $config['DB_DATABASE']);
        } else {
            $config['DB_HOST'] = $this->ask("What is the host of your {$config['DB_DRIVER']} database? (E.g. 127.0.0.1)", $config['DB_HOST']);

            $config['DB_DATABASE'] = $this->ask('What is the name of the database that assessment suite should use?', $config['DB_DATABASE']);

            $config['DB_USERNAME'] = $this->ask('What username should we connect with? (E.g. root)', $config['DB_USERNAME']);

            $config['DB_PASSWORD'] = $this->secret('What password should we connect with?(E.g. secret)', $config['DB_PASSWORD']);

            if ($this->confirm('Is your database listening on a non-standard port number?')) {
                $config['DB_PORT'] = $this->anticipate('What port number is your database using?', [3306, 5432], $config['DB_PORT']);
            }
        }

        // if ($this->confirm('Do you want to use a prefix on the table names?')) {
        //     $config['DB_PREFIX'] = $this->ask('Please enter the prefix now...', $config['DB_PREFIX']);
        // }

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        if (!$this->confirm('Are these settings correct?')) {
            return $this->configureDatabase($config);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure other drivers.
     *
     * @param array $default
     */
    protected function configureDrivers(array $default = [])
    {
        $config = array_merge([
            'CACHE_DRIVER' => null,
            'SESSION_DRIVER' => null,
            'QUEUE_DRIVER' => null,
        ], $default);

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        $config['CACHE_DRIVER'] = $this->choice('Which cache driver do you want to use?', [
            // 'apc' => 'APC(u)',
            'array' => 'Array',
            'database' => 'Database',
            'file' => 'File',
            'memcached' => 'Memcached',
            'redis' => 'Redis',
        ], $config['CACHE_DRIVER']);

        // We need to configure Redis.
        if ($config['CACHE_DRIVER'] === 'redis') {
            $this->configureRedis();
        }

        $config['SESSION_DRIVER'] = $this->choice('Which session driver do you want to use?', [
            // 'apc' => 'APC(u)',
            'array' => 'Array',
            'database' => 'Database',
            'file' => 'File',
            'memcached' => 'Memcached',
            'redis' => 'Redis',
        ], $config['SESSION_DRIVER']);

        // We need to configure Redis.
        if ($config['SESSION_DRIVER'] === 'redis') {
            $this->configureRedis();
        }

        $config['QUEUE_DRIVER'] = $this->choice('Which queue driver do you want to use?', [
            'null' => 'None',
            'sync' => 'Synchronous',
            'database' => 'Database',
            'beanstalkd' => 'Beanstalk',
            'sqs' => 'Amazon SQS',
            'redis' => 'Redis',
        ], $config['QUEUE_DRIVER']);

        // We need to configure Redis, but only if the cache driver wasn't redis.
        if ($config['QUEUE_DRIVER'] === 'redis' && ($config['SESSION_DRIVER'] !== 'redis' || $config['CACHE_DRIVER'] !== 'redis')) {
            $this->configureRedis();
        }

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        if (!$this->confirm('Are these settings correct?')) {
            return $this->configureDrivers($config);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure mail.
     *
     * @param array $config
     */
    protected function configureMail(array $config = [])
    {
        $config = array_merge([
            'MAIL_DRIVER' => null,
            'MAIL_HOST' => null,
            'MAIL_PORT' => null,
            'MAIL_USERNAME' => null,
            'MAIL_PASSWORD' => null,
            'MAIL_ADDRESS' => null,
            'MAIL_NAME' => null,
            'MAIL_ENCRYPTION' => null,
            'MAILGUN_DOMAIN' => null,
            'MAILGUN_SECRET' => null,
        ], $config);

        // Don't continue with these settings if we're not interested in notifications.
        if (!$this->confirm('Do you want Assessment Suite to send mail notifications?')) {
            return;
        }

        $config['MAIL_DRIVER'] = $this->choice('What driver do you want to use to send notifications?', [
            'smtp' => 'SMTP',
            'mail' => 'Mail',
            'sendmail' => 'Sendmail',
            'mailgun' => 'Mailgun',
            'mandrill' => 'Mandrill',
            'ses' => 'Amazon SES',
            'sparkpost' => 'SparkPost',
            'log' => 'Log (Testing)',
        ]);

        if (!$config['MAIL_DRIVER'] === 'log') {
            if ($config['MAIL_DRIVER'] === 'smtp') {
                $config['MAIL_HOST'] = $this->ask('Please supply your mail server host');
            }

            $config['MAIL_ADDRESS'] = $this->ask('What email address should we send notifications from?');
            $config['MAIL_USERNAME'] = $this->ask('What username should we connect as?');
            $config['MAIL_PASSWORD'] = $this->secret('What password should we connect with?');
        }

        if ($config['MAIL_DRIVER'] === 'mailgun') {
            $config['MAILGUN_DOMAIN'] = $this->ask('Please supply your mailgun domain');
            $config['MAILGUN_SECRET'] = $this->ask('Please supply your mailgun secret');
        }

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        if (!$this->confirm('Are these settings correct?')) {
            return $this->configureMail($config);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure the redis connection.
     */
    protected function configureRedis()
    {
        $config = [
            'REDIS_HOST' => null,
            'REDIS_DATABASE' => null,
            'REDIS_PORT' => null,
        ];

        $config['REDIS_HOST'] = $this->ask('What is the host of your redis server?');
        $config['REDIS_DATABASE'] = $this->ask('What is the name of the database that Assessment Suite should use?');
        $config['REDIS_PORT'] = $this->ask('What port should Assessment Suite use?', 6379);

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Format the configs into a pretty table that we can easily read.
     *
     * @param array $config
     */
    protected function formatConfigsTable(array $config)
    {
        $configRows = [];

        foreach ($config as $setting => $value) {
            $configRows[] = compact('setting', 'value');
        }

        $this->table(['Setting', 'Value'], $configRows);
    }

    /**
     * Writes to the .env file with given parameters.
     *
     * @param string $key
     * @param mixed  $value
     */
    protected function writeEnv($key, $value)
    {
        $dir = app()->environmentPath();
        $file = app()->environmentFile();
        $path = "{$dir}/{$file}";

        try {
            (new Dotenv($dir, $file))->load();

            $envKey = strtoupper($key);
            $envValue = env($envKey) ?: 'null';

            file_put_contents($path, str_replace(
                "{$envKey}={$envValue}",
                "{$envKey}={$value}",
                file_get_contents($path)
            ));
        } catch (InvalidPathException $e) {
            throw $e;
        }
    }

    /**
     * Migrate database.
     */
    protected function makeMigrations()
    {
        $this->info('--------------------------------------------------------------');
        $this->info('Migrating Database. Please be patient.');
        $this->call('migrate');
        $this->info('Database migrated.');
        $this->info('--------------------------------------------------------------');
    }

    /**
     * Seed database.
     */
    protected function seedDatabase()
    {
        $this->info('--------------------------------------------------------------');
        $this->info('Seeding Database. Please be patient.');
        $this->call('db:seed');
        $this->info('Database Seeded.');
        $this->info('--------------------------------------------------------------');
    }

    public function createSuperAdmin()
    {
        $config['name'] = null;
        $config['email'] = null;
        $config['password'] = null;

        $config['name'] = $this->ask('Name of administrator? ', $config['name']);

        $config['email'] = $this->ask('Email address of administrator?', $config['email']);

        $config['password'] = $this->secret('Password for administrator account? (E.g. secret)', $config['password']);

        $this->formatConfigsTable($config);

        if (!$this->confirm('Are these credentials correct?')) {
            return $this->createSuperAdmin();
        }
        $user = new User();
        $user->name = $config['name'];
        $user->email = $config['email'];
        $user->password = bcrypt($config['password']);
        $user->save();
        $this->info('Administrator account created successfully');
    }
}
