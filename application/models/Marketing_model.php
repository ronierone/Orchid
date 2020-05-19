<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marketing_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function simpanDataPelanggan($data, $gambar, $result)
    {
        $this->db->insert('customer', $data);
        $this->db->insert('dokumen_pelengkap', $gambar);
        $this->db->insert_batch('angsuran_lain', $result);
    }

    function tampilDataPelanggan()
    {
        $query = $this->db->get('customer');
        return $query;
    }

    function search_cust($nama)
    {
        $this->db->like('nama', $nama, 'BOTH');
        $this->db->order_by('nama', 'ASC');
        $this->db->limit(20);
        return $this->db->get('customer')->result();
    }


    function get_unit($id_project)
    {
        $this->db->where('ID_project', $id_project);
        $this->db->where('status', 0);
        $result = $this->db->get('unit')->result();

        return $result;
    }

    function cekidunitdipesan()
    {
        $query = $this->db->query("SELECT MAX(ID_unit_dipesan) as idunitdipesan from unit_dipesan");
        $hasil = $query->row();
        return $hasil->idunitdipesan;
    }
    function cekidvoucher()
    {
        $query = $this->db->query("SELECT MAX(ID_voucher) as idvoucher from voucher");
        $hasil = $query->row();
        return $hasil->idvoucher;
    }
    function cekidangsuranbulanan()
    {
        $query = $this->db->query("SELECT MAX(ID_angsuran_bulanan) as idangsuranbulanan from angsuran_bulanan");
        $hasil = $query->row();
        return $hasil->idangsuranbulanan;
    }
    function cekidinvoicebulanan()
    {
        $query = $this->db->query("SELECT MAX(ID_invoice_angsuran_bulanan) as idinvoiceangsuranbulanan from angsuran_bulanan");
        $hasil = $query->row();
        return $hasil->idinvoiceangsuranbulanan;
    }
    function cekidangsuranbulanandp()
    {
        $query = $this->db->query("SELECT MAX(ID_dp) as idangsurandp from angsuran_dp");
        $hasil = $query->row();
        return $hasil->idangsurandp;
    }
    function cekidinvoicebulanandp()
    {
        $query = $this->db->query("SELECT MAX(ID_invoice_dp) as idinvoiceangsurandp from angsuran_dp");
        $hasil = $query->row();
        return $hasil->idinvoiceangsurandp;
    }
    function cekidinjek()
    {
        $query = $this->db->query("SELECT MAX(ID_injek) as idinjek from angsuran_injek");
        $hasil = $query->row();
        return $hasil->idinjek;
    }
    function cekidinvoiceinjek()
    {
        $query = $this->db->query("SELECT MAX(ID_invoice_injek) as idinvoiceinjek from angsuran_injek");
        $hasil = $query->row();
        return $hasil->idinvoiceinjek;
    }

    function simpanUnitDipilih($id, $no_ktp, $dp, $lama_angsuran_dp, $angsuran_bulanan, $lama_angsuran_bulanan, $total_harga, $ktp_marketing, $id_unit, $id_project, $tanggal_akad, $status_marketing_fee)
    {
        $data = [
            'ID_unit_dipesan' => $id,
            'no_ktp' => $no_ktp,
            'DP' => $dp,
            'lama_angsuran_dp' => $lama_angsuran_dp,
            'angsuran_bulanan' => $angsuran_bulanan,
            'lama_angsuran' => $lama_angsuran_bulanan,
            'total_harga' =>  $total_harga,
            'ktp_marketing' => $ktp_marketing,
            'ID_unit' => $id_unit,
            'ID_project' => $id_project,
            'tanggal_akad' => $tanggal_akad,
            'status_marketing_fee' => $status_marketing_fee
        ];
        $this->db->insert('unit_dipesan', $data);
        $this->marketing->update_unit($id_unit);
        $query = $this->db->query("UPDATE project SET unit_kosong = unit_kosong - 1, unit_isi = unit_isi + 1 WHERE ID_project='$id_project'");
        return $query;
    }

    //simpan voucher
    function tambahvoucher($id, $nama, $nominal, $expired, $max_used)
    {
        $data = [
            'ID_voucher' => $id,
            'nama' => $nama,
            'nominal' => $nominal,
            'expired' => $expired,
            'max_used' => $max_used,
        ];

        $this->db->insert('voucher', $data);
    }
    public function update_unit($id_unit)
    {
        $data = [
            'status' => '1',
        ];
        $this->db->where('ID_unit', $id_unit);
        $this->db->update('unit', $data);
    }

    function proyeksi_angsuran($id, $ktp, $ke, $tanggal, $bulan, $tahun, $nominal, $sisa, $status, $invoice)
    {
        $data = [
            'ID_angsuran_bulanan' => $id,
            'no_ktp' => $ktp,
            'angsuran_ke' => $ke,
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nominal_angsuran_bulanan' => $nominal,
            'sisa_angsuran' => $sisa,
            'status' => $status,
            'ID_invoice_angsuran_bulanan' => $invoice
        ];
        $this->db->insert('angsuran_bulanan', $data);
    }
    function proyeksi_angsuran_dp($id, $ktp, $ke, $tanggal, $bulan, $tahun, $nominal, $sisa, $status, $invoice)
    {
        $data = [
            'ID_dp' => $id,
            'no_ktp' => $ktp,
            'angsuran_ke' => $ke,
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nominal_angsuran_dp' => $nominal,
            'sisa_angsuran' => $sisa,
            'status' => $status,
            'ID_invoice_dp' => $invoice
        ];
        $this->db->insert('angsuran_dp', $data);
    }
    function proyeksi_angsuran_injek($id, $ktp, $ke, $tanggal, $bulan, $tahun, $nominal, $sisa, $status, $invoice)
    {
        $data = [
            'ID_injek' => $id,
            'no_ktp' => $ktp,
            'angsuran_ke' => $ke,
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nominal_injek' => $nominal,
            'sisa_angsuran' => $sisa,
            'status' => $status,
            'ID_invoice_injek' => $invoice
        ];
        $this->db->insert('angsuran_injek', $data);
    }

    function cekidfee()
    {
        $query = $this->db->query("SELECT MAX(ID_marketing_fee) as id from marketing_fee");
        $hasil = $query->row();
        return $hasil->id;
    }
    function tambahDataMF($data)
    {
        $this->db->insert('marketing_fee', $data);
    }
}
