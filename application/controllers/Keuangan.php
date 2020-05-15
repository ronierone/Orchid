<?php defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Keuangan_model', 'keuangan');
        $this->load->model('Marketing_model', 'marketing');
        if ($this->session->userdata('masuk') != TRUE) {
            echo $this->session->set_flashdata('msg', 'Anda Harus Login Terlebih Dahulu !');
            redirect('Auth', 'refresh');
        }
    }

    /**
     * Redirect if needed, otherwise display the user list
     */
    public function index()
    {
        $title = 'Keuangan - General Ledger';
        $data = array(
            'title' => $title,
            'query' => $this->keuangan->tampilDataGL(),
            'query2' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/general_ledger', $data);
    }

    public function cetak_neraca()
    {
        $neraca_kas_kecil = $this->input->post('neraca_kas_kecil');
        $neraca_bank = $this->input->post('neraca_bank');
        $neraca_piutang_usaha = $this->input->post('neraca_piutang_usaha');
        $neraca_piutang_kredit_rumah = $this->input->post('neraca_piutang_kredit_rumah');
        $neraca_piutang_karyawan = $this->input->post('neraca_piutang_karyawan');
        $neraca_uang_muka = $this->input->post('neraca_uang_muka');
        $neraca_barang = $this->input->post('neraca_barang');
        $neraca_pekerjaan = $this->input->post('neraca_pekerjaan');
        $neraca_total_aktiva_lancar = $neraca_bank + $neraca_kas_kecil + $neraca_pekerjaan + $neraca_piutang_karyawan + $neraca_barang + $neraca_piutang_usaha + $neraca_piutang_kredit_rumah + $neraca_uang_muka;
        $neraca_tanah_dan_bangunan = $this->input->post('neraca_tanah_dan_bangunan');
        $neraca_peralatan_kantor = $this->input->post('neraca_peralatan_kantor');
        $neraca_total_aktiva_tidak_lancar = $neraca_tanah_dan_bangunan + $neraca_peralatan_kantor;
        $total_neraca = $neraca_total_aktiva_lancar + $neraca_total_aktiva_tidak_lancar;
        $nama_gl = $this->input->post('project_nrc');
        $bulan = date('m');
        $tahun = date('Y');
        $cetak = date('d/m/Y');
        $nama_bulan = date("F", strtotime('00-' . $bulan . '-01'));
        $nama_pdf = "Laporan_neraca_bulan_" . $nama_bulan;
        $mpdf = new \Mpdf\Mpdf();
        $html = '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    margin-left: auto;
                    margin-right: auto;
                    margin-top: 30px;
                }
                
                td,
                th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
            </style>
        </head>
        
        <body>
            <div style="text-align: center;">
                <div style="float: right;vertical-align:top;margin-left: 40px;word-wrap: break-word;">
                    <p style="line-height: 15px;font-size: 23px;font-weight: bold;">Laporan Neraca Royal Orchid Syariah</p>
                    <p style="line-height: 10px;font-size: 18px;">' . $nama_gl . '</p>
                    <p style="line-height: 10px;font-size: 18px;">Bulan ' . $nama_bulan . ' ' . $tahun . '</p>
                    <p style="line-height: 10px;font-size: 15px;">Dicetak : ' . $cetak . '</p>
                </div>
            </div>
        
            <table>
                <tr>
                    <th colspan="3">Aktiva</th>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Aktiva Lancar</th>
                </tr>
                <tr>
                    <td colspan="2">Kas Kecil</td>
                    <td colspan="1">' . $neraca_kas_kecil . '</td>
                </tr>
                <tr>
                    <td colspan="2">Bank</td>
                    <td colspan="1">' . $neraca_bank . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td colspan="2">Piutang Usaha</td>
                    <td colspan="1">' . $neraca_piutang_usaha . '</td>
                </tr>
                <tr>
                    <td colspan="2">Piutang Usaha Kredit Rumah</td>
                    <td colspan="1">' . $neraca_piutang_kredit_rumah . '</td>
                </tr>
                <tr>
                    <td colspan="2">Piutang Karyawan</td>
                    <td colspan="1">' . $neraca_piutang_karyawan . '</td>
                </tr>
                <tr>
                    <td colspan="2">Uang Muka</td>
                    <td colspan="1">' . $neraca_uang_muka . '</td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Persediaan</th>
                </tr>
                <tr>
                    <td colspan="2">Barang Jadi</td>
                    <td colspan="1">' . $neraca_barang . '</td>
                </tr>
                <tr>
                    <td colspan="2">Pekerjaan dalam progress</td>
                    <td colspan="1">' . $neraca_pekerjaan . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td colspan="2">Total Aktiva Lancar</td>
                    <td colspan="1">' . $neraca_total_aktiva_lancar . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Aktiva Tidak Lancar</th>
                </tr>
                <tr>
                    <td colspan="2">Tanah dan bangunan</td>
                    <td colspan="1">' . $neraca_tanah_dan_bangunan . '</td>
                </tr>
                <tr>
                    <td colspan="2">Peralatan Kantor</td>
                    <td colspan="1">' . $neraca_peralatan_kantor . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <tr>
                        <td colspan="2">Total Aktiva Tidak Lancar</td>
                        <td colspan="1">' . $neraca_total_aktiva_tidak_lancar . '</td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <tr>
                            <td colspan="2">Total Aktiva</td>
                            <td colspan="1">' . $total_neraca . '</td>
                        </tr>
            </table>
        </body>
        
        </html>';
        // Write some HTML code:
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output($nama_pdf, \Mpdf\Output\Destination::INLINE);
    }
    public function cetak_laba()
    {
        $penjualan_LR = $this->input->post('penjualan_LR');
        $harga_pokok_LR = $this->input->post('harga_pokok_LR');
        $laba_bruto = $this->input->post('laba_bruto');
        $lr1 = $this->input->post('lr1');
        $lr2 = $this->input->post('lr2');
        $lr3 = $this->input->post('lr3');
        $lr4 = $this->input->post('lr4');
        $lr5 = $this->input->post('lr5');
        $lr6 = $this->input->post('lr6');
        $lr7 = $this->input->post('lr7');
        $lr8 = $this->input->post('lr8');
        $lr9 = $this->input->post('lr9');
        $lr10 = $this->input->post('lr10');
        $lr11 = $this->input->post('lr11');
        $lr12 = $this->input->post('lr12');
        $lr13 = $this->input->post('lr13');
        $lr14 = $this->input->post('lr14');
        $lr15 = $this->input->post('lr15');
        $lr16 = $this->input->post('lr16');
        $lr17 = $this->input->post('lr17');
        $laba_kotor = $this->input->post('laba_kotor');
        $pajak_penghasilan = $this->input->post('pajak_penghasilan');
        $laba_setelah_pajak = $this->input->post('laba_setelah_pajak');
        $nama_gl = $this->input->post('project_LR');
        $bulan = date('m');
        $tahun = date('Y');
        $cetak = date('d/m/Y');
        $nama_bulan = date("F", strtotime('00-' . $bulan . '-01'));
        $nama_pdf = "Laporan_Penjualan&Laba_bulan_" . $nama_bulan;
        $mpdf = new \Mpdf\Mpdf();
        $html = '<!DOCTYPE html>
            <html lang="en">

            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
            table.tabel2 {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
                margin-top: 30px;
            }
            
            table.table1 {
                border-collapse: collapse;
                width: 100%;
            }
            
            table.tabel2 td,
            th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 5px;
            }
        </style>
        </head>

        <body>
        <table class="table1">
        <tr>
            <td width=15% style="text-align: start; vertical-align: middle;">
                <div style="margin: 10px;">
                    <img src="' . base_url('assets/images/logo3.png') . '" alt="LOGO" width="70px" height="100px">
                </div>
            </td>
            <td>
                <div style="text-align: start; vertical-align: middle;">
                    <p style="line-height: 15px;font-size: 18px;font-weight: bold;">Laporan Penjualan dan Laba Royal Orchid Syariah
                    </p>
                    <p style="line-height: 10px;font-size: 16px;">' . $nama_gl . '</p>
                    <p style="line-height: 10px;font-size: 13px;">Bulan ' . $nama_bulan . ' ' . $tahun . '</p>
                    <p style="line-height: 10px;font-size: 12px;">Dicetak : ' . $cetak . '</p>
                </div>
            </td>
        </tr>
    </table>
        <table class="tabel2">
        <tr>
        <td colspan="2">Penjualan</td>
        <td colspan="1">' . $penjualan_LR . '</td>
        </tr>
        <tr>
        <td colspan="2">Harga Pokok Penjualan</td>
        <td colspan="1">' . $harga_pokok_LR . '</td>
        </tr>
        <tr>
        <td colspan="2">Laba Bruto</td>
        <td colspan="1">' . $laba_bruto . '</td>
        </tr>
        <tr>
        <th colspan="3" style="text-align: center;">Biaya Operasional</th>
        </tr>
        <tr>
        <td colspan="2">Biaya Operasional Kantor</td>
        <td colspan="1">' . $lr1 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Promosi & Marketing</td>
        <td colspan="1">' . $lr2 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Sewa Kantor</td>
        <td colspan="1">' . $lr3 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya marketing_fee</td>
        <td colspan="1">' . $lr4 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Kurir</td>
        <td colspan="1">' . $lr5 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Listrik</td>
        <td colspan="1">' . $lr6 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Gaji Karyawan</td>
        <td colspan="1">' . $lr7 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Perijinan</td>
        <td colspan="1">' . $lr8 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Tukang</td>
        <td colspan="1">' . $lr9 . '</td>
        </tr>
        <tr>
        <td colspan="2">Biaya Sewa Mobil</td>
                <td colspan="1">' . $lr10 . '</td>
                </tr>
                <tr>
                <td colspan="2">Biaya Bensin, Toll dan Parkir</td>
                <td colspan="1">' . $lr11 . '</td>
                </tr>
                <tr>
                <td colspan="2">Biaya Admin Bank</td>
                <td colspan="1">' . $lr12 . '</td>
                </tr>
                <tr>
                <td colspan="2">Pendapatan Bunga</td>
                <td colspan="1">' . $lr13 . '</td>
                </tr>
                <tr>
                <td colspan="2">Biaya Entertaiment</td>
                <td colspan="1">' . $lr14 . '</td>
                </tr>
                <tr>
                <td colspan="2">Biaya Donasi & Sumbangan</td>
                <td colspan="1">' . $lr15 . '</td>
                </tr>
                <tr>
                <td colspan="2">Biaya Pematagan Lahan & Pembangunan</td>
                <td colspan="1">' . $lr16 . '</td>
                </tr>
                <tr>
                <td colspan="2">Biaya Pembebanan Per Unit</td>
                <td colspan="1">' . $lr17 . '</td>
                </tr>
                <tr>
                <td colspan="3"><br></td>
                </tr>
                <tr>
                <td colspan="2">Laba Kotor Sebelum Pajak</td>
                <td colspan="1">' . $laba_kotor . '</td>
                </tr>
                <tr>
                <td colspan="2">Pajak Penghasilan</td>
                <td colspan="1">' . $pajak_penghasilan . '</td>
                </tr>
                <tr>
                <td colspan="2">Laba Setelah Pajak</td>
                <td colspan="1">' . $laba_setelah_pajak . '</td>
                </tr>
                </table>
                </body>

                </html>';
        // Write some HTML code:

        $mpdf->SetHTMLFooter('');
        $mpdf->WriteHTML($html);
        // Output a PDF file directly to the browser
        $mpdf->Output($nama_pdf, \Mpdf\Output\Destination::INLINE);
    }

    public function cetak_pasiva()
    {
        $hutangUsaha        = $this->input->post('hu');
        $hutangKonsiyasi    = $this->input->post('hk');
        $hutangLeverensir   = $this->input->post('hl');
        $uangMuka           = $this->input->post('ump');
        $totalHutangLancar  = $this->input->post('thl');
        $hutangPS           = $this->input->post('hps');
        $totalHutang        = $this->input->post('th');
        $modalDisetor       = $this->input->post('md');
        $modalAdi           = $this->input->post('ma');
        $mohArief           = $this->input->post('mar');
        $samsunar           = $this->input->post('s');
        $adiDharma          = $this->input->post('ad');
        $saldoLaba          = $this->input->post('sld');
        $totalModal         = $this->input->post('tm');
        $totalPasiva        = $this->input->post('tpas');
        $nama_gl            = $this->input->post('project_pasiva');
        $bulan              = date('m');
        $tahun              = date('Y');
        $cetak              = date('d/m/Y');
        $nama_bulan         = date("F", strtotime('00-' . $bulan . '-01'));
        $nama_pdf           = "Laporan_neraca_bulan_" . $nama_bulan;
        $mpdf               = new \Mpdf\Mpdf();
        $html               = '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    margin-left: auto;
                    margin-right: auto;
                    margin-top: 30px;
                }
                
                td,
                th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
            </style>
        </head>
        
        <body>
            <div style="text-align: center;">
                <div style="float: right;vertical-align:top;margin-left: 40px;word-wrap: break-word;">
                    <p style="line-height: 15px;font-size: 23px;font-weight: bold;">Laporan Pasiva Royal Orchid Syariah</p>
                    <p style="line-height: 10px;font-size: 18px;">' . $nama_gl . '</p>
                    <p style="line-height: 10px;font-size: 18px;">Bulan ' . $nama_bulan . ' ' . $tahun . '</p>
                    <p style="line-height: 10px;font-size: 15px;">Dicetak : ' . $cetak . '</p>
                </div>
            </div>
        
            <table>
                <tr>
                    <th colspan="3" style="text-align: center;">Hutang Lancar</th>
                </tr>
                <tr>
                    <td colspan="2">Hutang Usaha</td>
                    <td colspan="1">' . $hutangUsaha . '</td>
                </tr>
                <tr>
                    <td colspan="2">Hutang Konsiyasi</td>
                    <td colspan="1">' . $hutangKonsiyasi . '</td>
                </tr>
                <tr>
                    <td colspan="2">Hutang Leverensir</td>
                    <td colspan="1">' . $hutangLeverensir . '</td>
                </tr>
                <tr>
                    <td colspan="2">Uang Muka Penjualan</td>
                    <td colspan="1">' . $uangMuka . '</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Hutang Lancar</strong></td>
                    <td colspan="1">' . $totalHutangLancar . '</td>
                </tr>
                <tr>
                    <td colspan="2">Hutang Pemegang Saham</td>
                    <td colspan="1">' . $hutangPS . '</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Hutang</strong></td>
                    <td colspan="1">' . $totalHutang . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Modal</th>
                </tr>
                <tr>
                    <td colspan="2">Modal Disetor</td>
                    <td colspan="1">' . $modalDisetor . '</td>
                </tr>
                <tr>
                    <td colspan="2">- Modal Adi</td>
                    <td colspan="1">' . $modalAdi . '</td>
                </tr>
                <tr>
                    <td colspan="2">- Mohamad arief</td>
                    <td colspan="1">' . $mohArief . '</td>
                </tr>
                <tr>
                    <td colspan="2">- Samsunar</td>
                    <td colspan="1">' . $samsunar . '</td>
                </tr>
                <tr>
                    <td colspan="2">- Adi Dharmar</td>
                    <td colspan="1">' . $adiDharma . '</td>
                </tr>
                <tr>
                    <td colspan="2">Saldo Laba Ditahan</td>
                    <td colspan="1">' . $saldoLaba . '</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Modal</strong></td>
                    <td colspan="1">' . $totalModal . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Pasiva</strong></td>
                    <td colspan="1">' . $totalPasiva . '</td>
                </tr>
            </table>
        </body>
        </html>';
        // Write some HTML code:
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output($nama_pdf, \Mpdf\Output\Destination::INLINE);
    }
    public function cetak_cf()
    {
        $pembayaranSup      = $this->input->post('CF1');
        $pembayaranK        = $this->input->post('CF2');
        $pembayaranPajak    = $this->input->post('CF3');
        $penerimaanPel      = $this->input->post('CF4');
        $setoranPS          = $this->input->post('CF5');
        $totalOC            = $this->input->post('CF6');
        $pembelianAset      = $this->input->post('CF7');
        $penjualanAset      = $this->input->post('CF8');
        $totalIC            = $this->input->post('CF9');
        $pembayaranB        = $this->input->post('CF10');
        $pembayaranPS       = $this->input->post('CF11');
        $pinjamanB          = $this->input->post('CF12');
        $pinjamanPS         = $this->input->post('CF13');
        $totalFI            = $this->input->post('CF14');
        $totalC             = $this->input->post('CF15');
        $nama_gl            = $this->input->post('project_cf');
        $bulan              = date('m');
        $tahun              = date('Y');
        $cetak              = date('d/m/Y');
        $nama_bulan         = date("F", strtotime('00-' . $bulan . '-01'));
        $nama_pdf           = "Laporan_neraca_bulan_" . $nama_bulan;
        $mpdf               = new \Mpdf\Mpdf();
        $html               = '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    margin-left: auto;
                    margin-right: auto;
                    margin-top: 30px;
                }
                
                td,
                th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
            </style>
        </head>
        
        <body>
            <div style="text-align: center;">
                <div style="float: right;vertical-align:top;margin-left: 40px;word-wrap: break-word;">
                    <p style="line-height: 15px;font-size: 23px;font-weight: bold;">Laporan Cash Flow Royal Orchid Syariah</p>
                    <p style="line-height: 10px;font-size: 18px;">' . $nama_gl . '</p>
                    <p style="line-height: 10px;font-size: 18px;">Bulan ' . $nama_bulan . ' ' . $tahun . '</p>
                    <p style="line-height: 10px;font-size: 15px;">Dicetak : ' . $cetak . '</p>
                </div>
            </div>
        
            <table>
                <tr>
                    <th colspan="3" style="text-align: center;">Dari Oprasional</th>
                </tr>
                <tr>
                    <td colspan="2">Pembayaran Supplier</td>
                    <td colspan="1">' . $pembayaranSup . '</td>
                </tr>
                <tr>
                    <td colspan="2">Pembayaran Pepada Paryawan</td>
                    <td colspan="1">' . $pembayaranK . '</td>
                </tr>
                <tr>
                    <td colspan="2">Pembayaran Pajak</td>
                    <td colspan="1">' . $pembayaranPajak . '</td>
                </tr>
                <tr>
                    <td colspan="2">Penerimaan dari Pelanggan</td>
                    <td colspan="1">' . $penerimaanPel . '</td>
                </tr>
                <tr>
                    <td colspan="2">Setoran Pemegang Saham</td>
                    <td colspan="1">' . $setoranPS . '</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Operasional Cash Flow</strong></td>
                    <td colspan="1">' . $totalOC . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Dari Investasi</th>
                </tr>
                <tr>
                    <td colspan="2">Pembelian Aset Tetap</td>
                    <td colspan="1">' . $pembelianAset . '</td>
                </tr>
                <tr>
                    <td colspan="2">Penjualan Aset tetap</td>
                    <td colspan="1">' . $penjualanAset . '</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Investing Cash Flow</strong></td>
                    <td colspan="1">' . $totalIC . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: center;">Financing</th>
                </tr>
                <tr>
                    <td colspan="2">Pembayaran Bank</td>
                    <td colspan="1">' . $pembayaranB . '</td>
                </tr>
                <tr>
                    <td colspan="2">Pembayaran Pemegang Saham</td>
                    <td colspan="1">' . $pembayaranPS . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td colspan="2">Pinjaman Bank</td>
                    <td colspan="1">' . $pinjamanB . '</td>
                </tr>
                <tr>
                    <td colspan="2">Pinjaman Pemegang Saham</td>
                    <td colspan="1">' . $pinjamanPS . '</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Financing Cash Floww</strong></td>
                    <td colspan="1">' . $totalFI . '</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Cash Floww</strong></td>
                    <td colspan="1">' . $totalC . '</td>
                </tr>
            </table>
        </body>
        </html>';
        // Write some HTML code:
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output($nama_pdf, \Mpdf\Output\Destination::INLINE);
    }

    public function journal()
    {
        $title = 'Keuangan - Journal';
        $data = array(
            'title' => $title,
            'query1' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/journal', $data);
    }
    public function addendum()
    {
        $title = 'Keuangan - Addendum';
        $data = array(
            'title' => $title,
            'query1' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/addendum', $data);
    }
    public function angsuran()
    {
        $title = 'Keuangan - Bayar Angsuran';
        $data = array(
            'title' => $title,
        );
        $this->template->load('layout/template_v', 'keuangan/angsuran', $data);
    }

    public function bayar_po()
    {
        $title = 'Keuangan - Bayar PO';
        $data = array(
            'title' => $title,
            'query' => $this->keuangan->bayarPO(),
        );
        $this->template->load('layout/template_v', 'keuangan/bayar_po', $data);
    }
    public function laporan_laba_rugi()
    {
        $title = 'Keuangan - Laporan Keuangan';
        $data = array(
            'title' => $title,
            'query' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/laba_rugi', $data);
    }
    public function laporan_neraca()
    {
        $title = 'Keuangan - Laporan Keuangan';
        $data = array(
            'title' => $title,
            'query' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/neraca', $data);
    }

    public function laporan_pasiva()
    {
        $title = 'Keuangan - Laporan Keuangan';
        $data = array(
            'title' => $title,
            'query' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/pasiva', $data);
    }
    public function laporan_cashflow()
    {
        $title = 'Keuangan - Laporan Keuangan';
        $data = array(
            'title' => $title,
            'query' => $this->db->get('project')->result(),
        );
        $this->template->load('layout/template_v', 'keuangan/cashflow', $data);
    }
    function updatePO()
    {
        $config['upload_path'] = './assets/images/bukti_pembayaran/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['encrypt_name'] = TRUE; //Enkripsi nama yang terupload
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!empty($_FILES['bukti_bayar']['name'])) {

            if ($this->upload->do_upload('bukti_bayar')) {
                $gbr = $this->upload->data();
                $gambar             = $gbr['file_name'];
            }
        }
        $bukti = $gambar;
        $id_keuangan = $this->session->userdata('ktp');
        $id = $this->input->post('idPO');
        $this->keuangan->updatePO($id, $id_keuangan, $bukti);
        echo $this->session->set_flashdata('msg', 'success-add-data');
        redirect('Keuangan/bayar_po');
    }


    public function tambahgl()
    {
        $this->form_validation->set_rules('nomor', 'Nomor', 'required|trim|is_unique[general_ledger.nomor]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('nominal', 'Nominal', 'required|trim');

        if ($this->form_validation->run() == false) {
            echo $this->session->set_flashdata('msg', 'error-register');
            redirect('Home/keuangan', 'refresh');
        } else {
            $namagl = $this->input->post('project_GL');
            $data = [
                'nomor' => $this->input->post('nomor'),
                'nama' => $this->input->post('nama'),
                'nominal' => $this->input->post('nominal')
            ];
            $this->keuangan->simpanDataGL($data, $namagl);
            echo $this->session->set_flashdata('msg', 'success-add-data');
            redirect('Home/keuangan');
        }
    }
    function get_autocomplete()
    {
        if (isset($_GET['term'])) {
            $result = $this->keuangan->search_cust($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label' => $row->nama,
                        'no_ktp' => $row->no_ktp,
                        'id_invoice' => $row->ID_invoice_dp,
                        'id_angsuran' => $row->ID_dp,
                        'nominal_pembayaran' => $row->nominal_angsuran_dp,
                    );
                echo json_encode($arr_result);
            } else {
                $res = $this->keuangan->search_bulanan($_GET['term']);
                if (count($res) > 0) {
                    foreach ($res as $row)
                        $arr_result[] = array(
                            'label' => $row->nama,
                            'no_ktp' => $row->no_ktp,
                            'id_invoice' => $row->ID_invoice_angsuran_bulanan,
                            'id_angsuran' => $row->ID_angsuran_bulanan,
                            'nominal_pembayaran' => $row->nominal_angsuran_bulanan,
                        );
                    echo json_encode($arr_result);
                }
            }
        }
    }

    function get_gl()
    {
        if (isset($_GET['term'])) {
            $result = $this->keuangan->search_gl($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label' => $row->nama,
                        'nomor' => $row->nomor,
                    );
                echo json_encode($arr_result);
            }
        }
    }

    function tambahjournal()
    {
        $id_project = $this->input->post('project_journal');
        $nomor_gl = $this->input->post('nomor_gl');
        $nomor_gl2 = $this->input->post('nomor_gl2');
        $nama_gl = $this->input->post('nama_gl');
        $nama_gl2 = $this->input->post('nama_gl2');
        $debit = preg_replace('/[Rp.]/', '', $this->input->post('debit_journal'));
        $kredit = preg_replace('/[Rp.]/', '', $this->input->post('kredit_journal'));
        $keterangan = $this->input->post('keterangan_journal');
        $tanggal = date("d/m/Y");
        $pembanding = 2;

        //get nama gl
        $dbnya = $this->keuangan->ceknamagl($id_project);

        //ambil ID data journal terbesar
        $dariDB = $this->keuangan->cekidjournal();
        $nourut = substr($dariDB, 3, 4);
        $kode1 =  $nourut + 1;
        $kodenya = sprintf("%04s", $kode1);
        $strkodenya = 'IJN' . $kodenya;

        //ceksaldo
        $dariDB2 = $this->keuangan->ceksaldo($nomor_gl, $dbnya);
        $debitnya = intval($dariDB2) + $debit;
        $this->keuangan->tambahjournal($strkodenya, $nomor_gl, $nama_gl, $debit, "0", $keterangan, $tanggal, $id_project, $debitnya, $dbnya);

        //ambil ID data journal terbesar
        $dariDB = $this->keuangan->cekidjournal();
        $nourut = substr($dariDB, 3, 4);
        $kode1 =  $nourut + 1;
        $kodenya = sprintf("%04s", $kode1);
        $strkodenya = 'IJN' . $kodenya;


        if ($nama_gl2 == "Bank") {
            $dariDB2 = $this->keuangan->ceksaldo($nomor_gl2, $dbnya);
            $kreditnya = intval($dariDB2) - $kredit;
        } else {
            $dariDB2 = $this->keuangan->ceksaldo($nomor_gl2, $dbnya);
            $kreditnya = intval($dariDB2) + $kredit;
        }

        $this->form_validation->set_rules('project_journal', 'Project Journal', 'required|trim');
        $this->form_validation->set_rules('nomor_gl', 'Nomor General Ledger', 'required|trim');
        $this->form_validation->set_rules('nomor_gl2', 'Nomor General Ledger 2', 'required|trim');
        $this->form_validation->set_rules('nama_gl', 'Nama General Ledger', 'required|trim');
        $this->form_validation->set_rules('nama_gl2', 'Nama General Ledger 2', 'required|trim');
        $this->form_validation->set_rules('debit_journal', 'Debit Journal', 'required|trim');
        $this->form_validation->set_rules('kredit_journal', 'Kredit Journal', 'required|trim');
        $this->form_validation->set_rules('keterangan_journal', 'Keterangan Journal', 'required|trim');


        $query = $this->keuangan->tambahjournal($strkodenya, $nomor_gl2, $nama_gl2, "0", $kredit, $keterangan, $tanggal, $id_project, $kreditnya, $dbnya);

        echo $this->session->set_flashdata('msg', 'success-add-data');
        redirect('Keuangan/journal');
    }

    function tambahangsuran()
    {
        $nama = $this->input->post('nama_angsuran');
        $idinvoice = $this->input->post('id_invoice');
        $idbayar = $this->input->post('id_angsuran');
        $tanggal_bayar = date('d-m-Y');
        $nominal = $this->input->post('nominal_pembayaran');
        $type = $this->input->post('type_bayar_angsuran');
        $nama_bank = $this->input->post('nama_bank_angsuran');
        $nomor_bank = $this->input->post('nomor_bank_angsuran');
        $hasil_rupiah = number_format($nominal, 2, ',', '.');
        $this->form_validation->set_rules('id_invoice', 'ID Invoice Angsuran', 'required|trim');
        $this->form_validation->set_rules('id_angsuran', 'ID Angsuran', 'required|trim');
        $this->form_validation->set_rules('nominal_pembayaran', 'Nominal Pembayran', 'required|trim');
        $this->form_validation->set_rules('type_bayar_angsuran', 'Type Bayar Angsuran', 'required|trim');
        // $this->form_validation->set_rules('nama_bank_angsuran', 'Nama BANK Angsuran', 'required|trim');
        // $this->form_validation->set_rules('nomor_bank_angsuran', 'Nomor BANK Angsuran', 'required|trim');
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [210, 148]]);
        $nama_pdf = "Invoice_Pembayaran";
        $html = '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
                table.tabel2 {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    margin-left: auto;
                    margin-right: auto;
                    margin-top: 10px;
                }
                
                table.table1 {
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                
                table.tabel2 td,
                th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 3px;
                }
            </style>
        </head><body>
        <table class="table1">
        <tr>
            <td width=13% style="text-align: start; vertical-align: middle;">
                <div style="margin: 10px;">
                <img src="' . base_url('assets/images/logo3.png') . '" alt="LOGO" width="60px" height="80px">
                </div>
            </td>
            <td width=70% style="text-align: start; vertical-align: middle;">
                <div style="text-align: start; vertical-align: middle;">
                    <p style="line-height: 15px;font-size: 18px;font-weight: bold;">Royal Orchid Syariah
                    </p>
                    <p style="line-height: 10px;font-size: 13px;">JL. Reog No.17A</p>
                    <p style="line-height: 10px;font-size: 13px;">Telp (022)87352781</p>
                    <p style="line-height: 10px;font-size: 13px;">www.royalorchidsyariah.com</p>
                </div>
            </td>
            <td width=20% style="text-align: start;">
                <div style="text-align: start; vertical-align: middle;">
                    <p style="line-height: 15px;font-size: 18px;font-weight: bold;">Invoice Pembayaran
                    </p>
                    <p style="line-height: 10px;font-size: 13px;">Nomor : ' . $idinvoice . '</p>
                </div>
            </td>
        </tr>
    </table>
            <table class="tabel2" style:"margin-top:10px">
                <tr>
                    <td width=30%>Sudah diterima dari :</td>
                    <td width=80%>' . $nama . '</td>
                </tr>
                <tr>
                    <td width=30%>Banyaknya :</td>
                    <td width=80%>1</td>
                </tr>
                <tr>
                    <td width=30%> Untuk pembayaran :</td>
                    <td width=80%>Pembayaran Angsuran</td>
                </tr>
                <tr>
                    <td width=30% style="font-size: 18px; font-weight: bold;">Rp.</td>
                    <td width=80% style="font-size: 18px; font-weight: bold;">' . $hasil_rupiah . '</td>
                </tr>
                <tr>
                <td width=30%>Jenis bayar :</td>
                <td width=80%>' . $type . '</td>
                </tr>
                <tr>
                <td width=30%>Nama Bank :</td>
                <td width=80%>' . $nama_bank . '</td>
                </tr>
                <tr>
                <td width=30%> Nomor Rekening :</td>
                <td width=80%>' . $nomor_bank . '</td>
                </tr>
            </table>
            <table width="100%" style="vertical-align: bottom;margin-top:10px">
    <tr>
        <td width="33%"></td>
        <td width="33%" align="center"></td>
        <td width="33%" style="text-align: right;">
        <div>
            <p>Bandung, ' . $tanggal_bayar . '</p>
            <br><br><br><br>
            <p>(' . $nama . ')</p>
        </div></td>
    </tr>
</table>
        </body>
        </html>
            ';
        // Write some HTML code:

        $mpdf->WriteHTML($html);
        $mpdf->Output($nama_pdf, \Mpdf\Output\Destination::INLINE);
        // Output a PDF file directly to the browser

        if ($this->form_validation->run() == false) {
            echo $this->session->set_flashdata('msg', 'error-register');
            redirect('Keuangan/angsuran');
        } else {
            $this->keuangan->bayarangsuran($idinvoice, $idbayar, $tanggal_bayar, $nominal, $type, $nama_bank, $nomor_bank);
            echo $this->session->set_flashdata('msg', 'success-add-data');
            redirect('Keuangan/angsuran');
        }
    }

    function sort_gl()
    {
        $project        =  $_GET['project_GL'];
        $data           =  $this->db->get($project)->result();
        echo "<tr><th>Nomor GL</th><th>Nama GL</th><th>Nominal GL</th>";
        foreach ($data as $r) {

            echo "<tr>
                <td>" .  strtoupper($r->nomor) . "</td>
                <td>" .  strtoupper($r->nama) . "</td>
                <td>" .  strtoupper($r->nominal) . "</td>";

            echo "</tr>";
        }
    }
    function sort_gl_utama()
    {
        $data = $this->db->get('general_ledger')->result();
        echo "<tr><th>Nomor GL</th><th>Nama GL</th><th>Nominal GL</th>";
        foreach ($data as $r) {

            echo "<tr>
                <td>" .  strtoupper($r->nomor) . "</td>
                <td>" .  strtoupper($r->nama) . "</td>
                <td>" .  strtoupper($r->nominal) . "</td>";

            echo "</tr>";
        }
    }
    function rubah_angsuran()
    {
        $ktp        = $_GET['no_ktp_addendum'];
        $data = $this->keuangan->rubah_angsuran($ktp)->result();
        $injek = $this->keuangan->rubah_injek($ktp)->result();
        // foreach ($injek as $i) {
        //     echo "<div class='form-group col-3'>";
        //     echo "<label for='exampleFormControlInput1'>Sisa Angsuran Injek</label>";
        //     echo "<input type='text' class='form-control' id='sisa_angsuran_injek_addendum_angsuran' name='sisa_angsuran_injek_addendum_angsuran' value='$i->nominal' readonly>";
        //     echo "</div>";
        // }
        foreach ($data as $r) {
            echo "<div class='form-group col-3'>";
            echo "<label for='exampleFormControlInput1'>Sisa Angsuran Sebelumnya</label>";
            echo "<input type='text' class='form-control' value='$r->nominal'  id='sisa_angsuran_sebelumnya_addendum' name='sisa_angsuran_sebelumnya_addendum' readonly>";
            echo "</div>";
        }
    }
    function rubah_injek()
    {
        $ktp        = $_GET['no_ktp_addendum'];
        $data = $this->keuangan->rubah_injek($ktp)->result();
        $bulan = $this->keuangan->rubah_angsuran($ktp)->result();
        $lama = $this->keuangan->update_injek($ktp)->result();

        foreach ($data as $r) {
            echo "<div class='form-group col-2'>";
            echo "<label for='exampleFormControlInput1'>Sisa Angsuran Injek</label>";
            echo "<input type='text' class='form-control' id='sisa_angsuran_injek_addendum' name='sisa_angsuran_injek_addendum' value='$r->nominal' readonly>";
            echo "</div>";
        }
        foreach ($bulan as $b) {
            echo "<div class='form-group col-2'>";
            echo "<label for='exampleFormControlInput1'>Sisa Angsuran Pokok</label>";
            echo "<input type='text' class='form-control' id='sisa_angsuran_pokok_addendum' name='sisa_angsuran_pokok_addendum' value='$b->nominal' readonly>";
            echo "</div>";
        }
        foreach ($lama as $l) {
            echo "<div class='form-group col-2'>";
            echo "<label for='exampleFormControlInput1'>Lama angsuran</label>";
            echo "<input type='text' class='form-control' id='lama_angsuran_pokok_addendum' name='lama_angsuran_pokok_addendum' value='$l->sisa_angsuran' readonly>";
            echo "</div>";
        }
    }
    function rubah_unit()
    {
        $ktp        = $_GET['no_ktp_addendum'];
        $data = $this->keuangan->rubah_unit($ktp)->result();

        foreach ($data as $r) {
            echo "<input type='text' class='form-control' id='unit_sebelumnya_addendum' name='unit_sebelumnya_addendum' value='$r->ID_unit' readonly>";
        }
    }
    function pilih_unit()
    {
        $ktp        = $_GET['no_ktp_addendum'];
        $data = $this->keuangan->pilih_unit($ktp)->result();
        echo "<select class='custom-select my-1 mr-sm-2' id='unit_baru_addendum' name='unit_baru_addendum'>";
        echo "<option selected>Pilih Project</option>";
        foreach ($data as $r) {
            echo "<option value='$r->ID_unit'>$r->ID_unit</option>";
        }
        echo "</select>";
    }
    function rubah_project()
    {
        $ktp        = $_GET['no_ktp_addendum'];
        $data = $this->keuangan->rubah_project($ktp)->result();

        foreach ($data as $r) {
            echo "<input type='text' class='form-control' id='project_sebelumnya_addendum' name='project_sebelumnya_addendum' value='$r->ID_project' readonly>";
        }
    }
    function get_unit()
    {
        // Ambil data ID Project yang dikirim via ajax post
        $id_project = $this->input->post('id_project');

        $unit = $this->keuangan->get_unit($id_project);

        // Buat variabel untuk menampung tag-tag option nya
        // Set defaultnya dengan tag option Pilih
        $lists = "<option value=''>Pilih Unit</option>";

        foreach ($unit as $data) {
            $lists .= "<option value='" . $data->ID_unit . "'>" . 'Nomor : ' . $data->nomor . ' / ' . 'Type : ' . $data->type . "</option>"; // Tambahkan tag option ke variabel $lists
        }

        $callback = array('list_unit' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

        echo json_encode($callback); // konversi varibael $callback menjadi JSON
    }

    function tambah_addendum()
    {

        $opsinya = $this->input->post('jenis_addendum');
        $no_ktp = $this->input->post('no_ktp_addendum');
        $unit_baru = $this->input->post('unit_baru_addendum');
        $unit_lama = $this->input->post('unit_sebelumnya_addendum');
        $unit_dipilih_project = $this->input->post('unit_baru_project_addendum');
        $project_baru = $this->input->post('project_baru_addendum');
        $project_sebelumnya = $this->input->post('project_sebelumnya_addendum');
        $sisa_angsuran_injek = intval($this->input->post('sisa_angsuran_injek_addendum'));
        $sisa_angsuran_bulanan = intval($this->input->post('sisa_angsuran_pokok_addendum'));
        $sisa_total = $sisa_angsuran_bulanan + $sisa_angsuran_injek;
        $injek_baru = intval($this->input->post('injek_baru_addendum'));
        $lama_injek_baru = intval($this->input->post('lama_injek_baru_addendum'));
        $totalnya = $sisa_total - ($injek_baru * $lama_injek_baru);
        $total_injek = $injek_baru * $lama_injek_baru;

        $lamanya_angsuran = $this->input->post('lama_angsuran_pokok_addendum');
        $total = $totalnya / $lamanya_angsuran;
        $bagi = $total / 1000;
        $dibulatkan = floor($bagi);
        $hasilnya = $dibulatkan * 1000;


        switch ($opsinya) {
            case 'rubah_angsuran':
                //ambil ID data angsuran terbesar
                $dariDB = $this->marketing->cekidangsuranbulanan();
                $nourut = substr($dariDB, 3, 4);
                //mengambil data invoice terbesar
                $dariDB2 = $this->marketing->cekidinvoicebulanan();
                $nourut2 = substr($dariDB2, 3, 4);
                $lama_bulanan = $this->input->post('lama_angsuran_bulanan_addendum');
                $tanggal =     date('d');
                $bulan = date('m');
                $tahun = date('y');
                $nominal_angsuran_bulanan = $this->input->post('angsuran_baru_addendum');
                $sisa_angsuran_sebelumnya = $this->input->post('sisa_angsuran_sebelumnya_addendum');
                $harganya = $sisa_angsuran_sebelumnya;
                $status = 0;
                $this->keuangan->update_addendum_angsuran($no_ktp);

                for ($i = 1; $i <= $lama_bulanan; $i++) {
                    //penentuan ID_angsuran_bulanan + Invoice otomatis
                    $kode1 =  $nourut + 1;
                    $kodenya = sprintf("%04s", $kode1);
                    $strkodenya = 'AB' . $kodenya;
                    $kodeinvoice = $nourut2 + 1;
                    $kodenyainvoice = sprintf("%04s", $kodeinvoice);
                    $strkodeinvoice = "IAB" . $kodenyainvoice;
                    //akhir penentuan ID_angsuran_bulanan + Invoice otomatis
                    $angsuran_ke = $i;        //angsuran ke---
                    $sesudah = $bulan + 1;
                    if ($sesudah > 12) { //jika bulan sudah lebih dari 12 , maka balik lagi menjadi 1
                        $sesudah = 1;
                        $tahun = $tahun + 1; //tahun bertambah jika bulan mencapai 12 dan balik menjadi 1
                    }
                    $sisa_angsuran = $harganya - $nominal_angsuran_bulanan;        //pengurangan sisa angsuran

                    if ($sisa_angsuran < $nominal_angsuran_bulanan) {
                        $nominal_angsuran_bulanan = $harganya;
                        $sisa_angsuran = $harganya - $nominal_angsuran_bulanan;
                    } else {
                        $harganya = $sisa_angsuran;
                    }
                    //sisa angsuran menjadi harga acuan untuk di kurangai angsuran
                    $bulan = $sesudah;                //merubah bulan menjadi bulan yang sudah di tambah
                    $nourut = $kode1;                //merubah nomor urut menjadi yang sudah di tambah
                    $nourut2 = $kodeinvoice;        //merubah invoice  menjadi yang sudah di tambah
                    $this->marketing->proyeksi_angsuran($strkodenya, $no_ktp, $angsuran_ke, $tanggal, $bulan, $tahun, $nominal_angsuran_bulanan, $sisa_angsuran, $status, $strkodeinvoice);
                }
                echo $this->session->set_flashdata('msg', 'success-add-data');
                redirect('Keuangan/addendum');
                break;

            case 'rubah_unit':
                $this->keuangan->update_addendum_unit_dipesan($no_ktp, $unit_baru);
                $this->keuangan->update_addendum_unit($unit_lama);
                $this->keuangan->update_addendum_unit_tambah($unit_baru);
                echo $this->session->set_flashdata('msg', 'success-add-data');
                redirect('Keuangan/addendum');
                break;
            case 'rubah_project':
                $this->keuangan->update_addendum_project($no_ktp, $unit_dipilih_project, $project_baru, $project_sebelumnya);
                echo $this->session->set_flashdata('msg', 'success-add-data');
                redirect('Keuangan/addendum');
                break;

            case 'rubah_injek':
                //ambil ID data angsuran injek
                $dariDB5 = $this->marketing->cekidinjek();
                $nourut5 = substr($dariDB5, 3, 4);
                //mengambil data invoice terbesar injek
                $dariDB6 = $this->marketing->cekidinvoiceinjek();
                $nourut6 = substr($dariDB6, 3, 4);
                $this->keuangan->update_addendum_angsuran($no_ktp);
                $this->keuangan->update_addendum_injek($no_ktp);
                $tanggal =     date('d');
                $bulan = date('m');
                $tahun = date('y');
                $status = 0;
                for ($i = 1; $i <= $lama_injek_baru; $i++) {
                    //penentuan ID_angsuran_bulanan + Invoice otomatis

                    $kode1 =  $nourut5 + 1;
                    $kodenya = sprintf("%04s", $kode1);
                    $strkodenya = 'AIJ' . $kodenya;
                    $kodeinvoice = $nourut6 + 1;
                    $kodenyainvoice = sprintf("%04s", $kodeinvoice);
                    $strkodeinvoice = "IIJ" . $kodenyainvoice;
                    $nourut5 = $kode1;                //merubah nomor urut menjadi yang sudah di tambah
                    $nourut6 = $kodeinvoice;        //merubah invoice  menjadi yang sudah di tambah
                    $angsuran_ke = $i;

                    $sisa_angsuran = $total_injek - $injek_baru;
                    $tahunnya = $tahun + 1;
                    $tahun = $tahunnya;
                    $this->marketing->proyeksi_angsuran_injek($strkodenya, $no_ktp, $angsuran_ke, $tanggal, $bulan, $tahunnya, $injek_baru, $sisa_angsuran, $status, $strkodeinvoice);
                    $total_injek = $sisa_angsuran;
                }
                for ($i = 1; $i <= $lamanya_angsuran; $i++) {
                    //ambil ID data angsuran terbesar
                    $dariDB = $this->marketing->cekidangsuranbulanan();
                    $nourut = substr($dariDB, 3, 4);
                    //mengambil data invoice terbesar
                    $dariDB2 = $this->marketing->cekidinvoicebulanan();
                    $nourut2 = substr($dariDB2, 3, 4);
                    //penentuan ID_angsuran_bulanan + Invoice otomatis
                    $kode1 =  $nourut + 1;
                    $kodenya = sprintf("%04s", $kode1);
                    $strkodenya = 'AB' . $kodenya;
                    $kodeinvoice = $nourut2 + 1;
                    $kodenyainvoice = sprintf("%04s", $kodeinvoice);
                    $strkodeinvoice = "IAB" . $kodenyainvoice;
                    //akhir penentuan ID_angsuran_bulanan + Invoice otomatis
                    $angsuran_ke = $i;        //angsuran ke---
                    $sesudah = $bulan + 1;
                    if ($sesudah > 12) { //jika bulan sudah lebih dari 12 , maka balik lagi menjadi 1
                        $sesudah = 1;
                        $tahun = $tahun + 1; //tahun bertambah jika bulan mencapai 12 dan balik menjadi 1
                    }
                    $sisa_angsuran = $totalnya - $hasilnya;        //pengurangan sisa angsuran

                    if ($sisa_angsuran < $hasilnya) {
                        $hasilnya = $totalnya;
                        $sisa_angsuran = $totalnya - $hasilnya;
                    } else {
                        $totalnya = $sisa_angsuran;
                    }
                    //sisa angsuran menjadi harga acuan untuk di kurangai angsuran
                    $bulan = $sesudah;                //merubah bulan menjadi bulan yang sudah di tambah
                    $nourut = $kode1;                //merubah nomor urut menjadi yang sudah di tambah
                    $nourut2 = $kodeinvoice;        //merubah invoice  menjadi yang sudah di tambah
                    $this->marketing->proyeksi_angsuran($strkodenya, $no_ktp, $angsuran_ke, $tanggal, $bulan, $tahun, $hasilnya, $sisa_angsuran, $status, $strkodeinvoice);
                }
                echo $this->session->set_flashdata('msg', 'success-add-data');
                redirect('Keuangan/addendum');
                break;
        }
    }
    //awal neraca
    function sort_neraca()
    {
        $project        =  $_GET['project_nrc'];
        $neraca_kas = $this->keuangan->pilih_neraca_kas_kecil($project)->result();
        $neraca_bank = $this->keuangan->pilih_neraca_bank($project)->result();
        $neraca_piutang_usaha = $this->keuangan->pilih_neraca_piutang_usaha($project)->result();
        $neraca_piutang_kredit_rumah = $this->keuangan->pilih_neraca_piutang_kredit_rumah($project)->result();
        $neraca_piutang_karyawan = $this->keuangan->pilih_neraca_piutang_karyawan($project)->result();
        $neraca_uang_muka = $this->keuangan->pilih_neraca_uang_muka($project)->result();
        $neraca_barang = $this->keuangan->pilih_neraca_barang_jadi($project)->result();
        $neraca_pekerjaan = $this->keuangan->pilih_neraca_pekerjaan_dalam_progress($project)->result();
        $neraca_tanah_dan_bangunan = $this->keuangan->pilih_neraca_tanah_dan_bangunan($project)->result();
        $neraca_peralatan_kantor = $this->keuangan->pilih_biaya_sewa_kantor($project)->result();


        //neraca
        foreach ($neraca_kas as $na) {
            echo "<div class='col-12'>";
            echo "<h4 style='text-align: center' class='col-12'>Aktiva</h4>";
            echo "<br>";
            echo "</div>";
            echo "<br>";
            echo "<div>";
            echo "<h6 class='col-12'>Aktiva Lancar</h6>";
            echo "</div>";
            echo "<br>";
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Kas Kecil</label>";
            echo "<input type='text' class='form-control' id='neraca_kas_kecil' name='neraca_kas_kecil' value='$na->nominal' readonly>";
            echo "</div>";
            $tna = $na->nominal;
        }
        foreach ($neraca_bank as $nb) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Bank</label>";
            echo "<input type='text' class='form-control' id='neraca_bank' name='neraca_bank' value='$nb->nominal' readonly>";
            echo "</div>";
            $tnb = $nb->nominal;
        }
        foreach ($neraca_piutang_usaha as $nc) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Piutang Usaha</label>";
            echo "<input type='text' class='form-control' id='neraca_piutang_usaha' name='neraca_piutang_usaha' value='$nc->nominal' readonly>";
            echo "</div>";
            $tnc = $nc->nominal;
        }
        foreach ($neraca_piutang_kredit_rumah as $nd) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Piutang Usaha Kredit Rumah</label>";
            echo "<input type='text' class='form-control' id='neraca_piutang_kredit_rumah' name='neraca_piutang_kredit_rumah' value='$nd->nominal' readonly>";
            echo "</div>";
            $tnd = $nd->nominal;
        }
        foreach ($neraca_piutang_karyawan as $ne) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Piutang karyawan</label>";
            echo "<input type='text' class='form-control' id='neraca_piutang_karyawan' name='neraca_piutang_karyawan' value='$ne->nominal' readonly>";
            echo "</div>";
            $tne = $ne->nominal;
        }
        foreach ($neraca_uang_muka as $nf) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Uang Muka</label>";
            echo "<input type='text' class='form-control' id='neraca_uang_muka' name='neraca_uang_muka' value='$nf->nominal' readonly>";
            echo "</div>";
            echo "<div class='col-12'>";
            echo "<br>";
            echo "<h6>Persediaan</h6>";
            echo "<br>";
            echo "</div>";
            $tnf = $nf->nominal;
        }
        foreach ($neraca_barang as $ng) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Barang Jadi</label>";
            echo "<input type='text' class='form-control' id='neraca_barang' name='neraca_barang' value='$ng->nominal' readonly>";
            echo "</div>";
            $tng = $ng->nominal;
        }
        foreach ($neraca_pekerjaan as $nh) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Pekerjaan dalam progress</label>";
            echo "<input type='text' class='form-control' id='neraca_pekerjaan' name='neraca_pekerjaan' value='$nh->nominal' readonly>";
            echo "</div>";
            $tnh = $nh->nominal;
            $tnz = $tna + $tnb + $tnc + $tnd + $tne + $tnf + $tng + $tnh;
            echo "<div class='form-group col-12'>";
            echo "<label for='formGroupExampleInput'>Total Aktiva Lancar</label>";
            echo "<input type='text' class='form-control' id='neraca_total_aktiva_lancar' name='neraca_total_aktiva_lancar' value='$tnz' readonly>";
            echo "</div>";
            echo "<div class='col-12'>";
            echo "<br>";
            echo "</div>";
            echo "<div class='col-12'>";
            echo "<h6>Aktiva Tidak Lancar</h6>";
            echo "<br>";
            echo "</div>";
        }
        foreach ($neraca_tanah_dan_bangunan as $ni) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Tanah dan Bangunan</label>";
            echo "<input type='text' class='form-control' id='neraca_tanah_dan_bangunan' name='neraca_tanah_dan_bangunan' value='$ni->nominal' readonly>";
            echo "</div>";
            $tni = $ni->nominal;
        }
        foreach ($neraca_peralatan_kantor as $nj) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Peralatan Kantor</label>";
            echo "<input type='text' class='form-control' id='neraca_peralatan_kantor' name='neraca_peralatan_kantor' value='$nj->nominal' readonly>";
            echo "</div>";
            $tnj = $nj->nominal;
            $tnx = $tni + $tnj;
            $tnv = $tnx + $tnz;
            echo "<div class='form-group col-12'>";
            echo "<label for='formGroupExampleInput'>Total Aktiva Tidak Lancar</label>";
            echo "<input type='text' class='form-control' id='neraca_aktiva_tidak_lancar' name='neraca_aktiva_tidak_lancar' value='$tnx' readonly>";
            echo "<div class='form-group col-12'>";
            echo "<br>";
            echo "<label for='formGroupExampleInput'>Total Aktiva</label>";
            echo "<input type='text' class='form-control' id='neraca_aktiva_tidak_lancar' name='neraca_aktiva_tidak_lancar' value='$tnv' readonly>";
        }
    }
    //akhir neraca
    function sort_lr()
    {
        $project        =  $_GET['project_LR'];
        $penjualan      =  $this->keuangan->pilih_penjualan($project)->result();
        $harga_pokok    =  $this->keuangan->pilih_hp($project)->result();
        $biaya_oprasi = $this->keuangan->pilih_biaya_operasional($project)->result();
        $biaya_promosi = $this->keuangan->pilih_biaya_promo_marketing($project)->result();
        $biaya_sewa_kantor = $this->keuangan->pilih_biaya_sewa_kantor($project)->result();
        $biaya_marketing_fee = $this->keuangan->pilih_biaya_marketing_fee($project)->result();
        $biaya_kurir = $this->keuangan->pilih_biaya_kurir($project)->result();
        $biaya_listrik = $this->keuangan->pilih_biaya_listrik($project)->result();
        $biaya_gaji_karyawan = $this->keuangan->pilih_biaya_gaji_karyawan($project)->result();
        $biaya_perijinan = $this->keuangan->pilih_biaya_perijinan($project)->result();
        $biaya_tukang = $this->keuangan->pilih_biaya_tukang($project)->result();
        $biaya_sewa_mobil = $this->keuangan->pilih_biaya_sewa_mobil($project)->result();
        $biaya_bensin = $this->keuangan->pilih_biaya_bensin($project)->result();
        $biaya_admin_bank = $this->keuangan->pilih_biaya_admin_bank($project)->result();
        $biaya_pendaptan_bunga = $this->keuangan->pilih_biaya_pendapatan_bunga($project)->result();
        $biaya_entertaiment = $this->keuangan->pilih_biaya_entertaiment($project)->result();
        $biaya_donasi = $this->keuangan->pilih_biaya_donasi($project)->result();
        $biaya_pematangan_lahan = $this->keuangan->pilih_biaya_pematangan_lahan($project)->result();
        $biaya_pembebanan_unit = $this->keuangan->pilih_biaya_pembebanan($project)->result();
        $biaya_pajak = $this->keuangan->pilih_biaya_pajak_penghasilan($project)->result();
        $biaya_pembangunan = $this->keuangan->pilih_biaya_pembangunan($project)->result();

        //Laba Rugi
        foreach ($penjualan as $a) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Penjualan</label>";
            echo "<input type='text' class='form-control' id='penjualan_LR' name='penjualan_LR' value='$a->nominal' readonly>";
            echo "</div>";
            $harganya = $a->nominal;
        }
        foreach ($harga_pokok as $b) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Harga pokok Penjualan</label>";
            echo "<input type='text' class='form-control' id='harga_pokok_LR' name='harga_pokok_LR' value='$b->nominal' readonly>";
            echo "</div>";
            $pokoknya = $b->nominal;
            $laba_bruto = $harganya - $pokoknya;
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Laba Bruto</label>";
            echo "<input type='text' class='form-control' id='laba_bruto' name='laba_bruto' value='$laba_bruto' readonly>";
            echo "</div>";
            echo "<div class='col-12'>";
            echo "<h5 style='text-align: center' class='col-12'>Biaya Operasional</h5>";
            echo "<br>";
            echo "</div>";
            echo "<br>";
        }
        foreach ($biaya_oprasi as $c) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Operasional Kantor</label>";
            echo "<input type='text' class='form-control' id='lr1' name='lr1' value='$c->nominal' readonly>";
            echo "</div>";
            $tc = $c->nominal;
        }
        foreach ($biaya_promosi as $d) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Promosi & Marketing</label>";
            echo "<input type='text' class='form-control' id='lr2' name='lr2' value='$d->nominal' readonly>";
            echo "</div>";
            $td = $d->nominal;
        }
        foreach ($biaya_sewa_kantor as $e) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Sewa kantor</label>";
            echo "<input type='text' class='form-control' id='lr3' name='lr3' value='$e->nominal' readonly>";
            echo "</div>";
            $te = $e->nominal;
        }
        foreach ($biaya_marketing_fee as $f) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Marketing Fee</label>";
            echo "<input type='text' class='form-control' id='lr4' name='lr4' value='$f->nominal' readonly>";
            echo "</div>";
            $tf = $f->nominal;
        }
        foreach ($biaya_kurir as $g) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Kurir</label>";
            echo "<input type='text' class='form-control' id='lr5' name='lr5' value='$g->nominal' readonly>";
            echo "</div>";
            $tg = $g->nominal;
        }
        foreach ($biaya_listrik as $h) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Bayar Listrik</label>";
            echo "<input type='text' class='form-control' id='lr6' name='lr6' value='$h->nominal' readonly>";
            echo "</div>";
            $th = $h->nominal;
        }
        foreach ($biaya_gaji_karyawan as $i) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Gaji Karyawan</label>";
            echo "<input type='text' class='form-control' id='lr7' name='lr7' value='$i->nominal' readonly>";
            echo "</div>";
            $ti = $i->nominal;
        }
        foreach ($biaya_perijinan as $j) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Perijinan</label>";
            echo "<input type='text' class='form-control' id='lr8' name='lr8' value='$j->nominal' readonly>";
            echo "</div>";
            $tj = $j->nominal;
        }
        foreach ($biaya_tukang as $k) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Tukang</label>";
            echo "<input type='text' class='form-control' id='lr9' name='lr9' value='$k->nominal' readonly>";
            echo "</div>";
            $tk = $k->nominal;
        }
        foreach ($biaya_sewa_mobil as $l) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Sewa Mobil</label>";
            echo "<input type='text' class='form-control' id='lr10' name='lr10' value='$l->nominal' readonly>";
            echo "</div>";
            $tl = $l->nominal;
        }
        foreach ($biaya_bensin as $m) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Bensin, Toll dan Parkir</label>";
            echo "<input type='text' class='form-control' id='lr11' name='lr11' value='$m->nominal' readonly>";
            echo "</div>";
            $tm = $m->nominal;
        }
        foreach ($biaya_admin_bank as $n) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Admin Bank</label>";
            echo "<input type='text' class='form-control' id='lr12' name='lr12' value='$n->nominal' readonly>";
            echo "</div>";
            $tn = $n->nominal;
        }
        foreach ($biaya_pendaptan_bunga as $o) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Pendapatan Bunga</label>";
            echo "<input type='text' class='form-control' id='lr13' name='lr13' value='$o->nominal' readonly>";
            echo "</div>";
            $to = $o->nominal;
        }
        foreach ($biaya_entertaiment as $p) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Entertaiment</label>";
            echo "<input type='text' class='form-control' id='lr14' name='lr14' value='$p->nominal' readonly>";
            echo "</div>";
            $tp = $p->nominal;
        }
        foreach ($biaya_donasi as $q) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Donasi dan Sumbangan</label>";
            echo "<input type='text' class='form-control' id='lr15' name='lr15' value='$q->nominal' readonly>";
            echo "</div>";
            $tq = $q->nominal;
        }
        foreach ($biaya_pembangunan as $t) {
            $tt = $t->nominal;
        }
        foreach ($biaya_pematangan_lahan as $r) {
            $tr = $r->nominal;
            $trr = $tr + $tt;
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Pematangan Lahan & Pembangunan</label>";
            echo "<input type='text' class='form-control' id='lr16' name='lr16' value='$trr' readonly>";
            echo "</div>";
        }
        foreach ($biaya_pembebanan_unit as $s) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Biaya Pembebanan Per Unit</label>";
            echo "<input type='text' class='form-control' id='lr17' name='lr17' value='$s->nominal' readonly>";
            echo "</div>";
            $ts = $s->nominal;
            $laba_kotor = $tc + $td + $te + $tf + $tg + $th + $ti + $tj + $tk + $tl + $tm + $tn + $to + $tp + $tq + $ts + $trr + $laba_bruto;
            echo " <div class='col-12'>";
            echo "<br>";
            echo "</div>";
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Laba Kotor Sebelum pajak</label>";
            echo "<input type='text' class='form-control' id='laba_kotor' name='laba_kotor' value='$laba_kotor' readonly>";
            echo "</div>";
        }
        foreach ($biaya_pajak as $u) {
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Pajak penghasilan</label>";
            echo "<input type='text' class='form-control' id='pajak_penghasilan' name='pajak_penghasilan' value='$u->nominal' readonly>";
            echo "</div>";
            $tu = $u->nominal;
            $tv = $laba_kotor - $tu;
            echo "<div class='form-group col-4'>";
            echo "<label for='formGroupExampleInput'>Laba Setelah Pajak</label>";
            echo "<input type='text' class='form-control' id='laba_setelah_pajak' name='laba_setelah_pajak' value='$tv' readonly>";
            echo "</div>";
        }
        //Akhir LAba Rugi
    }

    // LK Pasiva
    function sort_pasiva()
    {
        $project            = $_GET['project_pasiva'];
        //Laba Rugi
        // data load laba setela pajak 
        $penjualan      =  $this->keuangan->pilih_penjualan($project)->result();
        $harga_pokok    =  $this->keuangan->pilih_hp($project)->result();
        $biaya_oprasi = $this->keuangan->pilih_biaya_operasional($project)->result();
        $biaya_promosi = $this->keuangan->pilih_biaya_promo_marketing($project)->result();
        $biaya_sewa_kantor = $this->keuangan->pilih_biaya_sewa_kantor($project)->result();
        $biaya_marketing_fee = $this->keuangan->pilih_biaya_marketing_fee($project)->result();
        $biaya_kurir = $this->keuangan->pilih_biaya_kurir($project)->result();
        $biaya_listrik = $this->keuangan->pilih_biaya_listrik($project)->result();
        $biaya_gaji_karyawan = $this->keuangan->pilih_biaya_gaji_karyawan($project)->result();
        $biaya_perijinan = $this->keuangan->pilih_biaya_perijinan($project)->result();
        $biaya_tukang = $this->keuangan->pilih_biaya_tukang($project)->result();
        $biaya_sewa_mobil = $this->keuangan->pilih_biaya_sewa_mobil($project)->result();
        $biaya_bensin = $this->keuangan->pilih_biaya_bensin($project)->result();
        $biaya_admin_bank = $this->keuangan->pilih_biaya_admin_bank($project)->result();
        $biaya_pendaptan_bunga = $this->keuangan->pilih_biaya_pendapatan_bunga($project)->result();
        $biaya_entertaiment = $this->keuangan->pilih_biaya_entertaiment($project)->result();
        $biaya_donasi = $this->keuangan->pilih_biaya_donasi($project)->result();
        $biaya_pematangan_lahan = $this->keuangan->pilih_biaya_pematangan_lahan($project)->result();
        $biaya_pembebanan_unit = $this->keuangan->pilih_biaya_pembebanan($project)->result();
        $biaya_pajak = $this->keuangan->pilih_biaya_pajak_penghasilan($project)->result();
        $biaya_pembangunan = $this->keuangan->pilih_biaya_pembangunan($project)->result();

        foreach ($penjualan as $a) {
            $harganya = $a->nominal;
        }
        foreach ($harga_pokok as $b) {
            $pokoknya = $b->nominal;
            $laba_bruto = $harganya - $pokoknya;
        }
        foreach ($biaya_oprasi as $c) {
            $tc = $c->nominal;
        }
        foreach ($biaya_promosi as $d) {
            $td = $d->nominal;
        }
        foreach ($biaya_sewa_kantor as $e) {
            $te = $e->nominal;
        }
        foreach ($biaya_marketing_fee as $f) {
            $tf = $f->nominal;
        }
        foreach ($biaya_kurir as $g) {
            $tg = $g->nominal;
        }
        foreach ($biaya_listrik as $h) {
            $th = $h->nominal;
        }
        foreach ($biaya_gaji_karyawan as $i) {
            $ti = $i->nominal;
        }
        foreach ($biaya_perijinan as $j) {
            $tj = $j->nominal;
        }
        foreach ($biaya_tukang as $k) {
            $tk = $k->nominal;
        }
        foreach ($biaya_sewa_mobil as $l) {
            $tl = $l->nominal;
        }
        foreach ($biaya_bensin as $m) {
            $tm = $m->nominal;
        }
        foreach ($biaya_admin_bank as $n) {
            $tn = $n->nominal;
        }
        foreach ($biaya_pendaptan_bunga as $o) {
            $to = $o->nominal;
        }
        foreach ($biaya_entertaiment as $p) {
            $tp = $p->nominal;
        }
        foreach ($biaya_donasi as $q) {
            $tq = $q->nominal;
        }
        foreach ($biaya_pembangunan as $t) {
            $tt = $t->nominal;
        }
        foreach ($biaya_pematangan_lahan as $r) {
            $tr = $r->nominal;
            $trr = $tr + $tt;
        }
        foreach ($biaya_pembebanan_unit as $s) {
            $ts = $s->nominal;
            $laba_kotor = $tc + $td + $te + $tf + $tg + $th + $ti + $tj + $tk + $tl + $tm + $tn + $to + $tp + $tq + $ts + $trr + $laba_bruto;
        }
        foreach ($biaya_pajak as $u) {
            $tu = $u->nominal;
            $tv = $laba_kotor - $tu;
        }
        // Pasiva 
        $hutang_usaha       = $this->keuangan->pilih_hutangUsaha($project)->result();
        $hutang_konsiyasi   = $this->keuangan->pilih_hutangKonsiyasi($project)->result();
        $hutang_leverensir  = $this->keuangan->pilih_hutangLeverensir($project)->result();
        $uang_mukapenjualan = $this->keuangan->pilih_uangMukaP($project)->result();
        $hutang_ps          = $this->keuangan->pilih_hutangPemegangSaham($project)->result();
        $modal_disetor      = $this->keuangan->pilih_modalDisetor($project)->result();
        $modal_adi          = $this->keuangan->pilih_modalAdi($project)->result();
        $m_arief            = $this->keuangan->pilih_MArief($project)->result();
        $samsunar           = $this->keuangan->pilih_samsunar($project)->result();
        $adi_dharma         = $this->keuangan->pilih_adiDharma($project)->result();

        echo "<div class='col-12'>";
        echo "<h5 style='text-align: center' class='col-12'>Hutang Lancar</h5>";
        echo "<br>";
        echo "</div>";
        foreach ($hutang_usaha as $hu) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Hutang usaha</label>";
            echo "<input type='text' class='form-control' id='hu' name='hu' value='$hu->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $hus = $hu->nominal;
        }
        foreach ($hutang_konsiyasi as $hk) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Hutang Konsiyasi</label>";
            echo "<input type='text' class='form-control' id='hk' name='hk' value='$hk->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $hkon = $hk->nominal;
        }
        foreach ($hutang_leverensir as $hl) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Hutang Leverensir</label>";
            echo "<input type='text' class='form-control' id='hl' name='hl' value='$hl->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $hlev = $hl->nominal;
        }
        foreach ($uang_mukapenjualan as $ump) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Uang Muka Penjualan</label>";
            echo "<input type='text' class='form-control' id='ump' name='ump' value='$ump->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $um = $ump->nominal;
        }
        $thl = $hus + $hkon + $hlev + $um;
        echo "<div class='form-group col-6'>";
        echo "<label for='formGroupExampleInput'>Total Hutang Lancar</label>";
        echo "<input type='text' class='form-control' id='thl' name='thl' value='$thl' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";
        foreach ($hutang_ps as $hps) {
            echo "<div class='form-group col-6'>";
            echo "<label for='formGroupExampleInput'>Hutang Pemegang Saham</label>";
            echo "<input type='text' class='form-control' id='hps' name='hps' value='$hps->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "<br>";
            echo "</div>";
            $hutangPs = $hps->nominal;
        }

        $th = $thl + $hutangPs;
        echo "<div class='form-group col-12'>";
        echo "    <label for='formGroupExampleInput'>Total hutang</label>";
        echo "<input type='text' class='form-control' id='th' name='th' value ='$th' placeholder='Otomatis Terisi' readonly>";
        echo "<br>";
        echo "</div>";

        echo "<div class='col-12'>";
        echo "<h5 style='text-align: center' class='col-12'>Modal</h5>";
        echo "<br>";
        echo "</div>";
        echo "<br>";
        foreach ($modal_disetor as $md) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Modal Disetor</label>";
            echo "<input type='text' class='form-control' id='md' name='md' value='$md->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $tmd = $md->nominal;
        }
        foreach ($modal_adi as $ma) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Modal Adi</label>";
            echo "<input type='text' class='form-control' id='ma' name='ma' value='$ma->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $tmad = $ma->nominal;
        }
        foreach ($m_arief as $mar) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Mohamad arief</label>";
            echo "<input type='text' class='form-control' id='mar' name='mar' value='$mar->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $tmar = $mar->nominal;
        }
        foreach ($samsunar as $s) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Samsunar</label>";
            echo "<input type='text' class='form-control' id='s' name='s' value='$s->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $tms = $s->nominal;
        }
        foreach ($adi_dharma as $ad) {
            echo "<div class='form-group col-3'>";
            echo "<label for='formGroupExampleInput'>Adi Dharma</label>";
            echo "<input type='text' class='form-control' id='ad' name='ad' value='$ad->nominal' placeholder='Otomatis Terisi' readonly>";
            echo "</div>";
            $tmadr = $ad->nominal;
        }

        echo "<div class='form-group col-9'>";
        echo "<label for='formGroupExampleInput'>Saldo Laba Ditahan</label>";
        echo "<input type='text' class='form-control' id='sld' name='sld' value='$tv' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $tm = $tmd + $tmad + $tmar + $tms + $tmadr + $tv;
        echo "<div class='form-group col-12'>";
        echo "<label for='formGroupExampleInput'>Total Modal</label>";
        echo "<input type='text' class='form-control' id='tm' name='tm' value='$tm' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $totalPasiva = $th + $tm;
        echo "<div class='col-12'>";
        echo "<br>";
        echo "</div>";
        echo "<div class='form-group col-12'>";
        echo "<label for='formGroupExampleInput'>Total Pasiva</label>";
        echo "<input type='text' class='form-control' id='tpas' name='tpas' value='$totalPasiva' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";
    }

    function sort_cf()
    {
        $project            = $_GET['project_cf'];

        // Laba Rugi 
        $penjualan      =  $this->keuangan->pilih_penjualan($project)->result();
        $harga_pokok    =  $this->keuangan->pilih_hp($project)->result();
        $biaya_oprasi = $this->keuangan->pilih_biaya_operasional($project)->result();
        $biaya_promosi = $this->keuangan->pilih_biaya_promo_marketing($project)->result();
        $biaya_sewa_kantor = $this->keuangan->pilih_biaya_sewa_kantor($project)->result();
        $biaya_marketing_fee = $this->keuangan->pilih_biaya_marketing_fee($project)->result();
        $biaya_kurir = $this->keuangan->pilih_biaya_kurir($project)->result();
        $biaya_listrik = $this->keuangan->pilih_biaya_listrik($project)->result();
        $biaya_gaji_karyawan = $this->keuangan->pilih_biaya_gaji_karyawan($project)->result();
        $biaya_perijinan = $this->keuangan->pilih_biaya_perijinan($project)->result();
        $biaya_tukang = $this->keuangan->pilih_biaya_tukang($project)->result();
        $biaya_sewa_mobil = $this->keuangan->pilih_biaya_sewa_mobil($project)->result();
        $biaya_bensin = $this->keuangan->pilih_biaya_bensin($project)->result();
        $biaya_admin_bank = $this->keuangan->pilih_biaya_admin_bank($project)->result();
        $biaya_pendaptan_bunga = $this->keuangan->pilih_biaya_pendapatan_bunga($project)->result();
        $biaya_entertaiment = $this->keuangan->pilih_biaya_entertaiment($project)->result();
        $biaya_donasi = $this->keuangan->pilih_biaya_donasi($project)->result();
        $biaya_pematangan_lahan = $this->keuangan->pilih_biaya_pematangan_lahan($project)->result();
        $biaya_pembebanan_unit = $this->keuangan->pilih_biaya_pembebanan($project)->result();
        $biaya_pajak = $this->keuangan->pilih_biaya_pajak_penghasilan($project)->result();
        $biaya_pembangunan = $this->keuangan->pilih_biaya_pembangunan($project)->result();

        foreach ($penjualan as $a) {
            $harganya = $a->nominal;
        }
        foreach ($harga_pokok as $b) {
            $pokoknya = $b->nominal;
            $laba_bruto = $harganya - $pokoknya;
        }
        foreach ($biaya_oprasi as $c) {
            $tc = $c->nominal;
        }
        foreach ($biaya_promosi as $d) {
            $td = $d->nominal;
        }
        foreach ($biaya_sewa_kantor as $e) {
            $te = $e->nominal;
        }
        foreach ($biaya_marketing_fee as $f) {
            $tf = $f->nominal;
        }
        foreach ($biaya_kurir as $g) {
            $tg = $g->nominal;
        }
        foreach ($biaya_listrik as $h) {
            $th = $h->nominal;
        }
        foreach ($biaya_gaji_karyawan as $i) {
            $ti = $i->nominal;
        }
        foreach ($biaya_perijinan as $j) {
            $tj = $j->nominal;
        }
        foreach ($biaya_tukang as $k) {
            $tk = $k->nominal;
        }
        foreach ($biaya_sewa_mobil as $l) {
            $tl = $l->nominal;
        }
        foreach ($biaya_bensin as $m) {
            $tm = $m->nominal;
        }
        foreach ($biaya_admin_bank as $n) {
            $tn = $n->nominal;
        }
        foreach ($biaya_pendaptan_bunga as $o) {
            $to = $o->nominal;
        }
        foreach ($biaya_entertaiment as $p) {
            $tp = $p->nominal;
        }
        foreach ($biaya_donasi as $q) {
            $tq = $q->nominal;
        }
        foreach ($biaya_pembangunan as $t) {
            $tt = $t->nominal;
        }
        foreach ($biaya_pematangan_lahan as $r) {
            $tr = $r->nominal;
            $trr = $tr + $tt;
        }
        foreach ($biaya_pembebanan_unit as $s) {
            $ts = $s->nominal;
            $laba_kotor = $tc + $td + $te + $tf + $tg + $th + $ti + $tj + $tk + $tl + $tm + $tn + $to + $tp + $tq + $ts + $trr + $laba_bruto;
        }
        foreach ($biaya_pajak as $u) {
            $tu = $u->nominal;
            $tv = $laba_kotor - $tu;
        }

        // Neraca
        $neraca_uang_muka = $this->keuangan->pilih_neraca_uang_muka($project)->result();
        $neraca_barang = $this->keuangan->pilih_neraca_barang_jadi($project)->result();
        $neraca_piutang_karyawan = $this->keuangan->pilih_neraca_piutang_karyawan($project)->result();
        $neraca_peralatan_kantor = $this->keuangan->pilih_biaya_sewa_kantor($project)->result();
        foreach ($neraca_uang_muka as $nf) {
            $tnf = $nf->nominal;
        }
        foreach ($neraca_barang as $ng) {
            $tng = $ng->nominal;
        }
        foreach ($neraca_piutang_karyawan as $ne) {
            $tne = $ne->nominal;
        }
        foreach ($neraca_peralatan_kantor as $nj) {
            $tnj = $nj->nominal;
        }

        // Pasiva
        $uang_mukapenjualan = $this->keuangan->pilih_uangMukaP($project)->result();
        $hutang_ps          = $this->keuangan->pilih_hutangPemegangSaham($project)->result();
        $modal_disetor      = $this->keuangan->pilih_modalDisetor($project)->result();
        $modal_adi          = $this->keuangan->pilih_modalAdi($project)->result();
        $m_arief            = $this->keuangan->pilih_MArief($project)->result();
        $samsunar           = $this->keuangan->pilih_samsunar($project)->result();
        $adi_dharma         = $this->keuangan->pilih_adiDharma($project)->result();
        foreach ($uang_mukapenjualan as $ump) {
            $um = $ump->nominal;
        }
        foreach ($modal_disetor as $md) {
            $tmd = $md->nominal;
        }
        foreach ($modal_adi as $ma) {
            $tmad = $ma->nominal;
        }
        foreach ($m_arief as $mar) {
            $tmar = $mar->nominal;
        }
        foreach ($samsunar as $s) {
            $tms = $s->nominal;
        }
        foreach ($adi_dharma as $ad) {
            $tmadr = $ad->nominal;
        }
        foreach ($hutang_ps as $hps) {
            $hutangPs = $hps->nominal;
        }
        // View cashflow
        echo "<div class='col-12'>";
        echo "<h5 style='text-align: center' class='col-12'>Dari Operasional</h5>";
        echo "<br>";
        echo "</div>";

        $psRL = $tc + $td + $te + $tf + $tg + $th + $tj + $tk + $tl + $tm + $tn + $to + $tp + $tq + $tt + $tr + $ts;
        $psNer = $tnf;
        $psNer2 = $tng;
        $pS = $psRL - $psNer - $psNer2;
        echo "<div class='form-group col-4'>";
        echo "<label for='formGroupExampleInput'>Pembayaran Supplier</label>";
        echo "<input type='text' class='form-control' id='CF1' name='CF1' value='$pS' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $pkk = $ti - $tne;
        echo "<div class='form-group col-4'>";
        echo "<label for='formGroupExampleInput'>Pembayaran Kepada Karyawan</label>";
        echo "<input type='text' class='form-control' id='CF2' name='CF2' value='$pkk' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        echo "<div class='form-group col-4'>";
        echo "<label for='formGroupExampleInput'>Pembayaran Pajak</label>";
        echo "<input type='text' class='form-control' id='CF3' name='CF3' value='$tu' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        echo "<div class='form-group col-4'>";
        echo "<label for='formGroupExampleInput'>Penerimaan dari Pelanggan</label>";
        echo "<input type='text' class='form-control' id='CF4' name='CF4' value='$um' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $sps = $tmd + $tmad + $tmar + $tms + $tmadr;
        echo "<div class='form-group col-4'>";
        echo "<label for='formGroupExampleInput'>Setoran pemegang Saham</label>";
        echo "<input type='text' class='form-control' id='CF5' name='CF5' value='$sps' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $toc = $pS + $pkk + $tu + $um + $sps;
        echo "<div class='form-group col-12'>";
        echo "<label for='formGroupExampleInput'>Total Operasional CashFlow</label>";
        echo "<input type='text' class='form-control' id='CF6' name='CF6' value='$toc' placeholder='Otomatis Terisi' readonly>";
        echo "<br>";
        echo "</div>";

        echo "<div class='col-12'>";
        echo "<h5 style='text-align: center' class='col-12'>Dari Investasi</h5>";
        echo "<br>";
        echo "</div>";

        echo "<br>";
        echo "<div class='form-group col-6'>";
        echo "<label for='formGroupExampleInput'>Pembelian Aset Tetap</label>";
        echo "<input type='text' class='form-control' id='CF7' name='CF7' value='$tnj' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $pat = 1;
        echo "<div class='form-group col-6'>";
        echo "<label for='formGroupExampleInput'>Penjualan Aset Tetap</label>";
        echo "<input type='text' class='form-control' id='CF8' name='CF8' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $tic = $tnj + $pat;
        echo "<div class='form-group col-12'>";
        echo "<label for='formGroupExampleInput'>Total Investasi CashFlow</label>";
        echo "<input type='text' class='form-control' id='CF9' name='CF9' value='$tic' placeholder='Otomatis Terisi' readonly>";
        echo "<br>";
        echo "</div>";

        echo "<div class='col-12'>";
        echo "<h5 style='text-align: center' class='col-12'>Financing</h5>";
        echo "<br>";
        echo "</div>";

        $pemB = 1;
        echo "<div class='form-group col-3'>";
        echo "<label for='formGroupExampleInput'>Pembayaran Bank</label>";
        echo "<input type='text' class='form-control' id='CF10' name='CF10' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $pps = 1;
        echo "<div class='form-group col-3'>";
        echo "<label for='formGroupExampleInput'>Pembayaran Pemegang Saham</label>";
        echo "<input type='text' class='form-control' id='CF11' name='CF11' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $pinB = 1;
        echo "<div class='form-group col-3'>";
        echo "<label for='formGroupExampleInput'>Pinjaman Bank</label>";
        echo "<input type='text' class='form-control' id='CF12' name='CF12' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";


        echo "<div class='form-group col-3'>";
        echo "<label for='formGroupExampleInput'>Pinjaman Pemegang Saham</label>";
        echo "<input type='text' class='form-control' id='CF13' name='CF13' value='$hutangPs' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        $tfc = $pemB + $pps + $pinB + $hutangPs;
        echo "<div class='form-group col-12'>";
        echo "<label for='formGroupExampleInput'>Total Financing CashFlow</label>";
        echo "<input type='text' class='form-control' id='CF14' name='CF14' value='$tfc' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";

        echo "<div class='col-12'>";
        echo "<br>";
        echo "</div>";

        $tc = $toc + $tic + $tfc;
        echo "<div class='form-group col-12'>";
        echo "<label for='formGroupExampleInput'>Total CashFlow</label>";
        echo "<input type='text' class='form-control' id='CF15' name='CF15' value='$tc' placeholder='Otomatis Terisi' readonly>";
        echo "</div>";
    }
}
