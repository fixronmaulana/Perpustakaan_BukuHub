<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/book', 'Home::book');
$routes->get('/layanan', 'Home::layanan');
$routes->get('/leaderboard', 'Home::leaderboard');
$routes->get('/kontak', 'Home::kontak');
// $routes->post('/kontak/kirim', 'Home::kontakKirim'); // ← aktifkan saat form siap
$routes->post('login', 'Auth\LoginController::loginAction');
service('auth')->routes($routes);

/*
 * --------------------------------------------------------------------
 * Route Member (butuh login + group member)
 * --------------------------------------------------------------------
 */
$routes->group('member', ['filter' => 'memberFilter'], static function (RouteCollection $routes) {
    $routes->get('/', 'Member\MemberDashboardController::index');
    $routes->get('dashboard', 'Member\MemberDashboardController::index');
    $routes->get('kartu', 'Member\MemberDashboardController::kartu');
    $routes->get('peminjaman', 'Member\MemberDashboardController::peminjaman');
    $routes->get('pengembalian', 'Member\MemberDashboardController::pengembalian');
    $routes->get('kunjungan', 'Member\MemberDashboardController::kunjungan');
    $routes->get('daftarbuku', 'Member\MemberDashboardController::daftarbuku');
    $routes->get('daftarbuku/(:segment)', 'Member\MemberDashboardController::detailBuku/$1');
    $routes->get('poin',        'Member\MemberDashboardController::poin');
    $routes->get('leaderboard', 'Member\MemberDashboardController::leaderboard');
    $routes->get('kuis/(:num)',        'Member\MemberDashboardController::kuis/$1');
    $routes->post('kuis/(:num)/submit','Member\MemberDashboardController::submitKuis/$1');
    // routes profil member
    $routes->get('profil', 'Member\MemberProfilController::index');
    $routes->post('profil/update', 'Member\MemberProfilController::update');
    $routes->post('profil/password', 'Member\MemberProfilController::updatePassword');
});

$routes->group('admin', ['filter' => 'session'], static function (RouteCollection $routes) {
    $routes->get('/', 'Dashboard\DashboardController');
    $routes->get('dashboard', 'Dashboard\DashboardController::dashboard');

    $routes->get('members/import',          'Members\MembersController::importForm');
    $routes->post('members/import',         'Members\MembersController::importProcess');
    $routes->get('members/import/template', 'Members\MembersController::importTemplate');

    $routes->resource('members', ['controller' => 'Members\MembersController']);
    $routes->resource('books', ['controller' => 'Books\BooksController']);
    $routes->resource('categories', ['controller' => 'Books\CategoriesController']);
    $routes->resource('racks', ['controller' => 'Books\RacksController']);

    $routes->get('kunjungan',           'Admin\VisitsController::index');
    $routes->get('kunjungan/new',       'Admin\VisitsController::create');
    $routes->post('kunjungan',          'Admin\VisitsController::store');
    $routes->post('kunjungan/scan',     'Admin\VisitsController::scanQr');
    $routes->get('kunjungan/search',    'Admin\VisitsController::searchMember');
    $routes->delete('kunjungan/(:num)', 'Admin\VisitsController::delete/$1');

    $routes->get('loans/new/members/search', 'Loans\LoansController::searchMember');
    $routes->get('loans/new/books/search', 'Loans\LoansController::searchBook');
    $routes->post('loans/new', 'Loans\LoansController::new');
    $routes->resource('loans', ['controller' => 'Loans\LoansController']);

    $routes->get('kuis',                        'Admin\QuizzesController::index');
    $routes->post('kuis',                       'Admin\QuizzesController::store');
    $routes->get('kuis/(:num)',                 'Admin\QuizzesController::show/$1');
    $routes->post('kuis/(:num)/soal',           'Admin\QuizzesController::storeQuestion/$1');
    $routes->post('kuis/(:num)/soal/(:num)',    'Admin\QuizzesController::updateQuestion/$1/$2');
    $routes->post('kuis/(:num)/soal/(:num)/edit', 'Admin\QuizzesController::updateQuestion/$1/$2');
    $routes->delete('kuis/(:num)/soal/(:num)', 'Admin\QuizzesController::deleteQuestion/$1/$2');
    $routes->post('kuis/(:num)/toggle',         'Admin\QuizzesController::toggleActive/$1');
    $routes->delete('kuis/(:num)',              'Admin\QuizzesController::delete/$1');

    $routes->get('pengaturan-poin',  'Admin\PointSettingsController::index');
    $routes->post('pengaturan-poin', 'Admin\PointSettingsController::update');

    $routes->get('returns/new/search', 'Loans\ReturnsController::searchLoan');
    $routes->resource('returns', ['controller' => 'Loans\ReturnsController']);

    $routes->get('fines/returns/search', 'Loans\FinesController::searchReturn');
    $routes->get('fines/pay/(:any)', 'Loans\FinesController::pay/$1');
    $routes->resource('fines/settings', ['controller' => 'Loans\FineSettingsController', 'filter' => 'group:superadmin']);
    $routes->resource('fines', ['controller' => 'Loans\FinesController']);

    $routes->group('users', ['filter' => 'group:superadmin'], static function (RouteCollection $routes) {
        $routes->get('new', 'Users\RegisterController::index');
        $routes->post('', 'Users\RegisterController::registerAction');
    });
    $routes->resource('users', ['controller' => 'Users\UsersController', 'filter' => 'group:superadmin']);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
