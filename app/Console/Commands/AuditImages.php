<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Meal;
use App\Models\User;

class AuditImages extends Command
{
    protected $signature = 'images:audit {--limit=200 : Max meals/users to scan} {--json : Output JSON summary only} {--missing : Show only missing files}';
    protected $description = 'Audit meal and profile images: existence, URL generation, and missing references.';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $onlyMissing = $this->option('missing');
        $asJson = $this->option('json');

        $this->info('🔍 Auditing image records...');
        $publicConfigured = config('filesystems.disks.public');
        $disk = Storage::disk('public');
        $baseUrl = $publicConfigured['url'] ?? config('app.url').'/storage';

        $summary = [
            'config' => [
                'app_url' => config('app.url'),
                'public_url' => $baseUrl,
                'disk_root' => $publicConfigured['root'] ?? null,
            ],
            'meals' => [
                'scanned' => 0,
                'with_image' => 0,
                'missing_file' => 0,
                'external' => 0,
                'records' => []
            ],
            'users' => [
                'scanned' => 0,
                'with_photo' => 0,
                'missing_file' => 0,
                'external' => 0,
                'records' => []
            ]
        ];

        // Meals
        $meals = Meal::select('id','name','image_path')->orderBy('id')->limit($limit)->get();
        foreach ($meals as $meal) {
            $summary['meals']['scanned']++;
            if (!$meal->image_path) {
                continue;
            }
            $summary['meals']['with_image']++;
            $path = $meal->image_path;
            $external = str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
            if ($external) {
                $summary['meals']['external']++;
            }
            $exists = $external ? true : $disk->exists($path);
            if (!$exists) {
                $summary['meals']['missing_file']++;
            }
            if ($onlyMissing && $exists) {
                continue;
            }
            $summary['meals']['records'][] = [
                'id' => $meal->id,
                'name' => $meal->name,
                'path' => $path,
                'exists' => $exists,
                'external' => $external,
                'url_sample' => $external ? $path : ($meal->image_url ?? null)
            ];
        }

        // Users
        $users = User::select('id','name','profile_photo')->orderBy('id')->limit($limit)->get();
        foreach ($users as $user) {
            $summary['users']['scanned']++;
            if (!$user->profile_photo) {
                continue;
            }
            $summary['users']['with_photo']++;
            $path = $user->profile_photo;
            $external = str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
            if ($external) {
                $summary['users']['external']++;
            }
            $exists = $external ? true : $disk->exists($path);
            if (!$exists) {
                $summary['users']['missing_file']++;
            }
            if ($onlyMissing && $exists) {
                continue;
            }
            $summary['users']['records'][] = [
                'id' => $user->id,
                'name' => $user->name,
                'path' => $path,
                'exists' => $exists,
                'external' => $external,
                'url_sample' => $external ? $path : ($user->getProfilePhotoUrlAttribute() ?? null)
            ];
        }

        if ($asJson) {
            $this->line(json_encode($summary, JSON_PRETTY_PRINT));
            return Command::SUCCESS;
        }

        // Human-friendly output
        $this->section('Configuration');
        $this->line(' App URL:        '.$summary['config']['app_url']);
        $this->line(' Public URL:     '.$summary['config']['public_url']);
        $this->line(' Disk Root:      '.$summary['config']['disk_root']);
        $this->newLine();

        $this->section('Meals');
        $this->line(' Scanned:        '.$summary['meals']['scanned']);
        $this->line(' With Image:     '.$summary['meals']['with_image']);
        $this->line(' External:       '.$summary['meals']['external']);
        $this->line(' Missing Files:  '.$summary['meals']['missing_file']);
        $this->newLine();

        foreach ($summary['meals']['records'] as $rec) {
            $status = $rec['external'] ? '🌐 external' : ($rec['exists'] ? '✅ ok' : '❌ missing');
            $this->line(" Meal #{$rec['id']} {$rec['name']} - {$status}");
            $this->line("   Path: {$rec['path']}");
            if ($rec['url_sample']) {
                $this->line("   URL:  {$rec['url_sample']}");
            }
        }
        $this->newLine();

        $this->section('Users');
        $this->line(' Scanned:        '.$summary['users']['scanned']);
        $this->line(' With Photo:     '.$summary['users']['with_photo']);
        $this->line(' External:       '.$summary['users']['external']);
        $this->line(' Missing Files:  '.$summary['users']['missing_file']);
        $this->newLine();

        foreach ($summary['users']['records'] as $rec) {
            $status = $rec['external'] ? '🌐 external' : ($rec['exists'] ? '✅ ok' : '❌ missing');
            $this->line(" User #{$rec['id']} {$rec['name']} - {$status}");
            $this->line("   Path: {$rec['path']}");
            if ($rec['url_sample']) {
                $this->line("   URL:  {$rec['url_sample']}");
            }
        }

        $this->newLine();
        $this->info('📊 Audit complete. Use --json for machine-readable output or --missing to focus on missing files.');
        return Command::SUCCESS;
    }

    private function section(string $title): void
    {
        $this->info(str_repeat('-', 8).' '.$title.' '.str_repeat('-', 8));
    }
}
