<?php

namespace Config;

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

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Home & Public Gateway
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::processLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::processRegister');
    $routes->get('verify-password', 'Auth::verifyPassword');
    $routes->post('verify-password', 'Auth::processVerifyPassword');
    $routes->get('verifyPassword', 'Auth::verifyPassword');
    $routes->post('verifyPassword', 'Auth::processVerifyPassword');
    $routes->get('logout', 'Auth::logout');
});

// ====================================================================
// ADMIN ROUTES (Protected by 'admin' filter)
// ====================================================================
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Kelola Soal (Protected by 'soal_auth' filter)
    $routes->group('soal', ['filter' => 'soal_auth'], function ($routes) {
        $routes->get('/', 'Admin\Soal::index');
        
        // Paket Free
        $routes->get('free', 'Admin\Soal::free');
        $routes->get('free/tambah', 'Admin\Soal::freeTambah');
        $routes->get('free/edit', 'Admin\Soal::freeEdit');
        $routes->get('free/edit/(:num)', 'Admin\Soal::freeEdit/$1');
        $routes->get('free/penilaian', 'Admin\Soal::freePenilaian');
        $routes->get('free/penilaian/(:num)', 'Admin\Soal::freePenilaian/$1');

        // Paket Premium
        $routes->get('premium', 'Admin\Soal::premium');
        $routes->get('premium/tambah', 'Admin\Soal::premiumTambah');
        $routes->get('premium/edit', 'Admin\Soal::premiumEdit');
        $routes->get('premium/edit/(:num)', 'Admin\Soal::premiumEdit/$1');
        $routes->get('premium/penilaian', 'Admin\Soal::premiumPenilaian');
        $routes->get('premium/penilaian/(:num)', 'Admin\Soal::premiumPenilaian/$1');

        // CRUD Endpoints
        $routes->post('save-package', 'Admin\Soal::savePackage');
        $routes->post('update-package/(:num)', 'Admin\Soal::updatePackage/$1');
        $routes->match(['get', 'post'], 'delete-package/(:num)', 'Admin\Soal::deletePackage/$1');
        $routes->match(['get', 'post'], 'deletePackage/(:num)', 'Admin\Soal::deletePackage/$1');
        $routes->get('get-question/(:num)', 'Admin\Soal::getQuestionJson/$1');
        $routes->post('save-question', 'Admin\Soal::saveQuestion');
        $routes->post('update-question/(:num)', 'Admin\Soal::updateQuestion/$1');
        $routes->match(['get', 'post'], 'delete-question/(:num)', 'Admin\Soal::deleteQuestion/$1');
        $routes->match(['get', 'post'], 'deleteQuestion/(:num)', 'Admin\Soal::deleteQuestion/$1');
        $routes->post('upload-image', 'Admin\Soal::uploadQuestionImage');
        $routes->post('save-scoring', 'Admin\Soal::saveScoring');
        $routes->post('save-category', 'Admin\Soal::saveCategory');
        $routes->match(['get', 'post'], 'delete-category/(:num)', 'Admin\Soal::deleteCategory/$1');
        $routes->match(['get', 'post'], 'deleteCategory/(:num)', 'Admin\Soal::deleteCategory/$1');
        $routes->post('move-question-category/(:num)', 'Admin\Soal::moveQuestionCategory/$1');
        $routes->post('moveQuestionCategory/(:num)', 'Admin\Soal::moveQuestionCategory/$1');
    });

    // Data Murid
    $routes->group('murid', function ($routes) {
        $routes->get('/', 'Admin\Murid::index');
        $routes->get('database', 'Admin\Murid::database');
        $routes->get('detail/(:num)', 'Admin\Murid::detail/$1');
        $routes->get('nilai', 'Admin\Murid::nilai');
        $routes->post('nilai/update', 'Admin\Murid::updateNilai');
        $routes->get('absensi', 'Admin\Murid::absensi');
        $routes->post('absensi/save', 'Admin\Murid::saveAbsensi');
        $routes->get('login-siswa', 'Admin\Murid::loginSiswa');
        $routes->post('login-siswa/update', 'Admin\Murid::updateLoginSiswa');
        $routes->post('login-siswa/reset-password', 'Admin\Murid::resetPassword');
        $routes->post('toggle-status/(:num)', 'Admin\Murid::toggleStatus/$1');
        $routes->post('grant-premium/(:num)', 'Admin\Murid::grantPremium/$1');
    });

    // Kelola Jadwal Bimbel Online
    $routes->group('jadwal', function ($routes) {
        $routes->get('/', 'Admin\Jadwal::index');
        $routes->get('lihat', 'Admin\Jadwal::lihat');
        $routes->get('tambah', 'Admin\Jadwal::tambah');
        $routes->post('tambah', 'Admin\Jadwal::save');
        $routes->get('edit', 'Admin\Jadwal::edit');
        $routes->get('edit/(:num)', 'Admin\Jadwal::editForm/$1');
        $routes->post('update/(:num)', 'Admin\Jadwal::update/$1');
        $routes->post('delete/(:num)', 'Admin\Jadwal::delete/$1');
        $routes->get('link', 'Admin\Jadwal::link');
        $routes->post('link/save', 'Admin\Jadwal::saveLink');
        $routes->post('check-conflict', 'Admin\Jadwal::checkConflict');
    });

    // Pengaturan Sistem
    $routes->group('pengaturan', function ($routes) {
        $routes->get('/', 'Admin\Pengaturan::index');
        $routes->match(['get', 'post'], 'website', 'Admin\Pengaturan::website');
        $routes->match(['get', 'post'], 'whatsapp', 'Admin\Pengaturan::whatsapp');
        $routes->match(['get', 'post'], 'payment', 'Admin\Pengaturan::payment');
        $routes->match(['get', 'post'], 'premium', 'Admin\Pengaturan::premium');
        $routes->match(['get', 'post'], 'keamanan', 'Admin\Pengaturan::keamanan');
    });
});

// ====================================================================
// GURU / TENTOR ROUTES (Protected by 'tentor' filter)
// ====================================================================
$routes->group('tentor', ['filter' => 'tentor'], function ($routes) {
    $routes->get('/', 'Tentor\Dashboard::index');
    $routes->get('dashboard', 'Tentor\Dashboard::index');

    // Kelola Soal (Protected by 'soal_auth' filter)
    $routes->group('soal', ['filter' => 'soal_auth'], function ($routes) {
        $routes->get('/', 'Tentor\Soal::index');
        
        // Paket Free
        $routes->get('free', 'Tentor\Soal::free');
        $routes->get('free/tambah', 'Tentor\Soal::freeTambah');
        $routes->get('free/edit', 'Tentor\Soal::freeEdit');
        $routes->get('free/edit/(:num)', 'Tentor\Soal::freeEdit/$1');
        $routes->get('free/penilaian', 'Tentor\Soal::freePenilaian');
        $routes->get('free/penilaian/(:num)', 'Tentor\Soal::freePenilaian/$1');

        // Paket Premium
        $routes->get('premium', 'Tentor\Soal::premium');
        $routes->get('premium/tambah', 'Tentor\Soal::premiumTambah');
        $routes->get('premium/edit', 'Tentor\Soal::premiumEdit');
        $routes->get('premium/edit/(:num)', 'Tentor\Soal::premiumEdit/$1');
        $routes->get('premium/penilaian', 'Tentor\Soal::premiumPenilaian');
        $routes->get('premium/penilaian/(:num)', 'Tentor\Soal::premiumPenilaian/$1');

        // CRUD Endpoints
        $routes->post('save-package', 'Tentor\Soal::savePackage');
        $routes->post('update-package/(:num)', 'Tentor\Soal::updatePackage/$1');
        $routes->match(['get', 'post'], 'delete-package/(:num)', 'Tentor\Soal::deletePackage/$1');
        $routes->match(['get', 'post'], 'deletePackage/(:num)', 'Tentor\Soal::deletePackage/$1');
        $routes->get('get-question/(:num)', 'Tentor\Soal::getQuestionJson/$1');
        $routes->post('save-question', 'Tentor\Soal::saveQuestion');
        $routes->post('update-question/(:num)', 'Tentor\Soal::updateQuestion/$1');
        $routes->match(['get', 'post'], 'delete-question/(:num)', 'Tentor\Soal::deleteQuestion/$1');
        $routes->match(['get', 'post'], 'deleteQuestion/(:num)', 'Tentor\Soal::deleteQuestion/$1');
        $routes->post('upload-image', 'Tentor\Soal::uploadQuestionImage');
        $routes->post('save-scoring', 'Tentor\Soal::saveScoring');
        $routes->post('save-category', 'Tentor\Soal::saveCategory');
        $routes->match(['get', 'post'], 'delete-category/(:num)', 'Tentor\Soal::deleteCategory/$1');
        $routes->match(['get', 'post'], 'deleteCategory/(:num)', 'Tentor\Soal::deleteCategory/$1');
        $routes->post('move-question-category/(:num)', 'Tentor\Soal::moveQuestionCategory/$1');
        $routes->post('moveQuestionCategory/(:num)', 'Tentor\Soal::moveQuestionCategory/$1');
    });

    // Kelola Jadwal Bimbel Tentor
    $routes->group('jadwal', function ($routes) {
        $routes->get('/', 'Tentor\Jadwal::index');
        $routes->post('tambah', 'Tentor\Jadwal::save');
        $routes->post('update/(:num)', 'Tentor\Jadwal::update/$1');
        $routes->match(['get', 'post'], 'delete/(:num)', 'Tentor\Jadwal::delete/$1');
    });

    // Data Murid & Nilai Tentor
    $routes->group('murid', function ($routes) {
        $routes->get('/', 'Tentor\Murid::index');
        $routes->get('nilai', 'Tentor\Murid::nilai');
    });

    // Profil & Ubah Password Tentor
    $routes->group('profil', function ($routes) {
        $routes->get('/', 'Tentor\Profil::index');
        $routes->post('update', 'Tentor\Profil::update');
        $routes->get('password', 'Tentor\Profil::password');
        $routes->post('password', 'Tentor\Profil::updatePassword');
    });
});

// ====================================================================
// MURID ROUTES (Protected by 'murid' filter)
// ====================================================================
$routes->group('murid', ['filter' => 'murid'], function ($routes) {
    $routes->get('/', 'Murid\Dashboard::index');
    $routes->get('dashboard', 'Murid\Dashboard::index');

    // Paket Soal & CBT Engine
    $routes->group('soal', function ($routes) {
        $routes->get('/', 'Murid\Soal::index');
        $routes->get('free', 'Murid\Soal::free');
        $routes->get('premium', 'Murid\Soal::premium');
        $routes->get('mulai/(:num)', 'Murid\Soal::mulai/$1');
        $routes->get('kerjakan/(:num)', 'Murid\Soal::kerjakan/$1');
        $routes->post('simpan-jawaban', 'Murid\Soal::simpanJawaban');
        $routes->post('selesai/(:num)', 'Murid\Soal::selesai/$1');
        $routes->get('hasil/(:num)', 'Murid\Soal::hasil/$1');
    });

    // Payment Checkout
    $routes->group('payment', function ($routes) {
        $routes->get('(:num)', 'Murid\Payment::index/$1');
        $routes->post('process', 'Murid\Payment::process');
        $routes->get('success/(:num)', 'Murid\Payment::success/$1');
    });

    // Jadwal Kelas Online & Absensi
    $routes->group('jadwal', function ($routes) {
        $routes->get('/', 'Murid\Jadwal::index');
    });

    // Profil Murid
    $routes->group('profil', function ($routes) {
        $routes->get('/', 'Murid\Profil::index');
        $routes->get('username', 'Murid\Profil::username');
        $routes->post('username', 'Murid\Profil::updateUsername');
        $routes->get('password', 'Murid\Profil::password');
        $routes->post('password', 'Murid\Profil::updatePassword');
        $routes->get('bantuan', 'Murid\Profil::bantuan');
    });
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
