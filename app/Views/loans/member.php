<?php if (empty($members)) : ?>
  <h5 class="card-title fw-semibold my-4 text-danger">Anggota tidak ditemukan</h5>
  <p class="text-danger"><?= $msg ?? ''; ?></p>
<?php else : ?>
  <h5 class="card-title fw-semibold my-4">Hasil pencarian anggota</h5>
  <div class="overflow-x-scroll">
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nama Lengkap</th>
          <th scope="col">No. Identitas</th>
          <th scope="col">Tipe</th>
          <th scope="col">Email</th>
          <th scope="col">No. Telepon</th>
          <th scope="col">Jenis Kelamin</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 ?>
        <?php foreach ($members as $key => $member) : ?>
          <?php if (!$member['deleted_at']) : ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><b><?= $member['first_name'] . ' ' . $member['last_name']; ?></b></td>
              <td><?= $member['no_identitas']; ?></td>
              <td><?= $member['tipe_anggota']; ?></td>
              <td><?= $member['email']; ?></td>
              <td><?= $member['phone']; ?></td>
              <td><?= $member['gender']; ?></td>
              <td style="width: 120px;" class="text-center">
                <a href="<?= base_url("admin/loans/new/books/search?member-uid={$member['uid']}"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-check"></i>
                  Pilih
                </a>
              </td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>