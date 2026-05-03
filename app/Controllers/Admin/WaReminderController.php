<?php

namespace App\Controllers\Admin;

use App\Libraries\WaReminderService;
use App\Models\LoanModel;
use App\Models\WaLogModel;
use App\Models\WaTemplateModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class WaReminderController extends ResourceController
{
    protected WaTemplateModel $waTemplateModel;
    protected LoanModel $loanModel;
    protected WaLogModel $waLogModel;

    public function __construct()
    {
        $this->waTemplateModel = new WaTemplateModel();
        $this->loanModel       = new LoanModel();
        $this->waLogModel      = new WaLogModel();

    }

    // ──────────────────────────────────────
    // HALAMAN UTAMA
    // ──────────────────────────────────────

    public function index()
    {
        $templates = $this->waTemplateModel->findAll();

        $tomorrow  = date('Y-m-d', strtotime('+1 day'));
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $countBeforeDue = $this->loanModel
            ->where('DATE(due_date)', $tomorrow)
            ->where('return_date', null)
            ->where('deleted_at', null)
            ->countAllResults();

        $countOverdue = $this->loanModel
            ->where('DATE(due_date)', $yesterday)
            ->where('return_date', null)
            ->where('deleted_at', null)
            ->countAllResults();

        return view('wa_reminder/index', [
            'templates'      => $templates,
            'countBeforeDue' => $countBeforeDue,
            'countOverdue'   => $countOverdue,
            'fonnteToken'    => env('FONNTE_TOKEN') ? '✓ Terkonfigurasi' : '✗ Belum diset',
        ]);
    }

    // ──────────────────────────────────────
    // KIRIM MANUAL
    // ──────────────────────────────────────

    public function sendAll()
    {
        $service = new WaReminderService();
        $result  = $service->runAll();

        session()->setFlashdata([
            'msg'   => "Selesai! Terkirim: {$result['sent']}, Gagal: {$result['failed']}, Dilewati: {$result['skipped']}",
            'error' => $result['failed'] > 0 && $result['sent'] === 0,
            'logs'  => $result['logs'],
        ]);

        return redirect()->to('admin/wa-reminder');
    }

    public function sendByType(string $type)
    {
        if (!in_array($type, ['before_due', 'overdue'])) {
            session()->setFlashdata(['msg' => 'Tipe tidak valid', 'error' => true]);
            return redirect()->to('admin/wa-reminder');
        }

        $service = new WaReminderService();
        $result  = $type === 'before_due'
            ? $service->sendBeforeDueReminders()
            : $service->sendOverdueReminders();

        session()->setFlashdata([
            'msg'   => "Selesai! Terkirim: {$result['sent']}, Gagal: {$result['failed']}, Dilewati: {$result['skipped']}",
            'error' => $result['failed'] > 0 && $result['sent'] === 0,
            'logs'  => $result['logs'],
        ]);

        return redirect()->to('admin/wa-reminder');
    }

    // ──────────────────────────────────────
    // CRUD TEMPLATE
    // ──────────────────────────────────────

    public function create()
    {
        return view('wa_reminder/create', [
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        if (!$this->validate([
            'type'             => 'required|in_list[before_due,overdue]',
            'template_name'    => 'required|max_length[100]',
            'message_template' => 'required',
        ])) {
            return view('wa_reminder/create', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        $this->waTemplateModel->save([
            'type'             => $this->request->getVar('type'),
            'template_name'    => $this->request->getVar('template_name'),
            'message_template' => $this->request->getVar('message_template'),
            'is_active'        => $this->request->getVar('is_active') ? 1 : 0,
        ]);

        session()->setFlashdata(['msg' => 'Template berhasil ditambahkan']);
        return redirect()->to('admin/wa-reminder');
    }

    public function edit($id = null)
    {
        $template = $this->waTemplateModel->find($id);
        if (!$template) throw new PageNotFoundException('Template tidak ditemukan');

        return view('wa_reminder/edit', [
            'template'   => $template,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id = null)
    {
        $template = $this->waTemplateModel->find($id);
        if (!$template) throw new PageNotFoundException('Template tidak ditemukan');

        if (!$this->validate([
            'type'             => 'required|in_list[before_due,overdue]',
            'template_name'    => 'required|max_length[100]',
            'message_template' => 'required',
        ])) {
            return view('wa_reminder/edit', [
                'template'   => $template,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        $this->waTemplateModel->update($id, [
            'type'             => $this->request->getVar('type'),
            'template_name'    => $this->request->getVar('template_name'),
            'message_template' => $this->request->getVar('message_template'),
            'is_active'        => $this->request->getVar('is_active') ? 1 : 0,
        ]);

        session()->setFlashdata(['msg' => 'Template berhasil diperbarui']);
        return redirect()->to('admin/wa-reminder');
    }

    public function delete($id = null)
    {
        $template = $this->waTemplateModel->find($id);
        if (!$template) throw new PageNotFoundException('Template tidak ditemukan');

        $this->waTemplateModel->delete($id);
        session()->setFlashdata(['msg' => 'Template berhasil dihapus']);
        return redirect()->to('admin/wa-reminder');
    }

    public function toggle($id = null)
    {
        $template = $this->waTemplateModel->find($id);
        if (!$template) {
            session()->setFlashdata(['msg' => 'Template tidak ditemukan', 'error' => true]);
            return redirect()->to('admin/wa-reminder');
        }

        $this->waTemplateModel->update($id, [
            'is_active' => $template['is_active'] ? 0 : 1,
        ]);

        $status = $template['is_active'] ? 'dinonaktifkan' : 'diaktifkan';
        session()->setFlashdata(['msg' => "Template berhasil {$status}"]);
        return redirect()->to('admin/wa-reminder');
    }

    // ──────────────────────────────────────
    // PREVIEW TEMPLATE (AJAX)
    // ──────────────────────────────────────

    public function preview()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $messageTemplate = $this->request->getVar('message_template');

        $dummyLoan = [
            'first_name' => 'Budi',
            'last_name'  => 'Santoso',
            'phone'      => '08123456789',
            'book_title' => 'Laskar Pelangi',
            'loan_date'  => date('Y-m-d H:i:s', strtotime('-5 days')),
            'due_date'   => date('Y-m-d', strtotime('+1 day')),
        ];

        $service    = new WaReminderService();
        $reflection = new \ReflectionClass($service);
        $method     = $reflection->getMethod('renderTemplate');
        $method->setAccessible(true);
        $rendered   = $method->invoke($service, $messageTemplate, $dummyLoan);

        return $this->response->setJSON(['preview' => $rendered]);
    }
    // ──────────────────────────────────────
    // RIWAYAT LOG
    // ──────────────────────────────────────
    public function logs()
    {
        $itemPerPage = 20;

        $logs = $this->waLogModel
            ->orderBy('id', 'DESC')
            ->paginate($itemPerPage, 'wa_logs');

        return view('wa_reminder/logs', [
            'logs'        => $logs,
            'pager'       => $this->waLogModel->pager,
            'currentPage' => $this->request->getVar('page_wa_logs') ?? 1,
            'itemPerPage' => $itemPerPage,
        ]);
    }
    // ──────────────────────────────────────
    // PREVIEW DATA PEMINJAMAN (AJAX)
    // ──────────────────────────────────────

    public function previewLoans(string $type)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        if (!in_array($type, ['before_due', 'overdue'])) {
            return $this->response->setJSON(['error' => 'Tipe tidak valid']);
        }

        $date = $type === 'before_due'
            ? date('Y-m-d', strtotime('+1 day'))
            : date('Y-m-d', strtotime('-1 day'));

        $loans = $this->loanModel
            ->select('loans.id, loans.due_date, loans.loan_date, loans.quantity, members.first_name, members.last_name, members.phone, books.title as book_title')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('DATE(loans.due_date)', $date)
            ->where('loans.return_date', null)
            ->where('loans.deleted_at', null)
            ->findAll();

        return $this->response->setJSON(['loans' => $loans, 'type' => $type]);
    }
}