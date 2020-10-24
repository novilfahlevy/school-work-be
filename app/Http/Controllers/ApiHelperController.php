<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiHelperController extends Controller
{
    public $success_code = 200;
    public $success_message = 'Berhasil mengambil data!';

    public $created_code = 201;
    public $created_message = 'Data berhasil ditambahkan!';

    public $updated_message = 'Data berhasil diubah!';

    public $deleted_message = 'Data berhasil dihapus!';
}
