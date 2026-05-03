<?php

namespace App\Commands;

use App\Libraries\WaReminderService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Cara pakai manual via terminal:
 *   php spark wa:send-reminder
 *   php spark wa:send-reminder --type before_due
 *   php spark wa:send-reminder --type overdue
 *
 * Cara pakai otomatis via cron (setiap hari jam 08.00):
 *   0 8 * * * cd /path/project && php spark wa:send-reminder >> /dev/null 2>&1
 */
class SendWaReminder extends BaseCommand
{
    protected $group       = 'Reminder';
    protected $name        = 'wa:send-reminder';
    protected $description = 'Kirim reminder pengembalian buku via WhatsApp (H-1 & H+1)';

    protected $usage   = 'wa:send-reminder [options]';
    protected $options = [
        '--type' => 'Tipe reminder: before_due | overdue | all (default: all)',
    ];

    public function run(array $params)
    {
        $type = CLI::getOption('type') ?? 'all';

        CLI::write('======================================', 'cyan');
        CLI::write(' WA Reminder Pengembalian Buku', 'cyan');
        CLI::write('======================================', 'cyan');
        CLI::write('Waktu : ' . date('Y-m-d H:i:s'));
        CLI::write('Tipe  : ' . $type);
        CLI::newLine();

        $service = new WaReminderService();

        $result = match ($type) {
            'before_due' => $service->sendBeforeDueReminders(),
            'overdue'    => $service->sendOverdueReminders(),
            default      => $service->runAll(),
        };

        // Tampilkan log per item
        foreach ($result['logs'] as $log) {
            $status = $log['status'] ?? 'info';
            $color  = match ($status) {
                'sent'    => 'green',
                'failed'  => 'red',
                'skipped' => 'yellow',
                default   => 'white',
            };

            $msg = match ($status) {
                'sent'    => "[✓ TERKIRIM] {$log['nama']} ({$log['phone']}) — {$log['buku']}",
                'failed'  => "[✗ GAGAL]    {$log['nama']} ({$log['phone']}) — {$log['buku']} | {$log['reason']}",
                'skipped' => "[- SKIP]     " . ($log['nama'] ?? '') . " — " . ($log['buku'] ?? '') . " | {$log['reason']}",
                default   => "[i] " . ($log['reason'] ?? ''),
            };

            CLI::write($msg, $color);
        }

        CLI::newLine();
        CLI::write('--------------------------------------', 'cyan');
        CLI::write("Terkirim : {$result['sent']}",    'green');
        CLI::write("Gagal    : {$result['failed']}",  'red');
        CLI::write("Dilewati : {$result['skipped']}", 'yellow');
        CLI::write('======================================', 'cyan');
    }
}