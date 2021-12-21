<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Don't forget include/define RestController path

/**
 *
 * Controller Mahasiswa
 *
 * This controller for ...
 *
 * @package   CodeIgniter
 * @category  Controller REST
 * @author    Ahmad Faisol <mzfais@lecturer.itn.ac.id>
 * @link      https://github.com/mzfais/
 * @param     ...
 * @return    ...
 *
 */

use chriskacerguis\RestServer\RestController;

class Jemaat extends RestController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jemaat_Model', 'jmt');
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            $p = $this->get('page');
            $p = (empty($p) ? 1 : $p);
            $total_data = $this->jmt->count();
            $total_page = ceil($total_data / 5);
            $start = ($p - 1) * 5;

            $list = $this->jmt->get(null, 5, $start);
            if ($list) {
                $data = [
                    'status' => true,
                    'page' => $p,
                    'total_data' => $total_data,
                    'total_page' => $total_page,
                    'data' => $list
                ];
            } else {
                $data = [
                    'statu' => false,
                    'msg' => 'Data tidak ditemukan'
                ];
            }
            $this->response($data, RestController::HTTP_OK);
        } else {
            $data = $this->jmt->get($id);
            if ($data) {
                $this->response(['status' => true, 'data' => $data], RestController::HTTP_OK);
            } else {
                $this->response(['status' => false, 'msg' => $id . ' tidak ditemukan'], RestController::HTTP_NOT_FOUND);
            }
        }
    }
    public function index_post()
    {
        $data = [
            'nama_lengkap' => $this->post('nama'),
            'tempat_lahir' => $this->post('tmpl'),
            'tanggal_lahir' => $this->post('tgll'),
            'jenis_kelamin' => $this->post('jk'),
            'alamat' => $this->post('alamat')
        ];
        $simpan = $this->jmt->add($data);
        if ($simpan['status']) {
            $this->response(['status' => true, 'msg' => $simpan['data'] . 'Data telah ditambahkan'], RestController::HTTP_CREATED);
        } else {
            $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    public function index_put()
    {
        $data = [
            'nama_lengkap' => $this->put('nama'),
            'tempat_lahir' => $this->put('tmpl'),
            'tanggal_lahir' => $this->put('tgll'),
            'jenis_kelamin' => $this->put('jk'),
            'alamat' => $this->put('alamat')
        ];
        $id = $this->put('id_jemaat');
        if ($id === null) {
            $this->response(['status' => false, 'msg' => 'Masukkan Id Jemaat yang akan dirubah'], RestController::HTTP_BAD_REQUEST);
        }
        $simpan = $this->jmt->update($id, $data);
        if ($simpan['status']) {
            $status = (int)$simpan['data'];
            if ($status > 0) {
                $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah diubah'], RestController::HTTP_OK);
            } else {
                $this->response(['status' => false, 'msg' => 'Tidak ada data yang dirubah'], RestController::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    public function index_delete()
    {
        $id = $this->delete('id_jemaat');
        if ($id === null) {
            $this->response(['status' => false, 'msg' => 'Masukkan Id Jemaat yang akan dihapus'], RestController::HTTP_BAD_REQUEST);
        }
        $delete = $this->jmt->delete($id);
        if ($delete['status']) {
            $status = (int)$delete['data'];
            if ($status > 0) {
                $this->response(['status' => true, 'msg' => $id . ' Data telah dihapus'], RestController::HTTP_OK);
            } else {
                $this->response(['status' => false, 'msg' => 'Tidak ada data yang dihapus'], RestController::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => false, 'msg' => $delete['msg']], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}
