<?php

namespace App\Libraries;

use App\Models\LoanModel;
use App\Models\WaLogModel;
use App\Models\WaTemplateModel;
use CodeIgniter\I18n\Time;

class WaReminderService
{
    protected LoanModel $loanModel;
    protected WaTemplateModel $waTemplateModel;
    protected WaLogModel $waLogModel;
    protected FonnteService $fonnte;

    public function __construct()
    {
        $this->loanModel       = new LoanModel();
        $this->waTemplateModel = new WaTemplateModel();
        $this->waLogModel      = new WaLogModel();
        $this->fonnte          = new FonnteService();
    }

    public function runAll(): array
    {
        $beforeDue = $this->sendBeforeDueReminders();
        $overdue   = $this->sendOverdueReminders();

        return [
            'sent'    => $beforeDue['sent']    + $overdue['sent'],
            'failed'  => $beforeDue['failed']  + $overdue['failed'],
            'skipped' => $beforeDue['skipped'] + $overdue['skipped'],
            'logs'    => array_merge($beforeDue['logs'], $overdue['logs']),
        ];
    }

    public function sendBeforeDueReminders(): array
    {
        $template = $this->waTemplateModel->getActiveTemplate('before_due');

        if (!$template) {
            return $this->emptyResult('Template before_due tidak aktif atau tidak ditemukan');
        }

        $tomorrow = Time::now()->addDays(1)->toDateString();

        $loans = $this->loanModel
            ->select('loans.*, members.first_name, members.last_name, members.phone, books.title as book_title')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('DATE(loans.due_date)', $tomorrow)
            ->where('loans.return_date', null)
            ->where('loans.deleted_at', null)
            ->findAll();

        return $this->sendBatch($loans, $template, 'before_due');
    }

    public function sendOverdueReminders(): array
    {
        $template = $this->waTemplateModel->getActiveTemplate('overdue');

        if (!$template) {
            return $this->emptyResult('Template overdue tidak aktif atau tidak ditemukan');
        }

        $yesterday = Time::now()->subDays(1)->toDateString();

        $loans = $this->loanModel
            ->select('loans.*, members.first_name, members.last_name, members.phone, books.title as book_title')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('DATE(loans.due_date)', $yesterday)
            ->where('loans.return_date', null)
            ->where('loans.deleted_at', null)
            ->findAll();

        return $this->sendBatch($loans, $template, 'overdue');
    }

    protected function sendBatch(array $loans, array $template, string $type): array
    {
        $result = ['sent' => 0, 'failed' => 0, 'skipped' => 0, 'logs' => []];

        if (empty($loans)) {
            $result['logs'][] = [
                'status' => 'info',
                'reason' => "Tidak ada peminjaman untuk tipe '{$type}' hari ini",
            ];
            return $result;
        }

        foreach ($loans as $loan) {
            $nama    = trim(($loan['first_name'] ?? '') . ' ' . ($loan['last_name'] ?? ''));
            $sentAt  = Time::now()->toDateTimeString();

            if (empty($loan['phone'])) {
                $result['skipped']++;

                // Simpan ke log
                $this->waLogModel->save([
                    'loan_id'     => $loan['id'],
                    'member_name' => $nama,
                    'phone'       => '-',
                    'book_title'  => $loan['book_title'] ?? '-',
                    'type'        => $type,
                    'status'      => 'skipped',
                    'message'     => null,
                    'note'        => 'Nomor WA tidak tersedia',
                    'sent_at'     => $sentAt,
                ]);

                $result['logs'][] = [
                    'status' => 'skipped',
                    'nama'   => $nama,
                    'buku'   => $loan['book_title'] ?? '-',
                    'reason' => 'Nomor WA tidak tersedia',
                ];
                continue;
            }

            $message = $this->renderTemplate($template['message_template'], $loan);
            $send    = $this->fonnte->send($loan['phone'], $message);

            if ($send['success']) {
                $result['sent']++;

                // Simpan ke log
                $this->waLogModel->save([
                    'loan_id'     => $loan['id'],
                    'member_name' => $nama,
                    'phone'       => $loan['phone'],
                    'book_title'  => $loan['book_title'] ?? '-',
                    'type'        => $type,
                    'status'      => 'sent',
                    'message'     => $message,
                    'note'        => null,
                    'sent_at'     => $sentAt,
                ]);

                $result['logs'][] = [
                    'status' => 'sent',
                    'nama'   => $nama,
                    'phone'  => $loan['phone'],
                    'buku'   => $loan['book_title'] ?? '-',
                    'type'   => $type,
                ];
            } else {
                $result['failed']++;

                // Simpan ke log
                $this->waLogModel->save([
                    'loan_id'     => $loan['id'],
                    'member_name' => $nama,
                    'phone'       => $loan['phone'],
                    'book_title'  => $loan['book_title'] ?? '-',
                    'type'        => $type,
                    'status'      => 'failed',
                    'message'     => $message,
                    'note'        => $send['message'],
                    'sent_at'     => $sentAt,
                ]);

                $result['logs'][] = [
                    'status' => 'failed',
                    'nama'   => $nama,
                    'phone'  => $loan['phone'],
                    'buku'   => $loan['book_title'] ?? '-',
                    'reason' => $send['message'],
                ];
            }

            usleep(500000);
        }

        return $result;
    }

    protected function renderTemplate(string $template, array $loan): string
    {
        $nama          = trim(($loan['first_name'] ?? '') . ' ' . ($loan['last_name'] ?? ''));
        $judulBuku     = $loan['book_title'] ?? '-';
        $tglPinjam     = !empty($loan['loan_date'])
            ? Time::parse($loan['loan_date'])->toLocalizedString('d MMMM yyyy')
            : '-';
        $tglJatuhTempo = !empty($loan['due_date'])
            ? Time::parse($loan['due_date'])->toLocalizedString('d MMMM yyyy')
            : '-';

        $now           = Time::now();
        $dueDate       = !empty($loan['due_date']) ? Time::parse($loan['due_date']) : $now;
        $diffDays      = (int) abs($now->difference($dueDate)->getDays());
        $hariTersisa   = $now->isBefore($dueDate) ? $diffDays : 0;
        $hariTerlambat = $now->isAfter($dueDate)  ? $diffDays : 0;

        return str_replace(
            ['{nama}', '{judul_buku}', '{tgl_pinjam}', '{tgl_jatuh_tempo}', '{hari_tersisa}', '{hari_terlambat}'],
            [$nama,    $judulBuku,    $tglPinjam,    $tglJatuhTempo,    $hariTersisa,    $hariTerlambat],
            $template
        );
    }

    protected function emptyResult(string $reason): array
    {
        return [
            'sent'    => 0,
            'failed'  => 0,
            'skipped' => 0,
            'logs'    => [['status' => 'info', 'reason' => $reason]],
        ];
    }
}