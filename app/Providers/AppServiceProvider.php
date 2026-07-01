<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem as FlysystemFilesystem;
use Aws\S3\S3Client;
use Aws\Handler\GuzzleV6\GuzzleHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use Psr\Http\Message\RequestInterface;

class SupabaseFilesystemAdapter extends FilesystemAdapter
{
    public function url($path)
    {
        if (isset($this->config['url'])) {
            return rtrim($this->config['url'], '/') . '/' . ltrim($path, '/');
        }

        return parent::url($path);
    }
}

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production' || isset($_SERVER['HTTPS']) || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }

        Storage::extend('s3', function ($app, $config) {
            $stack = HandlerStack::create(new CurlHandler());

            $stack->push(function (callable $handler) {
                return function (RequestInterface $request, array $options) use ($handler) {
                    $path = $request->getUri()->getPath();
                    $fixed = preg_replace('#(/storage/v1/s3)(/storage/v1/s3)+#', '$1', $path);

                    if ($fixed !== $path) {
                        $request = $request->withUri($request->getUri()->withPath($fixed));
                    }

                    return $handler($request, $options);
                };
            });

            $client = new S3Client([
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
                'endpoint' => $config['endpoint'],
                'use_path_style_endpoint' => true,
                'http_handler' => new GuzzleHandler(new GuzzleClient(['handler' => $stack])),
            ]);

            $adapter = new AwsS3V3Adapter($client, $config['bucket']);

            return new SupabaseFilesystemAdapter(
                new FlysystemFilesystem($adapter),
                $adapter,
                $config
            );
        });
    }
}
