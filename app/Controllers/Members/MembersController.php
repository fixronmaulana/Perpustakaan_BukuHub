<?php

namespace App\Controllers\Members;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\BookStockModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MembersController extends ResourceController
{
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected BookStockModel $bookStockModel;
    protected LoanModel $loanModel;
    protected FineModel $fineModel;
    protected UserModel $userModel;

    /**
     * Mapping gender input Excel → nilai database
     * Key  : yang ditulis user di Excel (Laki-laki / Perempuan)
     * Value: yang disimpan ke database  (Male / Female)
     */
    private const GENDER_MAP = [
        'Laki-laki' => 'Male',
        'Perempuan' => 'Female',
    ];

    public function __construct()
    {
        $this->memberModel    = new MemberModel;
        $this->bookModel      = new BookModel;
        $this->bookStockModel = new BookStockModel;
        $this->loanModel      = new LoanModel;
        $this->fineModel      = new FineModel;
        $this->userModel      = new UserModel;

        helper('upload');
    }

    public function index()
    {
        $itemPerPage = 20;
        $currentPage = (int) ($this->request->getGet('page_members') ?? 1);

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $members = $this->memberModel
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->orLike('no_identitas', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'members');

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });
        } else {
            $members = $this->memberModel->paginate($itemPerPage, 'members');
        }

        return view('members/index', [
            'members'     => $members,
            'pager'       => $this->memberModel->pager,
            'currentPage' => $currentPage,
            'itemPerPage' => $itemPerPage,
            'search'      => $this->request->getGet('search'),
        ]);
    }

    public function show($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        $loans = $this->loanModel->where([
            'member_id'   => $member['id'],
            'return_date' => null,
        ])->findAll();

        $fines = $this->loanModel
            ->select('loans.id, fines.amount_paid, fines.fine_amount, fines.paid_at')
            ->join('fines', 'loans.id=fines.loan_id', 'LEFT')
            ->where('member_id', $member['id'])->findAll();

        $totakBooksLent = empty($loans) ? 0 : array_reduce(
            array_map(fn($loan) => $loan['quantity'], $loans),
            fn($carry, $item) => $carry + $item
        );

        $return    = array_filter($loans, fn($loan) => $loan['return_date'] != null);
        $lateLoans = array_filter($loans, fn($loan) =>
            $loan['return_date'] == null && Time::now()->isAfter(Time::parse($loan['due_date']))
        );

        $totalFines  = array_reduce(array_map(fn($f) => $f['fine_amount'],  $fines), fn($c, $i) => $c + $i);
        $paidFines   = array_reduce(array_map(fn($f) => $f['amount_paid'],  $fines), fn($c, $i) => $c + $i);
        $unpaidFines = $totalFines - $paidFines;

        if (!file_exists(MEMBERS_QR_CODE_PATH . $member['qr_code']) || empty($member['qr_code'])) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $member['first_name'] . ($member['last_name'] ? ' ' . $member['last_name'] : '');
            $qrCode      = $qrGenerator->generateQRCode(
                $member['uid'],
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );
            $this->memberModel->update($member['id'], ['qr_code' => $qrCode]);
            $member = $this->memberModel->where('uid', $uid)->first();
        }

        return view('members/show', [
            'member'         => $member,
            'totalBooksLent' => $totakBooksLent,
            'loanCount'      => count($loans),
            'returnCount'    => count($return),
            'lateCount'      => count($lateLoans),
            'unpaidFines'    => $unpaidFines,
            'paidFines'      => $paidFines,
        ]);
    }

    public function new()
    {
        return view('members/create', [
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function create()
    {
        if (!$this->validate([
            'first_name'   => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'    => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'no_identitas' => 'required|alpha_numeric|max_length[50]|is_unique[members.no_identitas]',
            'tipe_anggota' => 'required|in_list[Murid,Guru,Staf]',
            'email'        => 'permit_empty|valid_email|max_length[255]',
            'phone'        => 'permit_empty|alpha_numeric_punct|min_length[4]|max_length[20]',
            'gender'       => 'required|in_list[Male,Female]',
        ])) {
            return view('members/create', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        $firstName   = $this->request->getVar('first_name');
        $lastName    = $this->request->getVar('last_name');
        $noIdentitas = $this->request->getVar('no_identitas');
        $tipeAnggota = $this->request->getVar('tipe_anggota');
        $email       = $this->request->getVar('email') ?? '';
        $phone       = $this->request->getVar('phone') ?? '';
        $gender      = $this->request->getVar('gender');

        $shieldUser = new User([
            'username' => $noIdentitas,
            'email'    => $email ?: $noIdentitas . '@member.local',
            'password' => $noIdentitas,
        ]);
        $this->userModel->save($shieldUser);
        $shieldUser = $this->userModel->findById($this->userModel->getInsertID());
        $shieldUser->addGroup('member');
        $shieldUser->activate();
        $userId = $shieldUser->id;

        $uid         = sha1($firstName . $noIdentitas . rand(0, 1000) . md5($gender));
        $qrGenerator = new QRGenerator();
        $qrCodeLabel = $firstName . ($lastName ? ' ' . $lastName : '');
        $qrCode      = $qrGenerator->generateQRCode(
            data: $uid,
            labelText: $qrCodeLabel,
            dir: MEMBERS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        if (!$this->memberModel->save([
            'uid'          => $uid,
            'user_id'      => $userId,
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'no_identitas' => $noIdentitas,
            'tipe_anggota' => $tipeAnggota,
            'email'        => $email,
            'phone'        => $phone,
            'gender'       => $gender,
            'qr_code'      => $qrCode,
        ])) {
            $this->userModel->delete($userId, purge: true);
            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('members/create', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        session()->setFlashdata(['msg' => 'Insert new member successful']);
        return redirect()->to('admin/members');
    }

    public function edit($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();
        if (empty($member)) throw new PageNotFoundException('Member not found');

        return view('members/edit', [
            'member'     => $member,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();
        if (empty($member)) throw new PageNotFoundException('Member not found');

        if (!$this->validate([
            'first_name'   => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'    => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'no_identitas' => 'required|alpha_numeric|max_length[50]|is_unique[members.no_identitas,id,' . $member['id'] . ']',
            'tipe_anggota' => 'required|in_list[Murid,Guru,Staf]',
            'email'        => 'permit_empty|valid_email|max_length[255]',
            'phone'        => 'permit_empty|alpha_numeric_punct|min_length[4]|max_length[20]',
            'gender'       => 'required|in_list[Male,Female]',
        ])) {
            return view('members/edit', [
                'member'     => $member,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        $firstName   = $this->request->getVar('first_name');
        $lastName    = $this->request->getVar('last_name');
        $noIdentitas = $this->request->getVar('no_identitas');
        $tipeAnggota = $this->request->getVar('tipe_anggota');
        $email       = $this->request->getVar('email') ?? '';
        $phone       = $this->request->getVar('phone') ?? '';
        $gender      = $this->request->getVar('gender');

        $isChanged = ($firstName != $member['first_name'] || $noIdentitas != $member['no_identitas']);
        $newUid    = $isChanged
            ? sha1($firstName . $noIdentitas . rand(0, 1000) . md5($gender))
            : $member['uid'];

        if ($isChanged) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $firstName . ($lastName ? ' ' . $lastName : '');
            $qrCode      = $qrGenerator->generateQRCode($newUid, labelText: $qrCodeLabel, dir: MEMBERS_QR_CODE_PATH, filename: $qrCodeLabel);
            deleteMembersQRCode($member['qr_code']);
        } else {
            $qrCode = $member['qr_code'];
        }

        if (!empty($member['user_id'])) {
            $shieldUser = $this->userModel->findById($member['user_id']);
            if ($shieldUser) {
                $updateData = ['email' => $email ?: $noIdentitas . '@member.local'];
                if ($noIdentitas != $member['no_identitas']) {
                    $updateData['username'] = $noIdentitas;
                    $updateData['password'] = $noIdentitas;
                }
                $shieldUser->fill($updateData);
                $this->userModel->save($shieldUser);
            }
        }

        if (!$this->memberModel->save([
            'id'           => $member['id'],
            'uid'          => $newUid,
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'no_identitas' => $noIdentitas,
            'tipe_anggota' => $tipeAnggota,
            'email'        => $email,
            'phone'        => $phone,
            'gender'       => $gender,
            'qr_code'      => $qrCode,
        ])) {
            session()->setFlashdata(['msg' => 'Update failed']);
            return view('members/edit', [
                'member'     => $member,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        session()->setFlashdata(['msg' => 'Update member successful']);
        return redirect()->to('admin/members');
    }

    public function delete($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();
        if (empty($member)) throw new PageNotFoundException('Member not found');

        $pinjamanAktif = $this->loanModel->where([
            'member_id'   => $member['id'],
            'return_date' => null,
        ])->countAllResults();

        if ($pinjamanAktif > 0) {
            session()->setFlashdata([
                'msg'   => 'Anggota tidak dapat dihapus karena masih memiliki ' . $pinjamanAktif . ' peminjaman aktif.',
                'error' => true,
            ]);
            return redirect()->back();
        }

        if (!empty($member['user_id'])) {
            $this->userModel->delete($member['user_id'], purge: true);
        }

        if (!$this->memberModel->delete($member['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete member', 'error' => true]);
            return redirect()->back();
        }

        deleteMembersQRCode($member['qr_code']);

        session()->setFlashdata(['msg' => 'Member deleted successfully']);
        return redirect()->to('admin/members');
    }

    // ── IMPORT ANGGOTA ────────────────────────────────────────────────

    public function importForm()
    {
        return view('members/import');
    }

    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Anggota');

        // Header
        $sheet->setCellValue('A1', 'first_name');
        $sheet->setCellValue('B1', 'last_name');
        $sheet->setCellValue('C1', 'no_identitas');
        $sheet->setCellValue('D1', 'tipe_anggota');
        $sheet->setCellValue('E1', 'gender');
        $sheet->setCellValue('F1', 'email');
        $sheet->setCellValue('G1', 'phone');

        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Lebar kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setWidth(22);
        }

        $sheet->getStyle("C1:C10000")->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->getStyle("G1:G10000")->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // Contoh data baris 2 — gender pakai Laki-laki/Perempuan
        $sheet->setCellValue('A2', 'Budi');
        $sheet->setCellValue('B2', 'Santoso');
        $sheet->setCellValueExplicit('C2', '12345678', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('D2', 'Murid');
        $sheet->setCellValue('E2', 'Laki-laki');
        $sheet->setCellValue('F2', 'budi@email.com');
        $sheet->setCellValueExplicit('G2', '6281234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Dropdown validasi kolom D (tipe_anggota)
        $validation = $sheet->getCell('D2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowDropDown(false)
            ->setFormula1('"Murid,Guru,Staf"');

        // Dropdown validasi kolom E (gender) — Laki-laki/Perempuan
        $validationGender = $sheet->getCell('E2')->getDataValidation();
        $validationGender->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowDropDown(false)
            ->setFormula1('"Laki-laki,Perempuan"');  // ← diubah dari Male,Female

        $sheet->freezePane('A2');

        $writer   = new Xlsx($spreadsheet);
        $filename = 'template_import_anggota.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function importProcess()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            return view('members/import', [
                'hasilImport' => [
                    'total' => 0, 'berhasil' => 0, 'gagal' => 0,
                    'errors' => [['baris' => '-', 'no_identitas' => '-', 'pesan' => 'File tidak valid atau tidak ditemukan.']],
                ],
            ]);
        }

        $tmpPath = WRITEPATH . 'uploads/' . $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/', basename($tmpPath));

        $spreadsheet = IOFactory::load($tmpPath);
        $rows        = $spreadsheet->getActiveSheet()->toArray();
        @unlink($tmpPath);

        array_shift($rows); // skip header

        // ── Hapus baris kosong di akhir sebelum hitung total ──────────
        $rows = array_filter($rows, fn($row) => !empty(array_filter($row)));
        $rows = array_values($rows);

        $total = $berhasil = $gagal = 0;
        $errors = [];

        foreach ($rows as $nomor => $row) {
            $baris = $nomor + 2;

            $total++;
            $firstName   = trim($row[0] ?? '');
            $lastName    = trim($row[1] ?? '');
            $noIdentitas = trim((string) ($row[2] ?? ''));  // cast string: cegah 0 di depan hilang
            $tipeAnggota = trim($row[3] ?? '');
            $genderInput = trim($row[4] ?? '');             // Laki-laki / Perempuan
            $email       = trim($row[5] ?? '');
            $phone       = trim((string) ($row[6] ?? ''));  // cast string: cegah 0 di depan hilang

            // Validasi kolom wajib
            if (empty($firstName) || empty($noIdentitas) || empty($tipeAnggota) || empty($genderInput)) {
                $gagal++;
                $errors[] = ['baris' => $baris, 'no_identitas' => $noIdentitas ?: '—', 'pesan' => 'Kolom wajib tidak boleh kosong (first_name, no_identitas, tipe_anggota, gender).'];
                continue;
            }

            // Validasi tipe_anggota
            if (!in_array($tipeAnggota, ['Murid', 'Guru', 'Staf'])) {
                $gagal++;
                $errors[] = ['baris' => $baris, 'no_identitas' => $noIdentitas, 'pesan' => "tipe_anggota '{$tipeAnggota}' tidak valid. Gunakan: Murid, Guru, atau Staf."];
                continue;
            }

            // Validasi gender — harus Laki-laki atau Perempuan
            if (!array_key_exists($genderInput, self::GENDER_MAP)) {
                $gagal++;
                $errors[] = ['baris' => $baris, 'no_identitas' => $noIdentitas, 'pesan' => "gender '{$genderInput}' tidak valid. Gunakan: Laki-laki atau Perempuan."];
                continue;
            }

            // Konversi gender ke nilai database (Male / Female)
            $genderDb = self::GENDER_MAP[$genderInput];

            // Cek duplikat no_identitas
            if ($this->memberModel->where('no_identitas', $noIdentitas)->first()) {
                $gagal++;
                $errors[] = ['baris' => $baris, 'no_identitas' => $noIdentitas, 'pesan' => "No. Identitas '{$noIdentitas}' sudah terdaftar."];
                continue;
            }

            // Cek duplikat username di Shield
            if ($this->userModel->findByCredentials(['username' => $noIdentitas])) {
                $gagal++;
                $errors[] = ['baris' => $baris, 'no_identitas' => $noIdentitas, 'pesan' => "Username '{$noIdentitas}' sudah terdaftar di sistem."];
                continue;
            }

            try {
                $shieldUser = new User([
                    'username' => $noIdentitas,
                    'email'    => $email ?: $noIdentitas . '@member.local',
                    'password' => $noIdentitas,
                ]);
                $this->userModel->save($shieldUser);
                $shieldUser = $this->userModel->findById($this->userModel->getInsertID());
                $shieldUser->addGroup('member');
                $shieldUser->activate();
                $userId = $shieldUser->id;

                $uid         = sha1($firstName . $noIdentitas . rand(0, 1000) . md5($genderDb));
                $qrGenerator = new QRGenerator();
                $qrCodeLabel = $firstName . ($lastName ? ' ' . $lastName : '');
                $qrCode      = $qrGenerator->generateQRCode(
                    data: $uid,
                    labelText: $qrCodeLabel,
                    dir: MEMBERS_QR_CODE_PATH,
                    filename: $qrCodeLabel
                );

                $this->memberModel->save([
                    'uid'          => $uid,
                    'user_id'      => $userId,
                    'first_name'   => $firstName,
                    'last_name'    => $lastName,
                    'no_identitas' => $noIdentitas,
                    'tipe_anggota' => $tipeAnggota,
                    'email'        => $email,
                    'phone'        => $phone,
                    'gender'       => $genderDb,   // simpan Male/Female ke database
                    'qr_code'      => $qrCode,
                ]);

                $berhasil++;
            } catch (\Throwable $e) {
                $gagal++;
                $errors[] = ['baris' => $baris, 'no_identitas' => $noIdentitas, 'pesan' => 'Error: ' . $e->getMessage()];
            }
        }

        return view('members/import', [
            'hasilImport' => [
                'total'    => $total,
                'berhasil' => $berhasil,
                'gagal'    => $gagal,
                'errors'   => $errors,
            ],
        ]);
    }
}