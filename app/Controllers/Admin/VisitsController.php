<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\VisitModel;
use CodeIgniter\I18n\Time;

class VisitsController extends BaseController
{
    protected VisitModel  $visitModel;
    protected MemberModel $memberModel;

    public function __construct()
    {
        $this->visitModel  = new VisitModel();
        $this->memberModel = new MemberModel();
        helper(['upload', 'point']);
    }

    public function index()
    {
        $itemPerPage = 20;
        $search      = $this->request->getGet('search');

        $query = $this->visitModel
            ->select('visits.*, members.first_name, members.last_name, members.no_identitas, members.tipe_anggota')
            ->join('members', 'visits.member_id = members.id', 'LEFT')
            ->orderBy('visits.visit_date', 'DESC');

        if ($search) {
            $query->groupStart()
                ->like('members.first_name',     $search, insensitiveSearch: true)
                ->orLike('members.last_name',    $search, insensitiveSearch: true)
                ->orLike('members.no_identitas', $search, insensitiveSearch: true)
                ->groupEnd();
        }

        $visits = $query->paginate($itemPerPage, 'visits');

        return view('visits/index', [
            'visits'      => $visits,
            'pager'       => $this->visitModel->pager,
            'search'      => $search,
            'currentPage' => $this->request->getGet('page_visits') ?? 1,
            'itemPerPage' => $itemPerPage,
        ]);
    }

    public function report()
    {
        $dariTanggal = $this->request->getGet('dari_tanggal');
        $sampaiTanggal = $this->request->getGet('sampai_tanggal');
        $visits  = null;
        $summary = null;

        if ($dariTanggal && $sampaiTanggal) {
            $visits = $this->visitModel
                ->select('visits.*, members.first_name, members.last_name, members.no_identitas, members.tipe_anggota')
                ->join('members', 'visits.member_id = members.id', 'LEFT')
                ->orderBy('visits.visit_date', 'ASC')
                ->where('DATE(visits.visit_date) >=', $dariTanggal)
                ->where('DATE(visits.visit_date) <=', $sampaiTanggal)
                ->findAll();

            $summary = [
                'total' => count($visits),
                'murid' => count(array_filter($visits, fn($v) => $v['tipe_anggota'] === 'Murid')),
                'guru'  => count(array_filter($visits, fn($v) => $v['tipe_anggota'] === 'Guru')),
                'staf'  => count(array_filter($visits, fn($v) => $v['tipe_anggota'] === 'Staf')),
            ];
        }

        return view('visits/report', [
            'visits'        => $visits,
            'summary'       => $summary,
            'dariTanggal'   => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }

    public function exportPdf()
    {
        $dariTanggal   = $this->request->getGet('dari_tanggal');
        $sampaiTanggal = $this->request->getGet('sampai_tanggal');

        $query = $this->visitModel
            ->select('visits.*, members.first_name, members.last_name, members.no_identitas, members.tipe_anggota')
            ->join('members', 'visits.member_id = members.id', 'LEFT')
            ->orderBy('visits.visit_date', 'ASC');

        if ($dariTanggal && $sampaiTanggal) {
            $query->where('DATE(visits.visit_date) >=', $dariTanggal)
                ->where('DATE(visits.visit_date) <=', $sampaiTanggal);
        }

        $visits = $query->findAll();

        $summary = [
            'total'  => count($visits),
            'murid'  => count(array_filter($visits, fn($v) => $v['tipe_anggota'] === 'Murid')),
            'guru'   => count(array_filter($visits, fn($v) => $v['tipe_anggota'] === 'Guru')),
            'staf'   => count(array_filter($visits, fn($v) => $v['tipe_anggota'] === 'Staf')),
            'manual' => count(array_filter($visits, fn($v) => $v['method'] === 'manual')),
            'scan'   => count(array_filter($visits, fn($v) => $v['method'] === 'scan')),
        ];

        // Label periode
        if ($dariTanggal && $sampaiTanggal) {
            $periodeLabel = date('d/m/Y', strtotime($dariTanggal)) . ' — ' . date('d/m/Y', strtotime($sampaiTanggal));
        } else {
            $periodeLabel = 'Semua Data';
        }

        $html = view('visits/pdf_template', [
            'visits'       => $visits,
            'summary'      => $summary,
            'periodeLabel' => $periodeLabel,
        ]);

        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $namaFile = $dariTanggal && $sampaiTanggal
            ? 'laporan-kunjungan-' . $dariTanggal . '-sd-' . $sampaiTanggal . '.pdf'
            : 'laporan-kunjungan-semua.pdf';

        $isPreview = $this->request->getGet('preview') === '1';
        $dompdf->stream($namaFile, ['Attachment' => !$isPreview]);
    }
    public function create()
    {
        return view('visits/create', [
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        if (!$this->validate([
            'member_uid' => 'required',
            'visit_date' => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => 'Tanggal kunjungan wajib diisi.',
                    'valid_date' => 'Format tanggal tidak valid.',
                ],
            ],
            'notes' => 'permit_empty|max_length[500]',
        ])) {
            return view('visits/create', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getPost(),
            ]);
        }

        // Validasi tanggal tidak boleh masa depan
        $visitDateInput = strtotime($this->request->getPost('visit_date'));
        if ($visitDateInput > time()) {
            return view('visits/create', [
                'validation'     => \Config\Services::validation(),
                'oldInput'       => $this->request->getPost(),
                'errorVisitDate' => 'Tanggal kunjungan tidak boleh melebihi waktu sekarang.',
            ]);
        }

        $member = $this->memberModel
            ->where('uid', $this->request->getPost('member_uid'))
            ->first();

        if (empty($member)) {
            return view('visits/create', [
                'validation'  => \Config\Services::validation(),
                'oldInput'    => $this->request->getPost(),
                'errorMember' => 'Anggota tidak ditemukan.',
            ]);
        }

        $tanggalInput   = date('Y-m-d', strtotime($this->request->getPost('visit_date')));
        $sudahKunjungan = $this->visitModel
            ->where('member_id', $member['id'])
            ->where('DATE(visit_date)', $tanggalInput)
            ->first();

        if ($sudahKunjungan) {
            return view('visits/create', [
                'validation'  => \Config\Services::validation(),
                'oldInput'    => $this->request->getPost(),
                'errorMember' => esc($member['first_name']) . ' sudah tercatat berkunjung pada tanggal ' . date('d/m/Y', strtotime($tanggalInput)) . '. Satu anggota hanya bisa berkunjung sekali dalam sehari.',
            ]);
        }

        $this->visitModel->insert([
            'member_id'  => $member['id'],
            'visit_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('visit_date'))),
            'method'     => 'manual',
            'notes'      => $this->request->getPost('notes') ?: '-',
        ]);
        $visitId = $this->visitModel->getInsertID();

        $poinKunjungan = get_poin_setting('visit');
        catat_poin(
            $member['id'],
            'visit',
            $poinKunjungan,
            'Kunjungan perpustakaan (manual)',
            $visitId,
            'visit'
        );

        session()->setFlashdata('success_visit', [
            'nama'         => trim($member['first_name'] . ' ' . $member['last_name']),
            'no_identitas' => $member['no_identitas'],
            'poin'         => $poinKunjungan,
        ]);

        return redirect()->to('admin/kunjungan');
    }

    public function scanQr()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $uid = $this->request->getPost('uid');

        if (empty($uid)) {
            return $this->response->setJSON(['success' => false, 'message' => 'UID tidak ditemukan.']);
        }

        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anggota dengan UID tersebut tidak ditemukan.',
            ]);
        }

        $today          = Time::now()->toDateString();
        $sudahKunjungan = $this->visitModel
            ->where('member_id', $member['id'])
            ->where("DATE(visit_date) = '{$today}'")
            ->first();

        if ($sudahKunjungan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $member['first_name'] . ' sudah tercatat berkunjung hari ini.',
                'member'  => [
                    'nama'         => trim($member['first_name'] . ' ' . $member['last_name']),
                    'no_identitas' => $member['no_identitas'],
                    'tipe'         => $member['tipe_anggota'],
                ],
            ]);
        }

        $this->visitModel->insert([
            'member_id'  => $member['id'],
            'visit_date' => Time::now()->toDateTimeString(),
            'method'     => 'scan',
            'notes'      => null,
        ]);
        $visitId = $this->visitModel->getInsertID();

        $poinKunjungan = get_poin_setting('visit');
        catat_poin(
            $member['id'],
            'visit',
            $poinKunjungan,
            'Kunjungan perpustakaan (scan QR)',
            $visitId,
            'visit'
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Kunjungan berhasil dicatat.',
            'poin'    => $poinKunjungan,
            'member'  => [
                'nama'         => trim($member['first_name'] . ' ' . $member['last_name']),
                'no_identitas' => $member['no_identitas'],
                'tipe'         => $member['tipe_anggota'],
            ],
        ]);
    }

    public function delete($id = null)
    {
        $visit = $this->visitModel->find($id);

        if (empty($visit)) {
            session()->setFlashdata(['msg' => 'Data kunjungan tidak ditemukan.', 'error' => true]);
            return redirect()->back();
        }

        $this->visitModel->delete($id);

        session()->setFlashdata(['msg' => 'Kunjungan berhasil dihapus.']);
        return redirect()->to('admin/kunjungan');
    }

    public function searchMember()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $param = $this->request->getGet('param');

        if (empty($param)) {
            return $this->response->setJSON([]);
        }

        $members = $this->memberModel
            ->groupStart()
                ->like('first_name',     $param, insensitiveSearch: true)
                ->orLike('last_name',    $param, insensitiveSearch: true)
                ->orLike('no_identitas', $param, insensitiveSearch: true)
            ->groupEnd()
            ->where('deleted_at', null)
            ->findAll(10);

        $result = array_map(fn($m) => [
            'uid'          => $m['uid'],
            'nama'         => trim($m['first_name'] . ' ' . $m['last_name']),
            'no_identitas' => $m['no_identitas'],
            'tipe'         => $m['tipe_anggota'],
        ], $members);

        return $this->response->setJSON($result);
    }
}